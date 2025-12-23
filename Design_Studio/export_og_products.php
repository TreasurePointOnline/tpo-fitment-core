<?php
require_once('wp-load.php');
echo '<h1>📊 Product Data Exporter</h1>';

if (!class_exists('WooCommerce')) { die("❌ WooCommerce is not active."); }

// Ensure a directory for exports
$export_dir = WP_CONTENT_DIR . '/uploads/tpo-exports/';
if (!is_dir($export_dir)) {
    mkdir($export_dir, 0755, true);
}

// Generate a unique filename
$filename = 'tpo_og_product_export_' . date('Ymd_His') . '.csv';
$filepath = $export_dir . $filename;

$handle = fopen($filepath, 'w');

// Define CSV Headers (WooCommerce CSV import/export compatible)
$header = array(
    'ID', 'Type', 'SKU', 'Name', 'Published', 'Is featured?', 'Visibility in catalog',
    'Short description', 'Description', 'Date sale price starts', 'Date sale price ends',
    'Tax status', 'Tax class', 'In stock?', 'Stock', 'Low stock amount', 'Backorders allowed?',
    'Sold individually?', 'Weight (kg)', 'Length (cm)', 'Width (cm)', 'Height (cm)',
    'Allow customer reviews?', 'Purchase note', 'Sale price', 'Regular price', 'Categories',
    'Tags', 'Shipping class', 'Images', 'Download limit', 'Download expiry', 'Parent',
    'Grouped products', 'Upsells', 'Cross-sells', 'External URL', 'Button text', 'Position',
    'Attribute 1 name', 'Attribute 1 value(s)', 'Attribute 1 visible', 'Attribute 1 global',
    'Meta: _yoast_wpseo_title', 'Meta: _yoast_wpseo_metadesc', 'Meta: _rank_math_title', 'Meta: _rank_math_description' // Common SEO fields
);
fputcsv($handle, $header);

// Query all products
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

        $categories = wp_get_post_terms($product->get_id(), 'product_cat', array('fields' => 'names'));
        
        $row = array(
            'ID'                     => $product->get_id(),
            'Type'                   => $product->get_type(),
            'SKU'                    => $product->get_sku(),
            'Name'                   => $product->get_name(),
            'Published'              => ($product->get_status() == 'publish' ? 1 : 0),
            'Is featured?'           => ($product->is_featured() ? 1 : 0),
            'Visibility in catalog'  => $product->get_catalog_visibility(),
            'Short description'      => $product->get_short_description(),
            'Description'            => $product->get_description(),
            'Date sale price starts' => '',
            'Date sale price ends'   => '',
            'Tax status'             => $product->get_tax_status(),
            'Tax class'              => $product->get_tax_class(),
            'In stock?'              => ($product->is_in_stock() ? 1 : 0),
            'Stock'                  => $product->get_stock_quantity(),
            'Low stock amount'       => get_post_meta($product->get_id(), '_low_stock_amount', true),
            'Backorders allowed?'    => ($product->get_backorders() == 'no' ? 0 : 1),
            'Sold individually?'     => ($product->get_sold_individually() ? 1 : 0),
            'Weight (kg)'            => $product->get_weight(),
            'Length (cm)'            => $product->get_length(),
            'Width (cm)'             => $product->get_width(),
            'Height (cm)'            => $product->get_height(),
            'Allow customer reviews?'=> ($product->get_reviews_allowed() ? 1 : 0),
            'Purchase note'          => $product->get_purchase_note(),
            'Sale price'             => $product->get_sale_price(),
            'Regular price'          => $product->get_regular_price(),
            'Categories'             => implode(', ', $categories),
            'Tags'                   => implode(', ', wp_get_post_terms($product->get_id(), 'product_tag', array('fields' => 'names'))),
            'Shipping class'         => $product->get_shipping_class(),
            'Images'                 => get_the_post_thumbnail_url($product->get_id(), 'full'),
            'Download limit'         => '',
            'Download expiry'        => '',
            'Parent'                 => '',
            'Grouped products'       => '',
            'Upsells'                => '',
            'Cross-sells'            => '',
            'External URL'           => '',
            'Button text'            => '',
            'Position'               => '',
            'Attribute 1 name'       => '',
            'Attribute 1 value(s)'   => '',
            'Attribute 1 visible'    => '',
            'Attribute 1 global'     => '',
            'Meta: _yoast_wpseo_title' => get_post_meta($product->get_id(), '_yoast_wpseo_title', true),
            'Meta: _yoast_wpseo_metadesc' => get_post_meta($product->get_id(), '_yoast_wpseo_metadesc', true),
            'Meta: _rank_math_title' => get_post_meta($product->get_id(), 'rank_math_title', true),
            'Meta: _rank_math_description' => get_post_meta($product->get_id(), 'rank_math_description', true),
        );
        fputcsv($handle, $row);
    }
}
wp_reset_postdata();
fclose($handle);

echo "<h1>✅ Product Export Complete!</h1>";
echo "<p>Your product data has been exported to: <strong><a href='" . content_url("uploads/tpo-exports/{$filename}") . "'>{$filename}</a></strong></p>";
echo "<p><strong>IMPORTANT:</strong> Download this file, fill in the blanks, and keep it safe!</p>";
?>
