import ftplib
import os
import time

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def run_dump():
    print("🚀 Dumping Astra Settings...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # Upload dumper
        with open("dump_astra_settings.php", "rb") as f:
            ftps.storbinary("STOR public_html/dump_astra_settings.php", f)
            
        print("👉 Please visit: http://treasurepointonline.com/dump_astra_settings.php")
        print("   (Wait 10 seconds after clicking, then press Enter here to download the dump)")
        input() 
        
        # Download dump
        print("Downloading dump...")
        with open("astra_settings_dump.txt", "wb") as f:
            ftps.retrbinary("RETR public_html/astra_settings_dump.txt", f.write)
            
        print("✅ Dump downloaded. Reading first 50 lines...")
        with open("astra_settings_dump.txt", "r") as f:
            print(f.read(2000))
            
        ftps.quit()
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    run_dump()
