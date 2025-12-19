import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def upload_tax_creator():
    print("🚀 Uploading Tax Rate Creator Script...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        with open("create_tax_rate.php", "rb") as f:
            ftps.storbinary(f"STOR public_html/create_tax_rate.php", f)
        print("✅ Upload Complete.")
        print("👉 RUN IT: https://treasurepointonline.com/create_tax_rate.php")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    upload_tax_creator()
