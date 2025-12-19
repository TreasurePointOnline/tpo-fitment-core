import ftplib

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0" 
REMOTE_DIR = "public_html"

def scan_folders():
    try:
        print(f"Connecting to {FTP_HOST}...")
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        ftps.cwd(REMOTE_DIR)
        
        print(f"\n--- LOOKING INSIDE {REMOTE_DIR} ---")
        print("(If you see a folder named 'treasurepoint' or similar, THAT is where we need to be.)\n")
        
        # This command lists file details (permissions, date, name)
        lines = []
        ftps.retrlines('LIST', lines.append)
        
        for line in lines:
            # We are looking for lines starting with 'd' (directory)
            if line.startswith('d'):
                print(line)
            
        ftps.quit()
        
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    scan_folders()
