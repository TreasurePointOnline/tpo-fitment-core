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

    // Load Google Fonts (Russo One and Teko for logo - if needed for text logo)
    wp_enqueue_style( 'google-fonts-logo', 'https://fonts.googleapis.com/css2?family=Russo+One&family=Teko:wght@300;600&display=swap', array(), null );

    // Enqueue FontAwesome for icons
    wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), null );
}
add_action( 'wp_enqueue_scripts', 'astra_child_enqueue_styles' );

/**
 * Customize Astra Header Elements via Hooks.
 */

/**
 * Render the ENTIRE Custom Header (Logo, Search, Actions, Nav)
 * This replaces the default Astra header entirely.
 */
function tpo_render_full_header() {
    $home_url = esc_url( home_url( '/' ) );
    $logo_svg_url = content_url('uploads/2025/12/tpo-logo-v3.svg'); // V3 SVG
    
    // SAFEGUARD: Check if WooCommerce is active/loaded
    if ( function_exists( 'wc_get_cart_url' ) ) {
        $cart_url = esc_url( wc_get_cart_url() );
        $account_url = esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) );
    } else {
        // Fallback if WooCommerce is missing
        $cart_url = '#'; 
        $account_url = '#'; 
    }
    
    ?>
    <div class="tpo-header-wrapper">
        <!-- Main Header: Logo | Search | Actions -->
        <div class="tpo-main-header-bar">
            
            <!-- Logo -->
            <div class="tpo-logo-area">
                <a href="<?php echo $home_url; ?>" class="tpo-brand-link">
                    <img src="<?php echo $logo_svg_url; ?>" alt="TP Audio" class="tpo-logo-svg">
                </a>
            </div>

            <!-- Search -->
            <div class="tpo-search-area">
                <form role="search" method="get" class="tpo-search-form" action="<?php echo $home_url; ?>">
                    <input type="search" class="tpo-search-input" placeholder="Search for amps, subs, speakers..." name="s">
                    <button type="submit" class="tpo-search-btn"><i class="fas fa-search"></i></button>
                    <input type="hidden" name="post_type" value="product">
                </form>
            </div>

            <!-- Actions (Account/Cart) -->
            <div class="tpo-actions-area">
                <a href="<?php echo $account_url; ?>" class="tpo-action-btn">
                    <i class="fas fa-user tpo-icon"></i>
                    <span class="tpo-label">Account</span>
                </a>
                <a href="<?php echo $cart_url; ?>" class="tpo-action-btn">
                    <i class="fas fa-shopping-cart tpo-icon"></i>
                    <span class="tpo-label">Cart</span>
                </a>
            </div>

        </div>

        <!-- Secondary Navigation (The Red/Black Bar) -->
        <div class="tpo-secondary-nav-bar">
            <div class="tpo-nav-container">
                <a href="/product-category/car-audio/amplifiers/" class="tpo-nav-link">AMPLIFIERS</a>
                <a href="/product-category/car-audio/subwoofers/" class="tpo-nav-link">SUBWOOFERS</a>
                <a href="/product-category/car-audio/speakers/" class="tpo-nav-link">AUDIO SPEAKERS</a>
                <a href="/product-category/empty-enclosures/" class="tpo-nav-link">ENCLOSURES</a>
                <a href="/product-category/amp-kits-accessories/" class="tpo-nav-link">WIRING KITS</a>
            </div>
        </div>
    </div>
    <?php
}
// Hook into 'astra_header' which is the main wrapper. 
// Priority 5 ensures it runs before other potential hooks, but we are removing standard markup anyway.
add_action( 'astra_header', 'tpo_render_full_header', 5 );


/**
 * Remove Astra's default header sections that conflict
 */
function tpo_remove_astra_default_header() {
    remove_action( 'astra_header_markup', 'astra_header_markup_page_builder' ); // Remove default page builder header
    remove_action( 'astra_header_markup', 'astra_header_markup_standard' ); // Remove standard Astra header
    
    // Also remove the mobile header if it exists separately
    remove_action( 'astra_mobile_header_markup', 'astra_mobile_header_markup_standard' );
}
add_action( 'wp', 'tpo_remove_astra_default_header', 10 ); // Run at normal priority, 999 was maybe too late?

?>
