import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def deploy_fixed_skin():
    print("🚀 Deploying FIXED Skin Plugin...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # 1. Delete the .disabled one to be clean
        try:
            ftps.delete("public_html/wp-content/mu-plugins/tpo-skin.php.disabled")
        except: pass

        # 2. Upload Fixed Skin as active
        print("Uploading tpo-skin.php...")
        with open("tpo-skin-award-fixed.php", "rb") as f:
            ftps.storbinary("STOR public_html/wp-content/mu-plugins/tpo-skin.php", f)
            
        print("✅ SUCCESS: Fixed TPO Skin enabled.")
        print("👉 Check your homepage now.")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    deploy_fixed_skin()
