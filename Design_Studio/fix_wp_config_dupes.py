import ftplib
import re

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def fix_config_dupes():
    print("🚀 Cleaning up wp-config.php duplicates...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # 1. Download
        with open("wp-config_messy.php", "wb") as f:
            ftps.retrbinary("RETR public_html/wp-config.php", f.write)
            
        with open("wp-config_messy.php", "r", encoding="utf-8") as f:
            lines = f.readlines()
            
        # 2. Filter Lines
        # We will keep the FIRST occurrence of these constants and discard subsequent ones
        seen_constants = set()
        new_lines = []
        
        constants_to_check = [
            "WP_DEBUG", "WP_DEBUG_LOG", "WP_DEBUG_DISPLAY", 
            "WP_MEMORY_LIMIT", "WP_MAX_MEMORY_LIMIT",
            "WP_HOME", "WP_SITEURL", "FORCE_SSL_ADMIN"
        ]
        
        for line in lines:
            skip = False
            for const in constants_to_check:
                if f"define( '{const}'" in line or f"define('{const}'" in line:
                    if const in seen_constants:
                        skip = True # Duplicate found
                    else:
                        seen_constants.add(const)
            
            if not skip:
                new_lines.append(line)
                
        # 3. Save & Upload
        with open("wp-config_clean.php", "w", encoding="utf-8") as f:
            f.writelines(new_lines)
            
        with open("wp-config_clean.php", "rb") as f:
            ftps.storbinary("STOR public_html/wp-config.php", f)
            
        print("✅ wp-config.php cleaned.")
        ftps.quit()
        
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    fix_config_dupes()
