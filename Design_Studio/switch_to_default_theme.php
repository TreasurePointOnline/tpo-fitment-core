<?php
define('WP_USE_THEMES', false);
define('WP_INSTALLING', true); // Bypass full init to avoid crash
require_once('wp-load.php');
echo '<h1>🎨 Theme Switcher</h1>';

// Switch to Twenty Twenty-Five
$theme = 'twentytwentyfive';

// Check if it exists
$check = wp_get_theme($theme);
if ($check->exists()) {
    update_option('template', $theme);
    update_option('stylesheet', $theme);
    // update_option('current_theme', $theme); // This option is deprecated but good for older WP versions
    echo "<h1>✅ Switched to $theme</h1>";
} else {
    echo "<h1>❌ $theme not found!</h1>";
    echo "<p>Trying Twenty Twenty-Four instead...</p>";
    $theme = 'twentytwentyfour';
    $check = wp_get_theme($theme);
    if ($check->exists()) {
        update_option('template', $theme);
        update_option('stylesheet', $theme);
        echo "<h1>✅ Switched to $theme</h1>";
    } else {
        echo "<h1>❌ No default themes found! Site is critically broken.</h1>";
    }
}
?>
