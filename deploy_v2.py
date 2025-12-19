import ftplib
import os

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

# FILES TO UPLOAD
FILES_MAP = {
    "index.html": "public_html/index.html",
    "public/js/tpo-app.js": "public_html/public/js/tpo-app.js"
}

def deploy_v2():
    print(f"🚀 Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # Ensure directories exist
        try: ftps.mkd("public_html/public") 
        except: pass
        try: ftps.mkd("public_html/public/js") 
        except: pass

        for local_path, remote_path in FILES_MAP.items():
            if os.path.exists(local_path):
                print(f"   ⬆️ Uploading {local_path} -> {remote_path}...")
                with open(local_path, "rb") as f:
                    ftps.storbinary(f"STOR {remote_path}", f)
            else:
                print(f"   ⚠️ Warning: Local file not found: {local_path}")

        print("\n✅ DEPLOYMENT V2 COMPLETE!")
        print("👉 Check your site: http://treasurepointonline.com")
        
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    deploy_v2()
