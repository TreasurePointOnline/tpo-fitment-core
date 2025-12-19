<?php
require_once('wp-load.php');
echo '<h1>🧭 PHASE 2: Navigation Builder</h1>';

// 1. Get the Main Menu
$menu_name = 'Main Menu';
$menu = wp_get_nav_menu_object($menu_name);

if (!$menu) {
    // Create it if it's missing
    $menu_id = wp_create_nav_menu($menu_name);
    echo "<p>✅ Created New Menu: $menu_name</p>";
} else {
    $menu_id = $menu->term_id;
    echo "<p>ℹ️ Found Existing Menu (ID: $menu_id)</p>";
}

// 2. Helper Function to Add Items
function add_menu_item($menu_id, $title, $object_id, $type = 'post_type', $object = 'page', $parent_id = 0) {
    $item_id = wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => $title,
        'menu-item-object-id' => $object_id,
        'menu-item-object' => $object,
        'menu-item-type' => $type,
        'menu-item-status' => 'publish',
        'menu-item-parent-id' => $parent_id
    ));
    return $item_id;
}

// 3. Add 'Car Audio' (Parent)
$car_cat = get_term_by('name', 'Car Audio', 'product_cat');
if ($car_cat) {
    $car_menu_id = add_menu_item($menu_id, 'Car Audio', $car_cat->term_id, 'taxonomy', 'product_cat');
    echo "<p>✅ Added Car Audio Button</p>";

    // Add Children
    $subs = array('Amplifiers', 'Subwoofers', 'Speakers', 'Wiring & Accessories');
    foreach ($subs as $sub) {
        $term = get_term_by('name', $sub, 'product_cat');
        if ($term) {
            add_menu_item($menu_id, $sub, $term->term_id, 'taxonomy', 'product_cat', $car_menu_id);
            echo "<p>-- Added Dropdown: $sub</p>";
        }
    }
}

// 4. Add 'Marine Audio' (Parent)
$marine_cat = get_term_by('name', 'Marine Audio', 'product_cat');
if ($marine_cat) {
    $marine_menu_id = add_menu_item($menu_id, 'Marine Audio', $marine_cat->term_id, 'taxonomy', 'product_cat');
    echo "<p>✅ Added Marine Audio Button</p>";
    
    // Add Children
    $marine_subs = array('Marine Amps', 'Marine Speakers');
    foreach ($marine_subs as $sub) {
        $term = get_term_by('name', $sub, 'product_cat');
        if ($term) {
            add_menu_item($menu_id, $sub, $term->term_id, 'taxonomy', 'product_cat', $marine_menu_id);
            echo "<p>-- Added Dropdown: $sub</p>";
        }
    }
}

// 5. Add Important Pages
$pages = array('Shipping & Returns', 'Contact Us');
foreach ($pages as $p_name) {
    $p = get_page_by_title($p_name);
    if ($p) {
        add_menu_item($menu_id, $p_name, $p->ID, 'post_type', 'page');
        echo "<p>✅ Added Page: $p_name</p>";
    }
}

// 6. Assign to Theme Location
$locations = get_theme_mod('nav_menu_locations');
$locations['primary'] = $menu_id;
set_theme_mod('nav_menu_locations', $locations);
echo "<h2>✅ MENU COMPLETE. Go check the site!</h2>";
?>
