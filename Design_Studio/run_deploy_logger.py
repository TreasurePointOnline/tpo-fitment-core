import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def deploy_logger():
    print("🚀 Deploying TPO Debug Logger MU-Plugin...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # Ensure mu-plugins folder exists
        try:
            ftps.mkd("public_html/wp-content/mu-plugins")
        except: pass

        with open("tpo-error-logger.php", "rb") as f:
            ftps.storbinary("STOR public_html/wp-content/mu-plugins/tpo-error-logger.php", f)
            
        print("✅ TPO Debug Logger Deployed.")
        print("👉 It will now log all PHP errors to: public_html/wp-content/tpo-debug.log")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    deploy_logger()
