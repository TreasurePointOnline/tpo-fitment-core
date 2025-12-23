<?php
require_once('wp-load.php');
echo '<h1>📊 MasterList CSV Exporter</h1>';

global $wpdb;
$masterlist_table = $wpdb->prefix . 'product_masterlist';

// Ensure a directory for exports
$export_dir = WP_CONTENT_DIR . '/uploads/tpo-exports/';
if (!is_dir($export_dir)) {
    mkdir($export_dir, 0755, true);
}

// Generate a unique filename
$filename = 'tpo_masterlist_export_' . date('Ymd_His') . '.csv';
$filepath = $export_dir . $filename;

$handle = fopen($filepath, 'w');

// Get all column names from the masterlist table
$columns = $wpdb->get_col("DESC $masterlist_table");
fputcsv($handle, $columns); // Write header row

// Fetch all data from the masterlist table
$results = $wpdb->get_results("SELECT * FROM $masterlist_table", ARRAY_A);

if ($results) {
    foreach ($results as $row) {
        fputcsv($handle, array_values($row)); // Write data row
    }
}
fclose($handle);

echo "<h1>✅ MasterList Export Complete!</h1>";
echo "<p>Your comprehensive product data has been exported to: <strong><a href='" . content_url("uploads/tpo-exports/{$filename}") . "'>{$filename}</a></strong></p>";
echo "<p><strong>IMPORTANT:</strong> Download this file! This is your working document for product enrichment.</p>";
?>
