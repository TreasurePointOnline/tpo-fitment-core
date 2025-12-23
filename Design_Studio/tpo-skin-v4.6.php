<?php
/**
 * Plugin Name: TPO Skin Injector (V4.6 - Nav Dropdowns)
 * Description: SVG Logo (Fixed) + Full Nav Dropdowns.
 * Version: 4.6
 * Author: Treasure Point AI
 */

function tpo_rebuild_header() {
    $home_url = esc_url( home_url( '/' ) );
    $cart_url = esc_url( wc_get_cart_url() );
    $account_url = esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) );
    
    // SVG LOGO URL
    $logo_svg_url = content_url('uploads/2025/12/tpo-logo-v3.svg'); 

    // NAV HTML WITH DROPDOWNS
    $nav_html = '    <div class="tpo-sticky-nav">
        <div class="tpo-nav-container">
            <!-- Amplifiers -->
            <div class="tpo-dropdown">
                <a href="/product-category/amplifiers/" class="tpo-nav-link">AMPLIFIERS ▾</a>
                <div class="tpo-dropdown-content">
                    <a href="/product-category/amplifiers/?filter_series=ogs-series">OGS Series</a>
                </div>
            </div>
            
            <!-- Subwoofers -->
            <div class="tpo-dropdown">
                <a href="/product-category/subwoofers/" class="tpo-nav-link">SUBWOOFERS ▾</a>
                <div class="tpo-dropdown-content">
                    <a href="/product-category/subwoofers/?filter_series=og-gold-series">OG Gold Series</a>
                    <a href="/product-category/subwoofers/?filter_series=og-silver-series">OG Silver Series</a>
                    <a href="/product-category/subwoofers/?filter_series=og-bronze-series">OG Bronze Series</a>
                </div>
            </div>

            <!-- Audio Speakers -->
            <div class="tpo-dropdown">
                <a href="/product-category/audio-speakers/" class="tpo-nav-link">AUDIO SPEAKERS ▾</a>
                <div class="tpo-dropdown-content">
                    <a href="/product-category/audio-speakers/?filter_series=ogpro-series">OGPro Series</a>
                </div>
            </div>

            <!-- Empty Enclosures -->
            <a href="/product-category/empty-enclosures/" class="tpo-nav-link">ENCLOSURES</a>
            
            <!-- Wiring Kits -->
            <a href="/product-category/amp-kits-accessories/" class="tpo-nav-link">WIRING KITS</a>
        </div>
    </div>
    ';
    $nav_html_clean = str_replace(array("\r", "\n"), '', $nav_html);

    $js = <<<EOT
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var headerRow = document.querySelector('.site-primary-header-wrap .ast-builder-grid-row');
        var mainHeader = document.querySelector('.site-header');

        if(headerRow) {
            headerRow.innerHTML = '';
            headerRow.style.display = 'flex'; headerRow.style.justifyContent = 'space-between'; headerRow.style.alignItems = 'center'; headerRow.style.padding = '10px 20px';

            // LOGO (Uses V3 Logo SVG)
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
        .tpo-logo-svg { height: 55px; width: auto; } /* Adjust size as needed */

        /* Search & Actions (Same) */
        .tpo-brand-link { display: flex; align-items: center; text-decoration: none; }
        .tpo-search-area { flex-grow: 1; max-width: 600px; margin: 0 40px; position: relative; }
        .tpo-search-input { width: 100%; padding: 10px 20px; border-radius: 50px !important; background: #222 !important; border: 1px solid #444 !important; color: white !important; }
        .tpo-search-btn { position: absolute; right: 2px; top: 2px; border-radius: 50px !important; background: var(--tpo-red) !important; border: none !important; color: white; padding: 6px 15px !important; cursor: pointer; }
        .tpo-search-form { position: relative; }

        .tpo-actions-area { display: flex; gap: 20px; }
        .tpo-action-btn { color: #ccc; font-size: 11px; font-weight: 600; text-transform: uppercase; text-decoration: none; display: flex; flex-direction: column; align-items: center; }
        .tpo-icon { font-size: 20px; margin-bottom: 2px; }

        /* NAV BAR (With Dropdowns) */
        .tpo-sticky-nav { background-color: #1a1a1a; border-bottom: 3px solid var(--tpo-red); position: sticky; top: 0; z-index: 999; box-shadow: 0 5px 15px rgba(0,0,0,0.5); }
        .tpo-nav-container { max-width: 1400px; margin: 0 auto; display: flex; justify-content: center; }
        .tpo-nav-link { display: block; padding: 15px 25px; color: white; font-weight: 700; text-decoration: none; text-transform: uppercase; font-size: 14px; border-right: 1px solid #333; }
        .tpo-nav-link:hover { background-color: var(--tpo-red); color: white; }
        .tpo-nav-link:first-child { border-left: 1px solid #333; }

        /* DROPDOWN STYLES */
        .tpo-dropdown { position: relative; display: inline-block; }
        .tpo-dropdown-content {
            display: none; position: absolute; background-color: #222; min-width: 200px; box-shadow: 0 8px 16px rgba(0,0,0,0.5);
            border-top: 3px solid var(--tpo-red);
        }
        .tpo-dropdown-content a {
            color: white; padding: 12px 16px; text-decoration: none; display: block; font-size: 13px; text-transform: uppercase;
        }
        .tpo-dropdown-content a:hover { background-color: #333; color: var(--tpo-red); }
        .tpo-dropdown:hover .tpo-dropdown-content { display: block; }
        .tpo-dropdown:hover .tpo-nav-link { background-color: #333; color: var(--tpo-red); }

        /* WOOCOMMERCE (Same) */
        .woocommerce ul.products li.product { background: #1a1a1a !important; border: 1px solid #333 !important; }
        .woocommerce-loop-product__title { color: white !important; }
        .price { color: var(--tpo-red) !important; font-weight: bold; }
    </style>
EOT;
    echo $css;
}
add_action('wp_head', 'tpo_inject_modern_css', 999);
?>