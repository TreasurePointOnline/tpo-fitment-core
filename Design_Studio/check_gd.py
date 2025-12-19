import ftplib

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def check_gd():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        files = ftps.nlst("public_html")
        if "gd-config.php" in [f.split('/')[-1] for f in files]:
            print("✅ FOUND: gd-config.php")
        else:
            print("❌ NOT FOUND: gd-config.php")
            
        ftps.quit()
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    check_gd()
