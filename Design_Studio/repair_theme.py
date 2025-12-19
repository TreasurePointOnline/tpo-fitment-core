import ftplib

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"
REMOTE_THEME_PATH = "public_html/wp-content/themes/astra"

def fix_theme():
    print("🚑 Connecting to Repair the Theme ID Card...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # Overwrite the broken file with the repaired one
        print("   -> Uploading corrected style.css...")
        with open("style_repaired.css", "rb") as f:
            ftps.storbinary(f"STOR {REMOTE_THEME_PATH}/style.css", f)
            
        print("✅ SUCCESS: Theme ID Card restored.")
        ftps.quit()
        
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    fix_theme()
