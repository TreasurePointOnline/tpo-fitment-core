import ftplib
import os

# YOUR CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"
REMOTE_PATH = "public_html/"

def list_files():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        print("\n--- FILE LIST IN public_html ---")
        try:
            # Try to change to the directory first to ensure we are looking at the right place
            ftps.cwd(REMOTE_PATH)
            files = ftps.nlst()
        except Exception as e:
            print(f"Warning during listing: {e}")
            files = []

        if not files:
            print("ERROR: Could not list files (Folder might be empty or permissions denied).")
        
        for f in files:
            # Clean up the name if it contains the path
            name = f.replace("public_html/", "")
            
            # skip folders like . / ..
            if name in [".", ".."]:
                continue
            
            # Get size
            try:
                size = ftps.size(name)
                print(f"FILE: {name}  (Size: {size} bytes)")
            except:
                print(f"FOLDER/FILE: {name}")
                
        print("--------------------------------")
        ftps.quit()
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    list_files()
