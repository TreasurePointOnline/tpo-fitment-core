<?php
/**
 * Astra Child Theme functions and definitions
 */

/**
 * Enqueue parent theme styles
 */
function astra_child_enqueue_styles() {
    wp_enqueue_style( 'astra-parent', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'astra-child', get_stylesheet_directory_uri() . '/style.css', array('astra-parent') );

    // Load Google Fonts
    wp_enqueue_style( 'google-fonts-logo', 'https://fonts.googleapis.com/css2?family=Russo+One&family=Teko:wght@300;600&display=swap', array() );
}
add_action( 'wp_enqueue_scripts', 'astra_child_enqueue_styles' );

/**
 * Register Custom Menus (Optional now, but good to keep)
 */
function tpo_register_menus() {
    register_nav_menus( array( 'secondary_menu' => __( 'Secondary Header Menu', 'astra-child' ) ) );
}
add_action( 'init', 'tpo_register_menus' );


/**
 * HELPER: Recursive function to build menu items
 */
function tpo_build_nav_tree( $parent_id ) {
    // Get Child Categories (Brands/Series)
    $args = array(
        'taxonomy'   => 'product_cat',
        'parent'     => $parent_id,
        'hide_empty' => false, // Set to true on live to hide empty ones
    );
    $terms = get_terms( $args );

    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
        echo '<ul class="sub-menu">';
        foreach ( $terms as $term ) {
            // Check if this term has children (sub-cats)
            $children = get_terms( array('taxonomy'=>'product_cat', 'parent'=>$term->term_id, 'hide_empty'=>false) );
            $has_children = !empty($children);
            
            // Check for products if no sub-cats
            $has_products = false;
            if (!$has_children) {
                 $products = get_posts(array('post_type'=>'product', 'tax_query'=>array(array('taxonomy'=>'product_cat', 'field'=>'term_id', 'terms'=>$term->term_id)), 'posts_per_page'=>1));
                 if($products) $has_products = true;
            }

            $class = ($has_children || $has_products) ? 'has-children' : '';
            
            echo '<li class="' . $class . '">';
            echo '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>';
            
            // If it has sub-categories (like Series), recurse
            if ( $has_children ) {
                tpo_build_nav_tree( $term->term_id );
            } 
            // If no sub-categories, list Products directly (Flyout)
            elseif ( $has_products ) {
                $products = get_posts( array(
                    'post_type' => 'product',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field'    => 'term_id',
                            'terms'    => $term->term_id,
                        ),
                    ),
                    'orderby' => 'title',
                    'order'   => 'ASC',
                ));
                
                if ( $products ) {
                    echo '<ul class="sub-menu">';
                    foreach ( $products as $prod ) {
                        echo '<li><a href="' . get_permalink( $prod->ID ) . '">' . $prod->post_title . '</a></li>';
                    }
                    echo '</ul>';
                }
            }
            
            echo '</li>';
        }
        echo '</ul>';
    }
}


/**
 * MAIN HEADER RENDER
 */
function tpo_render_full_header() {
    $home_url = esc_url( home_url( '/' ) );
    $logo_svg_url = content_url('uploads/2025/12/tpo-logo-v3.svg');

    if ( function_exists( 'wc_get_cart_url' ) ) {
        $cart_url = esc_url( wc_get_cart_url() );
        $account_url = esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) );
    } else {
        $cart_url = '#';
        $account_url = '#';
    }
    
    ?>
    <div class="tpo-header-wrapper">
        <!-- Main Header -->
        <div class="tpo-main-header-bar">
            <div class="tpo-logo-area">
                <a href="<?php echo $home_url; ?>" class="tpo-brand-link">
                    <img src="<?php echo $logo_svg_url; ?>" alt="TP Audio" class="tpo-logo-svg">
                </a>
            </div>
            <div class="tpo-search-area">
                <form role="search" method="get" class="tpo-search-form" action="<?php echo $home_url; ?>">
                    <input type="search" class="tpo-search-input" placeholder="Search..." name="s">
                    <button type="submit" class="tpo-search-btn"><i class="fas fa-search"></i></button>
                    <input type="hidden" name="post_type" value="product">
                </form>
            </div>
            <div class="tpo-actions-area">
                <a href="<?php echo $account_url; ?>" class="tpo-action-btn">
                    <i class="fas fa-user tpo-icon"></i><span class="tpo-label">Account</span>
                </a>
                <a href="<?php echo $cart_url; ?>" class="tpo-action-btn">
                    <i class="fas fa-shopping-cart tpo-icon"></i><span class="tpo-label">Cart</span>
                </a>
            </div>
        </div>

        <div style="color: #666; text-align: center; font-size: 10px; background: #000;">
            BIG DOG AI WORKBENCH CONNECTED - DYNAMIC MODE
        </div>

        <!-- DYNAMIC Secondary Navigation -->
        <div class="tpo-secondary-nav-bar">
            <div class="tpo-nav-container">
                <ul class="tpo-nav-list">
                    <?php
                    // Define Top Level Categories to show
                    $top_cats = array('Amplifiers', 'Subwoofers', 'Audio Speakers', 'Enclosures', 'Wiring Kits');
                    
                    foreach ($top_cats as $cat_name) {
                        // Find the category object
                        $term = get_term_by( 'name', $cat_name, 'product_cat' );
                        
                        // If category exists, render it
                        if ( $term ) {
                            // Check for children to add arrow class
                            $children = get_terms( array('taxonomy'=>'product_cat', 'parent'=>$term->term_id, 'hide_empty'=>false) );
                            $class = !empty($children) ? 'has-children' : '';
                            
                            echo '<li class="' . $class . '">';
                            echo '<a href="' . esc_url( get_term_link( $term ) ) . '">' . strtoupper( $term->name ) . '</a>';
                            
                            // MAGIC: Build the dropdown tree automatically
                            tpo_build_nav_tree( $term->term_id );
                            
                            echo '</li>';
                        } else {
                            // Fallback for missing categories (keep layout intact)
                             echo '<li><a href="#">' . strtoupper( $cat_name ) . '</a></li>';
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <?php
}
add_action( 'astra_header', 'tpo_render_full_header', 5 );


/**
 * Remove Astra's default header
 */
function tpo_remove_astra_default_header() {
    remove_action( 'astra_header_markup', 'astra_header_markup_page_builder' );
    remove_action( 'astra_header_markup', 'astra_header_markup_standard' );
    remove_action( 'astra_mobile_header_markup', 'astra_mobile_header_markup_standard' );
}
add_action( 'wp', 'tpo_remove_astra_default_header', 10 );
?>
