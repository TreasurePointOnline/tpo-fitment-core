import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def run_check_mu_status():
    print("🚀 Uploading MU-Plugin Status Checker Script...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        with open("check_mu_status.php", "rb") as f:
            ftps.storbinary("STOR public_html/check_mu_status.php", f)
        print("✅ Upload Complete.")
        print("👉 VIEW: http://treasurepointonline.com/check_mu_status.php")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    run_check_mu_status()
