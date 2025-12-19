import ftplib

# --- CONFIGURATION ---
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0" 
REMOTE_DIR = "public_html"  # We are going to the ROOT

def takeover_site():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        ftps.cwd(REMOTE_DIR)
        print("? Connected to public_html")

        # 1. DISABLE THE OLD SITE (Rename conflicting files)
        print("\n--- DISABLING OLD WORDPRESS/PHP FILES ---")
        files = []
        ftps.retrlines('NLST', files.append)

        # List of files that block your new site
        blockers = ['index.php', '.htaccess', 'default.html']
        
        for file in blockers:
            if file in files:
                try:
                    new_name = file + ".DISABLED"
                    ftps.rename(file, new_name)
                    print(f"? Renamed '{file}' to '{new_name}' (Disabled)")
                except Exception as e:
                    print(f"? Could not rename {file}: {e}")

        # 2. UPLOAD YOUR NEW SITE
        print("\n--- UPLOADING NEW SITE ---")
        files_to_upload = ["index.html", "styles.css"]
        
        for filename in files_to_upload:
            try:
                print(f"Uploading {filename}...")
                with open(filename, "rb") as local_file:
                    ftps.storbinary(f"STOR {filename}", local_file)
                print(f"? {filename} uploaded.")
            except Exception as e:
                print(f"? Failed to upload {filename}: {e}")

        ftps.quit()
        print("\n--- TAKEOVER COMPLETE ---")
        print("Now go to http://treasurepointonline.com (Do not search for it!)")

    except Exception as e:
        print(f"\n? Error: {e}")

if __name__ == "__main__":
    takeover_site()
