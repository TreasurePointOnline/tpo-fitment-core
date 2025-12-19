<?php
// Load WordPress Environment
define('WP_USE_THEMES', false);
require_once('wp-load.php');
require_once(ABSPATH . 'wp-admin/includes/plugin.php');
require_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');

echo '<h1>🏗️ BIG DOG SETUP: Phase 4</h1>';

// --- 1. INSTALL & ACTIVATE PLUGINS ---
echo '<h2>🔌 Installing Plugins...</h2>';

$plugins_to_install = array(
    'ajax-search-for-woocommerce' => 'ajax-search-for-woocommerce/ajax-search-for-woocommerce.php',
    'seo-by-rank-math' => 'seo-by-rank-math/rank-math.php'
);

foreach ($plugins_to_install as $slug => $path) {
    // Check if active
    if (is_plugin_active($path)) {
        echo "<p>✅ $slug is already active.</p>";
    } else {
        // Check if installed but inactive
        $installed_plugins = get_plugins();
        if (isset($installed_plugins[$path])) {
            activate_plugin($path);
            echo "<p>✅ Activated existing plugin: $slug</p>";
        } else {
            // Install from repo
            echo "<p>⬇️ Downloading $slug...</p>";
            $upgrader = new Plugin_Upgrader(new Automatic_Upgrader_Skin());
            $install_result = $upgrader->install("https://downloads.wordpress.org/plugin/$slug.latest-stable.zip");
            
            if ($install_result) {
                activate_plugin($path);
                echo "<p>✅ Installed & Activated: $slug</p>";
            } else {
                echo "<p>❌ Failed to install: $slug</p>";
            }
        }
    }
}

// --- 2. CREATE LEGAL PAGES ---
echo '<h2>📄 Creating Legal Pages...</h2>';

$pages = array(
    'Privacy Policy' => 'Privacy Policy for OG Audio. [Update details later]',
    'Terms & Conditions' => 'Terms and Conditions. [Update details later]',
    'Returns & Warranty' => 'Our return policy is 30 days... [Update details later]',
    'Shipping Policy' => 'We ship via UPS/FedEx... [Update details later]',
    'Contact Us' => 'Visit us at 206 Marine Dr. Anderson, IN.',
    'About Us' => 'Welcome to Treasure Point / OG Audio.'
);

$page_ids = array();

// Get default admin user for author
$author_id = 1; 
$users = get_users(array('role' => 'administrator', 'number' => 1));
if(!empty($users)) { $author_id = $users[0]->ID; }

foreach ($pages as $title => $content) {
    $existing_page = get_page_by_title($title);
    if ($existing_page) {
        echo "<p>ℹ️ Page exists: $title (ID: {$existing_page->ID})</p>";
        $page_ids[$title] = $existing_page->ID;
    } else {
        $page_id = wp_insert_post(array(
            'post_title'   => $title,
            'post_content' => $content,
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_author'  => $author_id,
        ));
        if ($page_id) {
            echo "<p>✅ Created Page: <strong>$title</strong> (ID: $page_id)</p>";
            $page_ids[$title] = $page_id;
        }
    }
}

// --- 3. CREATE FOOTER MENU ---
echo '<h2>🧭 Building Footer Menu...</h2>';

$menu_name = 'Footer Menu';
$menu_exists = wp_get_nav_menu_object($menu_name);

if ($menu_exists) {
    $menu_id = $menu_exists->term_id;
    echo "<p>ℹ️ Menu '$menu_name' already exists (ID: $menu_id).</p>";
} else {
    $menu_id = wp_create_nav_menu($menu_name);
    echo "<p>✅ Created Menu: '$menu_name' (ID: $menu_id).</p>";
}

// Add pages to menu
if ($menu_id) {
    // Pages to add
    $footer_pages = array('Privacy Policy', 'Terms & Conditions', 'Returns & Warranty', 'Shipping Policy');
    
    foreach ($footer_pages as $p_title) {
        if (isset($page_ids[$p_title])) {
            // Check if item already in menu to avoid duplicates
            $items = wp_get_nav_menu_items($menu_id);
            $already_in_menu = false;
            if ($items) {
                foreach ($items as $item) {
                    if ($item->title == $p_title) { $already_in_menu = true; break; }
                }
            }

            if (!$already_in_menu) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' => $p_title,
                    'menu-item-object-id' => $page_ids[$p_title],
                    'menu-item-object' => 'page',
                    'menu-item-type' => 'post_type',
                    'menu-item-status' => 'publish'
                ));
                echo "<p>-- Added: $p_title</p>";
            } else {
                echo "<p>-- (Already in menu: $p_title)</p>";
            }
        }
    }
    
    // Assign to Footer Location (if Astra supports 'footer_menu' or similar)
    // Note: Astra often uses widgets for footer, but it has a 'secondary' or 'footer_menu' location in pro or customized versions.
    // We'll try to set it to 'secondary' or 'footer' just in case.
    $locations = get_theme_mod('nav_menu_locations');
    $locations['footer_menu'] = $menu_id; // Common key
    $locations['secondary'] = $menu_id;   // Common key
    set_theme_mod('nav_menu_locations', $locations);
    echo "<p>✅ Assigned Menu to Footer/Secondary locations.</p>";
}

// --- 4. FLUSH CACHE ---
echo '<h2>🧹 Flushing Cache...</h2>';
wp_cache_flush();
echo "<p>✅ Cache Flushed.</p>";

echo "<h2>🚀 SETUP COMPLETE.</h2>";
?>
