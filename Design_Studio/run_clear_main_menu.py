import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def run_clear_main_menu():
    print("🚀 Uploading Clear Main Menu Script...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        with open("clear_main_menu.php", "rb") as f:
            ftps.storbinary("STOR public_html/clear_main_menu.php", f)
        print("✅ Upload Complete.")
        print("👉 RUN IT: http://treasurepointonline.com/clear_main_menu.php")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    run_clear_main_menu()
