import ftplib
import os
import urllib.request
import zipfile
import shutil

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"
PLUGIN_URL = "https://downloads.wordpress.org/plugin/all-in-one-wp-migration.latest-stable.zip"
LOCAL_ZIP = "ai1wm.zip"
EXTRACT_DIR = "ai1wm_extracted"

def install_plugin_manually():
    print("🚀 Starting Manual Plugin Installation (Bypassing WP)...")
    
    # 1. Download Zip
    print("1. Downloading plugin from WordPress.org...")
    try:
        urllib.request.urlretrieve(PLUGIN_URL, LOCAL_ZIP)
        print("   -> Downloaded.")
    except Exception as e:
        print(f"❌ Download failed: {e}")
        return

    # 2. Extract
    print("2. Extracting zip...")
    try:
        if os.path.exists(EXTRACT_DIR):
            shutil.rmtree(EXTRACT_DIR)
        with zipfile.ZipFile(LOCAL_ZIP, 'r') as zip_ref:
            zip_ref.extractall(EXTRACT_DIR)
        print("   -> Extracted.")
    except Exception as e:
        print(f"❌ Extraction failed: {e}")
        return

    # 3. Upload via FTP
    print("3. Uploading to server (this will take a minute)...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # We need to upload the folder 'all-in-one-wp-migration' inside EXTRACT_DIR
        local_plugin_root = os.path.join(EXTRACT_DIR, "all-in-one-wp-migration")
        remote_plugin_root = "public_html/wp-content/plugins/all-in-one-wp-migration"
        
        # Walk through the directory and upload files
        for root, dirs, files in os.walk(local_plugin_root):
            # Calculate relative path
            rel_path = os.path.relpath(root, local_plugin_root)
            remote_path = remote_plugin_root
            if rel_path != ".":
                remote_path = f"{remote_plugin_root}/{rel_path}".replace("\\", "/")
            
            # Create remote directory if needed
            try:
                ftps.mkd(remote_path)
            except:
                pass # Exists
            
            for file in files:
                local_file_path = os.path.join(root, file)
                remote_file_path = f"{remote_path}/{file}".replace("\\", "/")
                
                print(f"   Uploading {file}...")
                with open(local_file_path, "rb") as f:
                    ftps.storbinary(f"STOR {remote_file_path}", f)
                    
        print("✅ Plugin files uploaded successfully.")
        ftps.quit()
        
        print("\n👉 ACTION: Go to your WP Admin -> Plugins and Activate 'All-in-One WP Migration'.")
        
    except Exception as e:
        print(f"❌ Upload failed: {e}")

if __name__ == "__main__":
    install_plugin_manually()
