<?php
require_once('wp-load.php');
echo '<h1>🔄 Updating WooCommerce Products from MasterList</h1>';

if (!class_exists('WooCommerce')) { die("❌ WooCommerce is not active!"); }

global $wpdb;
$masterlist_table = $wpdb->prefix . 'product_masterlist';

// Fetch all products from the masterlist
$master_products = $wpdb->get_results("SELECT * FROM $masterlist_table", ARRAY_A);

if (!$master_products) {
    echo "<p>⚠️ No products found in MasterList table. Please populate it first.</p>";
    die();
}

echo '<h2>Processing ' . count($master_products) . ' products...</h2>';

foreach ($master_products as $ml_product) {
    $product_id = $ml_product['product_id'];
    $wc_product = wc_get_product($product_id);

    if (!$wc_product) {
        echo "<p>❌ WooCommerce product with ID {$product_id} (SKU: {$ml_product['sku']}) not found. Skipping.</p>";
        continue;
    }

    echo "<p>Updating <strong>{$ml_product['name']}</strong> (ID: {$product_id})...</p>";

    // Update Core Product Fields
    $wc_product->set_description($ml_product['description']);
    $wc_product->set_short_description($ml_product['short_description']);
    $wc_product->set_sku($ml_product['sku']);
    $wc_product->set_regular_price($ml_product['regular_price']);
    if (!empty($ml_product['sale_price'])) {
        $wc_product->set_sale_price($ml_product['sale_price']);
    }

    // Set Attributes (specifically 'pa_series')
    $attributes = $wc_product->get_attributes();
    if (!empty($ml_product['attribute_series'])) {
        $attribute_name = 'pa_series'; // Global attribute slug
        $attribute_label = 'Series';   // Global attribute label
        
        // Ensure the term exists for the attribute
        $term_name = $ml_product['attribute_series'];
        $term_slug = sanitize_title($term_name);
        $attribute_taxonomy = 'pa_' . $attribute_slug;

        $term = get_term_by('slug', $term_slug, $attribute_taxonomy);
        if (!$term) {
            $term_result = wp_insert_term($term_name, $attribute_taxonomy);
            if (!is_wp_error($term_result)) {
                $term_id = $term_result['term_id'];
            } else {
                echo "<p>❌ Error creating term '{$term_name}' for Series attribute: " . $term_result->get_error_message() . "</p>";
                $term_id = 0;
            }
        } else {
            $term_id = $term->term_id;
        }

        if ($term_id) {
            $attribute_obj = new WC_Product_Attribute();
            $attribute_obj->set_id(wc_attribute_taxonomy_id_by_name($attribute_label));
            $attribute_obj->set_name($attribute_label);
            $attribute_obj->set_options(array($term_id));
            $attribute_obj->set_position(0);
            $attribute_obj->set_visible(true);
            $attribute_obj->set_variation(false);
            $attributes[$attribute_taxonomy] = $attribute_obj;
        }
    }
    $wc_product->set_attributes($attributes);

    // Update SEO Meta Data (Rank Math)
    if (!empty($ml_product['meta_seo_title'])) {
        update_post_meta($product_id, 'rank_math_title', $ml_product['meta_seo_title']);
        update_post_meta($product_id, '_rank_math_title', $ml_product['meta_seo_title']);
    }
    if (!empty($ml_product['meta_seo_description'])) {
        update_post_meta($product_id, 'rank_math_description', $ml_product['meta_seo_description']);
        update_post_meta($product_id, '_rank_math_description', $ml_product['meta_seo_description']);
    }

    // You can add more updates here for other fields as needed
    // e.g., images, weight, dimensions, etc.

    $wc_product->save();
    echo "<p>✅ Updated WooCommerce product: {$ml_product['name']}</p>";
}

echo '<h2>🚀 WooCommerce Catalog Updated from MasterList.</h2>';
?>
