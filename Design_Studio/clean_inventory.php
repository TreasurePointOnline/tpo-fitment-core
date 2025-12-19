<?php
require_once('wp-load.php');
echo '<h1>🧹 PHASE 3: Inventory Cleanup</h1>';

// The list of fake items we created earlier
$fake_items = array(
    'Sample Monoblock Amp', 
    'Sample 12-inch Sub', 
    'Sample Component Speakers', 
    'Sample Wiring Kit'
);

foreach ($fake_items as $name) {
    $product = get_page_by_title($name, OBJECT, 'product');
    if ($product) {
        // Force delete (bypass trash)
        wp_delete_post($product->ID, true);
        echo "<p>🗑️ Deleted Fake Item: $name</p>";
    } else {
        echo "<p>ℹ️ Item already gone: $name</p>";
    }
}
echo '<h2>✅ CLEANUP COMPLETE. Ready for Real Data.</h2>';
?>
