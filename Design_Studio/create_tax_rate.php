<?php
require_once('wp-load.php');
echo '<h1>💰 WooCommerce Tax Rate Creator (Direct DB)</h1>';

global $wpdb;

// Tax Rate Data
$rate = array(
    'tax_rate_country'  => 'US',
    'tax_rate_state'    => 'IN',
    'tax_rate'          => '7.0000',
    'tax_rate_name'     => 'Indiana Sales Tax',
    'tax_rate_priority' => 1,
    'tax_rate_compound' => 0,
    'tax_rate_shipping' => 1,
    'tax_rate_order'    => 0,
    'tax_rate_class'    => '',
);

// 1. Check if it exists
$exists = $wpdb->get_var( $wpdb->prepare(
    "SELECT tax_rate_id FROM {$wpdb->prefix}woocommerce_tax_rates 
    WHERE tax_rate_country = %s AND tax_rate_state = %s AND tax_rate_name = %s",
    $rate['tax_rate_country'], 
    $rate['tax_rate_state'], 
    $rate['tax_rate_name']
));

if ($exists) {
    echo "<p>ℹ️ Tax Rate already exists (ID: $exists).</p>";
} else {
    // 2. Insert into DB
    $result = $wpdb->insert(
        "{$wpdb->prefix}woocommerce_tax_rates",
        $rate,
        array('%s', '%s', '%s', '%s', '%d', '%d', '%d', '%d', '%s')
    );

    if ($result) {
        $rate_id = $wpdb->insert_id;
        echo "<h1>✅ SUCCESS: Indiana Tax Rate Created! (ID: $rate_id)</h1>";
        echo "<p>Rate: 7% for Indiana, USA.</p>";
    } else {
        echo "<h1>❌ Database Error</h1>";
        echo "<p>" . $wpdb->last_error . "</p>";
    }
}

// 3. Ensure "Enable Taxes" is turned ON in WooCommerce settings
update_option('woocommerce_calc_taxes', 'yes');
echo "<p>✅ 'Enable Taxes' setting forced to YES.</p>";

?>