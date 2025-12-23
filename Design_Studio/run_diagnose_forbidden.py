import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def run_diagnose_forbidden():
    print("🚀 Uploading Forbidden Diagnoser...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        with open("diagnose_forbidden.py", "rb") as f:
            ftps.storbinary("STOR public_html/diagnose_forbidden.py", f)
        print("✅ Upload Complete.")
        print("👉 RUN IT: http://treasurepointonline.com/diagnose_forbidden.py")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    run_diagnose_forbidden()
