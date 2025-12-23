import ftplib
import os

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def read_remote_php():
    print(f"📡 Connecting to {FTP_HOST} to read populate_masterlist.php...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        print("   Downloading populate_masterlist.php...")
        with open("populate_masterlist_remote.php", "wb") as f:
            ftps.retrbinary("RETR public_html/populate_masterlist.php", f.write)
        print("   ✅ File downloaded.")
        
        print("\n--- CONTENT OF populate_masterlist.php (REMOTE) ---")
        with open("populate_masterlist_remote.php", "r", encoding="utf-8", errors='ignore') as f:
            print(f.read())
        print("---------------------------------------------------")
        
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    read_remote_php()

