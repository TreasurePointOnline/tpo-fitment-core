<?php
require_once('wp-load.php');
echo '<h1>🧭 Navigation Builder (Restored Catalog)</h1>';

// 1. Delete Old Menu to start fresh
$menu_name = 'Main Menu';
$old_menu = wp_get_nav_menu_object($menu_name);
if ($old_menu) {
    wp_delete_nav_menu($old_menu->term_id);
    echo "<p>🗑️ Deleted old menu.</p>";
}

// 2. Create New Menu
$menu_id = wp_create_nav_menu($menu_name);
echo "<p>✅ Created New Menu: $menu_name</p>";

// Helper
function add_cat_link($menu_id, $name, $parent = 0) {
    $term = get_term_by('name', $name, 'product_cat');
    if ($term) {
        $item_id = wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => $name,
            'menu-item-object-id' => $term->term_id,
            'menu-item-object' => 'product_cat',
            'menu-item-type' => 'taxonomy',
            'menu-item-status' => 'publish',
            'menu-item-parent-id' => $parent
        ));
        return $item_id;
    }
    return 0;
}

// 3. Add Items
// HOME (Custom Link)
wp_update_nav_menu_item($menu_id, 0, array(
    'menu-item-title' => 'Home',
    'menu-item-url' => '/',
    'menu-item-status' => 'publish'
));

// AMPLIFIERS
add_cat_link($menu_id, 'Amplifiers');

// SUBWOOFERS
add_cat_link($menu_id, 'Subwoofers');

// SPEAKERS
add_cat_link($menu_id, 'Speakers');

// WIRING & ACCESSORIES
add_cat_link($menu_id, 'Wiring & Accessories');

// MARINE AUDIO
add_cat_link($menu_id, 'Marine Audio');

// EMPTY ENCLOSURES
add_cat_link($menu_id, 'Empty Enclosures');

// ACCOUNT
wp_update_nav_menu_item($menu_id, 0, array(
    'menu-item-title' => 'My Account',
    'menu-item-object' => 'page',
    'menu-item-type' => 'post_type',
    'menu-item-object-id' => get_option('woocommerce_myaccount_page_id'),
    'menu-item-status' => 'publish'
));

// 4. Assign to Theme Location
$locations = get_theme_mod('nav_menu_locations');
$locations['primary'] = $menu_id;
set_theme_mod('nav_menu_locations', $locations);

echo "<h2>✅ MENU COMPLETE. Go check the site!</h2>";
?>
