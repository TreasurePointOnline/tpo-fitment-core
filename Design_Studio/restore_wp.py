import ftplib
import os

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def restore_wordpress():
    print("🚀 Restoring WordPress Access...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # 1. Delete splash_index.html
        print("1. Deleting splash_index.html...")
        try:
            ftps.delete("public_html/index.html")
            print("✅ index.html deleted.")
        except Exception as e:
            print(f"❌ Failed to delete index.html: {e} (might be gone)")

        # 2. Restore index.php
        print("2. Restoring index.php from backup...")
        try:
            ftps.rename("public_html/index.php.bak", "public_html/index.php")
            print("✅ index.php restored.")
        except Exception as e:
            print(f"❌ Failed to restore index.php: {e} (might be already restored/missing)")
            
        ftps.quit()
        print("\n👉 Check your site: https://treasurepointonline.com")
        
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    restore_wordpress()
