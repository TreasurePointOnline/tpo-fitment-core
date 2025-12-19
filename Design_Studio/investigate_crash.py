import ftplib
import time
import os

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def fix_crash():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # STEP 1: Delete the script that caused the error
        print("1. Deleting the failed injector (inject_home.php)...")
        try:
            # We need to make sure we're in the right directory or path properly
            # Trying full path first
            ftps.delete("public_html/inject_home.php")
            print("   -> DELETED. The immediate danger is gone.")
        except Exception as e:
            # Maybe we need to cd first
            try:
                ftps.cwd('public_html')
                ftps.delete("inject_home.php")
                print("   -> DELETED (after cwd).")
            except:
                print(f"   -> File already gone or not found. ({e})")

        # STEP 2: Enable Debugging (The Black Box)
        print("2. Ensuring Debug Mode is ON...")
        # We assume we enabled it earlier, but let's verify by downloading wp-config
        try:
            # Reset to root/public_html just in case
            ftps.cwd('/') 
            
            with open("wp-config_check.php", "wb") as f:
                # Try public_html path
                try:
                    ftps.retrbinary("RETR public_html/wp-config.php", f.write)
                except:
                    # try relative if we are already in public_html (though we reset to / above)
                    # or maybe just 'wp-config.php' if login root is public_html
                    ftps.retrbinary("RETR wp-config.php", f.write)

            with open("wp-config_check.php", "r", encoding="utf-8") as f:
                config = f.read()
                
            if "define( 'WP_DEBUG', true )" not in config:
                print("   -> Debug was OFF according to this check.")
                # We won't modify it here to avoid risking another breakage, 
                # but valid info for the user.
            else:
                print("   -> Debug is confirmed ON.")
        except Exception as e:
            print(f"   -> Could not check wp-config: {e}")
        
        # STEP 3: Download the Error Log
        print("3. Hunting for the Error Log (debug.log)...")
        try:
            with open("debug_log.txt", "wb") as f:
                # Try standard path
                try:
                    ftps.retrbinary("RETR public_html/wp-content/debug.log", f.write)
                except:
                     # Try relative
                     ftps.retrbinary("RETR wp-content/debug.log", f.write)
                     
            print("   -> LOG FOUND! Downloading...")
            
            # Read the last 10 lines of the log
            print("\n--- CRASH REPORT (Last 10 Errors) ---")
            with open("debug_log.txt", "r", encoding="utf-8") as f:
                lines = f.readlines()
                for line in lines[-10:]:
                    print(line.strip())
            print("------------------------------------")
            
        except Exception as e:
            print(f"   -> No debug.log found yet. (WordPress hasn't written it yet, or path is wrong). Error: {e}")
            
        ftps.quit()
        print("\nNEXT STEP: Go to your Homepage. Does it load?")
        
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    fix_crash()
