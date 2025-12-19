import ftplib
import os

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"
REMOTE_LOG_PATH = "public_html/wp-content/debug.log"
LOCAL_LOG_FILE = "debug.log"

def read_log_tail():
    print(f"📡 Connecting to {FTP_HOST} to fetch debug.log...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        print(f"   Downloading {REMOTE_LOG_PATH}...")
        with open(LOCAL_LOG_FILE, "wb") as f:
            ftps.retrbinary(f"RETR {REMOTE_LOG_PATH}", f.write)
        print("   ✅ Log downloaded.")
        
        print("\n--- LAST 20 LINES OF debug.log ---")
        with open(LOCAL_LOG_FILE, "r", encoding="utf-8", errors='ignore') as f:
            lines = f.readlines()
            if not lines:
                print("(Log file is empty.)")
            else:
                for line in lines[-20:]:
                    print(line.strip())
        print("-----------------------------------")
        
        ftps.quit()
    except ftplib.error_perm as e:
        print(f"❌ Error: {e}")
        print("   (debug.log might not exist yet or permissions are wrong.)")
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    read_log_tail()
