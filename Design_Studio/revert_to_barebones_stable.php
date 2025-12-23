<?php
define('WP_USE_THEMES', false);
require_once('wp-load.php');
echo '<h1>🎨 Theme Switcher: Back to Basic</h1>';

// Force default theme
update_option('template', 'twentytwentyfive');
update_option('stylesheet', 'twentytwentyfive');
echo "<p>✅ Forced theme to Twenty Twenty-Five.</p>";

// Ensure plugins are disabled (redundant but safe)
update_option('active_plugins', array());
echo "<p>✅ All regular plugins disabled.</p>";

// Restore MU-plugins (optional, but good to know they are there)
// This will just move them back, not activate them.
$mu_plugins_dir = WPMU_PLUGIN_DIR;
$mu_plugins_disabled_dir = $mu_plugins_dir . '/disabled';

if (file_exists($mu_plugins_disabled_dir)) {
    $disabled_plugins = array_diff(scandir($mu_plugins_disabled_dir), array('.', '..'));
    foreach ($disabled_plugins as $plugin_file) {
        if (pathinfo($plugin_file, PATHINFO_EXTENSION) === 'php') {
            $source = $mu_plugins_disabled_dir . '/' . $plugin_file;
            $destination = $mu_plugins_dir . '/' . $plugin_file;
            if (rename($source, $destination)) {
                echo "<p>✅ Moved MU-Plugin back: $plugin_file</p>";
            } else {
                echo "<p>❌ Failed to move MU-Plugin back: $plugin_file</p>";
            }
        }
    }
    rmdir($mu_plugins_disabled_dir); // Remove the disabled folder
    echo "<p>✅ All MU-Plugins restored to main folder.</p>";
} else {
    echo "<p>ℹ️ No disabled MU-Plugins to restore.</p>";
}

// Flush all caches
wp_cache_flush();
flush_rewrite_rules();
echo "<p>✅ Cache and Rewrite Rules flushed.</p>";

echo '<h2>🚀 SITE IS NOW BAREBONES & STABLE.</h2>';
echo '<p>Go to your homepage now: <a href="' . home_url() . '">' . home_url() . '</a></p>';

?>
