import ftplib
import socket

# --- CONFIGURATION ---
DOMAIN = "treasurepointonline.com"
FTP_IP = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0" 
TARGET_DIR = "public_html/treasurepointonline.com"

def fix_site():
    print(f"--- DIAGNOSTIC CHECK ---")
    
    # 1. CHECK IP ADDRESS
    try:
        domain_ip = socket.gethostbyname(DOMAIN)
        print(f"Domain '{DOMAIN}' points to IP: {domain_ip}")
        print(f"We are uploading to FTP IP:     {FTP_IP}")
        
        if domain_ip != FTP_IP:
            print("? WARNING: These IPs do not match! This might be why you don't see updates.")
            print("(Proceeding anyway, but this is a major clue if it fails.)")
        else:
            print("? IPs match. We are on the correct server.")
    except:
        print("? Could not verify IP address automatically.")

    # 2. CONNECT AND CLEAN
    print(f"\n--- CONNECTING TO {TARGET_DIR} ---")
    try:
        ftps = ftplib.FTP_TLS(FTP_IP)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # Enter the subfolder
        try:
            ftps.cwd(TARGET_DIR)
            print(f"? Entered {TARGET_DIR}")
        except:
            print(f"? Could not find folder '{TARGET_DIR}'")
            print("Trying to create it...")
            try:
                ftps.mkd(TARGET_DIR)
                ftps.cwd(TARGET_DIR)
                print("? Folder created.")
            except:
                print("? CRITICAL: Cannot find or create the folder.")
                return

        # 3. WIPE THE FOLDER (The "Nuclear" Option)
        print("\n--- CLEANING FOLDER ---")
        files = []
        ftps.retrlines('NLST', files.append)
        
        for file in files:
            if file not in ['.', '..']: # Don't delete the folder itself
                try:
                    ftps.delete(file)
                    print(f"Deleted old file: {file}")
                except:
                    print(f"Skipped: {file} (Might be a folder)")

        # 4. UPLOAD FRESH FILES
        print("\n--- UPLOADING NEW SITE ---")
        files_to_upload = ["index.html", "styles.css"]
        
        for filename in files_to_upload:
            try:
                with open(filename, "rb") as local_file:
                    ftps.storbinary(f"STOR {filename}", local_file)
                print(f"? {filename} uploaded successfully.")
            except Exception as e:
                print(f"? Failed to upload {filename}: {e}")

        ftps.quit()
        print("\n--- FIX COMPLETE ---")
        print("Go to http://treasurepointonline.com and REFRESH.")

    except Exception as e:
        print(f"\n? CONNECTION ERROR: {e}")

if __name__ == "__main__":
    fix_site()
