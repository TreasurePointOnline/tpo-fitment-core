import os
import shutil
import subprocess
import datetime

# --- CONFIGURATION ---
# Path to your LocalWP site folder (Update this to match your machine!)
LOCAL_SITE_PATH = "C:\\Users\\Administrator\\Local Sites\\treasure-point-local"
PROJECT_ROOT = "C:\\Users\\Administrator\\tpo-fitment-core"

def package_site():
    print("📦 Packaging Local Site for GitHub...")
    
    # 1. Export local database
    # We will look for the SQL file that All-in-One WP Migration or LocalWP creates
    # Or we can use the 'wp-cli' if installed. 
    print("1. Creating Database Dump...")
    timestamp = datetime.datetime.now().strftime("%Y%m%d_%H%M%S")
    db_filename = f"local_site_backup_{timestamp}.sql"
    
    # For now, we will create a placeholder instruction or try to run wp-cli
    try:
        # If wp-cli is in path, this is the best way
        subprocess.run(["wp", "db", "export", os.path.join(PROJECT_ROOT, "latest_local_db.sql")], check=True)
        print("✅ Database exported to latest_local_db.sql")
    except:
        print("⚠️ WP-CLI not found. Please manually export your local DB to the project root as 'latest_local_db.sql'")

    # 2. Sync Theme and Plugins to Project
    print("2. Syncing Theme and Plugins...")
    folders_to_sync = [
        "app/public/wp-content/themes/astra-child",
        "app/public/wp-content/plugins/tpo-fitment-core" # Only sync YOUR custom plugin
    ]

    for folder in folders_to_sync:
        src = os.path.join(LOCAL_SITE_PATH, folder)
        dest_name = os.path.basename(folder)
        dest = os.path.join(PROJECT_ROOT, dest_name)
        
        if os.path.exists(src):
            if os.path.exists(dest):
                shutil.rmtree(dest)
            shutil.copytree(src, dest)
            print(f"✅ Synced: {dest_name}")
        else:
            print(f"ℹ️ Folder not found: {src}")

    print("\n🚀 DONE. You are now ready to run:")
    print("   git add .")
    print("   git commit -m 'Release: [Message]'")
    print("   git push origin main")

if __name__ == "__main__":
    package_site()
