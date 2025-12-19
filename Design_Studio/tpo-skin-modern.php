<?php
/**
 * Plugin Name: TPO Skin Injector (Modern Edition)
 * Description: Forces the sleek, modern header with SVG logo and centered search.
 * Version: 4.0
 * Author: Treasure Point AI
 */

function tpo_rebuild_header() {
    $home_url = esc_url( home_url( '/' ) );
    $cart_url = esc_url( wc_get_cart_url() );
    $account_url = esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) );
    
    $js = <<<EOT
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var headerRow = document.querySelector('.site-primary-header-wrap .ast-builder-grid-row');
        if(headerRow) {
            // 1. CLEAR EXISTING HEADER
            headerRow.innerHTML = '';
            headerRow.style.display = 'flex';
            headerRow.style.justifyContent = 'space-between';
            headerRow.style.alignItems = 'center';
            headerRow.style.padding = '10px 20px';

            // 2. INJECT NEW LOGO (SVG)
            var logoDiv = document.createElement('div');
            logoDiv.className = 'tpo-logo-area';
            logoDiv.innerHTML = `
                <a href="{$home_url}" class="tpo-brand-link">
                    <svg width="40" height="40" viewBox="0 0 100 100" class="tpo-logo-icon">
                        <path d="M50 0 L100 25 L100 75 L50 100 L0 75 L0 25 Z" fill="#D32F2F"/>
                        <path d="M50 15 L85 32 L85 68 L50 85 L15 68 L15 32 Z" fill="#000"/>
                        <text x="50" y="65" font-family="Arial" font-weight="900" font-size="40" text-anchor="middle" fill="#FFF">TP</text>
                    </svg>
                    <span class="tpo-brand-text">TREASURE <span style="color:#D32F2F">POINT</span></span>
                </a>
            `;
            headerRow.appendChild(logoDiv);

            // 3. INJECT CENTER SEARCH
            var searchDiv = document.createElement('div');
            searchDiv.className = 'tpo-search-area';
            searchDiv.innerHTML = `
                <form role="search" method="get" class="tpo-search-form" action="{$home_url}">
                    <input type="search" class="tpo-search-input" placeholder="Search for amps, subs, speakers..." name="s">
                    <button type="submit" class="tpo-search-btn">🔍</button>
                    <input type="hidden" name="post_type" value="product">
                </form>
            `;
            headerRow.appendChild(searchDiv);

            // 4. INJECT RIGHT ACTIONS (Cart / Account)
            var actionsDiv = document.createElement('div');
            actionsDiv.className = 'tpo-actions-area';
            actionsDiv.innerHTML = `
                <a href="{$account_url}" class="tpo-action-btn">
                    <span class="tpo-icon">👤</span>
                    <span class="tpo-label">Account</span>
                </a>
                <a href="{$cart_url}" class="tpo-action-btn">
                    <span class="tpo-icon">🛒</span>
                    <span class="tpo-label">Cart</span>
                </a>
            `;
            headerRow.appendChild(actionsDiv);
        }
        
        // 5. HIDE DEFAULT NAV (We want it below)
        // We will target the Astra menu container and move it
        var defaultNav = document.querySelector('.main-header-bar-navigation');
        if(defaultNav) {
            defaultNav.style.display = 'none'; // Hide default location
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
        /* === MODERN RESET === */
        :root {
            --tpo-red: #D32F2F;
            --tpo-dark: #0a0a0a;
            --tpo-panel: #141414;
            --tpo-text: #ffffff;
        }
        body, html { background-color: var(--tpo-dark) !important; color: white !important; font-family: 'Inter', sans-serif; }

        /* === HEADER LAYOUT === */
        .ast-main-header-wrap {
            background-color: var(--tpo-panel) !important;
            border-bottom: 1px solid #333 !important;
            position: sticky !important; top: 0; z-index: 1000;
        }

        /* LOGO */
        .tpo-brand-link { display: flex; align-items: center; text-decoration: none; gap: 10px; }
        .tpo-logo-icon { filter: drop-shadow(0 0 5px var(--tpo-red)); }
        .tpo-brand-text { font-size: 22px; font-weight: 800; color: white; letter-spacing: -0.5px; text-transform: uppercase; }

        /* SEARCH (Center) */
        .tpo-search-area { flex-grow: 1; max-width: 600px; margin: 0 40px; }
        .tpo-search-form { display: flex; width: 100%; position: relative; }
        .tpo-search-input {
            width: 100%; padding: 12px 20px; border-radius: 50px !important;
            background: #222 !important; border: 1px solid #444 !important; color: white !important;
            outline: none; transition: all 0.3s;
        }
        .tpo-search-input:focus { border-color: var(--tpo-red) !important; box-shadow: 0 0 10px rgba(211, 47, 47, 0.3); }
        .tpo-search-btn {
            position: absolute; right: 5px; top: 50%; transform: translateY(-50%);
            background: var(--tpo-red) !important; color: white; border-radius: 50px !important;
            padding: 8px 20px !important; border: none !important; cursor: pointer;
        }

        /* ACTIONS (Right) */
        .tpo-actions-area { display: flex; gap: 20px; }
        .tpo-action-btn {
            display: flex; flex-direction: column; align-items: center; text-decoration: none;
            color: #ccc; font-size: 11px; font-weight: 600; text-transform: uppercase;
            transition: 0.2s;
        }
        .tpo-action-btn:hover { color: white; transform: translateY(-2px); }
        .tpo-icon { font-size: 20px; margin-bottom: 4px; }

        /* === SECONDARY NAV BAR (Below Header) === */
        /* Since we hid the default nav, let's create a pseudo-nav using CSS on the existing menu structure if possible, 
           or we rely on the Footer Menu for now. 
           Actually, let's style the "Main Menu" if it exists in the DOM but move it. */
        
        /* For now, to keep it clean, we'll let the user use the Homepage BENTO GRID as the main nav visually. */

        /* PRODUCT CARDS (Modern) */
        .woocommerce ul.products li.product {
            background: #1a1a1a !important; border: 1px solid #333; border-radius: 12px; overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .woocommerce ul.products li.product:hover {
            transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.5); border-color: var(--tpo-red);
        }
        .woocommerce-loop-product__title { color: white !important; font-size: 16px !important; }
        .price { color: var(--tpo-red) !important; font-weight: 700; font-size: 1.1em; }
        .button.add_to_cart_button {
            background-color: white !important; color: black !important; border-radius: 4px !important; width: 100%;
            font-weight: 800 !important;
        }
        .button.add_to_cart_button:hover { background-color: var(--tpo-red) !important; color: white !important; }

    </style>
EOT;
    echo $css;
}
add_action('wp_head', 'tpo_inject_modern_css', 999);
?>
