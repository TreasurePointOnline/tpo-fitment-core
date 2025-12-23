<?php
/**
 * Plugin Name: TPO Skin Injector (V5 - Nav & Footer)
 * Description: Custom Nav structure and Pro Footer.
 * Version: 5.0
 * Author: Treasure Point AI
 */

function tpo_rebuild_header() {
    $home_url = esc_url( home_url( '/' ) );
    $cart_url = esc_url( wc_get_cart_url() );
    $account_url = esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) );
    
    // 1. UPDATED NAV STRUCTURE
    $nav_html = "
    <div class=\"tpo-sticky-nav\">
        <div class=\"tpo-nav-container\">
            <!-- Amplifiers -->
            <div class=\"tpo-dropdown\">
                <a href=\"/product-category/car-audio/amplifiers/\" class=\"tpo-nav-link\">AMPLIFIERS ▾</a>
                <div class=\"tpo-dropdown-content\">
                    <a href=\"/product-category/car-audio/amplifiers/\">Monoblock</a>
                    <a href=\"/product-category/car-audio/amplifiers/\">Multi-Channel</a>
                </div>
            </div>
            
            <!-- Subwoofers -->
            <div class=\"tpo-dropdown\">
                <a href=\"/product-category/car-audio/subwoofers/\" class=\"tpo-nav-link\">SUBWOOFERS ▾</a>
                <div class=\"tpo-dropdown-content\">
                    <a href=\"/product-category/car-audio/subwoofers/og-gold-subwoofers/\">OG Gold</a>
                    <a href=\"/product-category/car-audio/subwoofers/og-silver-subwoofers/\">OG Silver</a>
                    <a href=\"/product-category/car-audio/subwoofers/og-bronze-subwoofers/\">OG Bronze</a>
                </div>
            </div>

            <!-- Speakers -->
            <div class=\"tpo-dropdown\">
                <a href=\"/product-category/car-audio/speakers/\" class=\"tpo-nav-link\">SPEAKERS ▾</a>
                <div class=\"tpo-dropdown-content\">
                    <a href=\"/product-category/car-audio/speakers/ogpro/\">OG Pro Series</a>
                </div>
            </div>

            <!-- Enclosures -->
            <a href=\"/product-category/empty-enclosures/\" class=\"tpo-nav-link\">ENCLOSURES</a>
            
            <!-- Accessories -->
            <a href=\"/product-category/amp-kits-accessories/\" class=\"tpo-nav-link\">ACCESSORIES</a>
        </div>
    </div>
    ";
    $nav_html_clean = str_replace(array("\r", "\n"), '', $nav_html);

    // 2. PRO FOOTER HTML
    $footer_html = "
    <div class=\"tpo-pro-footer\">
        <div class=\"tpo-footer-grid\">
            <!-- Col 1: Brand & Social -->
            <div class=\"tpo-footer-col\">
                <h3 class=\"tpo-footer-title\">TREASURE POINT</h3>
                <p>Anderson's Premier Audio Destination.</p>
                <div class=\"tpo-socials\">
                    <a href=\"https://facebook.com\" target=\"_blank\">FB</a>
                    <a href=\"https://instagram.com\" target=\"_blank\">IG</a>
                </div>
            </div>
            
            <!-- Col 2: Links -->
            <div class=\"tpo-footer-col\">
                <h3 class=\"tpo-footer-title\">QUICK LINKS</h3>
                <a href=\"/contact/\">Contact Us</a>
                <a href=\"/privacy-policy/\">Privacy Policy</a>
                <a href=\"/terms-conditions/\">Terms</a>
                <a href=\"/shipping-returns/\">Shipping</a>
            </div>

            <!-- Col 3: Newsletter -->
            <div class=\"tpo-footer-col newsletter\">
                <h3 class=\"tpo-footer-title\">GET 10% OFF</h3>
                <p>Join the club for exclusive deals.</p>
                <form class=\"tpo-newsletter-form\">
                    <input type=\"email\" placeholder=\"Enter email...\">
                    <button>JOIN</button>
                </form>
            </div>
        </div>
        <div class=\"tpo-copyright\">
            &copy; " . date("Y") . " Treasure Point Audio. All Rights Reserved.
        </div>
    </div>
    ";
    $footer_html_clean = str_replace(array("\r", "\n"), '', $footer_html);

    $js = <<<EOT
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var headerRow = document.querySelector('.site-primary-header-wrap .ast-builder-grid-row');
        var mainHeader = document.querySelector('.site-header');
        var siteFooter = document.querySelector('.site-footer');

        // HEADER LOGIC (Same as before)
        if(headerRow) {
            headerRow.innerHTML = '';
            headerRow.style.display = 'flex'; headerRow.style.justifyContent = 'space-between'; headerRow.style.alignItems = 'center'; headerRow.style.padding = '10px 20px';

            var logoDiv = document.createElement('div');
            logoDiv.innerHTML = `<a href="{$home_url}" class="tpo-brand-link"><svg width="35" height="35" viewBox="0 0 100 100" class="tpo-logo-icon"><path d="M50 0 L100 25 L100 75 L50 100 L0 75 L0 25 Z" fill="#D32F2F"/><path d="M50 15 L85 32 L85 68 L50 85 L15 68 L15 32 Z" fill="#000"/><text x="50" y="65" font-family="Arial" font-weight="900" font-size="40" text-anchor="middle" fill="#FFF">TP</text></svg><span class="tpo-brand-text">TREASURE <span style="color:#D32F2F">POINT</span></span></a>`;
            headerRow.appendChild(logoDiv);

            var searchDiv = document.createElement('div');
            searchDiv.className = 'tpo-search-area';
            searchDiv.innerHTML = `<form role="search" method="get" class="tpo-search-form" action="{$home_url}"><input type="search" class="tpo-search-input" placeholder="Search..." name="s"><button type="submit" class="tpo-search-btn">🔍</button><input type="hidden" name="post_type" value="product"></form>`;
            headerRow.appendChild(searchDiv);

            var actionsDiv = document.createElement('div');
            actionsDiv.className = 'tpo-actions-area';
            actionsDiv.innerHTML = `<a href="{$account_url}" class="tpo-action-btn"><span class="tpo-icon">👤</span><span class="tpo-label">Account</span></a><a href="{$cart_url}" class="tpo-action-btn"><span class="tpo-icon">🛒</span><span class="tpo-label">Cart</span></a>`;
            headerRow.appendChild(actionsDiv);
        }

        // INJECT NAV
        if(mainHeader && !document.querySelector('.tpo-sticky-nav')) {
            var navDiv = document.createElement('div');
            navDiv.innerHTML = '{$nav_html_clean}';
            mainHeader.parentNode.insertBefore(navDiv, mainHeader.nextSibling);
        }

        // INJECT FOOTER (Replace Astra Footer)
        if(siteFooter) {
            siteFooter.innerHTML = '{$footer_html_clean}';
        }
    });
    </script>
EOT;
    echo $js;
}
add_action('wp_footer', 'tpo_rebuild_header');

function tpo_inject_modern_css() {
    $css = <<<EOT
    <style>
        /* === RESET === */
        :root { --tpo-red: #D32F2F; --tpo-dark: #0a0a0a; --tpo-panel: #141414; }
        body, html { background-color: var(--tpo-dark) !important; color: white !important; font-family: 'Inter', sans-serif; overflow-x: hidden; }

        .page-header, .entry-title, .ast-archive-description { display: none !important; }
        .ast-main-header-wrap { background-color: var(--tpo-panel) !important; border-bottom: 1px solid #333; }
        
        /* HEADER STYLES */
        .tpo-brand-link { display: flex; align-items: center; text-decoration: none; gap: 8px; }
        .tpo-brand-text { font-size: 22px; font-weight: 800; color: white; text-transform: uppercase; }
        .tpo-search-area { flex-grow: 1; max-width: 600px; margin: 0 40px; position: relative; }
        .tpo-search-input { width: 100%; padding: 10px 20px; border-radius: 50px !important; background: #222 !important; border: 1px solid #444 !important; color: white !important; }
        .tpo-search-btn { position: absolute; right: 2px; top: 2px; border-radius: 50px !important; background: var(--tpo-red) !important; border: none !important; color: white; padding: 6px 15px !important; cursor: pointer; }
        .tpo-actions-area { display: flex; gap: 20px; }
        .tpo-action-btn { color: #ccc; font-size: 11px; font-weight: 600; text-transform: uppercase; text-decoration: none; display: flex; flex-direction: column; align-items: center; }
        .tpo-icon { font-size: 20px; margin-bottom: 2px; }

        /* NAV BAR */
        .tpo-sticky-nav { background-color: #1a1a1a; border-bottom: 3px solid var(--tpo-red); position: sticky; top: 0; z-index: 999; box-shadow: 0 5px 15px rgba(0,0,0,0.5); }
        .tpo-nav-container { max-width: 1400px; margin: 0 auto; display: flex; justify-content: center; }
        .tpo-nav-link { display: block; padding: 15px 25px; color: white; font-weight: 700; text-decoration: none; text-transform: uppercase; font-size: 14px; border-right: 1px solid #333; }
        .tpo-nav-link:hover { background-color: var(--tpo-red); color: white; }
        .tpo-nav-link:first-child { border-left: 1px solid #333; }

        /* DROPDOWN */
        .tpo-dropdown { position: relative; display: inline-block; }
        .tpo-dropdown-content { display: none; position: absolute; background-color: #222; min-width: 200px; box-shadow: 0 8px 16px rgba(0,0,0,0.5); z-index: 1000; border-top: 3px solid var(--tpo-red); }
        .tpo-dropdown-content a { color: white; padding: 12px 16px; text-decoration: none; display: block; font-size: 13px; text-transform: uppercase; }
        .tpo-dropdown-content a:hover { background-color: #333; color: var(--tpo-red); }
        .tpo-dropdown:hover .tpo-dropdown-content { display: block; }
        .tpo-dropdown:hover .tpo-nav-link { background-color: #333; color: var(--tpo-red); }

        /* FOOTER */
        .tpo-pro-footer { background: #050505; border-top: 1px solid #333; padding: 60px 20px 20px 20px; margin-top: 50px; }
        .tpo-footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; max-width: 1200px; margin: 0 auto; }
        .tpo-footer-title { color: var(--tpo-red); font-size: 16px; font-weight: 800; margin-bottom: 20px; letter-spacing: 1px; }
        .tpo-footer-col p { color: #999; line-height: 1.6; font-size: 14px; }
        .tpo-footer-col a { display: block; color: #ccc; text-decoration: none; margin-bottom: 10px; transition: 0.2s; font-size: 14px; }
        .tpo-footer-col a:hover { color: white; transform: translateX(5px); }
        
        .tpo-socials { margin-top: 20px; }
        .tpo-socials a { display: inline-block; margin-right: 15px; font-weight: bold; background: #222; padding: 8px 15px; border-radius: 4px; }
        
        .tpo-newsletter-form { display: flex; margin-top: 15px; }
        .tpo-newsletter-form input { flex-grow: 1; padding: 10px; background: #222; border: 1px solid #444; color: white; outline: none; border-radius: 4px 0 0 4px; }
        .tpo-newsletter-form button { background: var(--tpo-red); border: none; color: white; font-weight: bold; padding: 0 15px; cursor: pointer; border-radius: 0 4px 4px 0; }

        .tpo-copyright { text-align: center; margin-top: 60px; padding-top: 20px; border-top: 1px solid #222; color: #555; font-size: 12px; }

        /* MOBILE FIXES */
        @media (max-width: 768px) {
            .tpo-nav-container { justify-content: flex-start; overflow-x: auto; white-space: nowrap; }
            .tpo-brand-text { font-size: 18px; }
            .tpo-search-area { display: none; } /* Hide search on mobile header to save space? Or keep small? */
            .tpo-footer-grid { grid-template-columns: 1fr; text-align: center; }
        }
        
        /* WOOCOMMERCE */
        .woocommerce ul.products li.product { background: #1a1a1a !important; border: 1px solid #333 !important; }
        .woocommerce-loop-product__title { color: white !important; }
        .price { color: var(--tpo-red) !important; font-weight: bold; }
    </style>
EOT;
    echo $css;
}
add_action('wp_head', 'tpo_inject_modern_css', 999);
?>
