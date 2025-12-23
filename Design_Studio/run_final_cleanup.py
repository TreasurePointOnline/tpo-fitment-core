import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def run_final_cleanup():
    print("🚀 Uploading Final Cleanup Script...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        with open("final_cleanup.py", "rb") as f:
            ftps.storbinary("STOR public_html/final_cleanup.py", f)
        print("✅ Upload Complete.")
        print("👉 RUN IT: http://treasurepointonline.com/final_cleanup.py")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    run_final_cleanup()
