<?php
define('WP_USE_THEMES', false);
require_once('wp-load.php');
echo '<h1>🎨 Theme Switcher: Back to Astra Parent</h1>';

// Switch to Astra Parent Theme
$theme = 'astra';

// Check if it exists
$check = wp_get_theme($theme);
if ($check->exists()) {
    update_option('template', $theme);
    update_option('stylesheet', $theme);
    echo "<p>✅ Switched to $theme.</p>";
} else {
    echo "<p>❌ Astra Parent Theme not found! This is critical.</p>";
}

// Flush all caches
wp_cache_flush();
flush_rewrite_rules();
echo "<p>✅ Cache and Rewrite Rules flushed.</p>";

echo '<h2>🚀 Done. Check site.</h2>';
?>
