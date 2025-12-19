<?php
require_once('wp-config.php');

echo '<h1>🗄️ Database Connection Test</h1>';

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($mysqli->connect_error) {
    die("❌ Connection Failed: " . $mysqli->connect_error);
}

echo "✅ Database Connection Successful!<br>";
echo "Host: " . DB_HOST . "<br>";
echo "User: " . DB_USER . "<br>";
echo "Database: " . DB_NAME . "<br>";

// Test a query
$result = $mysqli->query("SELECT option_value FROM {$table_prefix}options WHERE option_name = 'siteurl'");
if ($result) {
    $row = $result->fetch_assoc();
    echo "✅ Site URL from DB: " . $row['option_value'];
} else {
    echo "❌ Could not read options table. Prefix might be wrong.";
}

$mysqli->close();
?>
