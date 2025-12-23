<?php
/**
 * Plugin Name: TPO Skin Injector (Base Styling + Logo)
 * Description: Minimal skin to establish dark mode and custom logo.
 * Version: 5.0
 * Author: Treasure Point AI
 */

function tpo_rebuild_header() {
    $home_url = esc_url( home_url( '/' ) );
    $logo_svg_url = content_url('uploads/2025/12/tpo-logo-v3.svg'); // Our latest SVG logo

    $js = <<<EOT
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var headerRow = document.querySelector('.site-primary-header-wrap .ast-builder-grid-row');
        
        if(headerRow) {
            headerRow.innerHTML = ''; // Clear default Astra elements
            headerRow.style.display = 'flex'; 
            headerRow.style.justifyContent = 'flex-start'; // Align logo left
            headerRow.style.alignItems = 'center'; 
            headerRow.style.padding = '10px 20px'; // Basic padding

            // LOGO
            var logoDiv = document.createElement('div');
            logoDiv.className = 'tpo-logo-area';
            logoDiv.innerHTML = `<a href="{$home_url}" class="tpo-brand-link"><img src="{$logo_svg_url}" alt="TP Audio" class="tpo-logo-svg"></a>`;
            headerRow.appendChild(logoDiv);

            // Hide Astra's default site title, if still present
            var astraSiteTitle = document.querySelector('.site-title');
            if(astraSiteTitle) {
                astraSiteTitle.style.display = 'none';
            }
        }
    });
    </script>
EOT;
    echo $js;
}
add_action('wp_footer', 'tpo_rebuild_header');

function tpo_inject_base_css() {
    $css = <<<EOT
    <style>
        /* --- GLOBAL STYLES (FROM MEMORY) --- */
        :root { --tpo-red: #D32F2F; --tpo-dark: #0a0a0a; --tpo-panel: #141414; }
        body, html, #page { 
            background-color: var(--tpo-dark) !important; 
            color: #ffffff !important; 
            font-family: 'Inter', sans-serif !important; 
            overflow-x: hidden;
        }

        /* --- BASIC HEADER STYLES --- */
        .ast-main-header-wrap { 
            background-color: var(--tpo-panel) !important; 
            border-bottom: 1px solid #333; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }

        /* Logo */
        .tpo-logo-svg { height: 55px; width: auto; } /* Size of our custom SVG */
        .tpo-brand-link { display: flex; align-items: center; text-decoration: none; }

        /* Hide Astra's default site title and navigation elements if they persist */
        .site-title, .main-navigation, #site-navigation, #ast-desktop-header, .ast-primary-header-bar { display: none !important; }

        /* Hide page title on posts/pages */
        .entry-title, .page-header { display: none !important; }
        
        /* Basic Product Styling (from memory) */
        .woocommerce ul.products li.product { 
            background: #1a1a1a !important; border: 1px solid #333 !important; 
            border-radius: 8px; overflow: hidden; 
        }
        .woocommerce-loop-product__title { color: white !important; font-size: 16px; }
        .price { color: var(--tpo-red) !important; font-weight: bold; font-size: 1.1em; }
    </style>
EOT;
    echo $css;
}
add_action('wp_head', 'tpo_inject_base_css', 999);
?>
