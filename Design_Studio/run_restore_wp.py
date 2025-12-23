import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def run_restore_wp():
    print("🚀 Uploading WordPress Restore Script...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        with open("restore_wp.py", "rb") as f:
            ftps.storbinary("STOR public_html/restore_wp.py", f)
        print("✅ Upload Complete.")
        print("👉 RUN IT: https://treasurepointonline.com/restore_wp.py")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    run_restore_wp()
