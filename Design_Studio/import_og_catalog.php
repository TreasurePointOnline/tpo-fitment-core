<?php
require_once('wp-load.php');
echo '<h1>📦 OG Catalog Import</h1>';

if (!class_exists('WooCommerce')) { die("❌ WooCommerce not active."); }

// 1. FLUSH OLD DATA
echo '<h2>1. Flushing Old Inventory...</h2>';
$old_products = get_posts(array('post_type' => 'product', 'numberposts' => -1, 'fields' => 'ids'));
foreach ($old_products as $id) {
    wp_delete_post($id, true); // Force delete
}
echo "<p>✅ Deleted " . count($old_products) . " old products.</p>";

// 2. CREATE CATEGORY HIERARCHY
function create_cat($name, $parent_id = 0) {
    $term = wp_insert_term($name, 'product_cat', array('parent' => $parent_id));
    if (is_wp_error($term)) {
        $term_id = $term->get_error_data('term_exists');
        if (!$term_id) {
            // If checking existence failed, try getting it
            $t = get_term_by('name', $name, 'product_cat');
            return $t ? $t->term_id : 0;
        }
        return $term_id;
    }
    return $term['term_id'];
}

echo '<h2>2. Building Categories...</h2>';
// Root
$cat_amps = create_cat('Amplifiers');
$cat_subs = create_cat('Subwoofers');
$cat_spks = create_cat('Audio Speakers');
$cat_box  = create_cat('Empty Enclosures');
$cat_acc  = create_cat('Amp Kits & Accessories');

// Sub-Cats
$cat_subs_gold = create_cat('OG Gold Subwoofers', $cat_subs);
$cat_subs_silver = create_cat('OG Silver Subwoofers', $cat_subs);
$cat_subs_bronze = create_cat('OG Bronze Subwoofers', $cat_subs);
$cat_spks_pro = create_cat('OGPro', $cat_spks);
$cat_box_og = create_cat('OG', $cat_box);

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
    $product = new WC_Product_Simple();
    $product->set_name($item['name']);
    $product->set_regular_price($item['price']);
    $product->set_description(isset($item['desc']) ? $item['desc'] : "Authentic OG Audio Gear.");
    $product->set_status("publish");
    $product->set_catalog_visibility('visible');
    $product->set_category_ids(array($item['cat']));
    $product->save();
    echo "<p>✅ Created: {$item['name']}</p>";
}

echo "<h2>🚀 CATALOG IMPORT COMPLETE.</h2>";
?>
