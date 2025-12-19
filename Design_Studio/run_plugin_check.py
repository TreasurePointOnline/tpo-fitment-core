import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def run_plugin_check():
    print("🚀 Uploading Plugin Checker...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        with open("check_plugins.php", "rb") as f:
            ftps.storbinary(f"STOR public_html/check_plugins.php", f)
        print("✅ Upload Complete.")
        print("👉 RUN IT: https://treasurepointonline.com/check_plugins.php")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    run_plugin_check()
