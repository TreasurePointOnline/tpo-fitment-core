<?php
require_once('wp-load.php');
echo '<h1>🔄 Restoring Catalog to Working State</h1>';

if (!class_exists('WooCommerce')) { die("❌ WooCommerce is not active."); }

// 1. FLUSH ALL PRODUCTS
echo '<h2>1. Flushing All Products...</h2>';
$old_products = get_posts(array('post_type' => 'product', 'numberposts' => -1, 'post_status' => 'any', 'fields' => 'ids'));
foreach ($old_products as $id) {
    wp_delete_post($id, true); // Force delete
}
echo "<p>✅ Deleted all old products.</p>";

// 2. CREATE / VERIFY CORE CATEGORIES (Simplified)
function tpo_ensure_cat_exists($name) {
    $term = get_term_by('name', $name, 'product_cat');
    if ($term) {
        return $term->term_id;
    } else {
        $result = wp_insert_term($name, 'product_cat');
        return is_wp_error($result) ? 0 : $result['term_id'];
    }
}
echo '<h2>2. Ensuring Core Categories Exist...</h2>';
$cat_amps = tpo_ensure_cat_exists('Amplifiers');
$cat_subs = tpo_ensure_cat_exists('Subwoofers');
$cat_spks = tpo_ensure_cat_exists('Speakers');
$cat_wiring = tpo_ensure_cat_exists('Wiring & Accessories');
$cat_marine = tpo_ensure_cat_exists('Marine Audio'); // Add this back if you want it
$cat_enclosures = tpo_ensure_cat_exists('Empty Enclosures');

echo "<p>✅ Core categories ensured.</p>";

// 3. IMPORT BASIC SAMPLE PRODUCTS (Original working set)
echo '<h2>3. Importing Basic Sample Products...</h2>';
$basic_inventory = array(
    array('name' => 'Kicker CXA400.1 Mono Amp', 'cat' => 'Amplifiers', 'price' => 149.99),
    array('name' => 'JL Audio JD1000/1', 'cat' => 'Amplifiers', 'price' => 599.99),
    array('name' => 'Skar Audio RP-1500.1D', 'cat' => 'Amplifiers', 'price' => 189.99),
    array('name' => 'Kicker L7 Solobaric 12"', 'cat' => 'Subwoofers', 'price' => 299.99),
    array('name' => 'Rockford Fosgate P3 D4 12"', 'cat' => 'Subwoofers', 'price' => 179.99),
    array('name' => 'Hertz SPL Show 6.5"', 'cat' => 'Speakers', 'price' => 189.00),
    array('name' => '0 Gauge Amp Kit (OFC)', 'cat' => 'Wiring & Accessories', 'price' => 149.99),
    array('name' => 'Test Product (0.01 Sale)', 'cat' => 'Uncategorized', 'price' => 0.01), // For payment testing
);

foreach ($basic_inventory as $item) {
    // Check if product already exists by title
    $query = new WP_Query(array(
        'post_type' => 'product',
        'title' => $item['name'],
        'posts_per_page' => 1,
        'post_status' => 'any'
    ));
    if ($query->have_posts()) {
        echo "<p>ℹ️ Product already exists: {$item['name']}. Skipping.</p>";
        continue;
    }

    $product = new WC_Product_Simple();
    $product->set_name($item['name']);
    $product->set_sku($item['name']);
    $product->set_regular_price($item['price']);
    $product->set_status("publish");
    $product->set_catalog_visibility('visible');
    
    // Assign Category
    $cat_id = tpo_ensure_cat_exists($item['cat']);
    if ($cat_id) {
        $product->set_category_ids(array($cat_id));
    }
    $product->save();
    echo "<p>✅ Created: <strong>{$item['name']}</strong> in [{$item['cat']}]</p>";
}

echo "<h2>🚀 CATALOG RESTORE COMPLETE.</h2>";
?>
