import ftplib
import os

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"
REMOTE_THEME_PATH = "public_html/wp-content/themes/astra"

def deploy_skin():
    print("🚀 Connecting to deploy DESIGN (Skin Only)...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # Upload the style file we generated locally
        print("   -> Pushing style_local.css as style.css...")
        with open("style_local.css", "rb") as f:
            ftps.storbinary(f"STOR {REMOTE_THEME_PATH}/style.css", f)
            
        print("✅ SUCCESS: The site now has the 'OG Look'.")
        ftps.quit()
        
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    deploy_skin()
