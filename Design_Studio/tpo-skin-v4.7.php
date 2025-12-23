<?php
/**
 * Plugin Name: TPO Skin Injector (V4.7 - Robust Dynamic Nav)
 * Description: SVG Logo, Search, and Dynamic Dropdowns for Series and SKUs.
 * Version: 4.7
 * Author: Treasure Point AI
 */

// 1. AJAX ENDPOINT FOR NAVIGATION DATA
function tpo_get_nav_data() {
    $nav_data = [];

    $main_cats = array('Amplifiers', 'Subwoofers', 'Audio Speakers', 'Empty Enclosures', 'Wiring Kits');

    foreach ($main_cats as $main_cat_name) {
        $main_cat_term = get_term_by('name', $main_cat_name, 'product_cat');
        if (is_wp_error($main_cat_term) || !$main_cat_term) {
            error_log("TPO Nav Data: Main category '{$main_cat_name}' not found.");
            continue;
        }

        $menu_item = [
            'title' => strtoupper($main_cat_name),
            'url' => esc_url(get_term_link($main_cat_term)),
            'sub_items' => []
        ];

        // Fetch Series (terms for pa_series taxonomy) that have products in this category
        $series_terms = get_terms([
            'taxonomy'   => 'pa_series',
            'hide_empty' => true,
        ]);

        if (!is_wp_error($series_terms) && !empty($series_terms)) {
            foreach ($series_terms as $series_term) {
                // Check if this series actually has products within the current main category
                $products_in_series_and_cat = get_posts([
                    'post_type'      => 'product',
                    'posts_per_page' => -1,
                    'fields'         => 'ids',
                    'tax_query'      => [
                        'relation' => 'AND',
                        [
                            'taxonomy' => 'product_cat',
                            'field'    => 'term_id',
                            'terms'    => $main_cat_term->term_id,
                        ],
                        [
                            'taxonomy' => 'pa_series',
                            'field'    => 'term_id',
                            'terms'    => $series_term->term_id,
                        ],
                    ],
                ]);

                if (!empty($products_in_series_and_cat)) {
                    // Add Series as a submenu item
                    $series_item = [
                        'title' => $series_term->name,
                        'url'   => esc_url(add_query_arg('filter_series', $series_term->slug, get_term_link($main_cat_term))),
                        'products' => [] // Products under this series
                    ];

                    // Fetch specific products (SKUs) for this series and category
                    $products_in_dropdown = get_posts([
                        'post_type'      => 'product',
                        'posts_per_page' => -1, // Get all
                        'tax_query'      => [
                            'relation' => 'AND',
                            [
                                'taxonomy' => 'product_cat',
                                'field'    => 'term_id',
                                'terms'    => $main_cat_term->term_id,
                            ],
                            [
                                'taxonomy' => 'pa_series',
                                'field'    => 'term_id',
                                'terms'    => $series_term->term_id,
                            ],
                        ],
                        'orderby'        => 'title',
                        'order'          => 'ASC'
                    ]);

                    foreach ($products_in_dropdown as $product_post) {
                        $product_obj = wc_get_product($product_post->ID);
                        if ($product_obj) {
                            $series_item['products'][] = [
                                'title' => $product_obj->get_sku(),
                                'url'   => esc_url(get_permalink($product_post->ID))
                            ];
                        }
                    }
                    $menu_item['sub_items'][] = $series_item;
                }
            }
        }
        $nav_data[] = $menu_item;
    }

    // Add static links
    $nav_data[] = ['title' => 'CONTACT', 'url' => esc_url( get_permalink( get_page_by_title('Contact Us')->ID ) )];

    wp_send_json_success($nav_data);
}
add_action('wp_ajax_tpo_get_nav_data', 'tpo_get_nav_data');
add_action('wp_ajax_nopriv_tpo_get_nav_data', 'tpo_get_nav_data');

// 2. JAVASCRIPT & CSS (To build the menu dynamically)
function tpo_inject_dynamic_nav() {
    $home_url = esc_url( home_url( '/' ) );
    $cart_url = esc_url( wc_get_cart_url() );
    $account_url = esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) );
    $logo_svg_url = content_url('uploads/2025/12/tpo-logo-v3.svg'); 

    $js = <<<EOT
    <script>
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>'; // Ensure ajaxurl is defined

    document.addEventListener("DOMContentLoaded", function() {
        var headerRow = document.querySelector('.site-primary-header-wrap .ast-builder-grid-row');
        var mainHeader = document.querySelector('.site-header');

        if(headerRow) {
            headerRow.innerHTML = '';
            headerRow.style.display = 'flex'; headerRow.style.justifyContent = 'space-between'; headerRow.style.alignItems = 'center'; headerRow.style.padding = '10px 20px';

            // LOGO
            var logoDiv = document.createElement('div');
            logoDiv.className = 'tpo-logo-area';
            logoDiv.innerHTML = `<a href="{$home_url}" class="tpo-brand-link"><img src="{$logo_svg_url}" alt="TP Audio" class="tpo-logo-svg"></a>`;
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

        // --- DYNAMIC NAV BAR ---
        var navContainer = document.createElement('div');
        navContainer.className = 'tpo-sticky-nav';
        navContainer.innerHTML = '<div class="tpo-nav-container">Loading Navigation...</div>';
        if(mainHeader) {
             mainHeader.parentNode.insertBefore(navContainer, mainHeader.nextSibling);
        }

        fetch(ajaxurl + '?action=tpo_get_nav_data')
            .then(response => response.json())
            .then(data => {
                var navHtml = '';
                if (data.success && data.data) {
                    data.data.forEach(item => {
                        if (item.sub_items && item.sub_items.length > 0) {
                            // Main dropdown item (e.g., Amplifiers)
                            navHtml += `<div class="tpo-dropdown"><a href="${item.url}" class="tpo-nav-link">${item.title} ▾</a><div class="tpo-dropdown-content">`;
                            item.sub_items.forEach(sub => {
                                // Series dropdown (e.g., OGS Series)
                                if (sub.products && sub.products.length > 0) {
                                    navHtml += `<div class="tpo-sub-dropdown"><a href="${sub.url}">${sub.title} ❯</a><div class="tpo-sub-dropdown-content">`;
                                    sub.products.forEach(product => {
                                        navHtml += `<a href="${product.url}">${product.title}</a>`; // Product SKU link
                                    });
                                    navHtml += `</div></div>`;
                                } else {
                                    navHtml += `<a href="${sub.url}">${sub.title}</a>`;
                                }
                            });
                            navHtml += `</div></div>`;
                        } else {
                            navHtml += `<a href="${item.url}" class="tpo-nav-link">${item.title}</a>`;
                        }
                    });
                } else {
                    console.error('AJAX data error:', data.data);
                }
                document.querySelector('.tpo-nav-container').innerHTML = navHtml;
            })
            .catch(error => {
                console.error('Error fetching nav data:', error);
                document.querySelector('.tpo-nav-container').innerHTML = '<div style="color:red;">Failed to load navigation.</div>';
            });
    });
    </script>
EOT;
    echo $js;
}
add_action('wp_footer', 'tpo_inject_dynamic_nav');

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

        /* NAV BAR */
        .tpo-sticky-nav { background-color: #1a1a1a; border-bottom: 3px solid var(--tpo-red); position: sticky; top: 0; z-index: 999; box-shadow: 0 5px 15px rgba(0,0,0,0.5); }
        .tpo-nav-container { max-width: 1400px; margin: 0 auto; display: flex; justify-content: center; }
        .tpo-nav-link { display: block; padding: 15px 25px; color: white; font-weight: 700; text-decoration: none; text-transform: uppercase; font-size: 14px; border-right: 1px solid #333; }
        .tpo-nav-link:hover { background-color: var(--tpo-red); color: white; }
        .tpo-nav-link:first-child { border-left: 1px solid #333; }

        /* DROPDOWN STYLES (MULTI-LEVEL) */
        .tpo-dropdown { position: relative; display: inline-block; }
        .tpo-dropdown-content {
            display: none; position: absolute; background-color: #222; min-width: 250px; box-shadow: 0 8px 16px rgba(0,0,0,0.5); z-index: 1000;
            border-top: 3px solid var(--tpo-red); padding: 10px 0;
            left: 50%; transform: translateX(-50%); /* Center dropdown */
        }
        .tpo-dropdown-content a {
            color: white; padding: 8px 16px; text-decoration: none; display: block; font-size: 13px; text-transform: uppercase;
        }
        .tpo-dropdown-content a:hover { background-color: #333; color: var(--tpo-red); }
        .tpo-dropdown:hover .tpo-dropdown-content { display: block; }
        .tpo-dropdown:hover .tpo-nav-link { background-color: #333; color: var(--tpo-red); }

        /* SUB-DROPDOWNS (For Series -> Products) */
        .tpo-sub-dropdown { position: relative; }
        .tpo-sub-dropdown-content {
            display: none; position: absolute; left: 100%; top: 0; background-color: #333; min-width: 200px; box-shadow: 0 8px 16px rgba(0,0,0,0.5); z-index: 1001;
            border-left: 3px solid var(--tpo-red);
        }
        .tpo-sub-dropdown:hover .tpo-sub-dropdown-content { display: block; }
        .tpo-sub-dropdown a:hover { background-color: #444; color: var(--tpo-red); }

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