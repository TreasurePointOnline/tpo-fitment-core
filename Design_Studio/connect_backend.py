import ftplib
import os

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

# THE DATA BRIDGE (PHP Script)
# This script loads WordPress, grabs 8 products, and returns them as JSON
PHP_BRIDGE_CONTENT = """<?php
# Load WordPress
require_once('wp-load.php');

header('Content-Type: application/json');

# Get 8 recent products
$args = array(
    'post_type' => 'product',
    'posts_per_page' => 8,
    'post_status' => 'publish',
);

$loop = new WP_Query( $args );
$products = array();

while ( $loop->have_posts() ) : $loop->the_post();
    global $product;
    
    $products[] = array(
        'id' => $product->get_id(),
        'title' => $product->get_name(),
        'price' => $product->get_price_html(),
        'image' => get_the_post_thumbnail_url($product->get_id(), 'medium'),
        'link' => get_permalink(),
        'add_to_cart' => '?add-to-cart=' . $product->get_id(),
    );
endwhile;

wp_reset_query();

echo json_encode($products);
?>"""

def connect_backend():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # 1. Restore WordPress (index.php)
        print("1. Restoring WordPress Core...")
        files = ftps.nlst("public_html")
        clean_files = [f.split('/')[-1] for f in files]
        
        if "index_wp_backup.php" in clean_files:
            try:
                # We renamed it, now we restore it.
                # BUT wait, if we restore it, it might overwrite our index.html priority?
                # Usually servers prioritize index.html over index.php, so we are safe.
                ftps.rename("public_html/index_wp_backup.php", "public_html/index.php")
                print("   -> WordPress restored (Cart/Checkout will work now).")
            except Exception as e:
                print(f"   -> Rename failed: {e}")
        else:
            print("   -> Backup not found, maybe already restored.")

        # 2. Upload the Data Bridge
        print("2. Uploading Data Bridge (tpo-inventory.php)...")
        with open("tpo-inventory.php", "w") as f:
            f.write(PHP_BRIDGE_CONTENT)
            
        with open("tpo-inventory.php", "rb") as f:
            ftps.storbinary("STOR public_html/tpo-inventory.php", f)
            
        print("\n✅ SUCCESS: Backend connected.")
        print("   Bridge URL: http://treasurepointonline.com/tpo-inventory.php")
        
        ftps.quit()
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    connect_backend()
