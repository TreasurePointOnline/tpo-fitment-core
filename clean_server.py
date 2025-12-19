import ftplib

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0" 
REMOTE_DIR = "public_html"

def clean_server():
    try:
        print(f"Connecting to {FTP_HOST}...")
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        ftps.cwd(REMOTE_DIR)
        
        print(f"--- Scanning {REMOTE_DIR} ---")
        files = []
        ftps.retrlines('NLST', files.append)
        
        # Check for index.php (The Zombie File)
        if "index.php" in files:
            print("? FOUND index.php! This is blocking your new site.")
            print("Deleting index.php...")
            try:
                ftps.delete("index.php")
                print("? index.php deleted successfully.")
            except Exception as e:
                print(f"Could not delete index.php: {e}")
        else:
            print("? No index.php found (Good news).")

        # Check for index.html
        if "index.html" in files:
            print("? index.html is present.")
        else:
            print("? index.html is MISSING.")
            
        ftps.quit()
        
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    clean_server()
