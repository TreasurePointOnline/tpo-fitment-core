import ftplib
import os

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"
REMOTE_CONFIG_PATH = "public_html/wp-config.php"
LOCAL_CONFIG_FILE = "wp-config.php"

def read_config():
    print(f"📡 Connecting to {FTP_HOST} to fetch wp-config.php...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        print(f"   Downloading {REMOTE_CONFIG_PATH}...")
        with open(LOCAL_CONFIG_FILE, "wb") as f:
            ftps.retrbinary(f"RETR {REMOTE_CONFIG_PATH}", f.write)
        print("   ✅ wp-config.php downloaded.")
        
        print("\n--- CONTENT OF wp-config.php ---")
        with open(LOCAL_CONFIG_FILE, "r", encoding="utf-8", errors='ignore') as f:
            print(f.read())
        print("-----------------------------------")
        
        ftps.quit()
    except ftplib.error_perm as e:
        print(f"❌ Error: {e}")
        print("   (wp-config.php might not exist or permissions are wrong.)")
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    read_config()
