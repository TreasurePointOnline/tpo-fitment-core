import ftplib
import os
import time

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def run_backup():
    print("🚀 Initiating Full Site Backup...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # 1. Upload DB Exporter
        print("1. Uploading Database Exporter...")
        with open("db_export.php", "rb") as f:
            ftps.storbinary("STOR public_html/db_export.php", f)
            
        print("👉 ACTION REQUIRED: Visit this URL to generate the database dump:")
        print("   http://treasurepointonline.com/db_export.php")
        print("   (Wait until you see 'Database Export Complete', then run the download script)")
        
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    run_backup()
