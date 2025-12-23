import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def run_clean_start_amps_menu():
    print("🚀 Uploading Clean Start Amps Only Scripts...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()

        # Upload Product Importer
        with open("clean_start_amps_only.php", "rb") as f:
            ftps.storbinary("STOR public_html/clean_start_amps_only.php", f)
        print("✅ clean_start_amps_only.php Uploaded.")
        
        # Upload Menu Builder Script
        with open("build_menu_amps_only.php", "rb") as f:
            ftps.storbinary("STOR public_html/build_menu_amps_only.php", f)
        print("✅ build_menu_amps_only.php Uploaded.")
        
        ftps.quit()
        print("\n👉 FIRST: Run Product Importer: http://treasurepointonline.com/clean_start_amps_only.php")
        print("👉 THEN: Run Menu Builder: http://treasurepointonline.com/build_menu_amps_only.php")
        print("👉 FINALLY: Check Site: http://treasurepointonline.com")
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    run_clean_start_amps_menu()
