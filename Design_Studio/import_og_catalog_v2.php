<?php
require_once('wp-load.php');
echo '<h1>📦 OG Catalog Import (v2)</h1>';

if (!class_exists('WooCommerce')) { die("❌ WooCommerce not active."); }

// 1. FLUSH OLD DATA
echo '<h2>1. Flushing Old Inventory...</h2>';
$old_products = get_posts(array('post_type' => 'product', 'numberposts' => -1, 'post_status' => 'any', 'fields' => 'ids'));
foreach ($old_products as $id) {
    wp_delete_post($id, true); // Force delete
}
echo "<p>✅ Deleted " . count($old_products) . " old products.</p>";

// 2. CREATE CATEGORY HIERARCHY
function create_cat_if_not_exists($name, $parent_id = 0) {
    $term = get_term_by('name', $name, 'product_cat');
    if ($term) {
        echo "<p>ℹ️ Category exists: <strong>$name</strong></p>";
        return $term->term_id;
    } else {
        $result = wp_insert_term($name, 'product_cat', array('parent' => $parent_id));
        if (!is_wp_error($result)) {
            echo "<p>✅ Created Category: <strong>$name</strong></p>";
            return $result['term_id'];
        } else {
            echo "<p>❌ Error creating category $name: " . $result->get_error_message() . "</p>";
            return 0; // Failure
        }
    }
}

echo '<h2>2. Building Categories...</h2>';
// Root Categories
$cat_amps = create_cat_if_not_exists('Amplifiers');
$cat_subs = create_cat_if_not_exists('Subwoofers');
$cat_spks = create_cat_if_not_exists('Audio Speakers');
$cat_box  = create_cat_if_not_exists('Empty Enclosures');
$cat_acc  = create_cat_if_not_exists('Amp Kits & Accessories');

// Sub-Categories (ensure parent is valid)
$cat_subs_gold = $cat_subs ? create_cat_if_not_exists('OG Gold Subwoofers', $cat_subs) : 0;
$cat_subs_silver = $cat_subs ? create_cat_if_not_exists('OG Silver Subwoofers', $cat_subs) : 0;
$cat_subs_bronze = $cat_subs ? create_cat_if_not_exists('OG Bronze Subwoofers', $cat_subs) : 0;
$cat_spks_pro = $cat_spks ? create_cat_if_not_exists('OGPro', $cat_spks) : 0;
$cat_box_og = $cat_box ? create_cat_if_not_exists('OG', $cat_box) : 0;


// 3. IMPORT PRODUCTS
$inventory = array(
    // Amps
    array('name' => 'OGS1200A.1D', 'cat' => $cat_amps, 'price' => 199.99),
    array('name' => 'OGS1500A 1D', 'cat' => $cat_amps, 'price' => 249.99),
    array('name' => 'OGS2000A 1D', 'cat' => $cat_amps, 'price' => 299.99),
    array('name' => 'OGS3500A.1D', 'cat' => $cat_amps, 'price' => 499.99),
    array('name' => 'OGS4500A.1D', 'cat' => $cat_amps, 'price' => 599.99),

    // Gold Subs
    array('name' => 'Gold-OGWZ', 'cat' => $cat_subs_gold, 'price' => 399.99),
    array('name' => 'OG WZ 12"', 'cat' => $cat_subs_gold, 'price' => 349.99),
    array('name' => 'OG WZ 15"', 'cat' => $cat_subs_gold, 'price' => 389.99),

    // Silver Subs
    array('name' => 'Silver-OGWX', 'cat' => $cat_subs_silver, 'price' => 249.99),
    array('name' => 'OG WX 12"', 'cat' => $cat_subs_silver, 'price' => 199.99),
    array('name' => 'OG WX 15"', 'cat' => $cat_subs_silver, 'price' => 229.99),
    array('name' => 'Silver-OGX', 'cat' => $cat_subs_silver, 'price' => 249.99),
    array('name' => 'OG X 12"', 'cat' => $cat_subs_silver, 'price' => 199.99),
    array('name' => 'OG X 15"', 'cat' => $cat_subs_silver, 'price' => 229.99),

    // Bronze Subs
    array('name' => 'Bronze-OGWS', 'cat' => $cat_subs_bronze, 'price' => 149.99),
    array('name' => 'OG WSS 12"', 'cat' => $cat_subs_bronze, 'price' => 99.99),
    array('name' => 'OG WS 12"', 'cat' => $cat_subs_bronze, 'price' => 119.99),
    array('name' => 'OG WS 15"', 'cat' => $cat_subs_bronze, 'price' => 139.99),

    // Speakers
    array('name' => 'OGPro065', 'cat' => $cat_spks_pro, 'price' => 89.99),
    array('name' => 'OGPro069', 'cat' => $cat_spks_pro, 'price' => 99.99),
    array('name' => 'OGPro08', 'cat' => $cat_spks_pro, 'price' => 119.99, 'desc' => 'Coming Soon'),

    // Enclosures
    array('name' => '12" OG 1x12', 'cat' => $cat_box_og, 'price' => 149.99),
    array('name' => '10" OG 2x10', 'cat' => $cat_box_og, 'price' => 179.99),
    array('name' => '12" OG 2x12', 'cat' => $cat_box_og, 'price' => 199.99),
);

echo '<h2>3. Importing Products...</h2>';
foreach ($inventory as $item) {
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
    
    // Ensure category ID is valid
    if (!$item['cat']) {
        echo "<p>❌ Skipping '{$item['name']}': Invalid category ID.</p>";
        continue;
    }

    $product = new WC_Product_Simple();
    $product->set_name($item['name']);
    $product->set_regular_price($item['price']);
    $product->set_description(isset($item['desc']) ? $item['desc'] : "Authentic OG Audio Gear.");
    $product->set_status("publish");
    $product->set_catalog_visibility('visible');
    $product->set_category_ids(array($item['cat']));
    $product->save();
    echo "<p>✅ Created: <strong>{$item['name']}</strong> in [Category ID: {$item['cat']}]</p>";
}

echo "<h2>🚀 CATALOG IMPORT COMPLETE.</h2>";
?>
