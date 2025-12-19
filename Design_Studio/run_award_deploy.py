import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def deploy_award_winning_site():
    print("🚀 Deploying 'Sonic Cyberpunk' Upgrade...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # 1. Upload Homepage Builder
        print("1. Uploading Homepage Builder...")
        with open("build_award_home.php", "rb") as f:
            ftps.storbinary("STOR public_html/build_award_home.php", f)
            
        # 2. Upload Skin (Overwrite the old one)
        print("2. Upgrading Skin to Award Edition...")
        with open("tpo-skin-award.php", "rb") as f:
            ftps.storbinary("STOR public_html/wp-content/mu-plugins/tpo-skin.php", f)
            
        print("✅ UPGRADE COMPLETE.")
        print("👉 STEP 1: Run the Builder: http://treasurepointonline.com/build_award_home.php")
        print("👉 STEP 2: Check your site: http://treasurepointonline.com")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    deploy_award_winning_site()
