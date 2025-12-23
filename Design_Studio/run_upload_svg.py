import ftplib
import os
import time # Added this import

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def upload_svg_logo():
    print("🚀 Uploading SVG Logo...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # Ensure uploads folder structure exists (year/month)
        current_year = str(time.localtime().tm_year)
        current_month = str(time.localtime().tm_mon).zfill(2)
        remote_path = f"public_html/wp-content/uploads/{current_year}/{current_month}/"
        
        try:
            ftps.mkd(f"public_html/wp-content/uploads/{current_year}")
        except: pass
        try:
            ftps.mkd(remote_path)
        except: pass

        with open("tpo-logo.svg", "rb") as f:
            ftps.storbinary(f"STOR {remote_path}tpo-logo.svg", f)
            
        print("✅ SVG Logo Uploaded.")
        print(f"👉 Public URL: http://treasurepointonline.com/wp-content/uploads/{current_year}/{current_month}/tpo-logo.svg")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    upload_svg_logo()