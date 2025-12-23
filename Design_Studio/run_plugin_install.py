import ftplib
import os

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def upload_installer():
    print("🚀 Uploading All-in-One WP Migration Installer...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        with open("install_all_in_one.php", "rb") as f:
            ftps.storbinary("STOR public_html/install_all_in_one.php", f)
            
        print("✅ Upload Complete.")
        print("👉 CLICK THIS TO INSTALL: http://treasurepointonline.com/install_all_in_one.php")
        
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    upload_installer()
