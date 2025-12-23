<?php
/**
 * FORCED MU-PLUGIN DISABLER
 * Use with caution! This moves all mu-plugins to a disabled folder.
 */
define('WP_USE_THEMES', false);
require_once('wp-load.php');

echo '<h1>☢️ FORCE DISABLE ALL MU-PLUGINS</h1>';

$mu_plugins_dir = WPMU_PLUGIN_DIR;
$mu_plugins_disabled_dir = $mu_plugins_dir . '/disabled';

if (!file_exists($mu_plugins_disabled_dir)) {
    mkdir($mu_plugins_disabled_dir);
}

$mu_plugins = array_diff(scandir($mu_plugins_dir), array('.', '..', 'disabled'));
foreach ($mu_plugins as $plugin_file) {
    if (pathinfo($plugin_file, PATHINFO_EXTENSION) === 'php') {
        $source = $mu_plugins_dir . '/' . $plugin_file;
        $destination = $mu_plugins_disabled_dir . '/' . $plugin_file;
        if (rename($source, $destination)) {
            echo "<p>✅ Moved MU-Plugin: $plugin_file</p>";
        } else {
            echo "<p>❌ Failed to move MU-Plugin: $plugin_file</p>";
        }
    }
}
echo "<p>✅ All MU-Plugins moved to 'disabled' folder.</p>";

// Flush all caches
wp_cache_flush();
flush_rewrite_rules();
echo "<p>✅ Cache and Rewrite Rules flushed.</p>";

echo '<h2>🚀 Done. Check site.</h2>';
?>
