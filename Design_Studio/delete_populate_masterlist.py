import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def delete_populate():
    print("🚀 Deleting old populate_masterlist.php...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        try:
            ftps.delete("public_html/populate_masterlist.php")
            print("✅ Deleted populate_masterlist.php.")
        except Exception as e:
            print(f"❌ Failed to delete populate_masterlist.php: {e} (might not exist)")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    delete_populate()
