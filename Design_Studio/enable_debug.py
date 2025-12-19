import ftplib
import re
import os

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"
REMOTE_PATH = "public_html/wp-config.php"

def enable_debug():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # 1. Download
        print("Downloading wp-config.php...")
        remote_file = REMOTE_PATH
        try:
             ftps.cwd('public_html')
             remote_file = 'wp-config.php'
        except:
             ftps.cwd('/')
             if 'wp-config.php' in ftps.nlst():
                 remote_file = 'wp-config.php'

        with open("wp-config_debug.php", "wb") as f:
            ftps.retrbinary(f"RETR {remote_file}", f.write)

        # 2. Modify
        with open("wp-config_debug.php", "r", encoding="utf-8") as f:
            content = f.read()
            
        print("Injecting Debug Flags...")
        
        # Remove existing debug definitions using safe triple-quoted regex patterns
        p1 = r'''define\s*\(\s*['"]WP_DEBUG['"]\s*,\s*.*?\s*\);'''
        p2 = r'''define\s*\(\s*['"]WP_DEBUG_LOG['"]\s*,\s*.*?\s*\);'''
        p3 = r'''define\s*\(\s*['"]WP_DEBUG_DISPLAY['"]\s*,\s*.*?\s*\);'''
        
        content = re.sub(p1, "", content)
        content = re.sub(p2, "", content)
        content = re.sub(p3, "", content)

        # Add our strict debug block
        debug_block = """
define( 'WP_DEBUG', true )
define( 'WP_DEBUG_LOG', true )
define( 'WP_DEBUG_DISPLAY', false )
"""
        # Insert before "That's all, stop editing"
        if "/* That's all, stop editing" in content:
            content = content.replace("/* That's all, stop editing", debug_block + "\n/* That's all, stop editing")
        else:
            # Fallback: append to end if marker not found
            content += debug_block

        # 3. Save
        with open("wp-config_ready.php", "w", encoding="utf-8") as f:
            f.write(content)
            
        # 4. Upload
        print("Uploading new configuration...")
        with open("wp-config_ready.php", "rb") as f:
            ftps.storbinary(f"STOR {remote_file}", f)
            
        print("\n✅ SUCCESS: Debug Mode Enabled.")
        print("👉 ACTION REQUIRED: Go refresh your broken homepage TWICE.")
        print("   Then run the 'investigate_crash.py' script again to read the log.")
        
        ftps.quit()
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    enable_debug()