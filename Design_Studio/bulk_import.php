<?php
require_once('wp-load.php');
echo '<h1>📦 PHASE 3: Bulk Inventory Import (Fixed)</h1>';

if (!class_exists('WooCommerce')) {
    die("❌ WooCommerce is not active.");
}

// Helper to get category ID (Renamed to avoid conflict)
function tpo_get_cat_id($name) {
    $term = get_term_by('name', $name, 'product_cat');
    return $term ? $term->term_id : 0;
}

// THE INVENTORY LIST
$inventory = array(
    // AMPLIFIERS
    array('name' => 'Kicker CXA400.1 Mono Amp', 'price' => 149.99, 'cat' => 'Amplifiers', 'img' => 'https://via.placeholder.com/300x300.png?text=Kicker+Amp'),
    array('name' => 'JL Audio JD1000/1', 'price' => 599.99, 'cat' => 'Amplifiers', 'img' => 'https://via.placeholder.com/300x300.png?text=JL+Amp'),
    array('name' => 'Skar Audio RP-1500.1D', 'price' => 189.99, 'cat' => 'Amplifiers', 'img' => 'https://via.placeholder.com/300x300.png?text=Skar+Amp'),
    array('name' => 'Sundown Audio SALT-4', 'price' => 1099.00, 'cat' => 'Amplifiers', 'img' => 'https://via.placeholder.com/300x300.png?text=Sundown+Amp'),
    
    // SUBWOOFERS
    array('name' => 'Kicker L7 Solobaric 12"', 'price' => 299.99, 'cat' => 'Subwoofers', 'img' => 'https://via.placeholder.com/300x300.png?text=L7+Sub'),
    array('name' => 'Rockford Fosgate P3 D4 12"', 'price' => 179.99, 'cat' => 'Subwoofers', 'img' => 'https://via.placeholder.com/300x300.png?text=P3+Sub'),
    array('name' => 'JL Audio W7AE 13.5"', 'price' => 1399.99, 'cat' => 'Subwoofers', 'img' => 'https://via.placeholder.com/300x300.png?text=W7+Sub'),
    array('name' => 'American Bass XFL 15"', 'price' => 249.99, 'cat' => 'Subwoofers', 'img' => 'https://via.placeholder.com/300x300.png?text=XFL+Sub'),

    // SPEAKERS
    array('name' => 'Hertz SPL Show 6.5"', 'price' => 189.00, 'cat' => 'Speakers', 'img' => 'https://via.placeholder.com/300x300.png?text=Hertz+Spk'),
    array('name' => 'Focal Access 165AS', 'price' => 349.99, 'cat' => 'Speakers', 'img' => 'https://via.placeholder.com/300x300.png?text=Focal+Spk'),
    array('name' => 'Deaf Bonce Arnold 6.5"', 'price' => 129.90, 'cat' => 'Speakers', 'img' => 'https://via.placeholder.com/300x300.png?text=Arnold+Spk'),
    
    // WIRING
    array('name' => '0 Gauge Amp Kit (OFC)', 'price' => 149.99, 'cat' => 'Wiring & Accessories', 'img' => 'https://via.placeholder.com/300x300.png?text=0G+Kit'),
    array('name' => 'RCA Cables 17ft', 'price' => 19.99, 'cat' => 'Wiring & Accessories', 'img' => 'https://via.placeholder.com/300x300.png?text=RCA'),
);

foreach ($inventory as $item) {
    // Check if exists using WP_Query instead of get_page_by_title to avoid deprecation warnings
    $query = new WP_Query(array(
        'post_type' => 'product',
        'title' => $item['name'],
        'posts_per_page' => 1,
        'post_status' => 'any'
    ));

    if ($query->have_posts()) {
        echo "<p>ℹ️ Exists: {$item['name']}</p>";
        continue;
    }

    $product = new WC_Product_Simple();
    $product->set_name($item['name']);
    $product->set_regular_price($item['price']);
    $product->set_description("High performance car audio gear. Authorized Dealer.");
    $product->set_short_description("Brand new {$item['name']} in stock.");
    $product->set_status("publish");
    $product->set_catalog_visibility('visible');
    
    // Assign Category
    $cat_id = tpo_get_cat_id($item['cat']);
    if ($cat_id) {
        $product->set_category_ids(array($cat_id));
    }

    // Save
    $product->save();
    echo "<p>✅ Created: <strong>{$item['name']}</strong> in [{$item['cat']}]</p>";
}

echo "<h2>🚀 INVENTORY IMPORT COMPLETE.</h2>";
echo "<p>Go click 'Amplifiers' in your menu now!</p>";
?>