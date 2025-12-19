import ftplib
import os

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def clean_server():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # Files that block WordPress
        masks = ["index.html", "home.html", "prototype_home.html", "test_deploy.html"]
        
        for mask in masks:
            try:
                # Need to specify the full path on the server
                ftps.delete(f"public_html/{mask}")
                print(f"✅ DELETED MASK: {mask} (WordPress should breathe now)")
            except Exception as e:
                print(f"   (No {mask} found or could not delete, that's fine if not present) Error: {e}")
                
        ftps.quit()
        print("\n👉 Go refresh your site. The Engine should be back.")
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    clean_server()

