import ftplib
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def revert_to_v4_2_final():
    print("⏪ Reverting Skin to V4.2 (Stable Simplified Nav)...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        with open("tpo-skin-v4.2.php", "rb") as f:
            ftps.storbinary("STOR public_html/wp-content/mu-plugins/tpo-skin.php", f)
            
        print("✅ Reverted.")
        print("👉 Check site now: http://treasurepointonline.com")
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    revert_to_v4_2_final()
