import ftplib
import os
import time

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

REMOTE_SQL = "public_html/live_deployment.sql"
LOCAL_SQL = "backups/live_deployment.sql"
REMOTE_SCRIPT = "public_html/db_export.php"

def secure_backup():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # 1. Download
        print(f"⬇️ Downloading backup to {LOCAL_SQL}...")
        try:
            with open(LOCAL_SQL, "wb") as f:
                ftps.retrbinary(f"RETR {REMOTE_SQL}", f.write)
            print("✅ Download successful.")
        except Exception as e:
            print(f"❌ Download failed: {e}")
            ftps.quit()
            return

        # 2. Delete Remote Files
        print("🧹 Cleaning up server files...")
        try:
            ftps.delete(REMOTE_SQL)
            print(f"   -> Deleted {REMOTE_SQL}")
        except:
            print(f"   -> Could not delete {REMOTE_SQL} (already gone?)")

        try:
            ftps.delete(REMOTE_SCRIPT)
            print(f"   -> Deleted {REMOTE_SCRIPT}")
        except:
            print(f"   -> Could not delete {REMOTE_SCRIPT}")

        print("\n🎉 BACKUP SECURED & SERVER CLEANED.")
        print(f"File saved at: {os.path.abspath(LOCAL_SQL)}")
        
        ftps.quit()
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    secure_backup()
