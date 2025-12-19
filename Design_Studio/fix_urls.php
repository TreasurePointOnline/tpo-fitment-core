<?php
define('WP_USE_THEMES', false);
require_once('wp-load.php');
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

echo '<h1>🔧 Site URL Fixer & Replacer</h1>';

// 1. Update Options
$new_url = 'https://treasurepointonline.com';
$old_url = 'http://localhost'; // Target for replacement

echo "<h2>1. Updating Options...</h2>";
if (update_option('siteurl', $new_url)) {
    echo "<p>✅ Updated 'siteurl' to $new_url</p>";
} else {
    echo "<p>ℹ️ 'siteurl' was already correct or update failed.</p>";
}

if (update_option('home', $new_url)) {
    echo "<p>✅ Updated 'home' to $new_url</p>";
} else {
    echo "<p>ℹ️ 'home' was already correct or update failed.</p>";
}

// 2. Search and Replace in Database
// This is a simplified version of what wp search-replace does.
// It mainly targets posts and postmeta.
echo "<h2>2. Replacing '$old_url' with '$new_url' in Content...</h2>";

global $wpdb;

// Posts Content
$sql_posts = "UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, %s, %s)";
$count_posts = $wpdb->query($wpdb->prepare($sql_posts, $old_url, $new_url));
echo "<p>✅ Updated $count_posts posts/pages content.</p>";

// Post Meta (e.g. image links)
$sql_meta = "UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, %s, %s)";
$count_meta = $wpdb->query($wpdb->prepare($sql_meta, $old_url, $new_url));
echo "<p>✅ Updated $count_meta post meta entries.</p>";

// 3. Flush Rewrites
echo "<h2>3. Flushing Rewrite Rules...</h2>";
global $wp_rewrite;
$wp_rewrite->init();
$wp_rewrite->flush_rules();
echo "<p>✅ Rewrite rules flushed.</p>";

// 4. Flush Cache
echo "<h2>4. Flushing Cache...</h2>";
wp_cache_flush();
echo "<p>✅ Object cache flushed.</p>";

echo "<h2>🚀 ALL DONE. Your site URLs should be fixed.</h2>";
?>
