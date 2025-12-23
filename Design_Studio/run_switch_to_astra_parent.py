import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def run_switch_to_astra_parent():
    print("🚀 Uploading Astra Parent Theme Switcher...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        with open("switch_to_astra_parent.php", "rb") as f:
            ftps.storbinary("STOR public_html/switch_to_astra_parent.php", f)
        print("✅ Upload Complete.")
        print("👉 RUN IT: http://treasurepointonline.com/switch_to_astra_parent.php")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    run_switch_to_astra_parent()
