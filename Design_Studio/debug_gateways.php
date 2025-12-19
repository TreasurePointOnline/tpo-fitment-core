<?php
require_once('wp-load.php');
echo '<h1>💳 Payment Gateway Debugger</h1>';

if (!class_exists('WooCommerce')) {
    die("❌ WooCommerce is not active.");
}

// Get all available gateways
$gateways = WC()->payment_gateways->payment_gateways();
$found_godaddy = false;

echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Gateway ID</th><th>Title</th><th>Enabled?</th><th>Setup Status</th></tr>";

foreach ( $gateways as $gateway ) {
    $id = $gateway->id;
    $title = $gateway->title;
    $enabled = $gateway->enabled;
    
    // Check specific settings for GoDaddy/Poynt
    $settings_summary = "";
    if (stripos($id, 'poynt') !== false || stripos($id, 'godaddy') !== false) {
        $found_godaddy = true;
        
        // Check for common connection settings
        $merchant = $gateway->get_option('merchant_id');
        $app_id = $gateway->get_option('application_id');
        $api_key = $gateway->get_option('api_key');
        
        if ($merchant || $app_id || $api_key) {
            $settings_summary = "✅ Credentials Detected (Merchant ID present)";
        } else {
            $settings_summary = "❌ <strong>MISSING CREDENTIALS</strong> (Account not connected)";
        }
    } else {
        $settings_summary = "n/a";
    }

    $color = ($enabled === 'yes') ? '#dff0d8' : '#f8d7da';
    
    echo "<tr style='background-color: $color'>";
    echo "<td><strong>$id</strong></td>";
    echo "<td>$title</td>";
    echo "<td>$enabled</td>";
    echo "<td>$settings_summary</td>";
    echo "</tr>";
}
echo "</table>";

if ($found_godaddy) {
    echo "<h3>Analysis:</h3>";
    echo "<p>If the GoDaddy gateway is <strong>Enabled: no</strong>, we can try to force it ON.</p>";
    echo "<p>If it says <strong>MISSING CREDENTIALS</strong>, you MUST log in to WP Admin > WooCommerce > Settings > Payments and click 'Manage' or 'Connect' on GoDaddy Payments.</p>";
} else {
    echo "<h3>⚠️ Weird...</h3>";
    echo "<p>The GoDaddy plugin is active, but WooCommerce didn't register a gateway for it. Try deactivating and reactivating the plugin in WP Admin.</p>";
}
?>
