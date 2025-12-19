import ftplib

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def read_index():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        with open("index_check.php", "wb") as f:
            ftps.retrbinary("RETR public_html/index.php", f.write)
            
        with open("index_check.php", "r") as f:
            print("\n--- public_html/index.php ---")
            print(f.read())
            
        ftps.quit()
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    read_index()
