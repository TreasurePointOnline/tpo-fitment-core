<?php
require_once('wp-load.php');

// Directly call the function to get nav data (from tpo-skin.php, if active)
// If tpo-skin.php is NOT active, this will fail.
if (function_exists('tpo_get_nav_data')) {
    echo "<h1>Debug Nav Data Output (from tpo-skin.php)</h1>";
    tpo_get_nav_data(); // This function should send JSON and exit
} else {
    echo "<h1>Error: `tpo_get_nav_data` function not found.</h1>";
    echo "<p>Ensure `tpo-skin.php` is active and the function is correctly defined.</p>";
}
?>
