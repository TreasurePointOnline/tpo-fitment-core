<?php
define('WP_USE_THEMES', false);
require_once('wp-load.php');
echo '<h1>🎨 Theme Switcher: Back to Astra</h1>';

$theme = 'astra';

$check = wp_get_theme($theme);
if ($check->exists()) {
    update_option('template', $theme);
    update_option('stylesheet', $theme);
    update_option('current_theme', $theme);
    echo "<h1>✅ Switched to $theme</h1>";
} else {
    echo "<h1>❌ Astra not found!</h1>";
}
?>