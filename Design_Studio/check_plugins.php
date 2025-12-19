<?php
require_once('wp-load.php');
echo '<h1>🔌 Active Plugins Check</h1>';

$plugins = get_option('active_plugins');
$found_godaddy = false;

echo "<ul>";
if ($plugins) {
    foreach ($plugins as $plugin_file) {
        $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin_file);
        $name = $plugin_data['Name'];
        echo "<li><strong>$name</strong> ($plugin_file)</li>";
        
        if (stripos($name, 'GoDaddy') !== false || stripos($name, 'Poynt') !== false) {
            $found_godaddy = true;
        }
    }
}
echo "</ul>";

if ($found_godaddy) {
    echo "<h2>✅ GoDaddy Plugin Found!</h2>";
    echo "<p>Now we need to check if the GATEWAY is enabled in WooCommerce settings.</p>";
} else {
    echo "<h2>❌ GoDaddy Payments Plugin NOT Active.</h2>";
    echo "<p>You need to activate it in the Plugins menu first.</p>";
}

// Check WooCommerce Gateways
if (class_exists('WC_Payment_Gateways')) {
    echo "<h2>💳 WooCommerce Gateways Status</h2>";
    $gateways = WC()->payment_gateways->payment_gateways();
    echo "<ul>";
    foreach ( $gateways as $gateway ) {
        echo "<li>" . $gateway->title . " (ID: " . $gateway->id . ") - Enabled: <strong>" . $gateway->enabled . "</strong></li>";
    }
    echo "</ul>";
}
?>
