<?php
define('WP_USE_THEMES', false);
require_once('wp-load.php');
echo '<h1>⚙️ Forcing Astra Header Layout</h1>';

// Force Astra Header Layout settings
// These are typical options for Astra to control header elements
$astra_options = get_option('astra-settings', array());

// Set site identity
$astra_options['site-post-title'] = ''; // Ensure site title is not manually added from Customizer
$astra_options['display-site-title-responsive'] = array('desktop' => 1, 'tablet' => 1, 'mobile' => 1);
$astra_options['display-site-tagline-responsive'] = array('desktop' => 0, 'tablet' => 0, 'mobile' => 0); // Hide tagline

// Set Header Builder elements explicitly
$header_builder_layout = array(
    'desktop' => array(
        'above'   => array(),
        'primary' => array('site-identity', 'menu-1', 'search-box'), // Logo, Menu, Search
        'below'   => array(),
    ),
    'mobile'  => array(
        'above'   => array(),
        'primary' => array('site-identity', 'search-box', 'menu-toggle'), // Logo, Search, Hamburger
        'below'   => array(),
    ),
);
$astra_options['header-desktop-items'] = $header_builder_layout['desktop'];
$astra_options['header-mobile-items'] = $header_builder_layout['mobile'];

// Set header to transparent (we will override with our own background)
$astra_options['ast-header-custom-item-1'] = ''; // Clear custom items
$astra_options['header-main-sep'] = 0; // No separator
$astra_options['header-main-sep-color'] = '#1a1a1a';
$astra_options['header-bg-obj'] = array(
    'desktop' => array(
        'background-color'      => 'rgba(0,0,0,0)',
        'background-image'      => '',
        'background-repeat'     => 'no-repeat',
        'background-position'   => 'center center',
        'background-size'       => 'auto',
        'background-attachment' => 'scroll',
        'overlay_type'          => 'none',
        'background-media'      => '',
    )
);

update_option('astra-settings', $astra_options);

// Flush caches
wp_cache_flush();
flush_rewrite_rules();

echo "<p>✅ Astra Header layout forced to a standard configuration.</p>";
echo "<h2>🚀 Check site and re-deploy TPO skin.</h2>";
?>
