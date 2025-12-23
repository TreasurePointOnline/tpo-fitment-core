import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def emergency_disable_php():
    print("🚑 Emergency Disabling populate_masterlist.php...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        try:
            ftps.rename("public_html/populate_masterlist.php", "public_html/populate_masterlist.php.disabled")
            print("✅ SUCCESS: Script disabled. Site should load.")
        except Exception as e:
            print(f"❌ Failed to rename: {e}")
            
        ftps.quit()
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    emergency_disable_php()
