import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def upload_bypass():
    print("🚀 Uploading Homepage Bypass...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        with open("home_bypass.php", "rb") as f:
            ftps.storbinary("STOR public_html/home.php", f)
        print("✅ Upload Complete.")
        print("👉 TRY THIS: http://treasurepointonline.com/home.php")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    upload_bypass()
