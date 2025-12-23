import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def run_force_astra_header():
    print("🚀 Uploading Force Astra Header Script...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        with open("force_astra_header.php", "rb") as f:
            ftps.storbinary("STOR public_html/force_astra_header.php", f)
        print("✅ Upload Complete.")
        print("👉 RUN IT: http://treasurepointonline.com/force_astra_header.php")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    run_force_astra_header()
