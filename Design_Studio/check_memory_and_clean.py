import ftplib
import os

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def check_and_clean():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # 1. Remove wc_update_db.php
        print("1. Deleting problematic wc_update_db.php...")
        try:
            ftps.delete("public_html/wc_update_db.php")
            print("   -> Deleted.")
        except:
            print("   -> Already gone or not found.")

        # 2. Upload a small script to check live memory limit
        print("2. Uploading memory_check.php...")
        php_check_content = """<?php
        require_once('wp-load.php');
        echo "<h1>Memory Check</h1>";
        echo "<p>PHP Memory Limit: " . ini_get('memory_limit') . "</p>";
        echo "<p>WP Memory Limit: " . (defined('WP_MEMORY_LIMIT') ? WP_MEMORY_LIMIT : 'Not Defined') . "</p>";
        echo "<p>WP Max Memory Limit: " . (defined('WP_MAX_MEMORY_LIMIT') ? WP_MAX_MEMORY_LIMIT : 'Not Defined') . "</p>";
        ?>"""
        
        with open("memory_check.php", "w") as f:
            f.write(php_check_content)
        with open("memory_check.php", "rb") as f:
            ftps.storbinary("STOR public_html/memory_check.php", f)
            
        print("   -> Uploaded memory_check.php.")
        print("\n👉 ACTION: Visit http://treasurepointonline.com/memory_check.php to see the live memory settings.")
        
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    check_and_clean()
