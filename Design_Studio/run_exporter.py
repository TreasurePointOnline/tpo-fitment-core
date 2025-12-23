import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def run_exporter():
    print("🚀 Uploading Product Exporter...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        with open("export_og_products.php", "rb") as f:
            ftps.storbinary(f"STOR public_html/export_og_products.php", f)
        print("✅ Upload Complete.")
        print("👉 RUN IT: http://treasurepointonline.com/export_og_products.php")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    run_exporter()
