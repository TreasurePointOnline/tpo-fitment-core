import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def run_import_og_v2():
    print("🚀 Uploading OG Catalog Importer V2...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        with open("import_og_catalog_v2.php", "rb") as f:
            ftps.storbinary(f"STOR public_html/import_og_catalog_v2.php", f)
        print("✅ Upload Complete.")
        print("👉 RUN IT: http://treasurepointonline.com/import_og_catalog_v2.php")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    run_import_og_v2()
