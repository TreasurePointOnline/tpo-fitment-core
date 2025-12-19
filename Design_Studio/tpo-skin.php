<?php
/**
 * Plugin Name: TPO Skin Injector (Big Dog Edition)
 * Description: Forces the "OG Replica" Dark Theme and injects a central search bar.
 * Version: 2.0
 * Author: Treasure Point
 */

// 1. INJECT SEARCH BAR INTO HEADER
function tpo_inject_search_bar() {
    ?>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Find the Astra header container
        var header = document.querySelector('.site-primary-header-wrap .ast-builder-grid-row');
        
        if(header) {
            // Create the Search Container
            var searchDiv = document.createElement('div');
            searchDiv.className = 'tpo-header-search';
            searchDiv.innerHTML = `
                <form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <input type="search" class="search-field" placeholder="Search for amps, subs, speakers..." value="<?php echo get_search_query(); ?>" name="s" />
                    <button type="submit" value="Search">🔍</button>
                    <input type="hidden" name="post_type" value="product" />
                </form>
            `;
            
            // Insert it in the middle (after the Logo, before the Menu)
            // Astra usually has 3 columns: left, center, right. We try to target the center.
            // If strictly 2 columns, we insert after the first child.
            if(header.children.length > 1) {
                header.insertBefore(searchDiv, header.children[1]);
            } else {
                header.appendChild(searchDiv);
            }
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'tpo_inject_search_bar');


// 2. INJECT CSS
function tpo_inject_css() {
    ?>
    <style>
        /* === TPO GLOBAL DARK MODE === */
        body, html, #page, .site-content, .ast-container {
            background-color: #0a0a0a !important;
            color: #ffffff !important;
        }

        /* === HEADER STRUCTURE === */
        .ast-main-header-wrap {
            background-color: #141414 !important;
            border-bottom: 3px solid #d32f2f !important;
            position: sticky !important;
            top: 0;
            z-index: 99999;
            box-shadow: 0 4px 12px rgba(0,0,0,0.5);
        }
        
        /* Force Flex Layout */
        .site-primary-header-wrap .ast-builder-grid-row {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            gap: 20px;
            height: 80px;
        }

        /* === SEARCH BAR (Injected via JS) === */
        .tpo-header-search {
            flex-grow: 1;
            max-width: 600px;
            margin: 0 20px;
        }
        .tpo-header-search form {
            display: flex;
            width: 100%;
        }
        .tpo-header-search input {
            width: 100%;
            padding: 10px 15px;
            background: #222 !important;
            border: 1px solid #444 !important;
            color: white !important;
            border-radius: 4px 0 0 4px !important;
        }
        .tpo-header-search button {
            background: #d32f2f !important;
            color: white !important;
            border: none !important;
            padding: 0 20px !important;
            border-radius: 0 4px 4px 0 !important;
            cursor: pointer;
        }
        .tpo-header-search button:hover {
            background: #ff0000 !important;
        }

        /* === MENU LINKS === */
        .main-header-menu a, .menu-link {
            color: #ffffff !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 14px !important;
        }
        .main-header-menu a:hover {
            color: #d32f2f !important;
        }

        /* === CART ICON === */
        .ast-header-account-wrap a, .ast-cart-menu-wrap .ast-cart-menu-title-wrapper {
            color: white !important;
        }

        /* === WOOCOMMERCE DARK MODE === */
        .woocommerce ul.products li.product {
            background-color: #141414 !important;
            border: 1px solid #333 !important;
            border-radius: 6px !important;
            padding: 15px !important;
        }
        .woocommerce-loop-product__title { color: #fff !important; }
        .price { color: #d32f2f !important; font-weight: bold; }
        
        /* Buttons */
        button, .button, .single_add_to_cart_button {
            background-color: #d32f2f !important;
            color: white !important;
            font-weight: bold !important;
            text-transform: uppercase !important;
        }
        
    </style>
    <?php
}
add_action('wp_head', 'tpo_inject_css', 999);
?>