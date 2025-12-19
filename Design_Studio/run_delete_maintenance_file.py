import ftplib
import os

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def delete_maintenance_file():
    print("Attempting to delete .maintenance file from remote server...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()

        remote_maintenance_path = "public_html/.maintenance"

        if remote_maintenance_path in ftps.nlst('public_html/'):
            ftps.delete(remote_maintenance_path)
            print("Successfully deleted .maintenance file.")
        else:
            print(".maintenance file not found, or already deleted.")

        print("Check the site: http://treasurepointonline.com")
        ftps.quit()
    except ftplib.all_errors as e:
        print(f"FTP Error: {e}")
    except Exception as e:
        print(f"An unexpected error occurred: {e}")

if __name__ == "__main__":
    delete_maintenance_file()
