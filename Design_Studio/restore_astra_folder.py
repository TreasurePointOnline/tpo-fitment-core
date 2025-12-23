import ftplib
import os

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def restore_folders():
    print("🚀 Restoring Astra Folders...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # 1. Restore Theme
        try:
            ftps.rename("public_html/wp-content/themes/astra.disabled", "public_html/wp-content/themes/astra")
            print("✅ 'astra' theme restored.")
        except:
            print("⚠️ Could not restore 'astra' theme (maybe already done?)")

        # 2. Restore Plugin
        try:
            ftps.rename("public_html/wp-content/plugins/astra-sites.disabled", "public_html/wp-content/plugins/astra-sites")
            print("✅ 'astra-sites' plugin restored.")
        except:
            print("⚠️ Could not restore 'astra-sites' plugin (maybe already done?)")
            
        ftps.quit()
        print("\n👉 Go check your site now: http://treasurepointonline.com")
        
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    restore_folders()
