import ftplib
import os

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

# FILES TO UPLOAD (Adjusted for Design_Studio subfolder)
LOCAL_HTML = "../index.html"
LOCAL_CSS = "../public/css/tpo-fitment.css"

REMOTE_HTML = "public_html/index.html"
REMOTE_CSS_DIR = "public_html/public/css"
REMOTE_CSS = "public_html/public/css/tpo-fitment.css"

WP_INDEX = "public_html/index.php"
WP_BACKUP = "public_html/index_wp_backup.php"

def takeover_site():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # 1. Create Directories
        print("1. Creating directory structure...")
        try:
            ftps.mkd("public_html/public")
        except: pass
        try:
            ftps.mkd("public_html/public/css")
        except: pass

        # 2. Upload CSS
        print(f"2. Uploading CSS to {REMOTE_CSS}...")
        with open(LOCAL_CSS, "rb") as f:
            ftps.storbinary(f"STOR {REMOTE_CSS}", f)

        # 3. Upload HTML
        print(f"3. Uploading HTML to {REMOTE_HTML}...")
        with open(LOCAL_HTML, "rb") as f:
            ftps.storbinary(f"STOR {REMOTE_HTML}", f)

        # 4. Swap Index (The Takeover)
        print("4. Swapping WordPress for New Design...")
        
        # Check if index.php exists
        files = ftps.nlst("public_html")
        files_clean = [f.split('/')[-1] for f in files]
        
        if "index.php" in files_clean:
            print("   -> Found index.php. Renaming to index_wp_backup.php...")
            try:
                ftps.rename(WP_INDEX, WP_BACKUP)
                print("   -> SWAP COMPLETE.")
            except Exception as e:
                print(f"   -> Rename failed (maybe already renamed): {e}")
        else:
            print("   -> index.php not found (maybe already swapped).")

        print("\n✅ SUCCESS: Your new design is now LIVE.")
        print("👉 Check http://treasurepointonline.com")
        
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    takeover_site()