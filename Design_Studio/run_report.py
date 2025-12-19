import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def upload_report():
    print("🚀 Uploading System Report Script...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        with open("wp_report.php", "rb") as f:
            ftps.storbinary(f"STOR public_html/wp_report.php", f)
        print("✅ Upload Complete.")
        print("👉 VIEW REPORT: http://treasurepointonline.com/wp_report.php")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    upload_report()
