<?php
echo "<h1>🕵️ Deep Dive Debugger</h1>";

echo "<p>1. Loading wp-load.php...</p>";
define('WP_USE_THEMES', true);
require_once('wp-load.php');
echo "<p>✅ wp-load.php loaded.</p>";

echo "<p>2. Running wp()...</p>";
try {
    wp();
    echo "<p>✅ wp() complete. Query is set.</p>";
} catch (Exception $e) {
    echo "<p>❌ Error in wp(): " . $e->getMessage() . "</p>";
}

echo "<p>3. Loading Template Loader...</p>";
// We will wrap this in a try/catch if possible, but fatal errors might just die.
require_once( ABSPATH . WPINC . '/template-loader.php' );

echo "<p>✅ Template Loader finished (You shouldn't see this if a theme loads).</p>";
?>
