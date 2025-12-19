<?php
/**
 * Script to attempt to trigger WooCommerce database updates.
 * This should be run after a WooCommerce update if `wp wc update` is unavailable.
 */

define('WP_USE_THEMES', false);
require_once('wp-load.php');

// Ensure WooCommerce is active and its update functions are available
if ( ! class_exists('WooCommerce') ) {
    die("<h1>❌ WooCommerce is not active or not installed!</h1>");
}

if ( ! class_exists('WC_Install') ) {
    die("<h1>❌ WC_Install class not found!</h1><p>WooCommerce update functions might not be accessible directly.</p>");
}

echo "<h1>🚀 Attempting WooCommerce Database Update...</h1>";

// Mimic an admin context to trigger the update process
// This is a simplified approach, actual wp-cli does more.
// For full reliability, logging into WP Admin and visiting WooCommerce Status is best.

// Set current screen to prevent some admin notices from breaking output
global $current_screen;
$current_screen = WP_Screen::get( 'woocommerce_page_wc-settings' ); // or any admin screen context

// Try to trigger the update actions
do_action( 'admin_init' ); // This often runs the WC update logic
do_action( 'wp_loaded' );

// Check if update is pending and if update function exists
if ( method_exists('WC_Install', 'update_db_version') ) {
    $current_db_version = get_option( 'woocommerce_db_version', null );
    $target_db_version = WC_Install::get_db_version();

    if ( version_compare( $current_db_version, $target_db_version, '<' ) ) {
        echo "<p>Found pending database update from version <code>{$current_db_version}</code> to <code>{$target_db_version}</code>.</p>";
        
        // This is the direct function call, usually handled by admin hooks
        WC_Install::update_db_version(); // This will perform the update
        
        echo "<h1>✅ WooCommerce Database Update Attempted!</h1>";
        echo "<p>Please check your WooCommerce Status page in WP Admin to confirm the database version is up-to-date.</p>";
    } else {
        echo "<p>ℹ️ WooCommerce database is already up-to-date (Version: <code>{$current_db_version}</code>).</p>";
    }
} else {
    echo "<p>⚠️ Could not find <code>WC_Install::update_db_version()</code>. Automatic update via script may not be possible.</p>";
    echo "<p>Please log into your WordPress Admin, go to WooCommerce &gt; Status, and check for pending database updates there.</p>";
}

?>
