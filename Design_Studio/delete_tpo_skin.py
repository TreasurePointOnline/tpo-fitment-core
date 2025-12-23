import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def delete_tpo_skin():
    print("🚀 Deleting tpo-skin.php...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        try:
            ftps.delete("public_html/wp-content/mu-plugins/tpo-skin.php")
            print("✅ Deleted tpo-skin.php.")
        except Exception as e:
            print(f"❌ Failed to delete tpo-skin.php: {e} (might not exist)")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    delete_tpo_skin()
