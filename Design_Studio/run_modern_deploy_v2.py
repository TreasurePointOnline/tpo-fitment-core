import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def deploy_modern_v2():
    print("🚀 Deploying Modern Skin v4.1 (Sticky Nav + No Home Title)...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        with open("tpo-skin-modern-v2.php", "rb") as f:
            ftps.storbinary("STOR public_html/wp-content/mu-plugins/tpo-skin.php", f)
            
        print("✅ Skin Updated.")
        print("👉 Check the site: http://treasurepointonline.com")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    deploy_modern_v2()
