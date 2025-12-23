import ftplib
import os

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def deploy_splash_page():
    print("🚀 Deploying Splash Screen (index.html Takeover)...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # 1. Upload splash_index.html
        print("1. Uploading splash_index.html...")
        with open("splash_index.html", "rb") as f:
            ftps.storbinary("STOR public_html/index.html", f)
        print("✅ splash_index.html uploaded.")

        # 2. Rename index.php
        print("2. Renaming index.php to index.php.bak...")
        try:
            ftps.rename("public_html/index.php", "public_html/index.php.bak")
            print("✅ index.php renamed.")
        except Exception as e:
            print(f"❌ Failed to rename index.php: {e} (might be already renamed/missing)")
            
        ftps.quit()
        print("\n👉 Check your site: https://treasurepointonline.com")
        
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    deploy_splash_page()
