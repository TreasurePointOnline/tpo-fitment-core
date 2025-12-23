import ftplib
import os

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def run_nuclear():
    print("🚀 Uploading Nuclear Astra Reset...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # Upload
        with open("nuclear_reset_astra_options.php", "rb") as f:
            ftps.storbinary("STOR public_html/nuclear_reset_astra_options.php", f)
        print("✅ Upload Complete.")
        
        print("👉 RUN THIS NOW: http://treasurepointonline.com/nuclear_reset_astra_options.php")
        
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    run_nuclear()
