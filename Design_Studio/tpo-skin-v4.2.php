<?php
/**
 * Plugin Name: TPO Skin Injector (V4.2 - Simplified Nav)
 * Description: Simplified Nav + Current SVG Logo.
 * Version: 4.2
 * Author: Treasure Point AI
 */

function tpo_rebuild_header() {
    $home_url = esc_url( home_url( '/' ) );
    $cart_url = esc_url( wc_get_cart_url() );
    $account_url = esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) );
    
    // Current SVG LOGO URL
    $logo_svg_url = content_url('uploads/2025/12/tpo-logo-v3.svg'); // Assuming V3 is the current "best" SVG

    // NAV HTML
    $nav_html = "
    <div class=\"tpo-sticky-nav\">
        <div class=\"tpo-nav-container\">
            <a href=\"/product-category/car-audio/amplifiers/\" class=\"tpo-nav-link\">AMPLIFIERS</a>
            <a href=\"/product-category/car-audio/subwoofers/\" class=\"tpo-nav-link\">SUBWOOFERS</a>
            <a href=\"/product-category/car-audio/speakers/\" class=\"tpo-nav-link\">AUDIO SPEAKERS</a>
            <a href=\"/product-category/empty-enclosures/\" class=\"tpo-nav-link\">EMPTY ENCLOSURES</a>
            <a href=\"/product-category/car-audio/wiring-accessories/\" class=\"tpo-nav-link\">WIRING KITS</a>
        </div>
    </div>
    ";
    $nav_html_clean = str_replace(array("\r", "\n"), '', $nav_html);

    $js = <<<EOT
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var headerRow = document.querySelector('.site-primary-header-wrap .ast-builder-grid-row');
        var mainHeader = document.querySelector('.site-header');

        if(headerRow) {
            headerRow.innerHTML = '';
            headerRow.style.display = 'flex'; headerRow.style.justifyContent = 'space-between'; headerRow.style.alignItems = 'center'; headerRow.style.padding = '10px 20px';

            // LOGO (Uses latest working SVG image)
            var logoDiv = document.createElement('div');
            logoDiv.className = 'tpo-logo-area';
            logoDiv.innerHTML = `<a href="{$home_url}" class="tpo-brand-link"><img src="{$logo_svg_url}" alt="TP Audio" class="tpo-logo-svg"></a>`;
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
        body, html { background-color: var(--tpo-dark) !important; color: white !important; font-family: 'Inter', sans-serif; overflow-x: hidden; }

        .page-header, .entry-title, .ast-archive-description { display: none !important; }
        .ast-main-header-wrap { background-color: var(--tpo-panel) !important; border-bottom: 1px solid #333; }
        
        /* LOGO IMG (Using V3 SVG) */
        .tpo-logo-svg { height: 55px; width: auto; } /* Ensure it's using the V3 SVG size */

        /* Search & Actions (Same) */
        .tpo-brand-link { display: flex; align-items: center; text-decoration: none; }
        .tpo-search-area { flex-grow: 1; max-width: 600px; margin: 0 40px; position: relative; }
        .tpo-search-input { width: 100%; padding: 10px 20px; border-radius: 50px !important; background: #222 !important; border: 1px solid #444 !important; color: white !important; }
        .tpo-search-btn { position: absolute; right: 2px; top: 2px; border-radius: 50px !important; background: var(--tpo-red) !important; border: none !important; color: white; padding: 6px 15px !important; cursor: pointer; }
        .tpo-search-form { position: relative; }

        .tpo-actions-area { display: flex; gap: 20px; }
        .tpo-action-btn { color: #ccc; font-size: 11px; font-weight: 600; text-transform: uppercase; text-decoration: none; display: flex; flex-direction: column; align-items: center; }
        .tpo-icon { font-size: 20px; margin-bottom: 2px; }

        /* NAV BAR */
        .tpo-sticky-nav { background-color: #1a1a1a; border-bottom: 3px solid var(--tpo-red); position: sticky; top: 0; z-index: 999; box-shadow: 0 5px 15px rgba(0,0,0,0.5); }
        .tpo-nav-container { max-width: 1400px; margin: 0 auto; display: flex; justify-content: center; }
        .tpo-nav-link { display: block; padding: 15px 25px; color: white; font-weight: 700; text-decoration: none; text-transform: uppercase; font-size: 14px; border-right: 1px solid #333; }
        .tpo-nav-link:hover { background-color: var(--tpo-red); color: white; }
        .tpo-nav-link:first-child { border-left: 1px solid #333; }

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