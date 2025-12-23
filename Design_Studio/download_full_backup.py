import ftplib
import os

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def download_backup():
    print("🚀 Downloading Backup Files...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # 1. Download SQL Dump
        print("1. Downloading Database Backup (live_deployment.sql)...")
        try:
            with open("live_deployment.sql", "wb") as f:
                ftps.retrbinary("RETR public_html/live_deployment.sql", f.write)
            print("✅ Database backup saved to 'live_deployment.sql'")
        except Exception as e:
            print(f"❌ Failed to download SQL: {e} (Did you run the export URL?)")

        # 2. Download Critical Config
        print("2. Downloading wp-config.php...")
        try:
            with open("wp-config_backup.php", "wb") as f:
                ftps.retrbinary("RETR public_html/wp-config.php", f.write)
            print("✅ Config backup saved to 'wp-config_backup.php'")
        except:
             print("⚠️ Could not download wp-config.php")
             
        # 3. Download Active Theme Files (Astra Child)
        print("3. Downloading Astra Child Theme...")
        theme_files = ['functions.php', 'style.css']
        if not os.path.exists("astra_child_backup"):
            os.makedirs("astra_child_backup")
            
        for tf in theme_files:
            try:
                with open(f"astra_child_backup/{tf}", "wb") as f:
                    ftps.retrbinary(f"RETR public_html/wp-content/themes/astra-child/{tf}", f.write)
                print(f"   - {tf} saved.")
            except:
                print(f"   ⚠️ Could not download {tf}")
                
        ftps.quit()
        print("\n✅ DOWNLOAD COMPLETE.")
        
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    download_backup()
