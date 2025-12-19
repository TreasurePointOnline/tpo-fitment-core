import ftplib
import os

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def enable_skin():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        print("Re-enabling TPO Skin (mu-plugin)...")
        try:
            # Rename tpo-skin.php.bak back to tpo-skin.php
            ftps.rename("public_html/wp-content/mu-plugins/tpo-skin.php.bak", "public_html/wp-content/mu-plugins/tpo-skin.php")
            print("✅ SUCCESS: TPO Skin enabled.")
        except Exception as e:
            print(f"❌ Failed to enable skin. Error: {e}")
            
        ftps.quit()
        print("\n👉 Check your homepage now.")
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    enable_skin()
