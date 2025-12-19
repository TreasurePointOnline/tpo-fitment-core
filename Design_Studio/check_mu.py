import ftplib

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def check_mu():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        print("\n🔎 Scanning wp-content/mu-plugins...")
        try:
            files = ftps.mlsd("public_html/wp-content/mu-plugins")
            for name, facts in files:
                print(f"   - {name}")
        except:
            print("   (mu-plugins folder not found or empty)")
            
        ftps.quit()
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    check_mu()
