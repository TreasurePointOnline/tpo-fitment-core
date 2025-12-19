import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def run_gateway_debug():
    print("🚀 Uploading Gateway Debugger...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        with open("debug_gateways.php", "rb") as f:
            ftps.storbinary(f"STOR public_html/debug_gateways.php", f)
        print("✅ Upload Complete.")
        print("👉 RUN IT: https://treasurepointonline.com/debug_gateways.php")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    run_gateway_debug()
