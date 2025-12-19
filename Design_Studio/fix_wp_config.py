import ftplib
import re
import os

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"
REMOTE_PATH = "public_html/wp-config.php"

def fix_config():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # 1. Download
        print("Downloading broken wp-config.php...")
        remote_file = REMOTE_PATH
        try:
             ftps.cwd('public_html')
             remote_file = 'wp-config.php'
        except:
             ftps.cwd('/')
             if 'wp-config.php' in ftps.nlst():
                 remote_file = 'wp-config.php'

        with open("wp-config_broken.php", "wb") as f:
            ftps.retrbinary(f"RETR {remote_file}", f.write)

        # 2. Repair
        with open("wp-config_broken.php", "r", encoding="utf-8") as f:
            lines = f.readlines()
            
        print("Repairing content...")
        new_lines = []
        
        for line in lines:
            # Remove any lines that look like our injected debug/memory constants to start fresh
            if "WP_DEBUG" in line or "WP_DEBUG_LOG" in line or "WP_DEBUG_DISPLAY" in line or "WP_MEMORY_LIMIT" in line or "WP_MAX_MEMORY_LIMIT" in line:
                continue
            
            # Remove the empty newlines we might have added excessively
            if line.strip() == "" and len(new_lines) > 0 and new_lines[-1].strip() == "":
                continue

            new_lines.append(line)

        # Now Insert the clean block right before the "stop editing" line
        final_content = ""
        # Using concatenation to avoid syntax errors in file generation
        debug_block = "\n// --- TPO CONFIG START ---\\n"
        debug_block += "define( 'WP_MEMORY_LIMIT', '256M' );\n"
        debug_block += "define( 'WP_MAX_MEMORY_LIMIT', '512M' );\n"
        debug_block += "define( 'WP_DEBUG', true );\n"
        debug_block += "define( 'WP_DEBUG_LOG', true );\n"
        debug_block += "define( 'WP_DEBUG_DISPLAY', false );\n"
        debug_block += "// --- TPO CONFIG END ---\\n"

        inserted = False
        for line in new_lines:
            if "/* That's all, stop editing" in line and not inserted:
                final_content += debug_block + "\n" + line
                inserted = True
            else:
                final_content += line
        
        if not inserted:
            final_content += debug_block

        # 3. Save
        with open("wp-config_fixed.php", "w", encoding="utf-8") as f:
            f.write(final_content)
            
        # 4. Upload
        print("Uploading FIXED configuration...")
        with open("wp-config_fixed.php", "rb") as f:
            ftps.storbinary(f"STOR {remote_file}", f)
            
        print("\n✅ SUCCESS: wp-config.php should be fixed.")
        print("👉 Go check your site now.")
        
        ftps.quit()
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    fix_config()