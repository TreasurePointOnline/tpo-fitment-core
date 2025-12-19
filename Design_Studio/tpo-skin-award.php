<?php
/**
 * Plugin Name: TPO Skin Injector (Award Edition)
 * Description: Forces the "Award Winning" Dark Theme, Search, and Schema.
 * Version: 3.0
 * Author: Treasure Point AI
 */

// 1. INJECT SEARCH BAR (Refined)
function tpo_inject_search_bar() {
    ?>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var header = document.querySelector('.site-primary-header-wrap .ast-builder-grid-row');
        if(header && !document.querySelector('.tpo-header-search')) {
            var searchDiv = document.createElement('div');
            searchDiv.className = 'tpo-header-search';
            searchDiv.innerHTML = `
                <form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <input type="search" class="search-field" placeholder="Search..." value="<?php echo get_search_query(); ?>" name="s" />
                    <button type="submit">🔍</button>
                    <input type="hidden" name="post_type" value="product" />
                </form>
            `;
            if(header.children.length > 1) { header.insertBefore(searchDiv, header.children[1]); } 
            else { header.appendChild(searchDiv); }
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'tpo_inject_search_bar');

// 2. INJECT CSS (The Visual Magic)
function tpo_inject_css() {
    ?>
    <style>
        /* === RESET & VARIABLES === */
        :root {
            --tpo-black: #050505;
            --tpo-dark: #111111;
            --tpo-red: #ff0033; /* Neon Red */
            --tpo-glass: rgba(255, 255, 255, 0.05);
            --tpo-text: #ffffff;
        }
        body, html, .site-content, .ast-container {
            background-color: var(--tpo-black) !important;
            color: var(--tpo-text) !important;
            font-family: 'Inter', sans-serif !important;
        }

        /* === HEADER === */
        .ast-main-header-wrap {
            background: rgba(0,0,0,0.85) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,0,51,0.3) !important;
            position: sticky !important;
            top: 0; z-index: 99999;
        }
        .main-header-menu a {
            text-transform: uppercase; font-weight: 800; font-size: 13px; letter-spacing: 1px;
        }
        .tpo-header-search input {
            background: #222 !important; border: 1px solid #444 !important; color: white !important;
            border-radius: 50px !important; padding-left: 20px !important;
        }
        .tpo-header-search button {
            border-radius: 50px !important; background: var(--tpo-red) !important;
        }

        /* === HERO SECTION === */
        .tpo-hero {
            height: 80vh;
            background: linear-gradient(to bottom, rgba(0,0,0,0.3), var(--tpo-black)), url('https://images.unsplash.com/photo-1493238792000-8113da705763?w=1600&q=80');
            background-size: cover; background-position: center;
            display: flex; align-items: center; justify-content: center;
            text-align: center;
        }
        .tpo-title {
            font-size: 6rem; line-height: 0.9; font-weight: 900; letter-spacing: -2px;
            background: -webkit-linear-gradient(#fff, #999); -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
        }
        .tpo-subtitle { color: var(--tpo-red); letter-spacing: 3px; font-weight: 700; margin-bottom: 10px; }
        .tpo-lead { font-size: 1.2rem; color: #ccc; max-width: 600px; margin: 0 auto 40px auto; }
        
        /* Buttons */
        .tpo-btn {
            display: inline-block; padding: 15px 40px; font-weight: 800; letter-spacing: 1px; text-decoration: none;
            clip-path: polygon(10% 0, 100% 0, 100% 70%, 90% 100%, 0 100%, 0 30%); /* Cyberpunk Shape */
            transition: all 0.3s ease;
        }
        .tpo-btn.primary { background: var(--tpo-red); color: white; border: 2px solid var(--tpo-red); margin-right: 20px; }
        .tpo-btn.secondary { background: transparent; color: white; border: 2px solid rgba(255,255,255,0.3); }
        .tpo-btn:hover { transform: translateY(-5px); box-shadow: 0 0 20px rgba(255,0,51,0.5); }

        /* === BENTO GRID === */
        .tpo-section { padding: 80px 20px; max-width: 1400px; margin: 0 auto; }
        .tpo-section-title { font-size: 3rem; font-weight: 900; text-align: left; margin-bottom: 40px; border-left: 5px solid var(--tpo-red); padding-left: 20px; }
        
        .tpo-bento-grid {
            display: grid; grid-template-columns: repeat(4, 1fr); grid-template-rows: repeat(2, 300px); gap: 20px;
        }
        .tpo-bento-item {
            background-color: #222; background-size: cover; background-position: center;
            border-radius: 15px; position: relative; overflow: hidden;
            display: flex; align-items: flex-end; padding: 30px; text-decoration: none;
            transition: transform 0.3s ease;
        }
        .tpo-bento-item::before {
            content: ''; position: absolute; inset: 0; background: linear-gradient(to top, black, transparent);
        }
        .tpo-bento-label {
            position: relative; z-index: 2; font-size: 1.5rem; font-weight: 800; color: white; text-transform: uppercase;
        }
        .tpo-bento-item:hover { transform: scale(1.02); z-index: 10; border: 1px solid var(--tpo-red); }
        
        .tpo-bento-item.wide { grid-column: span 2; }
        .tpo-bento-item.tall { grid-row: span 2; }

        /* Mobile */
        @media (max-width: 768px) {
            .tpo-title { font-size: 3.5rem; }
            .tpo-bento-grid { display: flex; flex-direction: column; }
            .tpo-bento-item { height: 250px; }
        }
        
        /* SEO BLOCK */
        .tpo-seo-block { border-top: 1px solid #333; text-align: center; }
        .tpo-features { display: flex; justify-content: space-around; flex-wrap: wrap; margin-top: 40px; }
        .tpo-feature { flex: 1; min-width: 250px; padding: 20px; }
        .tpo-feature i { font-size: 3rem; color: var(--tpo-red); margin-bottom: 20px; }
    </style>
    <?php
}
add_action('wp_head', 'tpo_inject_css', 999);

// 3. INJECT SCHEMA (The SEO Nuke)
function tpo_inject_schema() {
    ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "AutoPartsStore",
      "name": "Treasure Point Audio",
      "image": "https://treasurepointonline.com/wp-content/uploads/logo.png",
      "@id": "https://treasurepointonline.com",
      "url": "https://treasurepointonline.com",
      "telephone": "765-643-5822",
      "priceRange": "$$",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "206 Marine Dr.",
        "addressLocality": "Anderson",
        "addressRegion": "IN",
        "postalCode": "46016",
        "addressCountry": "US"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": 40.1093,
        "longitude": -85.6767
      },
      "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": [
          "Monday", "Tuesday", "Wednesday", "Thursday", "Friday"
        ],
        "opens": "09:00",
        "closes": "17:00"
      },
      "sameAs": [
        "https://www.facebook.com/treasurepointaudio",
        "https://www.instagram.com/treasurepointaudio"
      ]
    }
    </script>
    <?php
}
add_action('wp_head', 'tpo_inject_schema');
?>
