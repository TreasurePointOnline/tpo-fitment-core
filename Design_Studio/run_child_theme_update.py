import ftplib
import os

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def upload_child_theme_updates():
    print("🚀 Uploading Astra Child Theme Updates...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        child_theme_path = "public_html/wp-content/themes/astra-child"
        
        # Upload functions.php
        with open("astra_child_functions.php", "rb") as f:
            ftps.storbinary(f"STOR {child_theme_path}/functions.php", f)
        print("✅ Uploaded astra-child/functions.php")

        # Upload style.css
        with open("astra_child_style.css", "rb") as f:
            ftps.storbinary(f"STOR {child_theme_path}/style.css", f)
        print("✅ Uploaded astra-child/style.css")
        
        ftps.quit()
        print("\n👉 Check your site: http://treasurepointonline.com")
        
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    upload_child_theme_updates()
