<?php
define('WP_USE_THEMES', false);
require_once('wp-load.php');

echo '<h1>🛠️ Astra Option Repair Tool</h1>';

$astra_settings = get_option('astra-settings');

if (!$astra_settings) {
    echo '<p>⚠️ No Astra Settings found. Creating defaults.</p>';
    $astra_settings = array();
}

echo '<pre>';
print_r($astra_settings);
echo '</pre>';

// The error is in array_merge() expecting an array but getting a string.
// This usually happens in the Builder components.

$builder_keys = array(
    'header-desktop-items',
    'header-mobile-items',
    'footer-desktop-items',
    'footer-mobile-items'
);

$fixed = false;

foreach ($builder_keys as $key) {
    if (isset($astra_settings[$key]) && !is_array($astra_settings[$key])) {
        echo "<p>❌ Found corrupted key: <strong>$key</strong> (Type: " . gettype($astra_settings[$key]) . ")</p>";
        
        // Reset to safe default structure
        if (strpos($key, 'header') !== false) {
             $astra_settings[$key] = array(
                'above'   => array(),
                'primary' => array('logo', 'menu-1'), // Safe defaults
                'below'   => array(),
            );
        } else {
            $astra_settings[$key] = array(
                'above'   => array(),
                'primary' => array('copyright'),
                'below'   => array(),
            );
        }
        $fixed = true;
        echo "<p>✅ Fixed <strong>$key</strong> to be an array.</p>";
    }
}

// Also check specifically for the crash point: component_loaded check
// It iterates over these arrays.

if ($fixed) {
    update_option('astra-settings', $astra_settings);
    echo '<h2>✅ Astra Settings Saved with Repairs.</h2>';
} else {
    echo '<h2>✅ No corruption found in top-level builder keys.</h2>';
}

// FORCE RESAVE of Permalinks and Flush Cache just in case
flush_rewrite_rules();
wp_cache_flush();

echo '<p>Cache flushed. Try loading the site.</p>';
?>