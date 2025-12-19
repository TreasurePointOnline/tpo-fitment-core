import ftplib
import os

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def delete_file(ftps, folder, filename):
    fullpath = f"{folder}/{filename}" if folder else filename
    print(f"Checking {fullpath}...")
    try:
        # Check if the folder exists and is accessible
        ftps.cwd('/') # Go to root
        current_dir_list = ftps.nlst(folder.split('/')[0]) # list contents of public_html
        if folder.split('/')[-1] not in current_dir_list:
             print(f"  - Folder '{folder}' does not seem to exist or is inaccessible.")
             return # Skip deletion if folder not found

        ftps.cwd(folder) # Change to target folder
        files_in_folder = ftps.nlst() # List files in target folder
        
        if filename in files_in_folder:
            print(f"  --> FOUND {filename}! Deleting...")
            ftps.delete(filename) # Delete directly by filename in current cwd
            print("  --> DELETED.")
        else:
            print(f"  - File '{filename}' not found in '{folder}'.")
    except ftplib.error_perm as e:
        print(f"  - Permission error accessing '{folder}': {e}")
    except Exception as e:
        print(f"  - Error processing {fullpath}: {e}")

def run():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # Target 1: The Root Folder
        print("\n--- TARGET 1: public_html (Root) ---")
        delete_file(ftps, "public_html", "home.html")
        delete_file(ftps, "public_html", "index.html") # Just in case

        # Target 2: The Subfolder (GoDaddy sometimes hides it here)
        print("\n--- TARGET 2: public_html/treasurepointonline.com ---")
        delete_file(ftps, "public_html/treasurepointonline.com", "home.html")
        
        ftps.quit()
        print("\n---------------------------------------------------")
        print("DONE. The blocker should be gone.")
        print("CHECK SITE NOW: http://treasurepointonline.com")
    except Exception as e:
        print(f"Error during FTP operation: {e}")

if __name__ == "__main__":
    run()
