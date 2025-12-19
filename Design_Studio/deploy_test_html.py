import ftplib
import os

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"
REMOTE_TARGET_PATH = "public_html/test_deploy.html"
LOCAL_SOURCE_PATH = "test_deploy.html"

def deploy_test():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        print(f"Uploading {LOCAL_SOURCE_PATH} to {REMOTE_TARGET_PATH}...")
        with open(LOCAL_SOURCE_PATH, "rb") as f:
            ftps.storbinary(f"STOR {REMOTE_TARGET_PATH}", f)
            
        print("\n✅ SUCCESS: test_deploy.html uploaded.")
        print(f"👉 Verify now: http://treasurepointonline.com/test_deploy.html")
        
        ftps.quit()
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    deploy_test()
