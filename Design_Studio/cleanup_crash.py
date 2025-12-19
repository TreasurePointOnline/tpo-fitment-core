import ftplib
import os

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

try:
    ftps = ftplib.FTP_TLS(FTP_HOST)
    ftps.login(user=FTP_USER, passwd=FTP_PASS)
    ftps.prot_p()
    try:
        ftps.delete("public_html/build_structure.php")
        print("✅ SUCCESS: Bad script deleted.")
    except:
        print("ℹ️ File already gone.")
    ftps.quit()
except Exception as e:
    print(f"Error: {e}")
