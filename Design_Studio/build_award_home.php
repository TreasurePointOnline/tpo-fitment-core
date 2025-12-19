<?php
require_once('wp-load.php');
echo '<h1>🏆 Building the "Award-Winning" Homepage...</h1>';

// 1. THE CONTENT (Cyberpunk/High-End Style)
$html_content = '
<!-- HERO SECTION -->
<div class="tpo-hero">
    <div class="tpo-hero-content">
        <h4 class="tpo-subtitle">ANDERSON\'S PREMIER AUTHORIZED DEALER</h4>
        <h1 class="tpo-title">LOUD.<br>CLEAR.<br>LEGENDARY.</h1>
        <p class="tpo-lead">Experience car audio the way it was meant to be heard. From competition-grade subwoofers to crystal-clear marine systems.</p>
        <div class="tpo-buttons">
            <a href="/shop/" class="tpo-btn primary">SHOP INVENTORY</a>
            <a href="/contact/" class="tpo-btn secondary">BOOK INSTALLATION</a>
        </div>
    </div>
</div>

<!-- CATEGORY BENTO GRID -->
<div class="tpo-section">
    <h2 class="tpo-section-title">EXPLORE THE GEAR</h2>
    <div class="tpo-bento-grid">
        <a href="/product-category/car-audio/subwoofers/" class="tpo-bento-item wide" style="background-image: url(\''https://images.unsplash.com/photo-1558486012-817176f84c6d?auto=format&fit=crop&w=800\')">
            <div class="tpo-bento-label">SUBWOOFERS</div>
        </a>
        <a href="/product-category/car-audio/amplifiers/" class="tpo-bento-item" style="background-image: url(\''https://images.unsplash.com/photo-1625234832484-961f3627c2ce?auto=format&fit=crop&w=400\')">
            <div class="tpo-bento-label">AMPLIFIERS</div>
        </a>
        <a href="/product-category/marine-audio/" class="tpo-bento-item" style="background-image: url(\''https://images.unsplash.com/photo-1564694202779-bc908c32786c?auto=format&fit=crop&w=400\')">
            <div class="tpo-bento-label">MARINE</div>
        </a>
        <a href="/product-category/car-audio/speakers/" class="tpo-bento-item tall" style="background-image: url(\''https://images.unsplash.com/photo-1545167622-3a6ac756afa4?auto=format&fit=crop&w=400\')">
            <div class="tpo-bento-label">SPEAKERS</div>
        </a>
        <a href="/product-category/installation/" class="tpo-bento-item wide" style="background-image: url(\''https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?auto=format&fit=crop&w=800\')">
            <div class="tpo-bento-label">PRO INSTALLATION</div>
        </a>
    </div>
</div>

<!-- SEO CONTENT BLOCK (Hidden in plain sight design) -->
<div class="tpo-section tpo-seo-block">
    <div class="tpo-container">
        <h2>Why Treasure Point Audio?</h2>
        <div class="tpo-features">
            <div class="tpo-feature">
                <i class="fas fa-certificate"></i>
                <h3>Authorized Dealer</h3>
                <p>Full warranty support for Kicker, JL Audio, and more.</p>
            </div>
            <div class="tpo-feature">
                <i class="fas fa-tools"></i>
                <h3>Expert Install</h3>
                <p>20+ years of experience in custom fabrication.</p>
            </div>
            <div class="tpo-feature">
                <i class="fas fa-shipping-fast"></i>
                <h3>Fast Shipping</h3>
                <p>Free shipping on orders over $99 nationwide.</p>
            </div>
        </div>
    </div>
</div>
';

// 2. CREATE PAGE
$home_id = wp_insert_post(array(
    'post_title'   => 'Home Award Layout',
    'post_content' => $html_content,
    'post_status'  => 'publish',
    'post_type'    => 'page',
    'post_author'  => 1
));

// 3. SET AS FRONT PAGE
if($home_id) {
    update_option('show_on_front', 'page');
    update_option('page_on_front', $home_id);
    echo "<p>✅ New 'Award Layout' created (ID: $home_id) and set as Homepage.</p>";
}

echo "<h2>🚀 HOMEPAGE DEPLOYED. Now updating styles...</h2>";
?>
