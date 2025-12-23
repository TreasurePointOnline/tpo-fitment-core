<?php
require_once('wp-load.php');
echo '<h1>🗄️ Product MasterList Table Creator</h1>';

global $wpdb;
$table_name = $wpdb->prefix . 'product_masterlist';

$charset_collate = $wpdb->get_charset_collate();

// SQL to create the table with comprehensive fields
$sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    product_id bigint(20) UNSIGNED NOT NULL,
    sku varchar(255) NOT NULL,
    name varchar(255) NOT NULL,
    type varchar(50) DEFAULT 'simple' NOT NULL,
    published tinyint(1) DEFAULT 1 NOT NULL,
    is_featured tinyint(1) DEFAULT 0 NOT NULL,
    visibility_in_catalog varchar(50) DEFAULT 'visible' NOT NULL,
    short_description text,
    description longtext,
    sale_price_starts date,
    sale_price_ends date,
    tax_status varchar(50) DEFAULT 'taxable' NOT NULL,
    tax_class varchar(50) DEFAULT '' NOT NULL,
    in_stock tinyint(1) DEFAULT 1 NOT NULL,
    stock int(10) DEFAULT NULL,
    low_stock_amount int(10) DEFAULT NULL,
    backorders_allowed varchar(50) DEFAULT 'no' NOT NULL,
    sold_individually tinyint(1) DEFAULT 0 NOT NULL,
    weight decimal(10,4) DEFAULT NULL,
    length decimal(10,4) DEFAULT NULL,
    width decimal(10,4) DEFAULT NULL,
    height decimal(10,4) DEFAULT NULL,
    allow_customer_reviews tinyint(1) DEFAULT 1 NOT NULL,
    purchase_note text,
    sale_price decimal(10,4) DEFAULT NULL,
    regular_price decimal(10,4) NOT NULL,
    categories text NOT NULL,
    tags text,
    shipping_class varchar(255),
    images longtext,
    download_limit int(10) DEFAULT NULL,
    download_expiry int(10) DEFAULT NULL,
    parent_sku varchar(255),
    grouped_products text,
    upsells text,
    cross_sells text,
    external_url varchar(255),
    button_text varchar(255),
    position int(10) DEFAULT 0 NOT NULL,
    attribute_series varchar(255),
    attribute_brand varchar(255),
    attribute_color varchar(255),
    attribute_material varchar(255),
    attribute_power_rms varchar(255),
    attribute_power_max varchar(255),
    attribute_size varchar(255),
    attribute_impedance varchar(255),
    attribute_channels varchar(255),
    meta_seo_title varchar(255),
    meta_seo_description text,
    meta_og_title varchar(255),
    meta_og_description text,
    meta_og_image varchar(255),
    ai_generated_desc_status varchar(50) DEFAULT 'draft',
    ai_generated_meta_status varchar(50) DEFAULT 'draft',
    ai_generated_keywords text,
    ai_ranking_score int(10) DEFAULT 0 NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY sku (sku)
) $charset_collate;";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);

if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
    echo "<p>✅ Database table `{$table_name}` created successfully!</p>";
} else {
    echo "<p>❌ Failed to create table `{$table_name}`.</p>";
    echo "<p>Error: " . $wpdb->last_error . "</p>";
}

echo '<h2>🚀 MasterList Setup Complete.</h2>';
?>
