<?php
/**
 * Plugin Name: TPO Skin Injector (Minimal - V4.8)
 * Description: Essential PHP functions and schema. Header/Nav moved to Child Theme.
 * Version: 4.8
 * Author: Treasure Point AI
 */

// Placeholder for AJAX functions if needed later
// Currently, this will only inject schema.

// 3. INJECT SCHEMA (The SEO Nuke)
function tpo_inject_schema() {
    $schema = <<<EOT
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
EOT;
    echo $schema;
}
add_action('wp_head', 'tpo_inject_schema');
?>
