<?php
/**
 * TPO Plugin Installer: All-in-One WP Migration
 */
define('WP_USE_THEMES', false);
define('WP_INSTALLING', true); // Bypass some checks
require_once('wp-load.php');
require_once(ABSPATH . 'wp-admin/includes/plugin-installer.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/plugin.php');
require_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');

echo '<h1>📦 Installing All-in-One WP Migration...</h1>';

$plugin_slug = 'all-in-one-wp-migration';

// 1. Check if already installed
if (file_exists(WP_PLUGIN_DIR . '/' . $plugin_slug)) {
    echo "<p>ℹ️ Plugin folder already exists. Attempting activation...</p>";
    activate_plugin($plugin_slug . '/all-in-one-wp-migration.php');
    echo "<h1>✅ Plugin Activated!</h1>";
} else {
    // 2. Install
    echo "<p>Downloading and installing from WordPress.org...</p>";
    
    // Create the upgrader
    $upgrader = new Plugin_Upgrader(new Automatic_Upgrader_Skin());
    
    // Get the API info
    $api = plugins_api('plugin_information', array(
        'slug' => $plugin_slug,
        'fields' => array('sections' => false)
    ));

    if (is_wp_error($api)) {
        die("❌ Error fetching plugin info: " . $api->get_error_message());
    }

    $result = $upgrader->install($api->download_link);

    if (is_wp_error($result)) {
        die("❌ Installation failed: " . $result->get_error_message());
    }

    echo "<p>Installation successful. Activating...</p>";
    activate_plugin($plugin_slug . '/all-in-one-wp-migration.php');
    echo "<h1>✅ Plugin Installed and Activated!</h1>";
}

echo '<p><a href="' . admin_url('admin.php?page=ai1wm_export') . '">Go to Export Page (Requires Login)</a></p>';
?>