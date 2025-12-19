import ftplib

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def list_root():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        print("\n📂 Checking Root Files...")
        files = ftps.mlsd("public_html")
        
        for name, facts in files:
            if name in ['index.php', 'index.html', 'index_wp_backup.php', '.htaccess']:
                print(f"   - {name} (Size: {facts.get('size', 'N/A')})")
            
        ftps.quit()
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    list_root()

