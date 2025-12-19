<?php
require_once('wp-load.php');

// Ensure only admins (or anyone if you want it public for a sec) can see this
// For simplicity, we'll just output. Delete after use.

echo "<h1>WordPress System Report</h1>";

// 1. Core Info
global $wp_version;
echo "<h2>Core</h2>";
echo "<ul>";
echo "<li><strong>WordPress Version:</strong> " . $wp_version . "</li>";
echo "<li><strong>Site URL:</strong> " . site_url() . "</li>";
echo "<li><strong>Home URL:</strong> " . home_url() . "</li>";
echo "<li><strong>Multisite:</strong> " . (is_multisite() ? 'Yes' : 'No') . "</li>";
echo "<li><strong>Debug Mode:</strong> " . (defined('WP_DEBUG') && WP_DEBUG ? 'Enabled' : 'Disabled') . "</li>";
echo "</ul>";

// 2. Server Info
echo "<h2>Server</h2>";
echo "<ul>";
echo "<li><strong>PHP Version:</strong> " . phpversion() . "</li>";
echo "<li><strong>Server Software:</strong> " . $_SERVER['SERVER_SOFTWARE'] . "</li>";
echo "<li><strong>MySQL Version:</strong> " . $wpdb->db_version() . "</li>";
echo "<li><strong>PHP Memory Limit:</strong> " . ini_get('memory_limit') . "</li>";
echo "<li><strong>WP Memory Limit:</strong> " . WP_MEMORY_LIMIT . "</li>";
echo "<li><strong>WP Max Memory Limit:</strong> " . WP_MAX_MEMORY_LIMIT . "</li>";
echo "</ul>";

// 3. Active Theme
$theme = wp_get_theme();
echo "<h2>Active Theme</h2>";
echo "<ul>";
echo "<li><strong>Name:</strong> " . $theme->get('Name') . "</li>";
echo "<li><strong>Version:</strong> " . $theme->get('Version') . "</li>";
echo "<li><strong>Author:</strong> " . $theme->get('Author') . "</li>";
echo "<li><strong>Parent Theme:</strong> " . ($theme->parent() ? $theme->parent()->get('Name') : 'None') . "</li>";
echo "</ul>";

// 4. Active Plugins
echo "<h2>Active Plugins</h2>";
$plugins = get_option('active_plugins');
echo "<ul>";
if ($plugins) {
    foreach ($plugins as $plugin_file) {
        $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin_file);
        echo "<li><strong>" . $plugin_data['Name'] . "</strong> (v" . $plugin_data['Version'] . ")</li>";
    }
} else {
    echo "<li>No active plugins found.</li>";
}
echo "</ul>";

// 5. Must-Use Plugins
echo "<h2>Must-Use Plugins</h2>";
$mu_plugins = get_mu_plugins();
echo "<ul>";
if ($mu_plugins) {
    foreach ($mu_plugins as $mu_plugin_file => $mu_plugin_data) {
        echo "<li><strong>" . $mu_plugin_data['Name'] . "</strong> (v" . $mu_plugin_data['Version'] . ")</li>";
    }
} else {
    echo "<li>No must-use plugins found.</li>";
}
echo "</ul>";

?>
