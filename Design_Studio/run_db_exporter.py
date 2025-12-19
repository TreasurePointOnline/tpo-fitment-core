import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def upload_db_exporter():
    print("🚀 Uploading Database Exporter Script...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        with open("db_export.php", "rb") as f:
            ftps.storbinary(f"STOR public_html/db_export.php", f)
        print("✅ Upload Complete.")
        print("👉 RUN IT: https://treasurepointonline.com/db_export.php")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    upload_db_exporter()
