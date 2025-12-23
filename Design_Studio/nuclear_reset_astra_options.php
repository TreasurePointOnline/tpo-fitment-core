<?php
define('WP_USE_THEMES', false);
require_once('wp-load.php');

echo '<h1>☢️ NUCLEAR RESET: ASTRA SETTINGS</h1>';

// 1. Delete the option
if (delete_option('astra-settings')) {
    echo "<p>✅ 'astra-settings' option DELETED.</p>";
} else {
    echo "<p>⚠️ Could not delete 'astra-settings' (maybe it didn't exist?).</p>";
}

// 2. Also clear theme mods for astra
remove_theme_mods(); 
echo "<p>✅ Theme mods cleared.</p>";

echo "<h2>🚀 Settings Wiped. Astra will now start fresh.</h2>";
?>