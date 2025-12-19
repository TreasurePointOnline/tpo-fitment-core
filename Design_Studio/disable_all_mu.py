import ftplib

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def disable_all_mu():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        print("Creating disabled folder...")
        try:
            ftps.mkd("public_html/wp-content/mu-plugins/disabled")
        except: pass

        targets = [
            "woocommerce-analytics-proxy-speed-module.php",
            "automation-by-installatron.php"
        ]
        
        for target in targets:
            print(f"Moving {target}...")
            try:
                ftps.rename(
                    f"public_html/wp-content/mu-plugins/{target}", 
                    f"public_html/wp-content/mu-plugins/disabled/{target}"
                )
                print("   ✅ Disabled.")
            except Exception as e:
                print(f"   ⚠️ Could not move (maybe already moved): {e}")
            
        ftps.quit()
        print("\n👉 Check homepage now.")
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    disable_all_mu()
