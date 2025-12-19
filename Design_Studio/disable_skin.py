import ftplib
import os

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def disable_skin():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        print("Disabling TPO Skin (mu-plugin)...")
        try:
            # Rename tpo-skin.php to .php.bak to stop it from loading
            ftps.rename("public_html/wp-content/mu-plugins/tpo-skin.php", "public_html/wp-content/mu-plugins/tpo-skin.php.bak")
            print("✅ SUCCESS: TPO Skin disabled.")
        except Exception as e:
            print(f"❌ Failed to disable skin: {e}")
            
        ftps.quit()
        print("\n👉 Try loading your homepage now.")
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    disable_skin()
