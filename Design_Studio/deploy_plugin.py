import ftplib
import os

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

LOCAL_FILE = "tpo-skin.php"
REMOTE_DIR = "public_html/wp-content/mu-plugins"
REMOTE_FILE = "public_html/wp-content/mu-plugins/tpo-skin.php"

def deploy_mu_plugin():
    print("🚀 Deploying 'TPO Skin' as a Must-Use Plugin...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # 1. Create mu-plugins directory (it often doesn't exist by default)
        try:
            ftps.mkd("public_html/wp-content/mu-plugins")
            print("   -> Created mu-plugins folder.")
        except:
            print("   -> mu-plugins folder already exists.")

        # 2. Upload
        print(f"   -> Uploading {LOCAL_FILE}...")
        with open(LOCAL_FILE, "rb") as f:
            ftps.storbinary(f"STOR {REMOTE_FILE}", f)
            
        print("✅ SUCCESS: TPO Skin is installed and AUTO-ACTIVATED.")
        ftps.quit()
        
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    deploy_mu_plugin()
