import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def run_big_dog():
    print("🚀 Uploading Big Dog Setup Script...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        with open("big_dog_setup.php", "rb") as f:
            ftps.storbinary(f"STOR public_html/big_dog_setup.php", f)
        print("✅ Upload Complete.")
        print("👉 RUN IT: https://treasurepointonline.com/big_dog_setup.php")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    run_big_dog()
