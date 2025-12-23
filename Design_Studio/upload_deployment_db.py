import ftplib
import os

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def upload_db():
    local_file = "deploy_this.sql"
    if not os.path.exists(local_file):
        print(f"❌ Error: {local_file} not found in this folder!")
        return

    print(f"🚀 Uploading {local_file} to Live Server...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        with open(local_file, "rb") as f:
            ftps.storbinary(f"STOR public_html/{local_file}", f)
            
        print("✅ Upload Complete.")
        ftps.quit()
    except Exception as e:
        print(f"❌ FTP Error: {e}")

if __name__ == "__main__":
    upload_db()
