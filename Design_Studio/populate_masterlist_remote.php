<?php
require_once('wp-load.php');
echo '<h1>🤖 AI MasterList Populator</h1>';

if (!class_exists('WooCommerce')) { die("❌ WooCommerce is not active!"); }

global $wpdb;
$masterlist_table = $wpdb->prefix . 'product_masterlist';

// Helper to get category names
function tpo_get_product_categories($product_id) {
    $terms = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'names'));
    return implode(', ', $terms);
}

// Helper to get a product attribute value
function tpo_get_product_attribute($product, $attribute_slug) {
    $attributes = $product->get_attributes();
    if (isset($attributes[$attribute_slug])) {
        return $attributes[$attribute_slug]->get_terms()[0]->name; // Assuming single value
    }
    return '';
}

// Function to generate content (MIMICKING AI, as direct API call isn't possible here)
function ai_generate_description($product_name, $category, $series) {
    $base_desc = "Experience the power of the {$product_name} from Treasure Point. ";
    if ($series) {
        $base_desc .= "Part of the renowned {$series} series, this {$category} delivers unparalleled performance. ";
    }
    $base_desc .= "Engineered for enthusiasts, it combines cutting-edge technology with robust design. Upgrade your audio system today!";
    return $base_desc;
}

function ai_generate_meta_title($product_name, $category) {
    return "{$product_name} - {$category} | Treasure Point Audio";
}

function ai_generate_meta_description($product_name, $category) {
    return "Shop the {$product_name}, a high-performance {$category} from Treasure Point Audio. Experience powerful sound with fast shipping.";
}


// 1. Fetch Existing WooCommerce Products
echo '<h2>1. Fetching WooCommerce Products...</h2>';
$args = array(
    'post_type'      => 'product',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
);
$products_query = new WP_Query($args);

if ($products_query->have_posts()) {
    while ($products_query->have_posts()) {
        $products_query->the_post();
        global $product;

        $product_id = $product->get_id();
        $sku = $product->get_sku();
        $name = $product->get_name();
        $categories_str = tpo_get_product_categories($product_id);
        $series_value = tpo_get_product_attribute($product, 'pa_series');

        // Check if product already exists in masterlist
        $existing_master = $wpdb->get_row($wpdb->prepare("SELECT * FROM $masterlist_table WHERE product_id = %d", $product_id));

        // AI Generated Fields (for empty ones)
        $ai_short_desc = $product->get_short_description() ? $product->get_short_description() : ai_generate_description($name, $categories_str, $series_value);
        $ai_desc = $product->get_description() ? $product->get_description() : ai_generate_description($name, $categories_str, $series_value) . " Learn more about its features and specifications.";
        $ai_meta_title = get_post_meta($product_id, '_rank_math_title', true) ? get_post_meta($product_id, '_rank_math_title', true) : ai_generate_meta_title($name, $categories_str);
        $ai_meta_desc = get_post_meta($product_id, '_rank_math_description', true) ? get_post_meta($product_id, '_rank_math_description', true) : ai_generate_meta_description($name, $categories_str);
        $ai_keywords = "{$name}, {$categories_str}, {$series_value}, car audio, audio system, Treasure Point";


        $data = array(
            'product_id'                 => $product_id,
            'sku'                        => $sku,
            'name'                       => $name,
            'type'                       => $product->get_type(),
            'published'                  => ($product->get_status() == 'publish' ? 1 : 0),
            'is_featured'                => ($product->is_featured() ? 1 : 0),
            'visibility_in_catalog'      => $product->get_catalog_visibility(),
            'short_description'          => $ai_short_desc,
            'description'                => $ai_desc,
            'tax_status'                 => $product->get_tax_status(),
            'tax_class'                  => $product->get_tax_class(),
            'in_stock'                   => ($product->is_in_stock() ? 1 : 0),
            'stock'                      => $product->get_stock_quantity(),
            'low_stock_amount'           => get_post_meta($product_id, '_low_stock_amount', true),
            'backorders_allowed'         => $product->get_backorders(),
            'sold_individually'          => ($product->get_sold_individually() ? 1 : 0),
            'weight'                     => $product->get_weight(),
            'length'                     => $product->get_length(),
            'width'                      => $product->get_width(),
            'height'                     => $product->get_height(),
            'allow_customer_reviews'     => ($product->get_reviews_allowed() ? 1 : 0),
            'purchase_note'              => $product->get_purchase_note(),
            'sale_price'                 => $product->get_sale_price(),
            'regular_price'              => $product->get_regular_price(),
            'categories'                 => $categories_str,
            'tags'                       => implode(', ', wp_get_post_terms($product_id, 'product_tag', array('fields' => 'names'))),
            'shipping_class'             => $product->get_shipping_class(),
            'images'                     => get_the_post_thumbnail_url($product_id, 'full'),
            'attribute_series'           => $series_value,
            'meta_seo_title'             => $ai_meta_title,
            'meta_seo_description'       => $ai_meta_desc,
            'ai_generated_desc_status'   => $product->get_short_description() ? 'manual' : 'generated',
            'ai_generated_meta_status'   => get_post_meta($product_id, '_rank_math_title', true) ? 'manual' : 'generated',
            'ai_generated_keywords'      => $ai_keywords
        );

        if ($existing_master) {
            $wpdb->update($masterlist_table, $data, array('product_id' => $product_id));
            echo "<p>🔄 Updated MasterList entry for: <strong>{$name}</strong></p>";
        } else {
            $wpdb->insert($masterlist_table, $data);
            echo "<p>✅ Added to MasterList: <strong>{$name}</strong></p>";
        }
    }
}
wp_reset_postdata();

echo '<h2>🚀 MasterList Population Complete.</h2>';
?>
