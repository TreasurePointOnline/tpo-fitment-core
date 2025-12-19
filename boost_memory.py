import ftplib
import re
import os

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"
REMOTE_PATH = "public_html/wp-config.php"

def boost_memory():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # 1. Download wp-config.php
        print("Downloading wp-config.php...")
        
        remote_file = REMOTE_PATH
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
            
        # Check if already boosted
        if "WP_MEMORY_LIMIT" in content:
            print("Memory limit found. Attempting to upgrade...")
            # Using triple quotes to avoid syntax errors with single/double quotes
            pattern = r'''define\s*\(\s*['"]WP_MEMORY_LIMIT['"]\s*,\s*['"]\d+M['"]\s*\);'''
            replacement = "define( 'WP_MEMORY_LIMIT', '256M' );"
            content = re.sub(pattern, replacement, content)
        else:
            print("No memory limit set. Adding Turbo Boost...")
            boost_code = "\ndefine( 'WP_MEMORY_LIMIT', '256M' );\ndefine( 'WP_MAX_MEMORY_LIMIT', '512M' );\n"
            if "/* That's all, stop editing" in content:
                content = content.replace("/* That's all, stop editing", boost_code + "/* That's all, stop editing")
            else:
                content += boost_code

        # 3. Save
        with open("wp-config_boosted.php", "w", encoding="utf-8") as f:
            f.write(content)
            
        # 4. Upload
        print("Uploading boosted config...")
        with open("wp-config_boosted.php", "rb") as f:
            ftps.storbinary(f"STOR {remote_file}", f)
            
        print("\nSUCCESS: Memory Limit Increased to 256MB.")
        print("Go refresh your Products page now.")
        
        ftps.quit()
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    boost_memory()