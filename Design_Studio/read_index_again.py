import ftplib

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def read_index_again():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        print("\n--- public_html/index.php ---")
        temp_file = "index_check_again.php"
        with open(temp_file, "wb") as f:
            ftps.retrbinary("RETR public_html/index.php", f.write)
            
        with open(temp_file, "r") as f:
            content = f.read()
            print(content)
            if "HELLO WORLD!" in content:
                print("\n⚠️ WARNING: index.php contains 'HELLO WORLD!' - it has been overwritten!")
            else:
                print("\n✅ index.php content looks like standard WordPress.")
            
        ftps.quit()
        os.remove(temp_file) # Clean up temp file
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    read_index_again()

