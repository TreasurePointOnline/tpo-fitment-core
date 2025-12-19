<?php
require_once('wp-load.php');
echo '<h1>🧪 Adding Test Product & Free Shipping</h1>';

if (!class_exists('WooCommerce')) {
    die("❌ WooCommerce is not active.");
}

// 1. ADD TEST PRODUCT
$product_name = 'Test Product (0.01 Sale)';
$product_price = 0.01;

// Check if exists
$query = new WP_Query(array(
    'post_type' => 'product',
    'title' => $product_name,
    'posts_per_page' => 1,
    'post_status' => 'any'
));

if ($query->have_posts()) {
    echo "<p>ℹ️ Test Product already exists: $product_name</p>";
} else {
    $product = new WC_Product_Simple();
    $product->set_name($product_name);
    $product->set_regular_price($product_price);
    $product->set_description("This is a special product for testing payment gateway functionality. Price: $0.01.");
    $product->set_short_description("Test product for payment gateway testing.");
    $product->set_status("publish");
    $product->set_catalog_visibility('visible');
    $product->save();
    echo "<p>✅ Created Test Product: <strong>$product_name</strong> ($$product_price)</p>";
}

// 2. ENABLE FREE SHIPPING (Temporarily)
// Get all shipping zones
$shipping_zones = WC_Shipping_Zones::get_zones();
$free_shipping_enabled = false;

// Check if Free Shipping already exists in any zone
foreach ( $shipping_zones as $zone ) {
    $shipping_methods = $zone->get_shipping_methods();
    foreach ( $shipping_methods as $method ) {
        if ( $method->id === 'free_shipping' && $method->instance_id ) {
            $method->set_enabled('yes');
            $method->save();
            echo "<p>ℹ️ Free Shipping already enabled in zone: {$zone->get_zone_name()}</p>";
            $free_shipping_enabled = true;
            break 2; // Break both loops
        }
    }
}

if (!$free_shipping_enabled) {
    // If no free shipping method exists, add one to the "Rest of the World" zone
    $rest_of_world_zone = new WC_Shipping_Zone(0); // Zone ID 0 is "Rest of the World"
    $free_shipping_instance_id = $rest_of_world_zone->add_shipping_method('free_shipping');
    if ($free_shipping_instance_id) {
        $free_shipping_method = new WC_Shipping_Free_Shipping($free_shipping_instance_id);
        $free_shipping_method->set_option('requires', 'min_amount');
        $free_shipping_method->set_option('min_amount', '0.01'); // Requires min amount to trigger
        $free_shipping_method->set_enabled('yes');
        $free_shipping_method->save();
        echo "<p>✅ Enabled Free Shipping (min 0.01) for 'Rest of the World' zone.</p>";
    } else {
        echo "<p>❌ Failed to add Free Shipping method.</p>";
    }
}

echo "<h2>🚀 Test Setup Complete.</h2>";
echo "<p>Go to the Shop page, find '{$product_name}', add to cart, and try to checkout!</p>";
?>
