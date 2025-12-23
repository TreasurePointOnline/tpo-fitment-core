import ftplib
import os

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def fetch_tpo_log():
    print(f"📡 Connecting to {FTP_HOST} to fetch tpo-debug.log...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        print("   Downloading tpo-debug.log...")
        log_content = ""
        try:
            with open("tpo-debug.log", "wb") as f:
                ftps.retrbinary("RETR public_html/wp-content/tpo-debug.log", f.write)
            with open("tpo-debug.log", "r", encoding="utf-8", errors='ignore') as f:
                log_content = f.read()
            print("   ✅ Log downloaded.")
        except Exception as e:
            print(f"❌ Error downloading log: {e}")
            log_content = "Log file not found or inaccessible."

        print("\n--- CONTENT OF tpo-debug.log ---")
        print(log_content)
        print("-----------------------------------")
        
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    fetch_tpo_log()
