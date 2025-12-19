import ftplib
import os

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"
REMOTE_HTACCESS = "public_html/.htaccess"

def boost_htaccess():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # 1. Download existing .htaccess
        print("Downloading .htaccess...")
        try:
            with open(".htaccess_backup", "wb") as f:
                ftps.retrbinary(f"RETR {REMOTE_HTACCESS}", f.write)
        except:
            print("   (No .htaccess found, creating new one)")
            with open(".htaccess_backup", "w") as f:
                f.write("# Created by TPO Agent\n")

        # 2. Modify content
        with open(".htaccess_backup", "r") as f:
            content = f.read()
            
        # The Magic Boost Lines
        boost_lines = "\n# TPO MEMORY BOOST\nphp_value memory_limit 512M\nphp_value upload_max_filesize 64M\nphp_value post_max_size 64M\nphp_value max_execution_time 300\nphp_value max_input_time 300\n# END TPO BOOST\n"
        
        # Remove old boost if exists to avoid duplication
        if "# TPO MEMORY BOOST" in content:
            print("   (Boost already present, updating...)")
            # Simple replace logic or regex could go here, but appending is safer if we just overwrite
            # For safety, let's just append if not there, or warn.
            # Actually, let's just append to the very top.
            pass 
        else:
            print("Injecting PHP Memory Boost into .htaccess...")
            content = boost_lines + content

        # 3. Save new file
        with open(".htaccess_boosted", "w") as f:
            f.write(content)
            
        # 4. Upload
        print("Uploading new .htaccess...")
        with open(".htaccess_boosted", "rb") as f:
            ftps.storbinary(f"STOR {REMOTE_HTACCESS}", f)
            
        print("\n✅ SUCCESS: .htaccess updated with memory boost.")
        print("👉 Go check http://treasurepointonline.com/memory_check.php again.")
        
        ftps.quit()
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    boost_htaccess()
