<?php
// Load WordPress Core
require_once('wp-load.php');

echo '<h1>🏗️ PHASE 1: Structure & Trust (REVISED)</h1>';

// --- A. CREATE CATEGORIES (The Hierarchy) ---
$categories = array(
    'Car Audio' => array('Amplifiers', 'Subwoofers', 'Speakers', 'Wiring & Accessories'),
    'Marine Audio' => array('Marine Amps', 'Marine Speakers'),
    'Installation' => array('Dash Kits', 'Harnesses')
);

foreach ($categories as $parent => $children) {
    // 1. Create Parent
    $parent_term = get_term_by('name', $parent, 'product_cat');
    if ($parent_term) {
        $parent_id = $parent_term->term_id;
        echo "<p>ℹ️ Parent Category exists: <strong>$parent</strong> (ID: $parent_id)</p>";
    } else {
        $result = wp_insert_term($parent, 'product_cat');
        if (!is_wp_error($result)) {
            $parent_id = $result['term_id'];
            echo "<p>✅ Created Parent: <strong>$parent</strong> (ID: $parent_id)</p>";
        } else {
            echo "<p>❌ Error creating parent $parent: " . $result->get_error_message() . "</p>";
            continue; // Skip children if parent failed
        }
    }

    // 2. Create Children
    foreach ($children as $child) {
        $child_term = get_term_by('name', $child, 'product_cat');
        if ($child_term) {
            echo "<p>-- ℹ️ Child Category exists: $child</p>";
        } else {
            $result = wp_insert_term($child, 'product_cat', array('parent' => $parent_id));
            if (!is_wp_error($result)) {
                echo "<p>-- ✅ Created Child: $child</p>";
            } else {
                echo "<p>-- ❌ Error creating child $child: " . $result->get_error_message() . "</p>";
            }
        }
    }
}

// --- B. CREATE LEGAL PAGES ---
$pages = array(
    'Contact Us' => '<p>Call us at 765-643-5822 or visit us at 206 Marine Dr., Anderson, IN.</p>',
    'Privacy Policy' => '<p>Standard Privacy Policy for Treasure Point Audio...</p>',
    'Terms of Service' => '<p>Terms and Conditions for use of this site...</p>',
    'Shipping & Returns' => '<p>We offer shipping on all orders over $99...</p>'
);

// Get a default author ID (usually 1 for the admin)
$author_id = 1; 
$user_query = new WP_User_Query( array( 'role' => 'administrator', 'number' => 1 ) );
if ( ! empty( $user_query->get_results() ) ) {
    $admin_user = $user_query->get_results()[0];
    $author_id = $admin_user->ID;
}


foreach ($pages as $title => $content) {
    if (!get_page_by_title($title)) {
        $page_id = wp_insert_post(array(
            'post_title'   => $title,
            'post_content' => $content,
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_author'  => $author_id, // Assign to an author
        ));
        if ($page_id) {
            echo "<p>✅ Created Page: <strong>$title</strong></p>";
        } else {
            echo "<p>❌ Error creating page: $title</p>";
        }
    } else {
        echo "<p>ℹ️ Page exists: $title</p>";
    }
}
echo '<h2>✅ STRUCTURE COMPLETE.</h2>';
?>