import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def upload_hello():
    print("🚀 Uploading Hello World Test...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        with open("hello.php", "rb") as f:
            ftps.storbinary(f"STOR public_html/hello.php", f)
        print("✅ Upload Complete.")
        print("👉 TEST LINK: http://treasurepointonline.com/hello.php")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    upload_hello()
