import ftplib

# CREDENTIALS
FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def deep_scan():
    print(f"Connecting to {FTP_HOST}...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        print("\n🔎 Scanning for blocker files...")
        files = ftps.mlsd("public_html")
        
        blockers = []
        for name, facts in files:
            print(f"   - {name}")
            if "maintenance" in name.lower() or "coming_soon" in name.lower() or "default" in name.lower():
                blockers.append(name)
        
        if blockers:
            print(f"\n⚠️ FOUND POTENTIAL BLOCKERS: {blockers}")
        else:
            print("\n✅ No obvious maintenance files found.")
            
        ftps.quit()
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    deep_scan()
