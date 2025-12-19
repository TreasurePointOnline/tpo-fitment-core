import ftplib

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

TEST_INDEX = '<?php echo "<h1>I AM THE NEW INDEX</h1>"; ?>'

def swap_index():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # 1. Backup real index
        print("Backing up real index.php...")
        try:
            ftps.rename("public_html/index.php", "public_html/index.php.real")
        except:
            pass # Maybe already renamed

        # 2. Upload fake index
        print("Uploading TEST index.php...")
        with open("index_test.php", "w") as f:
            f.write(TEST_INDEX)
            
        with open("index_test.php", "rb") as f:
            ftps.storbinary("STOR public_html/index.php", f)
            
        print("✅ Index Swapped.")
        print("👉 Check homepage. If you see 'I AM THE NEW INDEX', the server is fine.")
        
        ftps.quit()
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    swap_index()
