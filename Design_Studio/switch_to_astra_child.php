<?php
define('WP_USE_THEMES', false);
require_once('wp-load.php');
echo '<h1>🎨 Restoring Astra Child Theme</h1>';

// Switch to Astra Child
$theme = 'astra-child';
$parent = 'astra';

// Check if it exists
$check = wp_get_theme($theme);
if ($check->exists()) {
    update_option('template', $parent);
    update_option('stylesheet', $theme);
    echo "<h1>✅ Switched back to $theme</h1>";
} else {
    echo "<h1>❌ $theme not found!</h1>";
}
?>