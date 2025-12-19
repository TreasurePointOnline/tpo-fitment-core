import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def run_structure():
    print(" Uploading Structure Script...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        with open("build_structure.php", "rb") as f:
            ftps.storbinary(f"STOR public_html/build_structure.php", f)
        print(" Upload Complete.")
        print(" Run it here: http://treasurepointonline.com/build_structure.php")
        ftps.quit()
    except Exception as e:
        print(f" Error: {e}")

if __name__ == "__main__":
    run_structure()
