import ftplib
import os

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def delete_hello():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        print("Deleting public_html/hello.php...")
        try:
            ftps.delete("public_html/hello.php")
            print("✅ SUCCESS: hello.php deleted.")
        except Exception as e:
            print(f"❌ Failed to delete hello.php (might not exist): {e}")
            
        ftps.quit()
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    delete_hello()
