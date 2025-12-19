import ftplib

# --- CONFIGURATION ---
# We are changing the host to the domain as you requested
FTP_HOST = "treasurepointonline.com"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0" 

def run_test():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        print("? Connected successfully.")

        # --- TEST 1: UPLOAD TO ROOT (public_html) ---
        print("\n--- TEST 1: Uploading Beacon to ROOT ---")
        ftps.cwd('/public_html')
        
        with open("beacon_root.txt", "w") as f:
            f.write("HELLO! The website lives in the ROOT folder (public_html).")
            
        with open("beacon_root.txt", "rb") as file:
            ftps.storbinary("STOR beacon_root.txt", file)
        print("? Uploaded 'beacon_root.txt' to public_html")


        # --- TEST 2: UPLOAD TO SUBFOLDER (public_html/treasurepointonline.com) ---
        print("\n--- TEST 2: Uploading Beacon to SUBFOLDER ---")
        try:
            ftps.cwd('/public_html/treasurepointonline.com')
            
            with open("beacon_sub.txt", "w") as f:
                f.write("HELLO! The website lives in the SUBFOLDER.")
                
            with open("beacon_sub.txt", "rb") as file:
                ftps.storbinary("STOR beacon_sub.txt", file)
            print("? Uploaded 'beacon_sub.txt' to public_html/treasurepointonline.com")
        except:
            print("? Could not enter subfolder (That is okay, it might not be used).")

        ftps.quit()
        print("\n--- TEST COMPLETE ---")
        print("Please check the links in the instructions below.")

    except Exception as e:
        print(f"\n? CONNECTION ERROR: {e}")
        print("If this failed, the IP address (107.180.116.158) was actually correct.")

if __name__ == "__main__":
    run_test()
