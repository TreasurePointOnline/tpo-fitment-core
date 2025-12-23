import ftplib
import os

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def run_repair():
    print("🚀 Uploading Astra Repair Script...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # Upload
        with open("repair_astra_options.php", "rb") as f:
            ftps.storbinary("STOR public_html/repair_astra_options.php", f)
        print("✅ Upload Complete.")
        
        # We can't curl from here easily to trigger it if public access is 500ing, 
        # but the script needs to run via HTTP or CLI.
        # Since the site is critical erroring, hitting the URL might still run the script 
        # IF the error happens later in the boot process.
        # However, since it requires wp-load.php, and the error is in a theme, 
        # simply loading this isolated file MIGHT bypass the theme loading if we define WP_USE_THEMES false.
        
        print("👉 Please visit this URL in your browser to run the fix:")
        print("   http://treasurepointonline.com/repair_astra_options.php")
        
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    run_repair()
