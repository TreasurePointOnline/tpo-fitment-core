<?php
require_once('wp-load.php');
echo '<h1>📊 Product Data Auditor</h1>';

if (!class_exists('WooCommerce')) { die("❌ WooCommerce is not active!"); }

// 1. CHECK FOR PRODUCTS
echo '<h2>1. Current Product Count:</h2>';
$product_count = wp_count_posts('product')->publish;
if ($product_count == 0) {
    echo "<p>✅ No published products found. The product list is clean!</p>";
} else {
    echo "<p>⚠️ Found <strong>{$product_count}</strong> published products. Product list is NOT clean.</p>";
}

// 2. LIST ALL AVAILABLE PRODUCT FIELDS
echo '<h2>2. Comprehensive List of Product Fields:</h2>';
echo '<p>Use these fields for your CSV import. Fill in as much as possible for SEO and completeness.</p>';
echo '<table border="1" cellpadding="5" cellspacing="0">';
echo '<thead><tr><th>Field Name</th><th>Description</th><th>Example/Notes</th></tr></thead>';
echo '<tbody>';

$fields = array(
    // Core Product Fields
    'ID'                        => 'WooCommerce Product ID (leave blank for new products, useful for updates)',
    'Type'                      => 'Product type: simple, variable, grouped, external', 'simple',
    'SKU'                       => 'Stock Keeping Unit (Unique identifier, often Model Number)', 'OGS1200A.1D',
    'Name'                      => 'Product Name/Title', 'OGS1200A.1D Amplifier',
    'Published'                 => '1 for published, 0 for draft', '1',
    'Is featured?'              => '1 for featured, 0 for not featured', '0',
    'Visibility in catalog'     => 'visible, catalog, search, hidden', 'visible',
    'Short description'         => 'Brief product description (for product loops/archives)', 'Powerful Monoblock Amp',
    'Description'               => 'Full product description (supports HTML)', '<h2>Features</h2><ul><li>Class D...</li></ul>',
    'Date sale price starts'    => 'YYYY-MM-DD (Optional)', '',
    'Date sale price ends'      => 'YYYY-MM-DD (Optional)', '',
    'Tax status'                => 'taxable, shipping, none', 'taxable',
    'Tax class'                 => 'standard, reduced-rate, zero-rate (or custom tax class name)', 'standard',
    'In stock?'                 => '1 for in stock, 0 for out of stock', '1',
    'Stock'                     => 'Quantity in stock', '100',
    'Low stock amount'          => 'Threshold for low stock notification', '5',
    'Backorders allowed?'       => '0=no, 1=notify, 2=yes', '0',
    'Sold individually?'        => '1 for yes, 0 for no', '0',
    'Weight (kg)'               => 'Product weight in kg (or your WC base unit)', '2.5',
    'Length (cm)'               => 'Product length in cm (or your WC base unit)', '30',
    'Width (cm)'                => 'Product width in cm', '20',
    'Height (cm)'               => 'Product height in cm', '5',
    'Allow customer reviews?'   => '1 for yes, 0 for no', '1',
    'Purchase note'             => 'Note sent to customer after purchase', 'Thank you for your order!',
    'Sale price'                => 'Optional sale price', '199.99',
    'Regular price'             => 'Regular price (required)', '249.99',

    // Categorization & Relationships
    'Categories'                => 'Comma-separated list of categories (e.g., Car Audio > Amplifiers, Featured)', 'Amplifiers',
    'Tags'                      => 'Comma-separated list of tags', 'OG Audio, Class D',
    'Shipping class'            => 'Name of shipping class (e.g., Heavy Items)', '',
    'Images'                    => 'Comma-separated image URLs (main image first)', 'http://example.com/amp.jpg, http://example.com/amp-side.jpg',
    'Download limit'            => 'For downloadable products (0 for unlimited)', '',
    'Download expiry'           => 'For downloadable products (days)', '',
    'Parent'                    => 'SKU of parent product (for variations)', '',
    'Grouped products'          => 'Comma-separated SKUs of products in the group', '',
    'Upsells'                   => 'Comma-separated SKUs of upsell products', '',
    'Cross-sells'               => 'Comma-separated SKUs of cross-sell products', '',
    'External URL'              => 'URL for external/affiliate products', '',
    'Button text'               => 'Button text for external products', 'Buy Now',
    'Position'                  => 'Menu order position', '0',

    // Attributes (VERY IMPORTANT for filtering and SEO)
    'Attribute 1 name'          => 'Global attribute name (e.g., Color, Brand, Series)', 'Series',
    'Attribute 1 value(s)'      => 'Comma-separated values (e.g., OGS Series)', 'OGS Series',
    'Attribute 1 visible'       => '1 for visible on product page, 0 for hidden', '1',
    'Attribute 1 global'        => '1 for global attribute, 0 for custom product attribute', '1',
    // ... You can add more attributes by incrementing 'Attribute X name', 'Attribute X value(s)', etc.

    // SEO (Meta Data)
    'Meta: _yoast_wpseo_title'    => 'Yoast SEO Title (if using Yoast)', 'OGS1200A.1D - Car Amplifier | Treasure Point',
    'Meta: _yoast_wpseo_metadesc' => 'Yoast SEO Meta Description', 'Shop the OGS1200A.1D Monoblock Amplifier. Free Shipping.',
    'Meta: _rank_math_title'      => 'Rank Math SEO Title (if using Rank Math)', 'OGS1200A.1D - Car Amplifier | Treasure Point',
    'Meta: _rank_math_description' => 'Rank Math SEO Meta Description', 'Shop the OGS1200A.1D Monoblock Amplifier. Free Shipping.',
    'Meta: _thumbnail_id'         => 'Attachment ID of featured image (for image import, use Images column instead)', '',
    'Meta: _sale_price'           => 'Sale price meta (matches Sale price column)', '199.99',
    'Meta: _regular_price'        => 'Regular price meta (matches Regular price column)', '249.99',
    // Many other custom meta fields can exist, but these are the most common and useful.
);

echo '<tbody>';
foreach ($fields as $field => $description) {
    echo "<tr><td><code>$field</code></td><td>$description</td></tr>";
}
echo '</tbody></table>';

echo '<h2>3. Tips for your Spreadsheet:</h2>';
echo '<ul>';
echo '<li><strong>Required Fields:</strong> Type, SKU, Name, Regular price, Categories.</li>';
echo '<li><strong>Images:</strong> Provide full URLs. You can upload them to your WordPress Media Library first or use external URLs.</li>';
echo '<li><strong>Categories:</strong> Use the exact hierarchy (e.g., <code>Amplifiers &gt; OGS Series</code>).</li>';
echo '<li><strong>Attributes:</strong> Define Attribute 1 Name (e.g., "Brand", "Series"), then its values. Use <code>pa_</code> prefix for global attributes if you\'re editing raw XML/JSON, but CSV usually handles "Series" directly.</li>';
echo '<li><strong>SEO:</strong> Fill in Rank Math/Yoast specific meta fields for best results.</li>';
echo '</ul>';
?>
