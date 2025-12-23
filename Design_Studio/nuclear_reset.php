<?php
/**
 * Nuclear Reset Script
 * Forces default theme, disables all plugins, resets .htaccess and flushes cache.
 * Use as a last resort to bring a broken WordPress site back to a functional, barebones state.
 */
define('WP_USE_THEMES', false);
require_once('wp-load.php');

echo '<h1>☢️ NUCLEAR RESET IN PROGRESS ☢️</h1>';
echo '<p>Attempting to restore site to a barebones functional state...</p>';

// --- 1. FORCE DEFAULT THEME ---
echo '<h2>1. Forcing Default Theme...</h2>';
$default_theme = 'twentytwentyfive';
$check = wp_get_theme($default_theme);
if (!$check->exists()) {
    $default_theme = 'twentytwentyfour'; // Fallback
    $check = wp_get_theme($default_theme);
    if (!$check->exists()) {
        echo "<p>❌ No default themes found! Cannot force a safe theme. Site might remain broken.</p>";
        // Attempt to proceed but warn
    }
}
update_option('template', $default_theme);
update_option('stylesheet', $default_theme);
echo "<p>✅ Theme forced to: <strong>$default_theme</strong></p>";

// --- 2. DISABLE ALL PLUGINS ---
echo '<h2>2. Disabling All Regular Plugins...</h2>';
update_option('active_plugins', array());
echo "<p>✅ All regular plugins disabled.</p>";

// --- 3. DISABLE ALL MU-PLUGINS (by moving) ---
echo '<h2>3. Disabling All Must-Use Plugins...</h2>';
$mu_plugins_dir = WPMU_PLUGIN_DIR;
$mu_plugins_disabled_dir = $mu_plugins_dir . '/disabled';

if (!file_exists($mu_plugins_disabled_dir)) {
    mkdir($mu_plugins_disabled_dir);
}

$mu_plugins = array_diff(scandir($mu_plugins_dir), array('.', '..', 'disabled'));
$moved_count = 0;
foreach ($mu_plugins as $plugin_file) {
    if (pathinfo($plugin_file, PATHINFO_EXTENSION) === 'php') {
        $source = $mu_plugins_dir . '/' . $plugin_file;
        $destination = $mu_plugins_disabled_dir . '/' . $plugin_file;
        if (rename($source, $destination)) {
            echo "<p>✅ Moved MU-Plugin: $plugin_file</p>";
            $moved_count++;
        } else {
            echo "<p>❌ Failed to move MU-Plugin: $plugin_file</p>";
        }
    }
}
echo "<p>✅ Moved $moved_count MU-Plugins to 'disabled' folder.</p>";

// --- 4. RESET .HTACCESS TO WORDPRESS DEFAULTS ---
echo '<h2>4. Resetting .htaccess...</h2>';
$htaccess_content = "
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress
";
file_put_contents(ABSPATH . '.htaccess', $htaccess_content);
echo "<p>✅ .htaccess reset to WordPress defaults.</p>";


// --- 5. FLUSH ALL CACHES ---
echo '<h2>5. Flushing All Caches...</h2>';
wp_cache_flush();
flush_rewrite_rules();
echo "<p>✅ Cache and Rewrite Rules flushed.</p>";

echo '<h1>✅ NUCLEAR RESET COMPLETE.</h1>';
echo '<p>Your site is now in its most basic, stable state.</p>';
echo '<p>Go to your homepage now: <a href="' . home_url() . '">' . home_url() . '</a></p>';
?>