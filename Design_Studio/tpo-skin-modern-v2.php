<?php
/**
 * Plugin Name: TPO Skin Injector (Modern Edition v4.1)
 * Description: SVG Logo, Search, and Sticky Secondary Nav with Dropdowns.
 * Version: 4.1
 * Author: Treasure Point AI
 */

function tpo_rebuild_header() {
    $home_url = esc_url( home_url( '/' ) );
    $cart_url = esc_url( wc_get_cart_url() );
    $account_url = esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) );
    
    // THE SECONDARY NAV HTML
    // We inject this after the main header
    $nav_html = '
    <div class="tpo-sticky-nav">
        <div class="tpo-nav-container">
            <div class="tpo-dropdown">
                <a href="/product-category/car-audio/" class="tpo-nav-link">CAR AUDIO ▾</a>
                <div class="tpo-dropdown-content">
                    <a href="/product-category/car-audio/amplifiers/">Amplifiers</a>
                    <a href="/product-category/car-audio/subwoofers/">Subwoofers</a>
                    <a href="/product-category/car-audio/speakers/">Speakers</a>
                    <a href="/product-category/car-audio/wiring-accessories/">Wiring &amp; Install</a>
                </div>
            </div>
            
            <div class="tpo-dropdown">
                <a href="/product-category/marine-audio/" class="tpo-nav-link">MARINE ▾</a>
                <div class="tpo-dropdown-content">
                    <a href="/product-category/marine-audio/marine-amps/">Marine Amps</a>
                    <a href="/product-category/marine-audio/marine-speakers/">Marine Speakers</a>
                    <a href="/product-category/marine-audio/towers/">Tower Speakers</a>
                </div>
            </div>

            <a href="/product-category/powersports/" class="tpo-nav-link">POWERSPORTS</a>
            <a href="/product-category/brands/" class="tpo-nav-link">BRANDS</a>
            <a href="/contact/" class="tpo-nav-link">CONTACT</a>
        </div>
    </div>
    ';
    
    // Minify HTML for JS string
    $nav_html_clean = str_replace(array("\r", "\n"), '', $nav_html);

    $js = <<<EOT
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var headerRow = document.querySelector('.site-primary-header-wrap .ast-builder-grid-row');
        var mainHeader = document.querySelector('.site-header');

        if(headerRow) {
            // 1. CLEAR & REBUILD TOP HEADER
            headerRow.innerHTML = '';
            headerRow.style.display = 'flex'; headerRow.style.justifyContent = 'space-between'; headerRow.style.alignItems = 'center'; headerRow.style.padding = '10px 20px';

            // LOGO
            var logoDiv = document.createElement('div');
            logoDiv.innerHTML = `<a href="{$home_url}" class="tpo-brand-link"><svg width="35" height="35" viewBox="0 0 100 100" class="tpo-logo-icon"><path d="M50 0 L100 25 L100 75 L50 100 L0 75 L0 25 Z" fill="#D32F2F"/><path d="M50 15 L85 32 L85 68 L50 85 L15 68 L15 32 Z" fill="#000"/><text x="50" y="65" font-family="Arial" font-weight="900" font-size="40" text-anchor="middle" fill="#FFF">TP</text></svg><span class="tpo-brand-text">TREASURE <span style="color:#D32F2F">POINT</span></span></a>`;
            headerRow.appendChild(logoDiv);

            // SEARCH
            var searchDiv = document.createElement('div');
            searchDiv.className = 'tpo-search-area';
            searchDiv.innerHTML = `<form role="search" method="get" class="tpo-search-form" action="{$home_url}"><input type="search" class="tpo-search-input" placeholder="Search..." name="s"><button type="submit" class="tpo-search-btn">🔍</button><input type="hidden" name="post_type" value="product"></form>`;
            headerRow.appendChild(searchDiv);

            // ACTIONS
            var actionsDiv = document.createElement('div');
            actionsDiv.className = 'tpo-actions-area';
            actionsDiv.innerHTML = `<a href="{$account_url}" class="tpo-action-btn"><span class="tpo-icon">👤</span><span class="tpo-label">Account</span></a><a href="{$cart_url}" class="tpo-action-btn"><span class="tpo-icon">🛒</span><span class="tpo-label">Cart</span></a>`;
            headerRow.appendChild(actionsDiv);
        }

        // 2. INJECT SECONDARY NAV BAR (The "Sticky Buttons")
        if(mainHeader && !document.querySelector('.tpo-sticky-nav')) {
            var navDiv = document.createElement('div');
            navDiv.innerHTML = '{$nav_html_clean}';
            mainHeader.parentNode.insertBefore(navDiv, mainHeader.nextSibling);
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
        body, html { background-color: var(--tpo-dark) !important; color: white !important; font-family: 'Inter', sans-serif; }

        /* === HIDE "HOME" TITLE === */
        .page-header, .entry-title, .ast-archive-description { display: none !important; }

        /* === HEADER === */
        .ast-main-header-wrap { background-color: var(--tpo-panel) !important; border-bottom: 1px solid #333; }
        
        /* Elements from JS */
        .tpo-brand-link { display: flex; align-items: center; text-decoration: none; gap: 10px; }
        .tpo-brand-text { font-size: 22px; font-weight: 800; color: white; text-transform: uppercase; }
        .tpo-search-area { flex-grow: 1; max-width: 600px; margin: 0 40px; position: relative; }
        .tpo-search-input { width: 100%; padding: 10px 20px; border-radius: 50px !important; background: #222 !important; border: 1px solid #444 !important; color: white !important; }
        .tpo-search-btn { position: absolute; right: 5px; top: 2px; border-radius: 50px !important; background: var(--tpo-red) !important; border: none !important; color: white; padding: 6px 15px !important; cursor: pointer; }
        .tpo-actions-area { display: flex; gap: 20px; }
        .tpo-action-btn { color: #ccc; font-size: 11px; font-weight: 600; text-transform: uppercase; text-decoration: none; display: flex; flex-direction: column; align-items: center; }
        .tpo-icon { font-size: 18px; margin-bottom: 2px; }

        /* === STICKY SECONDARY NAV === */
        .tpo-sticky-nav {
            background-color: #1a1a1a;
            border-bottom: 3px solid var(--tpo-red);
            position: sticky; top: 0; z-index: 999; /* Below WP Admin bar if present */
            box-shadow: 0 5px 15px rgba(0,0,0,0.5);
        }
        .tpo-nav-container {
            max-width: 1400px; margin: 0 auto; display: flex; justify-content: center;
        }
        .tpo-nav-link {
            display: block; padding: 15px 25px; color: white; font-weight: 700; text-decoration: none; text-transform: uppercase; font-size: 14px;
            border-right: 1px solid #333;
        }
        .tpo-nav-link:hover { background-color: var(--tpo-red); color: white; }
        .tpo-nav-link:first-child { border-left: 1px solid #333; }

        /* DROPDOWN LOGIC */
        .tpo-dropdown { position: relative; display: inline-block; }
        .tpo-dropdown-content {
            display: none; position: absolute; background-color: #222; min-width: 200px; box-shadow: 0 8px 16px rgba(0,0,0,0.5); z-index: 1000;
            border-top: 3px solid var(--tpo-red);
        }
        .tpo-dropdown-content a {
            color: white; padding: 12px 16px; text-decoration: none; display: block; font-size: 13px; text-transform: uppercase;
        }
        .tpo-dropdown-content a:hover { background-color: #333; color: var(--tpo-red); }
        .tpo-dropdown:hover .tpo-dropdown-content { display: block; }
        .tpo-dropdown:hover .tpo-nav-link { background-color: #333; color: var(--tpo-red); }

        /* WOOCOMMERCE DARK MODE TWEAKS */
        .woocommerce ul.products li.product { background: #1a1a1a !important; border: 1px solid #333 !important; padding: 15px !important; }
        .woocommerce-loop-product__title { color: white !important; }
        .price { color: var(--tpo-red) !important; font-weight: bold; }
    </style>
EOT;
    echo $css;
}
add_action('wp_head', 'tpo_inject_modern_css', 999);
?>
