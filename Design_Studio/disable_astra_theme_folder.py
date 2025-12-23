import ftplib
import os

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def disable_astra_folder():
    print("🚀 Disabling Astra Theme Folder...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # Rename the folder
        try:
            ftps.rename("public_html/wp-content/themes/astra", "public_html/wp-content/themes/astra.disabled")
            print("✅ 'astra' theme folder renamed to 'astra.disabled'.")
        except Exception as e:
            print(f"❌ Failed to rename: {e} (Maybe it's already disabled?)")
            
        ftps.quit()
        print("\n👉 Now the site should be broken in a NEW way (ignoring Astra).")
        print("👉 Try to run the repair tool NOW: http://treasurepointonline.com/repair_astra_options.php")
        
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    disable_astra_folder()
