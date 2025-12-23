import ftplib
import os

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def diagnose_forbidden():
    print("🚀 Diagnosing Forbidden (403) Error...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # 1. Check permissions of public_html directory
        print("1. Checking permissions of public_html...")
        try:
            # mlst command gives detailed facts, including perm
            facts = ftps.mlst("public_html")
            for entry in facts:
                if entry[0] == "public_html":
                    perms = entry[1].get('perm', 'N/A')
                    print(f"   -> public_html permissions: {perms}")
        except Exception as e:
            print(f"   ❌ Could not get public_html permissions: {e}")

        # 2. Check permissions of index.php
        print("2. Checking permissions of index.php...")
        try:
            facts = ftps.mlst("public_html/index.php")
            for entry in facts:
                if entry[0] == "index.php":
                    perms = entry[1].get('perm', 'N/A')
                    print(f"   -> index.php permissions: {perms}")
        except Exception as e:
            print(f"   ❌ Could not get index.php permissions: {e}")
            
        # 3. Read .htaccess content
        print("3. Reading .htaccess content...")
        htaccess_content = ""
        try:
            with open("temp_htaccess.txt", "wb") as f:
                ftps.retrbinary("RETR public_html/.htaccess", f.write)
            with open("temp_htaccess.txt", "r", encoding="utf-8", errors='ignore') as f:
                htaccess_content = f.read()
            os.remove("temp_htaccess.txt")
            print("\n--- .htaccess Content ---")
            print(htaccess_content)
            print("-------------------------")
        except Exception as e:
            print(f"   ❌ Could not read .htaccess: {e}")
            
        ftps.quit()
        print("\n🚀 Diagnostics Complete.")
        
    except Exception as e:
        print(f"❌ Critical Error: {e}")

if __name__ == "__main__":
    diagnose_forbidden()
