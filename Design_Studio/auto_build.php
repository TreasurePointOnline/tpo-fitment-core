<?php
define('WP_USE_THEMES', false);
require_once('wp-load.php');
require_once(ABSPATH . 'wp-admin/includes/admin.php');

echo '<h1>🤖 TPO Autopilot Optimizer</h1>';

// --- 1. PERMALINKS (Critical for SEO) ---
global $wp_rewrite;
$wp_rewrite->set_permalink_structure('/%postname%/');
$wp_rewrite->flush_rules();
echo "<p>✅ Permalinks set to /post-name/ (Best for SEO).</p>";

// --- 2. GENERAL SETTINGS ---
update_option('timezone_string', 'America/New_York'); // Indiana
update_option('start_of_week', 1); // Monday
update_option('blogdescription', 'Premium Car Audio & Marine Electronics');
update_option('users_can_register', 1); // Allow customers
echo "<p>✅ General Settings optimized (Timezone, Tagline).</p>";

// --- 3. WOOCOMMERCE SETTINGS ---
update_option('woocommerce_enable_guest_checkout', 'yes');
update_option('woocommerce_enable_reviews', 'yes');
update_option('woocommerce_enable_review_rating', 'yes');
update_option('woocommerce_currency', 'USD');
update_option('woocommerce_weight_unit', 'lbs');
update_option('woocommerce_dimension_unit', 'in');
echo "<p>✅ WooCommerce optimized (Reviews, Guest Checkout, Units).</p>";

// --- 4. RANK MATH SEO (Auto-Config) ---
// We set optimal defaults if Rank Math is active
if (is_plugin_active('seo-by-rank-math/rank-math.php')) {
    update_option('rank_math_modules', array(
        'titles' => 'on',
        'sitemap' => 'on',
        'woocommerce' => 'on',
        'image-seo' => 'on',
        'search-console' => 'off' // Requires auth
    ));
    
    // Set Default Title Formats
    $titles = get_option('rank_math_titles');
    $titles['pt_product_title'] = '%title% %sep% %sitename%';
    $titles['pt_product_description'] = 'Buy %title% at the best price from Treasure Point. Authorized Dealer. Fast Shipping.';
    update_option('rank_math_titles', $titles);
    
    echo "<p>✅ Rank Math SEO configured with e-commerce best practices.</p>";
} else {
    echo "<p>⚠️ Rank Math not active. Skipping SEO config.</p>";
}

// --- 5. CLEANUP ---
// Disable Comments on Pages (Looks unprofessional on business sites)
$pages = get_pages();
foreach ($pages as $page) {
    $update = array(
        'ID' => $page->ID,
        'comment_status' => 'closed',
        'ping_status' => 'closed'
    );
    wp_update_post($update);
}
echo "<p>✅ Comments disabled on all static pages.</p>";

// --- 6. SECURITY TWEAKS ---
// Disable file editing in dashboard (safer)
if (!defined('DISALLOW_FILE_EDIT')) {
    // We can't edit wp-config here easily, but we can set the option if a plugin checks it
    // Often this requires wp-config edit. We'll skip for now to avoid breaking the site again.
}

echo "<h2>🚀 OPTIMIZATION COMPLETE.</h2>";
?>
