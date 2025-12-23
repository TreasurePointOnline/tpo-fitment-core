import os
import glob
import shutil
import paramiko
from dotenv import load_dotenv

# Load Credentials
load_dotenv()
SSH_HOST = os.getenv("SSH_HOST")
SSH_USER = os.getenv("SSH_USER")
SSH_PASS = os.getenv("SSH_PASS")

print("🔍 SEARCHING FOR MIGRATION FILES...")

# 1. Look in Current Folder
current_files = glob.glob("*.wpress")
# 2. Look in Downloads Folder
home = os.path.expanduser("~")
download_files = glob.glob(os.path.join(home, "Downloads", "*.wpress"))

all_files = current_files + download_files

if not all_files:
    print("❌ CRITICAL ERROR: No .wpress files found!")
    print("   Did you actually download the export file from LocalWP?")
    exit(1)

# Pick the newest file
best_file = all_files[0]
print(f"✅ Found file: {best_file}")

# Move it to project folder if needed
local_filename = "migration.wpress"
if best_file != local_filename and not os.path.exists(local_filename):
    print("   -> Moving file here...")
    shutil.copy(best_file, local_filename)

# UPLOAD
print(f"🚀 Connecting to {SSH_HOST}...")
try:
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    ssh.connect(SSH_HOST, username=SSH_USER, password=SSH_PASS)
    sftp = ssh.open_sftp()

    remote_path = "public_html/wp-content/ai1wm-backups/migration.wpress"
    
    # Ensure backup folder exists
    try:
        sftp.stat("public_html/wp-content/ai1wm-backups")
    except FileNotFoundError:
        sftp.mkdir("public_html/wp-content/ai1wm-backups")

    print(f"⬆️  Uploading to Server...")
    def progress(transferred, total):
        percent = (transferred / total) * 100
        print(f"   Upload: {percent:.1f}%", end='\r')

    sftp.put(local_filename, remote_path, callback=progress)
    
    sftp.close()
    ssh.close()
    print("\n\n🎉 SUCCESS! Go to WP Admin -> All-in-One WP Migration -> BACKUPS -> Restore")

except Exception as e:
    print(f"\n❌ ERROR: {e}")
