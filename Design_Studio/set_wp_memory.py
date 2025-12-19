import ftplib
import re
import os

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"
REMOTE_PATH = "public_html/wp-config.php"

def set_wp_memory_limits():
    print(f"Connecting to {FTP_HOST} to set WP memory limits...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # 1. Download wp-config.php
        print("Downloading wp-config.php...")
        remote_file = REMOTE_PATH
        # Try to locate the file carefully
        try:
             ftps.cwd('public_html')
             remote_file = 'wp-config.php'
        except:
             print("Warning: public_html folder not found directly. Trying root...")
             ftps.cwd('/')
             try:
                 if 'wp-config.php' in ftps.nlst():
                     remote_file = 'wp-config.php'
                 else:
                     print("Error: wp-config.php not found in root or public_html.")
                     return
             except:
                 print("Error listing root directory.")
                 return

        with open("wp-config_temp.php", "wb") as f:
            ftps.retrbinary(f"RETR {remote_file}", f.write)

        # 2. Read and Modify
        with open("wp-config_temp.php", "r", encoding="utf-8") as f:
            content = f.read()
            
        print("Ensuring WP_MEMORY_LIMIT and WP_MAX_MEMORY_LIMIT are set...")

        # Function to ensure a define is present or updated with a simpler string replacement
        def ensure_define_simple(config_content, define_name, define_value):
            new_line = f"define( '{define_name}', '{define_value}' );"
            
            # Manually escape brackets for safety during script creation
            pattern_str = r"define\s*\(\s*[\'\"]" + re.escape(define_name) + r"[\'\"]\s*,\s*[\'\"]\d+M[\'\"]\s*);"
            pattern = re.compile(pattern_str, re.IGNORECASE)
            
            if pattern.search(config_content):
                print(f"   -> Updating existing {define_name} to {define_value}.")
                config_content = pattern.sub(new_line, config_content)
            else:
                print(f"   -> Adding {define_name} as {define_value}.")
                if "/* That's all, stop editing" in config_content:
                    config_content = config_content.replace(
                        "/* That's all, stop editing",
                        f"\n{new_line}\n/* That's all, stop editing"
                    )
                else:
                    config_content += f"\n{new_line}\n"
            return config_content

        content = ensure_define_simple(content, 'WP_MEMORY_LIMIT', '256M')
        content = ensure_define_simple(content, 'WP_MAX_MEMORY_LIMIT', '512M')
        
        # 3. Save
        with open("wp-config_modified.php", "w", encoding="utf-8") as f:
            f.write(content)
            
        # 4. Upload
        print("Uploading modified wp-config.php...")
        with open("wp-config_modified.php", "rb") as f:
            ftps.storbinary(f"STOR {remote_file}", f)
            
        print("\n✅ SUCCESS: WP_MEMORY_LIMIT and WP_MAX_MEMORY_LIMIT should be updated.")
        print("👉 Go check http://treasurepointonline.com/memory_check.php again to verify.")
        
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    set_wp_memory_limits()
