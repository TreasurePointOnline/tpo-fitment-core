import ftplib
import os

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def create_child_theme():
    print("🚀 Creating Astra Child Theme...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        child_theme_path = "public_html/wp-content/themes/astra-child"
        
        # 1. Create directory
        try:
            ftps.mkd(child_theme_path)
            print("✅ Created astra-child directory.")
        except:
            print("ℹ️ astra-child directory already exists.")
            
        # 2. Create style.css
        style_css_content = """/*
Theme Name: Astra Child Theme
Theme URI: https://treasurepointonline.com
Description: My custom Astra Child Theme
Author: Treasure Point
Author URI: https://treasurepointonline.com
Template: astra
Version: 1.0.0
Text Domain: astra-child
*/

/* Add your custom CSS here */
"""
        with open("astra_child_style.css", "w") as f:
            f.write(style_css_content)
        with open("astra_child_style.css", "rb") as f:
            ftps.storbinary(f"STOR {child_theme_path}/style.css", f)
        print("✅ Created astra-child/style.css")

        # 3. Create functions.php (initial content)
        functions_php_content = """<?php
/**
 * Astra Child Theme functions and definitions
 */

/**
 * Enqueue parent theme styles
 */
function astra_child_enqueue_styles() {
    wp_enqueue_style( 'astra-parent', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'astra-child', get_stylesheet_directory_uri() . '/style.css', array('astra-parent') );

    // Load Google Fonts (Russo One and Teko for logo)
    wp_enqueue_style( 'google-fonts-logo', 'https://fonts.googleapis.com/css2?family=Russo+One&family=Teko:wght@300;600&display=swap', array(), null );
}
add_action( 'wp_enqueue_scripts', 'astra_child_enqueue_styles' );

/**
 * Add your custom functions below.
 */

?>
"""
        with open("astra_child_functions.php", "w") as f:
            f.write(functions_php_content)
        with open("astra_child_functions.php", "rb") as f:
            ftps.storbinary(f"STOR {child_theme_path}/functions.php", f)
        print("✅ Created astra-child/functions.php")
        
        ftps.quit()
        print("\n👉 ACTION: Go to WordPress Admin -> Appearance -> Themes and ACTIVATE 'Astra Child Theme'.")
        print("   Then check your site: http://treasurepointonline.com")
        
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    create_child_theme()
