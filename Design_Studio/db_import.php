<?php
// TPO Restore Engine
// Reads backup.sql from snapshot folder and imports it.
define('WP_USE_THEMES', false);
require_once('wp-load.php');

echo '<h1>⏳ TPO Restore Engine</h1>';
echo '<p>Starting database restoration...</p>';

// Settings
$snapshot_file = ABSPATH . 'tpo_snapshot_latest/backup.sql';

if (!file_exists($snapshot_file)) {
    die("❌ Error: Snapshot file not found at $snapshot_file");
}

// Database Connection
$db_name = DB_NAME;
$db_user = DB_USER;
$db_password = DB_PASSWORD;
$db_host = DB_HOST;

try {
    $dsn = "mysql:host={$db_host};dbname={$db_name}";
    $pdo = new PDO($dsn, $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Read and run the SQL file
    // NOTE: For very large files, reading into memory might fail.
    // Ideally we stream line by line.
    
    $handle = fopen($snapshot_file, "r");
    if ($handle) {
        $sql = '';
        $count = 0;
        
        // Disable foreign key checks for speed/safety during import
        $pdo->exec("SET foreign_key_checks = 0");
        
        while (($line = fgets($handle)) !== false) {
            // Skip comments
            if (substr(trim($line), 0, 2) == '--' || $line == '')
                continue;

            $sql .= $line;
            
            // Execute when we find a semicolon ending a query
            if (substr(trim($line), -1, 1) == ';') {
                try {
                    $pdo->exec($sql);
                    $count++;
                } catch (PDOException $e) {
                    // Ignore "Table already exists" errors if we didn't drop them first
                    // But our dump usually handles structure.
                    echo "<p>⚠️ SQL Error on query #$count: " . $e->getMessage() . "</p>";
                }
                $sql = ''; // Reset for next query
            }
        }
        
        $pdo->exec("SET foreign_key_checks = 1");
        fclose($handle);
        
        echo "<h1>✅ Database Restored Successfully!</h1>";
        echo "<p>Executed $count queries.</p>";
        
        // Flush Cache
        wp_cache_flush();
        flush_rewrite_rules();
        echo "<p>Cache flushed.</p>";
        
    } else {
        echo "❌ Could not open SQL file.";
    }

} catch (PDOException $e) {
    echo "<h1>❌ Connection Failed</h1>";
    echo $e->getMessage();
}
?>