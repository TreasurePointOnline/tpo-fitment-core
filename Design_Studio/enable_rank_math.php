<?php
require_once('wp-load.php');
require_once(ABSPATH . 'wp-admin/includes/plugin.php');

echo '<h1>✅ Enabling Rank Math SEO</h1>';

$plugin_path = 'seo-by-rank-math/rank-math.php';

if (is_plugin_active($plugin_path)) {
    echo "<p>ℹ️ Rank Math is already active.</p>";
} else {
    $result = activate_plugin($plugin_path);
    if (is_wp_error($result)) {
        echo "<p>❌ Failed to activate Rank Math: " . $result->get_error_message() . "</p>";
    } else {
        echo "<p>✅ Rank Math SEO activated.</p>";
    }
}
?>
