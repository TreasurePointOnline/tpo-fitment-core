import ftplib

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def check_perms():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        print("\n🔎 File Permissions Scan...")
        # Get raw list to see permissions
        lines = []
        ftps.retrlines('LIST public_html', lines.append)
        
        for line in lines:
            # Simple parsing for display
            if "index.php" in line or ".htaccess" in line or "maintenance" in line:
                print(line)
            
        ftps.quit()
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    check_perms()

