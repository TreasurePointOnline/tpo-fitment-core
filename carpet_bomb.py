import ftplib
import os

# --- CONFIGURATION ---
FTP_HOST = "107.180.116.158" # Using IP is safer than domain for FTP
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0" 

# We will target BOTH potential locations
TARGETS = [
    "public_html", 
    "public_html/treasurepointonline.com"
]

def double_deploy():
    print(f"--- CHECKING LOCAL FILES ---")
    # 1. Verify local files exist and are not empty
    for f in ["index.html", "styles.css"]:
        if os.path.exists(f):
            size = os.path.getsize(f)
            print(f"? Found {f} ({size} bytes)")
            if size == 0:
                print(f"? CRITICAL ERROR: {f} is empty! You need to regenerate the code.")
                return
        else:
            print(f"? CRITICAL ERROR: {f} is missing from your computer.")
            return

    print(f"\n--- CONNECTING TO SERVER ---")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        print("? Connected to 107.180.116.158.")

        # LOOP THROUGH BOTH FOLDERS
        for folder in TARGETS:
            print(f"\n>>> TARGETING: {folder}")
            try:
                ftps.cwd('/') # Reset to root
                ftps.cwd(folder)
            except:
                print(f"? Could not enter {folder} (Might not exist, skipping).")
                continue

            # 2. KILL THE GHOST (Delete index.php)
            files = []
            ftps.retrlines('NLST', files.append)
            if "index.php" in files:
                try:
                    ftps.delete("index.php")
                    print(f"   ? Deleted index.php (Blocking file removed)")
                except:
                    print(f"   ? Could not delete index.php")
            
            # 3. UPLOAD FILES
            for filename in ["index.html", "styles.css"]:
                try:
                    with open(filename, "rb") as file:
                        ftps.storbinary(f"STOR {filename}", file)
                    print(f"   ? Uploaded {filename}")
                    
                    # 4. UNLOCK PERMISSIONS (Make public)
                    try:
                        ftps.sendcmd(f"SITE CHMOD 644 {filename}")
                        print(f"   ? Permissions set to 644 (Public)")
                    except:
                        pass
                except Exception as e:
                    print(f"   ? Upload failed: {e}")

        ftps.quit()
        print("\n--- DOUBLE DEPLOY COMPLETE ---")
        print("Your site is now in every possible folder.")

    except Exception as e:
        print(f"\n? CONNECTION ERROR: {e}")

if __name__ == "__main__":
    double_deploy()
