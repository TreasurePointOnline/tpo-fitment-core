import ftplib
import os

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def update_config_ssl():
    print("🚀 Updating wp-config.php for SSL...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # 1. Download wp-config
        with open("wp-config_ssl_temp.php", "wb") as f:
            ftps.retrbinary("RETR public_html/wp-config.php", f.write)
            
        with open("wp-config_ssl_temp.php", "r", encoding="utf-8") as f:
            lines = f.readlines()
            
        new_lines = []
        ssl_defined = False
        
        # 2. Insert SSL constants
        ssl_block = """
// --- SSL FORCED BY TPO ---
define('WP_HOME', 'https://treasurepointonline.com');
define('WP_SITEURL', 'https://treasurepointonline.com');
define('FORCE_SSL_ADMIN', true);
"""
        
        for line in lines:
            if "WP_HOME" in line or "WP_SITEURL" in line or "FORCE_SSL_ADMIN" in line:
                continue # Skip existing to replace them
            
            new_lines.append(line)
            
            # Insert after the TPO config start block we added earlier, or near the top
            if "table_prefix" in line and not ssl_defined:
                new_lines.append(ssl_block)
                ssl_defined = True
                
        # Fallback if table_prefix not found (unlikely)
        if not ssl_defined:
            new_lines.insert(1, ssl_block) # Insert at top
            
        with open("wp-config_ssl_ready.php", "w", encoding="utf-8") as f:
            f.writelines(new_lines)
            
        # 3. Upload
        with open("wp-config_ssl_ready.php", "rb") as f:
            ftps.storbinary("STOR public_html/wp-config.php", f)
            
        print("✅ wp-config.php updated with HTTPS constants.")
        
        ftps.quit()
        
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    update_config_ssl()
