<?php
require_once('wp-load.php');
echo '<h1>📦 Clean Start & OGS Amplifiers Only Import</h1>';

if (!class_exists('WooCommerce')) { die("❌ WooCommerce not active."); }

// 1. FLUSH ALL PRODUCTS
echo '<h2>1. Flushing All Products...</h2>';
$old_products = get_posts(array('post_type' => 'product', 'numberposts' => -1, 'post_status' => 'any', 'fields' => 'ids'));
foreach ($old_products as $id) {
    wp_delete_post($id, true); // Force delete
}
echo "<p>✅ Deleted " . count($old_products) . " old products.</p>";

// 2. DELETE GLOBAL ATTRIBUTES (Specifically "Series")
echo '<h2>2. Deleting Global Attributes...</h2>';
$attribute_name_to_delete = 'Series';
$attribute_slug_to_delete = 'pa_series';

$attribute_id = wc_attribute_taxonomy_id_by_name($attribute_name_to_delete);
if ($attribute_id) {
    global $wpdb;
    $wpdb->delete($wpdb->prefix . 'woocommerce_attribute_taxonomies', array('attribute_id' => $attribute_id));
    $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "woocommerce_termmeta"); // Delete associated terms table if it was created
    $wpdb->query("DELETE FROM {$wpdb->prefix}terms WHERE taxonomy = '{$attribute_slug_to_delete}'");
    $wpdb->query("DELETE FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy = '{$attribute_slug_to_delete}'");
    echo "<p>✅ Deleted global attribute: <strong>{$attribute_name_to_delete}</strong></p>";
} else {
    echo "<p>ℹ️ Global attribute '{$attribute_name_to_delete}' not found.</p>";
}
echo "<p>✅ Global attributes cleanup complete.</p>";


// 3. CREATE / VERIFY CORE CATEGORIES (Simplified)
function tpo_ensure_cat_exists($name) {
    $term = get_term_by('name', $name, 'product_cat');
    if ($term) {
        echo "<p>ℹ️ Category exists: <strong>$name</strong></p>";
        return $term->term_id;
    } else {
        $result = wp_insert_term($name, 'product_cat');
        if (!is_wp_error($result)) {
            echo "<p>✅ Created Category: <strong>$name</strong></p>";
            return $result['term_id'];
        } else {
            echo "<p>❌ Error creating category $name: " . $result->get_error_message() . "</p>";
            return 0;
        }
    }
}
echo '<h2>3. Ensuring Core Categories Exist...</h2>';
$cat_amps = tpo_ensure_cat_exists('Amplifiers');
$cat_subs = tpo_ensure_cat_exists('Subwoofers');
$cat_spks = tpo_ensure_cat_exists('Audio Speakers');
$cat_box  = tpo_ensure_cat_exists('Empty Enclosures');
$cat_wiring  = tpo_ensure_cat_exists('Wiring Kits');

echo "<p>✅ Core categories ensured.</p>";

// 4. IMPORT ONLY OGS AMPLIFIERS
echo '<h2>4. Importing OGS Amplifiers...</h2>';
$ogs_amplifiers = array(
    array('name' => 'OGS1200A.1D AMPLIFIER', 'sku' => 'OGS1200A.1D', 'cat' => 'Amplifiers', 'price' => 320.00),
    array('name' => 'OGS1500A 1D AMPLIFIER', 'sku' => 'OGS1500A.1D', 'cat' => 'Amplifiers', 'price' => 390.00),
    array('name' => 'OGS2000A 1D AMPLIFIER', 'sku' => 'OGS2000A.1D', 'cat' => 'Amplifiers', 'price' => 520.00),
    array('name' => 'OGS3500A.1D AMPLIFIER', 'sku' => 'OGS3500A.1D', 'cat' => 'Amplifiers', 'price' => 948.00),
    array('name' => 'OGS4500A.1D AMPLIFIER', 'sku' => 'OGS4500A.1D', 'cat' => 'Amplifiers', 'price' => 899.00),
);

foreach ($ogs_amplifiers as $item) {
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
    $product->set_sku($item['sku']);
    $product->set_regular_price($item['price']);
    $product->set_description("High performance {$item['name']} from OG Audio. Perfect for a powerful car audio system.");
    $product->set_short_description("Top-tier {$item['name']} from OG Audio.");
    $product->set_status("publish");
    $product->set_catalog_visibility('visible');
    
    $cat_id = tpo_ensure_cat_exists($item['cat']);
    if ($cat_id) {
        $product->set_category_ids(array($cat_id));
    }
    $product->save();
    echo "<p>✅ Created: <strong>{$item['name']}</strong> (SKU: {$item['sku']}) in [{$item['cat']}]</p>";
}

echo "<h2>🚀 CLEAN START & OGS AMPLIFIERS IMPORT COMPLETE.</h2>";
?>
