import ftplib
import os

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def run_restore():
    print("🚀 Uploading Theme Restorer...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # Upload
        with open("switch_to_astra_child.php", "rb") as f:
            ftps.storbinary("STOR public_html/switch_to_astra_child.php", f)
        print("✅ Upload Complete.")
        
        print("👉 Please visit this URL to restore your design:")
        print("   http://treasurepointonline.com/switch_to_astra_child.php")
        
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    run_restore()
