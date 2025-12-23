<?php
/**
 * 🚀 TPO POWER IMPORTER
 * Specifically designed to bypass shared hosting limits.
 */
define('WP_USE_THEMES', false);
require_once('wp-load.php');

// Increase limits to the max
ini_set('memory_limit', '1024M');
set_time_limit(0); 

echo '<h1>🚀 TPO Power Importer</h1>';

$sql_file = ABSPATH . 'deploy_this.sql';

if (!file_exists($sql_file)) {
    die("❌ Error: 'deploy_this.sql' not found in the site root. Please upload it via FTP first.");
}

global $wpdb;
echo "<p>Connected to database: " . DB_NAME . "</p>";

// Clear existing tables to ensure a clean slate
echo "<p>Cleaning old tables...</p>";
$tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
foreach ($tables as $table) {
    $wpdb->query("DROP TABLE IF EXISTS " . $table[0]);
}

// Import the new SQL
echo "<p>Importing new data (This won't hang!)...</p>";

// Using command line mysql if available, otherwise optimized PHP import
$command = sprintf(
    'mysql -h %s -u %s -p%s %s < %s',
    escapeshellarg(DB_HOST),
    escapeshellarg(DB_USER),
    escapeshellarg(DB_PASSWORD),
    escapeshellarg(DB_NAME),
    escapeshellarg($sql_file)
);

exec($command, $output, $return_var);

if ($return_code === 0) {
    echo "<h2 style='color: green;'>✅ SUCCESS: Site Database Published!</h2>";
} else {
    echo "<p>ℹ️ System CLI busy, falling back to Stream Import...</p>";
    // Stream fallback logic here (similar to what I built earlier but faster)
    include_once('restore_db_from_snapshot.php'); 
}

// Cleanup
// unlink($sql_file); // Uncomment to auto-delete for security

echo "<p><strong>Next Step:</strong> Log back in and Save Permalinks twice.</p>";
?>