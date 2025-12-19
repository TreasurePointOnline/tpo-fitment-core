<?php
require_once('wp-load.php');
echo '<h1>💳 GoDaddy/Poynt Settings Check</h1>';

// Try standard name
$settings_godaddy = get_option('woocommerce_godaddy_payments_settings');
// Try old/internal name
$settings_poynt = get_option('woocommerce_poynt_settings');
// Try general gateway settings pattern
$settings_gateway = get_option('woocommerce_poynt_credit_card_settings');

function print_settings($name, $settings) {
    echo "<h2>Option: $name</h2>";
    if ($settings) {
        echo "<pre>";
        // Sanitize
        if (is_array($settings)) {
            if (isset($settings['application_id'])) $settings['application_id'] = '*****' . substr($settings['application_id'], -4);
            if (isset($settings['private_key'])) $settings['private_key'] = '*****KEY*****';
            if (isset($settings['api_key'])) $settings['api_key'] = '*****KEY*****';
            echo json_encode($settings, JSON_PRETTY_PRINT);
        } else {
            echo "Value: " . $settings;
        }
        echo "</pre>";
    } else {
        echo "<p>❌ Not found.</p>";
    }
}

print_settings('woocommerce_godaddy_payments_settings', $settings_godaddy);
print_settings('woocommerce_poynt_settings', $settings_poynt);
print_settings('woocommerce_poynt_credit_card_settings', $settings_gateway);

?>