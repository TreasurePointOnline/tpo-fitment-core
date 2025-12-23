<?php
require_once('wp-load.php');
echo '<h1>🔌 MU-Plugin Status Check</h1>';

$mu_plugins = get_mu_plugins();

echo "<h2>Must-Use (MU) Plugins</h2>";
if ($mu_plugins) {
    echo "<ul>";
    $tpo_skin_found = false;
    foreach ($mu_plugins as $file => $data) {
        echo "<li><strong>" . $data['Name'] . "</strong> (File: $file)</li>";
        if (strpos($file, 'tpo-skin.php') !== false) {
            $tpo_skin_found = true;
        }
    }
    echo "</ul>";
    if ($tpo_skin_found) {
        echo "<p>✅ 'TPO Skin' is listed as an active MU-Plugin.</p>";
    } else {
        echo "<p>❌ 'TPO Skin' is NOT listed as an active MU-Plugin.</p>";
    }
} else {
    echo "<p>No MU plugins found.</p>";
}
?>
