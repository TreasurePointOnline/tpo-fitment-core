import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def upload_option_getter():
    print("🚀 Uploading GoDaddy Settings Getter Script...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        with open("get_godaddy_settings.php", "rb") as f:
            ftps.storbinary(f"STOR public_html/get_godaddy_settings.php", f)
        print("✅ Upload Complete.")
        print("👉 VIEW SETTINGS: https://treasurepointonline.com/get_godaddy_settings.php")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    upload_option_getter()
