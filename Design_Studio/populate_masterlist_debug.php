<?php
require_once('wp-load.php');
echo '<h1>🤖 AI MasterList Populator (FINAL DEBUG VERSION)</h1>'; // Renamed header for clarity

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
        $attribute_term_obj = $attributes[$attribute_slug]->get_terms();
        if(!empty($attribute_term_obj)){
            return $attribute_term_obj[0]->name;
        }
    }
    return '';
}

// AI Content Generators
function ai_generate_description($product_name, $category, $series) {
    $base_desc = "Experience the power of the {$product_name} from Treasure Point. ";
    if ($series) { $base_desc .= "Part of the renowned {$series} series, this {$category} delivers unparalleled performance. "; }
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

$processed_count = 0;
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
        $existing_master = $wpdb->get_row($wpdb->prepare("SELECT id FROM $masterlist_table WHERE product_id = %d", $product_id));

        // AI Generated Fields (for empty ones)
        $ai_short_desc = $product->get_short_description() ? $product->get_short_description() : ai_generate_description($name, $categories_str, $series_value);
        $ai_desc = $product->get_description() ? $product->get_description() : ai_generate_description($name, $categories_str, $series_value) . " Learn more about its features and specifications.";
        $ai_meta_title = get_post_meta($product_id, '_rank_math_title', true) ? get_post_meta($product_id, '_rank_math_title', true) : ai_generate_meta_title($name, $categories_str);
        $ai_meta_desc = get_post_meta($product_id, '_rank_math_description', true) ? get_post_meta($product_id, '_rank_math_description', true) : ai_generate_meta_description($name, $categories_str);
        $ai_keywords = "{$name}, {$categories_str}, {$series_value}, car audio, audio system, Treasure Point";


        $data = array(
            // Omit 'id' as it's AUTO_INCREMENT
            'product_id'                 => $product_id,
            'sku'                        => $sku ? $sku : $name,
            'name'                       => $name ? $name : 'Unknown Product',
            'type'                       => $product->get_type() ? $product->get_type() : 'simple',
            'published'                  => ($product->get_status() == 'publish' ? 1 : 0),
            'is_featured'                => ($product->is_featured() ? 1 : 0),
            'visibility_in_catalog'      => $product->get_catalog_visibility() ? $product->get_catalog_visibility() : 'visible',
            'short_description'          => $ai_short_desc,
            'description'                => $ai_desc,
            'sale_price_starts'          => $product->get_date_on_sale_from() ? $product->get_date_on_sale_from()->format('Y-m-d') : null,
            'sale_price_ends'            => $product->get_date_on_sale_to() ? $product->get_date_on_sale_to()->format('Y-m-d') : null,
            'tax_status'                 => $product->get_tax_status() ? $product->get_tax_status() : 'taxable',
            'tax_class'                  => $product->get_tax_class() ? $product->get_tax_class() : '',
            'in_stock'                   => ($product->is_in_stock() ? 1 : 0),
            'stock'                      => $product->get_stock_quantity(),
            'low_stock_amount'           => get_post_meta($product_id, '_low_stock_amount', true),
            'backorders_allowed'         => $product->get_backorders() ? $product->get_backorders() : 'no',
            'sold_individually'          => ($product->get_sold_individually() ? 1 : 0),
            'weight'                     => $product->get_weight() ? (float)$product->get_weight() : null, // Cast to float
            'length'                     => $product->get_length() ? (float)$product->get_length() : null,
            'width'                      => $product->get_width() ? (float)$product->get_width() : null,
            'height'                     => $product->get_height() ? (float)$product->get_height() : null,
            'allow_customer_reviews'     => ($product->get_reviews_allowed() ? 1 : 0),
            'purchase_note'              => $product->get_purchase_note() ? $product->get_purchase_note() : null,
            'sale_price'                 => $product->get_sale_price() ? (float)$product->get_sale_price() : null,
            'regular_price'              => $product->get_regular_price() ? (float)$product->get_regular_price() : 0.00,
            'categories'                 => $categories_str ? $categories_str : 'Uncategorized',
            'tags'                       => implode(', ', wp_get_post_terms($product_id, 'product_tag', array('fields' => 'names'))),
            'shipping_class'             => $product->get_shipping_class() ? $product->get_shipping_class() : null,
            'images'                     => get_the_post_thumbnail_url($product_id, 'full'),
            'download_limit'             => null,
            'download_expiry'            => null,
            'parent_sku'                 => null,
            'grouped_products'           => null,
            'upsells'                    => null,
            'cross_sells'                => null,
            'external_url'               => null,
            'button_text'                => null,
            'position'                   => (int) get_post_meta($product_id, 'menu_order', true),
            'attribute_series'           => $series_value,
            'attribute_brand'            => '',
            'attribute_color'            => '',
            'attribute_material'         => '',
            'attribute_power_rms'        => '',
            'attribute_power_max'        => '',
            'attribute_size'             => '',
            'attribute_impedance'        => '',
            'attribute_channels'         => '',
            'meta_seo_title'             => $ai_meta_title,
            'meta_seo_description'       => $ai_meta_desc,
            'meta_og_title'              => null,
            'meta_og_description'        => null,
            'meta_og_image'              => null,
            'ai_generated_desc_status'   => $product->get_short_description() ? 'manual' : 'generated',
            'ai_generated_meta_status'   => get_post_meta($product_id, '_rank_math_title', true) ? 'manual' : 'generated',
            'ai_generated_keywords'      => $ai_keywords,
            'ai_ranking_score'           => 0
        );

        $formats = array(
            '%d', // product_id
            '%s', // sku
            '%s', // name
            '%s', // type
            '%d', // published
            '%d', // is_featured
            '%s', // visibility_in_catalog
            '%s', // short_description
            '%s', // description
            '%s', // sale_price_starts
            '%s', // sale_price_ends
            '%s', // tax_status
            '%s', // tax_class
            '%d', // in_stock
            '%d', // stock
            '%d', // low_stock_amount
            '%s', // backorders_allowed
            '%d', // sold_individually
            '%f', // weight // Changed to float
            '%f', // length // Changed to float
            '%f', // width  // Changed to float
            '%f', // height // Changed to float
            '%d', // allow_customer_reviews
            '%s', // purchase_note
            '%f', // sale_price // Changed to float
            '%f', // regular_price // Changed to float
            '%s', // categories
            '%s', // tags
            '%s', // shipping_class
            '%s', // images
            '%d', // download_limit
            '%d', // download_expiry
            '%s', // parent_sku
            '%s', // grouped_products
            '%s', // upsells
            '%s', // cross_sells
            '%s', // external_url
            '%s', // button_text
            '%d', // position
            '%s', // attribute_series
            '%s', // attribute_brand
            '%s', // attribute_color
            '%s', // attribute_material
            '%s', // attribute_power_rms
            '%s', // attribute_power_max
            '%s', // attribute_size
            '%s', // attribute_impedance
            '%s', // attribute_channels
            '%s', // meta_seo_title
            '%s', // meta_seo_description
            '%s', // meta_og_title
            '%s', // meta_og_description
            '%s', // meta_og_image
            '%s', // ai_generated_desc_status
            '%s', // ai_generated_meta_status
            '%s', // ai_generated_keywords
            '%d', // ai_ranking_score
        );


        if ($existing_master) {
            $update_result = $wpdb->update($masterlist_table, $data, array('product_id' => $product_id), $formats);
            if ($update_result === false) {
                echo "<p>❌ Failed to update MasterList for: {$name}. Error: {$wpdb->last_error}</p>";
            } else {
                echo "<p>🔄 Updated MasterList entry for: <strong>{$name}</strong></p>";
                $processed_count++;
            }
        } else {
            $insert_result = $wpdb->insert($masterlist_table, $data, $formats);
            if ($insert_result === false) {
                echo "<p>❌ Failed to insert into MasterList: {$name}. Error: {$wpdb->last_error}</p>";
            } else {
                echo "<p>✅ Added to MasterList: <strong>{$name}</strong></p>";
                $processed_count++;
            }
        }
    }
}
wp_reset_postdata();

echo "<h2>🚀 MasterList Population Complete. Processed {$processed_count} products.</h2>";
$final_count = $wpdb->get_var("SELECT COUNT(*) FROM $masterlist_table");
echo "<p>Final MasterList row count: <strong>{$final_count}</strong></p>";
?>