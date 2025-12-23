<?php
// Load WordPress Environment
require_once('wp-load.php');

echo '<h1>🗄️ WordPress Database Exporter (Bypass Mode)</h1>';

// BYPASSING SECURITY CHECK FOR USER CONVENIENCE
// WARNING: This script is publicly accessible until deleted.

// Ensure enough memory and time
ini_set('memory_limit', '512M');
set_time_limit(300);

// Output filename
$backup_file_name = 'live_deployment.sql';

// Get database credentials from wp-config.php
$db_name = DB_NAME;
$db_user = DB_USER;
$db_password = DB_PASSWORD;
$db_host = DB_HOST;

try {
    $dsn = "mysql:host={$db_host};dbname={$db_name}";
    $pdo = new PDO($dsn, $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8'");

    $sql_content = "-- WordPress Database Backup\n";
    $sql_content .= "-- Host: {$db_host}\n";
    $sql_content .= "-- Database: {$db_name}\n";
    $sql_content .= "-- Generation Time: " . date('Y-m-d H:i:s') . "\n\n";
    $sql_content .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
    $sql_content .= "SET time_zone = \"+00:00\";\n\n";
    $sql_content .= "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;";
    $sql_content .= "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;";
    $sql_content .= "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;";
    $sql_content .= "/*!40101 SET NAMES utf8 */;";
    $sql_content .= "--\n-- Database: `$db_name`\n--\n\n";

    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

    foreach ($tables as $table) {
        $sql_content .= "-- --------------------------------------------------------\n";
        $sql_content .= "--\n-- Table structure for table `$table`\n--\n\n";
        
        $create_table = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(PDO::FETCH_ASSOC);
        $sql_content .= $create_table['Create Table'] . ";\n\n";

        $rows = $pdo->query("SELECT * FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
        if ($rows) {
            foreach ($rows as $row) {
                $sql_content .= "INSERT INTO `{$table}` VALUES(";
                $values = array();
                foreach ($row as $value) {
                    if (is_null($value)) {
                        $values[] = "NULL";
                    } else {
                        $values[] = $pdo->quote($value);
                    }
                }
                $sql_content .= implode(',', $values) . ");\n";
            }
            $sql_content .= "\n";
        }
    }

    $sql_content .= "\n/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;";
    $sql_content .= "/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;";
    $sql_content .= "/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";

    $file_path = ABSPATH . $backup_file_name;
    file_put_contents($file_path, $sql_content);

    echo "<h1>✅ Database Export Complete!</h1>";
    echo "<p>Your database has been exported to: <strong><a href='" . site_url('/' . $backup_file_name) . "'>{$backup_file_name}</a></strong></p>";
    echo "<p><strong>IMPORTANT:</strong> Download this file immediately!</p>";

} catch (PDOException $e) {
    echo "<h1>❌ Database Export Failed!</h1>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>