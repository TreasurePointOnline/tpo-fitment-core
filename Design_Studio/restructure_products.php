<?php
require_once('wp-load.php');
echo '<h1>📦 OG Product Restructure & Series Import</h1>';

if (!class_exists('WooCommerce')) { die("❌ WooCommerce not active."); }

// Helper to create/get category
function tpo_create_or_get_cat($name, $parent_id = 0) {
    $term = get_term_by('name', $name, 'product_cat');
    if ($term) {
        return $term->term_id;
    } else {
        $result = wp_insert_term($name, 'product_cat', array('parent' => $parent_id));
        return is_wp_error($result) ? 0 : $result['term_id'];
    }
}

// 1. CLEAN UP UNUSED CATEGORIES (Specific Deletions)
echo '<h2>1. Cleaning Up Categories...</h2>';
$categories_to_delete = array(
    'OG Gold Subwoofers', 'OG Silver Subwoofers', 'OG Bronze Subwoofers',
    'OGPro', 'OG'
);
foreach ($categories_to_delete as $cat_name) {
    $term = get_term_by('name', $cat_name, 'product_cat');
    if ($term) {
        wp_delete_term($term->term_id, 'product_cat');
        echo "<p>🗑️ Deleted category: <strong>$cat_name</strong></p>";
    }
}
echo "<p>✅ Category cleanup complete.</p>";

// 2. ENSURE ROOT CATEGORIES (The 5 Core Ones)
echo '<h2>2. Ensuring Core Categories Exist...</h2>';
$core_categories = array(
    'Amplifiers', 'Subwoofers', 'Audio Speakers', 'Empty Enclosures', 'Amp Kits & Accessories'
);
foreach($core_categories as $cat_name) {
    tpo_create_or_get_cat($cat_name);
    echo "<p>✅ Ensured: <strong>$cat_name</strong></p>";
}

// 3. CREATE GLOBAL PRODUCT ATTRIBUTE: "Series"
echo '<h2>3. Creating Product Attribute: "Series"...</h2>';
$attribute_name = 'Series';
$attribute_slug = 'series';
$attribute_id = wc_attribute_taxonomy_id_by_name($attribute_name);

if (!$attribute_id) {
    $attribute_id = wc_create_attribute(array(
        'name'         => $attribute_name,
        'slug'         => $attribute_slug,
        'type'         => 'select', // Can be select, text, etc.
        'order_by'     => 'menu_order',
        'has_archives' => true,
    ));
    if (!is_wp_error($attribute_id)) {
        echo "<p>✅ Created global attribute: <strong>$attribute_name</strong></p>";
    } else {
        echo "<p>❌ Error creating attribute: " . $attribute_id->get_error_message() . "</p>";
    }
} else {
    echo "<p>ℹ️ Global attribute 'Series' already exists (ID: $attribute_id).</p>";
}

// 4. FLUSH ALL EXISTING PRODUCTS TO START FRESH WITH NEW STRUCTURE
echo '<h2>4. Flushing All Products...</h2>';
$old_products = get_posts(array('post_type' => 'product', 'numberposts' => -1, 'post_status' => 'any', 'fields' => 'ids'));
foreach ($old_products as $id) {
    wp_delete_post($id, true);
}
echo "<p>✅ Deleted all old products.</p>";

// 5. IMPORT PRODUCTS WITH NEW ATTRIBUTES
echo '<h2>5. Importing OG Products with Series...</h2>';

// Define the products with their top-level category and series
$og_products_data = array(
    // Amplifiers
    array('name' => 'OGS1200A.1D', 'cat' => 'Amplifiers', 'series' => 'OGS Series', 'price' => 199.99),
    array('name' => 'OGS1500A 1D', 'cat' => 'Amplifiers', 'series' => 'OGS Series', 'price' => 249.99),
    array('name' => 'OGS2000A 1D', 'cat' => 'Amplifiers', 'series' => 'OGS Series', 'price' => 299.99),
    array('name' => 'OGS3500A.1D', 'cat' => 'Amplifiers', 'series' => 'OGS Series', 'price' => 499.99),
    array('name' => 'OGS4500A.1D', 'cat' => 'Amplifiers', 'series' => 'OGS Series', 'price' => 599.99),

    // Subwoofers - Gold
    array('name' => 'Gold-OGWZ', 'cat' => 'Subwoofers', 'series' => 'OG Gold Series', 'price' => 399.99),
    array('name' => 'OG WZ 12"', 'cat' => 'Subwoofers', 'series' => 'OG Gold Series', 'price' => 349.99),
    array('name' => 'OG WZ 15"', 'cat' => 'Subwoofers', 'series' => 'OG Gold Series', 'price' => 389.99),

    // Subwoofers - Silver
    array('name' => 'Silver-OGWX', 'cat' => 'Subwoofers', 'series' => 'OG Silver Series', 'price' => 249.99),
    array('name' => 'OG WX 12"', 'cat' => 'Subwoofers', 'series' => 'OG Silver Series', 'price' => 199.99),
    array('name' => 'OG WX 15"', 'cat' => 'Subwoofers', 'series' => 'OG Silver Series', 'price' => 229.99),
    array('name' => 'Silver-OGX', 'cat' => 'Subwoofers', 'series' => 'OG Silver Series', 'price' => 249.99),
    array('name' => 'OG X 12"', 'cat' => 'Subwoofers', 'series' => 'OG Silver Series', 'price' => 199.99),
    array('name' => 'OG X 15"', 'cat' => 'Subwoofers', 'series' => 'OG Silver Series', 'price' => 229.99),

    // Subwoofers - Bronze
    array('name' => 'Bronze-OGWS', 'cat' => 'Subwoofers', 'series' => 'OG Bronze Series', 'price' => 149.99),
    array('name' => 'OG WSS 12"', 'cat' => 'Subwoofers', 'series' => 'OG Bronze Series', 'price' => 99.99),
    array('name' => 'OG WS 12"', 'cat' => 'Subwoofers', 'series' => 'OG Bronze Series', 'price' => 119.99),
    array('name' => 'OG WS 15"', 'cat' => 'Subwoofers', 'series' => 'OG Bronze Series', 'price' => 139.99),

    // Audio Speakers
    array('name' => 'OGPro065', 'cat' => 'Audio Speakers', 'series' => 'OGPro Series', 'price' => 89.99),
    array('name' => 'OGPro069', 'cat' => 'Audio Speakers', 'series' => 'OGPro Series', 'price' => 99.99),
    array('name' => 'OGPro08', 'cat' => 'Audio Speakers', 'series' => 'OGPro Series', 'price' => 119.99, 'desc' => 'Coming Soon'),

    // Empty Enclosures
    array('name' => '12" OG 1x12', 'cat' => 'Empty Enclosures', 'series' => 'OG Enclosures', 'price' => 149.99),
    array('name' => '10" OG 2x10', 'cat' => 'Empty Enclosures', 'series' => 'OG Enclosures', 'price' => 179.99),
    array('name' => '12" OG 2x12', 'cat' => 'Empty Enclosures', 'series' => 'OG Enclosures', 'price' => 199.99),
);

foreach ($og_products_data as $item) {
    // Check if product exists by title (SKU will be name for now)
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
    
    // Get Category ID
    $cat_id = tpo_create_or_get_cat($item['cat']);
    if (!$cat_id) {
        echo "<p>❌ Skipping '{$item['name']}': Invalid category ID for '{$item['cat']}'.</p>";
        continue;
    }

    $product = new WC_Product_Simple();
    $product->set_name($item['name']);
    $product->set_sku($item['name']); // Use name as SKU for now
    $product->set_regular_price($item['price']);
    $product->set_description(isset($item['desc']) ? $item['desc'] : "Authentic OG Audio Gear.");
    $product->set_status("publish");
    $product->set_catalog_visibility('visible');
    $product->set_category_ids(array($cat_id));
    
    // Set 'Series' attribute
    if (!empty($item['series'])) {
        $attribute_term = get_term_by('name', $item['series'], 'pa_series');
        if (!$attribute_term) {
            $attribute_term = wp_insert_term($item['series'], 'pa_series');
            if (is_wp_error($attribute_term)) {
                echo "<p>❌ Error creating Series term '{$item['series']}': " . $attribute_term->get_error_message() . "</p>";
                continue;
            }
            $attribute_term_id = $attribute_term['term_id'];
        } else {
            $attribute_term_id = $attribute_term->term_id;
        }

        $product_attributes = $product->get_attributes();
        $product_attributes['pa_series'] = new WC_Product_Attribute();
        $product_attributes['pa_series']->set_id($attribute_id); // Use global attribute ID
        $product_attributes['pa_series']->set_name('Series');
        $product_attributes['pa_series']->set_options(array($attribute_term_id));
        $product_attributes['pa_series']->set_visible(true);
        $product_attributes['pa_series']->set_variation(false);
        $product->set_attributes($product_attributes);
    }

    $product->save();
    echo "<p>✅ Created: <strong>{$item['name']}</strong> (SKU: {$item['name']}) in [{$item['cat']}]</p>";
}

echo "<h2>🚀 PRODUCT RESTRUCTURE COMPLETE.</h2>";
?>
