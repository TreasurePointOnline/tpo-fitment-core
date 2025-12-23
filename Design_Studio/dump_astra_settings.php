<?php
define('WP_USE_THEMES', false);
require_once('wp-load.php');

$astra_settings = get_option('astra-settings');

// Save to a file we can download
file_put_contents('astra_settings_dump.txt', print_r($astra_settings, true));
echo "<h1>Settings Dumped</h1>";
?>