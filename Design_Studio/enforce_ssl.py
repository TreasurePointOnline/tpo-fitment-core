import ftplib
import os

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def enforce_ssl_htaccess():
    print("🚀 Enforcing SSL in .htaccess...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # 1. Download current .htaccess
        with open("htaccess_current.txt", "wb") as f:
            ftps.retrbinary("RETR public_html/.htaccess", f.write)
            
        with open("htaccess_current.txt", "r") as f:
            content = f.read()
            
        # 2. Add Redirect Rules if not present
        ssl_rules = """
# FORCE HTTPS (Added by TPO Agent)
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</IfModule>
"""
        if "RewriteCond %{HTTPS} off" not in content:
            new_content = ssl_rules + "\n" + content
            print("   -> Added SSL Redirect Rules.")
            
            with open("htaccess_ssl.txt", "w") as f:
                f.write(new_content)
                
            # 3. Upload
            with open("htaccess_ssl.txt", "rb") as f:
                ftps.storbinary("STOR public_html/.htaccess", f)
            print("✅ .htaccess updated to force HTTPS.")
            
        else:
            print("ℹ️ SSL Rules already present in .htaccess.")
            
        ftps.quit()
        
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    enforce_ssl_htaccess()
