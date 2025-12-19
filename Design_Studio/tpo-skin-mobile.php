<?php
/**
 * Plugin Name: TPO Skin Injector (Mobile Fixed v4.2)
 * Description: Forces the sleek header and fixes mobile layout issues.
 * Version: 4.2
 * Author: Treasure Point AI
 */

function tpo_rebuild_header() {
    $home_url = esc_url( home_url( '/' ) );
    $cart_url = esc_url( wc_get_cart_url() );
    $account_url = esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) );
    
    // SECONDARY NAV (Mobile Optimized)
    // We add a class 'tpo-desktop-nav' to hide it on phones if we want a burger menu instead
    // But for now, let's make it scrollable on mobile
    $nav_html = '
    <div class="tpo-sticky-nav">
        <div class="tpo-nav-container">
            <a href="/product-category/car-audio/" class="tpo-nav-link">CAR AUDIO</a>
            <a href="/product-category/marine-audio/" class="tpo-nav-link">MARINE</a>
            <a href="/product-category/powersports/" class="tpo-nav-link">POWERSPORTS</a>
            <a href="/contact/" class="tpo-nav-link">CONTACT</a>
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
            headerRow.style.display = 'flex'; 
            headerRow.style.justifyContent = 'space-between'; 
            headerRow.style.alignItems = 'center'; 
            headerRow.style.padding = '10px 15px'; // Reduced padding for mobile

            // LOGO (Smaller on mobile)
            var logoDiv = document.createElement('div');
            logoDiv.className = 'tpo-logo-area';
            logoDiv.innerHTML = `<a href="{$home_url}" class="tpo-brand-link"><svg width="30" height="30" viewBox="0 0 100 100" class="tpo-logo-icon"><path d="M50 0 L100 25 L100 75 L50 100 L0 75 L0 25 Z" fill="#D32F2F"/><path d="M50 15 L85 32 L85 68 L50 85 L15 68 L15 32 Z" fill="#000"/><text x="50" y="65" font-family="Arial" font-weight="900" font-size="40" text-anchor="middle" fill="#FFF">TP</text></svg><span class="tpo-brand-text">TREASURE <span style="color:#D32F2F">POINT</span></span></a>`;
            headerRow.appendChild(logoDiv);

            // SEARCH (Hidden on mobile initially, or simplified)
            var searchDiv = document.createElement('div');
            searchDiv.className = 'tpo-search-area';
            searchDiv.innerHTML = `<form role="search" method="get" class="tpo-search-form" action="{$home_url}"><input type="search" class="tpo-search-input" placeholder="Search..." name="s"><button type="submit" class="tpo-search-btn">🔍</button><input type="hidden" name="post_type" value="product"></form>`;
            headerRow.appendChild(searchDiv);

            // ACTIONS
            var actionsDiv = document.createElement('div');
            actionsDiv.className = 'tpo-actions-area';
            actionsDiv.innerHTML = `<a href="{$cart_url}" class="tpo-action-btn"><span class="tpo-icon">🛒</span></a>`; // Only Cart on mobile to save space
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
        
        .tpo-brand-link { display: flex; align-items: center; text-decoration: none; gap: 8px; }
        .tpo-brand-text { font-size: 18px; font-weight: 800; color: white; text-transform: uppercase; white-space: nowrap; }
        
        .tpo-search-area { flex-grow: 1; max-width: 600px; margin: 0 20px; }
        .tpo-search-input { width: 100%; padding: 8px 15px; border-radius: 50px !important; background: #222 !important; border: 1px solid #444 !important; color: white !important; font-size: 14px; }
        .tpo-search-btn { position: absolute; right: 2px; top: 2px; border-radius: 50px !important; background: var(--tpo-red) !important; border: none !important; color: white; padding: 6px 12px !important; cursor: pointer; }
        .tpo-search-form { position: relative; }

        .tpo-actions-area { display: flex; gap: 15px; }
        .tpo-action-btn { color: #ccc; font-size: 11px; text-decoration: none; display: flex; flex-direction: column; align-items: center; }
        .tpo-icon { font-size: 22px; }

        /* === MOBILE RESPONSIVE RULES === */
        @media (max-width: 768px) {
            /* Hide Search text placeholder on small screens? No, keep it small. */
            .tpo-brand-text { display: none; } /* Hide text logo on mobile to save space */
            .tpo-search-area { margin: 0 10px; }
            
            /* Scrollable Nav */
            .tpo-nav-container {
                justify-content: flex-start; /* Align left */
                overflow-x: auto; /* Enable scrolling */
                white-space: nowrap; /* Keep on one line */
                padding-bottom: 5px; /* Scrollbar space */
                -webkit-overflow-scrolling: touch;
            }
            .tpo-nav-link {
                padding: 12px 15px;
                font-size: 12px;
            }
            
            /* Adjust Hero Text */
            .tpo-title { font-size: 3rem !important; }
            .tpo-bento-grid { grid-template-columns: 1fr !important; grid-template-rows: auto !important; }
            .tpo-bento-item { height: 200px !important; }
        }

        /* STICKY NAV */
        .tpo-sticky-nav {
            background-color: #1a1a1a; border-bottom: 3px solid var(--tpo-red);
            position: sticky; top: 0; z-index: 999;
        }
        .tpo-nav-container { max-width: 1400px; margin: 0 auto; display: flex; justify-content: center; }
        .tpo-nav-link { display: block; padding: 15px 25px; color: white; font-weight: 700; text-decoration: none; text-transform: uppercase; font-size: 14px; border-right: 1px solid #333; }
        .tpo-nav-link:hover { background-color: var(--tpo-red); color: white; }

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
