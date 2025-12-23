import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def run_restore_and_menu():
    print("🚀 Uploading Catalog & Menu Restore Scripts...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()

        # Upload Catalog Restore Script
        with open("restore_catalog.php", "rb") as f:
            ftps.storbinary("STOR public_html/restore_catalog.php", f)
        print("✅ restore_catalog.php Uploaded.")
        
        # Upload Menu Builder Script
        with open("build_menu_restored.php", "rb") as f:
            ftps.storbinary("STOR public_html/build_menu_restored.php", f)
        print("✅ build_menu_restored.php Uploaded.")
        
        ftps.quit()
        print("\n👉 FIRST: Run Catalog Restore: http://treasurepointonline.com/restore_catalog.php")
        print("👉 THEN: Run Menu Builder: http://treasurepointonline.com/build_menu_restored.php")
        print("👉 FINALLY: Check Site: http://treasurepointonline.com")
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    run_restore_and_menu()
