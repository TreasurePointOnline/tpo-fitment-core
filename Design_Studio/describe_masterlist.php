<?php
require_once('wp-load.php');
echo '<h1>📊 MasterList Table Description</h1>';

global $wpdb;
$masterlist_table = $wpdb->prefix . 'product_masterlist';

$columns = $wpdb->get_results("DESCRIBE $masterlist_table");

if ($wpdb->last_error) {
    echo "<p>❌ Error describing table: " . $wpdb->last_error . "</p>";
} elseif (!$columns) {
    echo "<p>⚠️ Table `{$masterlist_table}` not found or empty.</p>";
} else {
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<thead><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr></thead>";
    echo "<tbody>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>" . $col->Field . "</td>";
        echo "<td>" . $col->Type . "</td>";
        echo "<td>" . $col->Null . "</td>";
        echo "<td>" . $col->Key . "</td>";
        echo "<td>" . $col->Default . "</td>";
        echo "<td>" . $col->Extra . "</td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
}
?>
