import ftplib

# --- CONFIGURATION ---
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0" 
REMOTE_DIR = "public_html/treasurepointonline.com"

def unlock_files():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        ftps.cwd(REMOTE_DIR)
        print(f"? Entered {REMOTE_DIR}")

        # List all files
        files = []
        ftps.retrlines('NLST', files.append)

        print("\n--- UNLOCKING FILES (Setting to 644) ---")
        for file in files:
            if file not in ['.', '..', 'treasureman1']: # Skip folders
                try:
                    # This command forces the server to make the file public
                    ftps.sendcmd(f"SITE CHMOD 644 {file}")
                    print(f"? Unlocked: {file}")
                except Exception as e:
                    print(f"? Could not unlock {file}: {e}")

        ftps.quit()
        print("\n--- UNLOCK COMPLETE ---")

    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    unlock_files()
