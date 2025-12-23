import ftplib
import os
import time

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def upload_svg_v2():
    print("🚀 Uploading SVG Logo V2...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        current_year = str(time.localtime().tm_year)
        current_month = str(time.localtime().tm_mon).zfill(2)
        remote_path = f"public_html/wp-content/uploads/{current_year}/{current_month}/"
        
        with open("tpo-logo-v2.svg", "rb") as f:
            ftps.storbinary(f"STOR {remote_path}tpo-logo-v2.svg", f)
            
        print("✅ SVG V2 Uploaded.")
        print(f"👉 URL: http://treasurepointonline.com/wp-content/uploads/{current_year}/{current_month}/tpo-logo-v2.svg")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    upload_svg_v2()
