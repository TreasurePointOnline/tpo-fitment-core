import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def run_menu():
    print("🚀 Connecting to organize the menu...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        with open("build_menu.php", "rb") as f:
            ftps.storbinary(f"STOR public_html/build_menu.php", f)
        print("✅ Upload Complete.")
        print("👉 RUN THIS LINK: http://treasurepointonline.com/build_menu.php")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    run_menu()
