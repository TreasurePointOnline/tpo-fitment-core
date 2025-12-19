import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"
REMOTE_THEME_PATH = "public_html/wp-content/themes/astra"

def clean_slate():
    print("🧹 Wiping the slate clean...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        with open("style_reset.css", "rb") as f:
            ftps.storbinary(f"STOR {REMOTE_THEME_PATH}/style.css", f)
        print("✅ SUCCESS: Theme is back to Factory Default.")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    clean_slate()
