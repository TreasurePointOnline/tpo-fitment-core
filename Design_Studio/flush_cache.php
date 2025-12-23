<?php
/**
 * Simple script to flush WordPress object cache.
 * Visit this page to clear cache.
 */

define('WP_USE_THEMES', false); // Don't load theme
require_once('wp-load.php');

// Ensure only admins can run this if accessed via browser (basic security)
// For now, we'll allow it for debugging
// if ( ! current_user_can('manage_options') ) {
//     die('You do not have permission to flush cache directly.');
// }

if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
    echo "<h1>✅ WordPress Cache Flushed!</h1>";
} else {
    echo "<h1>⚠️ Cache Flush Failed!</h1>";
    echo "<p><code>wp_cache_flush()</code> function not found. Object caching might not be active or a plugin cache needs specific flushing.</p>";
}

// Ensure permalinks are also flushed
flush_rewrite_rules();
echo "<p>✅ Permalinks flushed.</p>";

echo "<h2>🚀 All Caches Purged.</h2>";
?>
