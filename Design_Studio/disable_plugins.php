<?php
require_once('wp-load.php');
echo '<h1>🔪 Plugin Slayer</h1>';

$keep = array(
    'woocommerce/woocommerce.php',
    'godaddy-payments/godaddy-payments.php'
);

$active_plugins = get_option('active_plugins');
$new_plugins = array();

echo "<ul>";
foreach ($active_plugins as $plugin) {
    if (in_array($plugin, $keep)) {
        $new_plugins[] = $plugin;
        echo "<li>✅ Keeping: $plugin</li>";
    } else {
        echo "<li>❌ Disabling: $plugin</li>";
    }
}
echo "</ul>";

update_option('active_plugins', $new_plugins);
echo "<h2>🚀 Plugins Updated. Only Core Commerce is active.</h2>";
?>
