import ftplib
import os
import random
import string
import time # Import time for time.time()

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def force_delete_index_html_direct():
    print("🚀 Forcing Deletion of index.html and flushing cache...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # 1. Force delete index.html
        print("1. Attempting to delete public_html/index.html...")
        try:
            ftps.delete("public_html/index.html")
            print("✅ index.html deleted.")
        except Exception as e:
            print(f"❌ Failed to delete index.html: {e} (might be already gone or permissions issue)")

        # 2. Try to flush server cache by touching a random file
        print("2. Attempting to flush server cache...")
        random_filename = ''.join(random.choices(string.ascii_lowercase + string.digits, k=10)) + ".txt"
        temp_file_path = "public_html/" + random_filename
        
        # Create a local temp file for upload
        local_temp_file = "temp_cache_buster.txt"
        with open(local_temp_file, "w") as f:
            f.write("cache bust " + str(time.time()))
        
        with open(local_temp_file, "rb") as f:
            ftps.storbinary(f"STOR {temp_file_path}", f)
        ftps.delete(temp_file_path) # Delete it immediately from server
        os.remove(local_temp_file) # Delete local temp file
        print("✅ Server cache attempted flushed by file touch.")
            
        ftps.quit()
        print("\n👉 Check your site: https://treasurepointonline.com")
        
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    force_delete_index_html_direct()
