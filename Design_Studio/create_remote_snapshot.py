import ftplib
import os

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"
SNAPSHOT_DIR = "public_html/tpo_snapshot_latest"

def create_snapshot():
    print("🚀 Creating Time Capsule (Snapshot) on Server...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # 1. Create Directory
        try:
            ftps.mkd(SNAPSHOT_DIR)
            print(f"✅ Created directory: {SNAPSHOT_DIR}")
        except:
            print(f"ℹ️ Directory {SNAPSHOT_DIR} already exists (updating it).")

        # 2. Upload SQL Dump
        print("Uploading Database Backup (this may take a moment)...")
        try:
            with open("live_deployment.sql", "rb") as f:
                ftps.storbinary(f"STOR {SNAPSHOT_DIR}/backup.sql", f)
            print("✅ Database uploaded.")
        except Exception as e:
            print(f"❌ Database upload failed: {e}")

        # 3. Upload Config
        print("Uploading Configuration...")
        try:
            with open("wp-config_backup.php", "rb") as f:
                ftps.storbinary(f"STOR {SNAPSHOT_DIR}/wp-config.php", f)
            print("✅ Config uploaded.")
        except Exception as e:
            print(f"❌ Config upload failed: {e}")

        # 4. Upload Theme Files
        print("Uploading Theme Files...")
        try:
            with open("astra_child_backup/functions.php", "rb") as f:
                ftps.storbinary(f"STOR {SNAPSHOT_DIR}/functions.php", f)
            with open("astra_child_backup/style.css", "rb") as f:
                ftps.storbinary(f"STOR {SNAPSHOT_DIR}/style.css", f)
            print("✅ Theme files uploaded.")
        except Exception as e:
            print(f"❌ Theme upload failed: {e}")
            
        ftps.quit()
        print("\n✅ TIME CAPSULE SEALED.")
        print(f"Location: {SNAPSHOT_DIR}/")
        
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    create_snapshot()
