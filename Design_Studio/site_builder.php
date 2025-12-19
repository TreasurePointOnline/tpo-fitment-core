<?php
// Load WordPress Core (This is the safe way to talk to the database)
require_once('wp-load.php');

echo '<h1>🏗️ Treasure Point Site Builder</h1>';

// --- 1. CREATE HOME PAGE ---
$home_page_content = '
<div class="wp-block-cover alignfull has-black-background-color has-background-dim-60 has-background-dim"><div class="wp-block-cover__inner-container">
<h1 class="has-text-align-center has-white-color" style="font-size:4rem; text-transform:uppercase;">Unleash Your Sound</h1>
<p class="has-text-align-center has-large-font-size">Official OG Audio Partner</p>
<div class="wp-block-buttons aligncenter"><div class="wp-block-button"><a class="wp-block-button__link has-background" style="background-color:#d32f2f" href="/shop/">Shop Now</a></div></div>
</div></div>
<h2 class="has-text-align-center">Featured Equipment</h2>
';

$home_id = wp_insert_post(array(
    'post_title'   => 'Home',
    'post_content' => $home_page_content,
    'post_status'  => 'publish',
    'post_type'    => 'page',
));

if($home_id) {
    echo '<p>✅ Created Home Page (ID: ' . $home_id . ')</p>';
    // Set as Front Page
    update_option('show_on_front', 'page');
    update_option('page_on_front', $home_id);
    echo '<p>✅ Set Home Page as Front Page</p>';
}

// --- 2. CREATE MENU ---
$menu_name = 'Main Menu';
$menu_exists = wp_get_nav_menu_object($menu_name);

if(!$menu_exists){
    $menu_id = wp_create_nav_menu($menu_name);
    
    // Add Items
    wp_update_nav_menu_item($menu_id, 0, array('menu-item-title' => 'Home', 'menu-item-url' => '/', 'menu-item-status' => 'publish'));
    wp_update_nav_menu_item($menu_id, 0, array('menu-item-title' => 'Shop', 'menu-item-url' => '/shop/', 'menu-item-status' => 'publish'));
    wp_update_nav_menu_item($menu_id, 0, array('menu-item-title' => 'Cart', 'menu-item-url' => '/cart/', 'menu-item-status' => 'publish'));
    wp_update_nav_menu_item($menu_id, 0, array('menu-item-title' => 'My Account', 'menu-item-url' => '/my-account/', 'menu-item-status' => 'publish'));
    
    // Assign to Primary Location
    $locations = get_theme_mod('nav_menu_locations');
    $locations['primary'] = $menu_id;
    set_theme_mod('nav_menu_locations', $locations);
    echo '<p>✅ Created and Assigned Main Menu</p>';
} else {
    echo '<p>ℹ️ Menu already exists.</p>';
}

// --- 3. CREATE SAMPLE PRODUCTS ---
if (class_exists('WooCommerce')) {
    $products = array(
        'Sample Monoblock Amp' => 299.99,
        'Sample 12-inch Sub' => 149.99,
        'Sample Component Speakers' => 89.99,
        'Sample Wiring Kit' => 49.99
    );

    foreach ($products as $name => $price) {
        // Check if exists
        $existing = get_page_by_title($name, OBJECT, 'product');
        if (!$existing) {
            $product = new WC_Product_Simple();
            $product->set_name($name);
            $product->set_regular_price($price);
            $product->set_status("publish");
            $product->set_catalog_visibility('visible');
            $product->save();
            echo "<p>✅ Created Product: $name</p>";
        }
    }
} else {
    echo '<p>❌ WooCommerce not detected. Is it active?</p>';
}

echo '<h2>🚀 SITE BUILD COMPLETE. Go check it out!</h2>';
?>
