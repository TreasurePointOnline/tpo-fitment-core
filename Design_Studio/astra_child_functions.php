<?php
/**
 * Treasure Point Audio Child Theme functions and definitions
 */

// 1. Enqueue Child Theme Styles
function child_enqueue_styles() {
    wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), time(), 'all' );
}
add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );

// 2. Disable Astra Header Builder (Force our custom one)
add_filter( 'astra_is_header_footer_builder_active', '__return_false' );

// 3. Register Menus
function tpo_register_menus() {
    register_nav_menus( array(
        'tpo_main_menu' => __( 'Main Header Menu', 'astra-child' ),
    ) );
}
add_action( 'init', 'tpo_register_menus' );

// 4. Helper Function: Recursive Menu Builder
function tpo_build_nav_tree( $parent_id ) {
    $args = array(
        'taxonomy' => 'product_cat',
        'parent' => $parent_id,
        'hide_empty' => false, 
    );
    $terms = get_terms( $args );

    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
        echo '<ul class="sub-menu">';
        foreach ( $terms as $term ) {
            // Check for children
            $children = get_terms( array('taxonomy'=>'product_cat', 'parent'=>$term->term_id, 'hide_empty'=>false) );
            
            // Check for products
            $products = get_posts(array(
                'post_type' => 'product',
                'tax_query' => array(array('taxonomy'=>'product_cat', 'field'=>'term_id', 'terms'=>$term->term_id)),
                'posts_per_page' => 1
            ));

            $has_children = !empty($children);
            $has_products = !empty($products);
            
            $class = ($has_children || $has_products) ? 'menu-item-has-children' : '';
            $link = get_term_link( $term );
            
            echo '<li class="' . $class . '"><a href="' . esc_url($link) . '">' . esc_html( $term->name ) . '</a>';

            if ( $has_children ) {
                tpo_build_nav_tree( $term->term_id );
            } elseif ( $has_products ) {
                // List products if no sub-categories
                $product_list = get_posts( array(
                    'post_type' => 'product',
                    'posts_per_page' => -1,
                    'tax_query' => array( array( 'taxonomy' => 'product_cat', 'field' => 'term_id', 'terms' => $term->term_id ) ),
                    'orderby' => 'title', 
                    'order' => 'ASC'
                ));
                if($product_list) {
                    echo '<ul class="sub-menu">';
                    foreach($product_list as $prod) {
                        echo '<li><a href="' . get_permalink($prod->ID) . '">' . $prod->post_title . '</a></li>';
                    }
                    echo '</ul>';
                }
            }
            echo '</li>';
        }
        echo '</ul>';
    }
}

// 5. Render The Header HTML
function tpo_render_full_header() {
    ?>
    <header id="tpo-custom-header" class="site-header">
        <div class="tpo-top-bar">
            <div class="container">
                <div class="top-left">
                     <span>📍 206 Marine Dr. Anderson, IN</span>
                     <span>📞 (765) 555-0199</span>
                </div>
                <div class="top-right">
                    <a href="<?php echo wc_get_account_endpoint_url('dashboard'); ?>">My Account</a>
                    <a href="<?php echo wc_get_cart_url(); ?>">Cart</a>
                </div>
            </div>
        </div>

        <div class="tpo-main-bar">
            <div class="container">
                <div class="logo">
                    <a href="<?php echo home_url(); ?>">
                       <h2 style="color:white; margin:0;">TREASURE POINT</h2>
                    </a>
                </div>
                
                <nav class="tpo-nav">
                    <ul class="tpo-menu-root">
                        <li><a href="<?php echo home_url(); ?>">Home</a></li>
                        <li><a href="<?php echo home_url('/shop'); ?>">Shop All</a></li>

                        <?php
                        $main_cats = array('Car Audio', 'Marine Audio', 'Safety & Security');
                        foreach($main_cats as $cat_name) {
                            $term = get_term_by('name', $cat_name, 'product_cat');
                            if($term) {
                                echo '<li class="menu-item-has-children"><a href="' . get_term_link($term) . '">' . strtoupper($cat_name) . '</a>';
                                tpo_build_nav_tree($term->term_id);
                                echo '</li>';
                            }
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    
    <style>
        /* CRITICAL CSS TO MAKE IT LOOK DECENT IMMEDIATELY */
        #tpo-custom-header { background: #1a1a1a; color: white; font-family: sans-serif; margin-bottom: 20px;}
        .tpo-top-bar { background: #000; font-size: 12px; padding: 5px 0; }
        .tpo-main-bar { padding: 15px 0; border-bottom: 3px solid #ff0000; }
        .container { max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; padding: 0 20px; }
        .tpo-nav ul { list-style: none; margin: 0; padding: 0; display: flex; gap: 20px; }
        .tpo-nav a { color: white; text-decoration: none; font-weight: bold; text-transform: uppercase; }
        .tpo-nav li { position: relative; }
        
        /* Dropdowns */
        .sub-menu { 
            display: none; 
            position: absolute; 
            top: 100%; 
            left: 0; 
            background: #222; 
            min-width: 200px; 
            z-index: 999; 
            flex-direction: column !important; 
            border-top: 2px solid red;
            padding:0;
        }
        .tpo-nav li:hover > .sub-menu { display: flex; }
        .sub-menu li { padding: 10px; border-bottom: 1px solid #333; margin:0;}
        .sub-menu a { font-size: 14px; text-transform: none; color: #ccc; }
        
        /* Grandchildren dropdowns */
        .sub-menu .sub-menu { top: 0; left: 100%; margin-top: -2px; }
    </style>
    <?php
}

// 6. Inject Header into Astra
function tpo_switch_headers() {
    remove_action( 'astra_header', 'astra_header_markup' );
    add_action( 'astra_header', 'tpo_render_full_header' );
}
add_action( 'wp', 'tpo_switch_headers' );
