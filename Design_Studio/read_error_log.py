import ftplib
import sys
import os

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def read_logs():
    print(f"📡 Connecting to {FTP_HOST} to find the crash report...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # We will look for common log files
        # Note: public_html might be implicit in login, so we try relative paths too if absolute fails
        log_files = [
            "public_html/wp-content/debug.log", 
            "wp-content/debug.log", 
            "public_html/error_log", 
            "error_log"
        ]
        found_log = False
        downloaded_filename = "crash_report.txt"

        for log_path in log_files:
            try:
                print(f"   🔎 Checking for {log_path}...")
                with open(downloaded_filename, "wb") as f:
                    ftps.retrbinary(f"RETR {log_path}", f.write)
                
                print(f"   ✅ FOUND: {log_path}")
                found_log = True
                break # Stop searching if we found one
            except Exception as e:
                # print(f"     (Not found: {e})") # Quietly fail
                continue
        
        if found_log:
            print("\n🚨 --- CRASH REPORT (Last 20 Lines) --- 🚨")
            try:
                with open(downloaded_filename, "r", encoding="utf-8", errors='ignore') as f:
                    lines = f.readlines()
                    # Print the last 20 lines
                    if not lines:
                         print("(File was empty)")
                    for line in lines[-20:]:
                        print(line.strip())
            except Exception as e:
                print(f"Could not read the text file locally: {e}")
            print("------------------------------------------")
        else:
            print("\n⚠️ No error logs found. The server isn't writing them yet.")
            print("   (This usually means the error is in wp-config.php or .htaccess)")

        ftps.quit()
    except Exception as e:
        print(f"❌ Connection Error: {e}")

if __name__ == "__main__":
    read_logs()
