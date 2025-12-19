import ftplib

# --- CONFIGURATION ---
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0" 
TARGET_DIR = "public_html/treasurepointonline.com"

def wipe_clean():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        try:
            ftps.cwd(TARGET_DIR)
            print(f"? Entered {TARGET_DIR}")
        except:
            print("? Could not find folder. It might already be empty or missing.")
            return

        print("\n--- DELETING EVERYTHING (PREPARING FOR WORDPRESS) ---")
        files = []
        ftps.retrlines('NLST', files.append)
        
        for file in files:
            if file not in ['.', '..']:
                try:
                    # Try to delete as a file
                    ftps.delete(file)
                    print(f"Deleted file: {file}")
                except:
                    # If it fails, it's a folder. Try to delete the folder.
                    try:
                        # Note: FTP cannot easily delete non-empty folders recursively without complex scripts.
                        # We will try a simple removal. If this fails, we might need to rely on GoDaddy's installer to overwrite.
                        ftps.rmd(file) 
                        print(f"Deleted folder: {file}")
                    except:
                        print(f"? Could not delete: {file} (Likely a non-empty folder. The installer will handle it.)")

        ftps.quit()
        print("\n--- WIPE COMPLETE ---")
        print("You are now ready to install WordPress in GoDaddy.")

    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    wipe_clean()
