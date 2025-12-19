<?php
require_once('wp-load.php');
echo '<h1>🔌 Active Plugins List</h1>';

$active_plugins = get_option('active_plugins');
$mu_plugins = get_mu_plugins();

echo "<h2>Standard Plugins</h2>";
if ($active_plugins) {
    echo "<ul>";
    foreach ($active_plugins as $plugin) {
        echo "<li>$plugin</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No standard plugins active.</p>";
}

echo "<h2>Must-Use (MU) Plugins</h2>";
if ($mu_plugins) {
    echo "<ul>";
    foreach ($mu_plugins as $file => $data) {
        echo "<li>$file</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No MU plugins found.</p>";
}
?>
