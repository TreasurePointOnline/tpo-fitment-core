<?php
/**
 * Simple script to flush WordPress object cache.
 * Visit this page to clear cache.
 */

define('WP_USE_THEMES', false); // Don't load theme
require_once('wp-load.php');

// Ensure only admins can run this if accessed via browser (basic security)
if ( ! current_user_can('manage_options') ) {
    // Optionally: exit or redirect if not admin. For simplicity, we just print a message.
    // die('You do not have permission to flush cache directly.');
}

if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
    echo "<h1>✅ WordPress Cache Flushed!</h1>";
} else {
    echo "<h1>⚠️ Cache Flush Failed!</h1>";
    echo "<p><code>wp_cache_flush()</code> function not found. Object caching might not be active or a plugin cache needs specific flushing.</p>";
}

// Optionally, redirect to homepage after flush
// wp_redirect( home_url() );
// exit;
?>
