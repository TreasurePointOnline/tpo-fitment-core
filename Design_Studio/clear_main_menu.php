<?php
require_once('wp-load.php');
echo '<h1>🧹 Clearing Main Menu</h1>';

// Get the Main Menu
$menu_name = 'Main Menu';
$menu = wp_get_nav_menu_object($menu_name);

if ($menu) {
    // Get all items in the menu
    $menu_items = wp_get_nav_menu_items($menu->term_id);

    // Delete each item
    if ($menu_items) {
        foreach ($menu_items as $menu_item) {
            wp_delete_post($menu_item->ID, true); // Delete menu item post
            echo "<p>🗑️ Deleted menu item: {$menu_item->title}</p>";
        }
    }
    
    // Unassign menu from primary location
    $locations = get_theme_mod('nav_menu_locations');
    if (isset($locations['primary'])) {
        unset($locations['primary']);
        set_theme_mod('nav_menu_locations', $locations);
        echo "<p>✅ Unassigned menu from primary location.</p>";
    } else {
        echo "<p>ℹ️ Menu not assigned to primary location.</p>";
    }

    echo "<h2>✅ Main Menu Cleared.</h2>";

} else {
    echo "<p>ℹ️ Main Menu not found.</p>";
}
?>
