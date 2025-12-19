import ftplib
import os

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"
PLUGIN_PATH = "public_html/wp-content/plugins/ajax-search-for-woocommerce"
DISABLED_PLUGIN_PATH = "public_html/wp-content/plugins/ajax-search-for-woocommerce_DISABLED"

def disable_plugin():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        print(f"Attempting to rename plugin folder: {PLUGIN_PATH} to {DISABLED_PLUGIN_PATH}...")
        
        # Change directory to plugins folder to perform rename reliably
        try:
            ftps.cwd("public_html/wp-content/plugins")
            ftps.rename("ajax-search-for-woocommerce", "ajax-search-for-woocommerce_DISABLED")
            print("✅ SUCCESS: Plugin 'Ajax Search for WooCommerce' has been disabled.")
        except Exception as e:
            print(f"❌ Failed to rename plugin folder. It might already be disabled or path is wrong. Error: {e}")
            
        ftps.quit()
        print("\n👉 Go check your homepage now! It should load.")
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    disable_plugin()
