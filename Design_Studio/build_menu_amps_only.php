<?php
require_once('wp-load.php');
echo '<h1>🧭 Navigation Builder (Amps Only)</h1>';

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

// Helper to add Category link
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

// Helper to add Product link
function add_product_link($menu_id, $product_id, $parent = 0) {
    $product = wc_get_product($product_id);
    if ($product) {
        $item_id = wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => $product->get_sku(), // Use SKU as menu item title
            'menu-item-object-id' => $product_id,
            'menu-item-object' => 'product',
            'menu-item-type' => 'post_type',
            'menu-item-status' => 'publish',
            'menu-item-parent-id' => $parent
        ));
        return $item_id;
    }
    return 0;
}


// 3. Add Items
// AMPLIFIERS (Parent Menu Item)
$amps_menu_id = add_cat_link($menu_id, 'Amplifiers');
if ($amps_menu_id) {
    // Sub-item: OG-Audio (Brand/Series)
    $og_audio_series_menu_id = wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => 'OG-Audio (Brand)',
        'menu-item-url' => '/product-category/amplifiers/', // Link to the main category for now
        'menu-item-status' => 'publish',
        'menu-item-parent-id' => $amps_menu_id
    ));

    // Get OGS Amplifiers and add them under 'OG-Audio (Brand)'
    $ogs_amps = array(
        'OGS1200A.1D AMPLIFIER', 'OGS1500A 1D AMPLIFIER', 'OGS2000A 1D AMPLIFIER',
        'OGS3500A.1D AMPLIFIER', 'OGS4500A.1D AMPLIFIER'
    );
    foreach ($ogs_amps as $amp_name) {
        $product_post = get_page_by_title($amp_name, OBJECT, 'product');
        if ($product_post) {
            add_product_link($menu_id, $product_post->ID, $og_audio_series_menu_id);
        }
    }
}

// OTHER TOP LEVEL CATEGORIES
add_cat_link($menu_id, 'Subwoofers');
add_cat_link($menu_id, 'Audio Speakers');
add_cat_link($menu_id, 'Empty Enclosures');
add_cat_link($menu_id, 'Wiring Kits');


// 4. Assign to Theme Location
$locations = get_theme_mod('nav_menu_locations');
$locations['primary'] = $menu_id;
set_theme_mod('nav_menu_locations', $locations);

echo "<h2>✅ MENU COMPLETE. Go check the site!</h2>";
?>
