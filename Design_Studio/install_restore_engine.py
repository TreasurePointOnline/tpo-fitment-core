import ftplib
import os

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def upload_restore_engine():
    print("🚀 Installing Restore Engine...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # Upload to root (so it can be run via web) or snapshot dir?
        # Root is easier to execute: treasurepointonline.com/restore_site.php
        
        with open("db_import.php", "rb") as f:
            ftps.storbinary("STOR public_html/restore_db_from_snapshot.php", f)
            
        print("✅ Restore Engine Installed.")
        print("   Script: public_html/restore_db_from_snapshot.php")
        print("   (DO NOT RUN unless you want to revert changes!)")
        
        ftps.quit()
        
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    upload_restore_engine()
