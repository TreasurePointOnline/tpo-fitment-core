import os
import glob
import paramiko
from dotenv import load_dotenv

# 1. Load Credentials
load_dotenv()
SSH_HOST = os.getenv("SSH_HOST")
SSH_USER = os.getenv("SSH_USER")
SSH_PASS = os.getenv("SSH_PASS")

if not SSH_HOST:
    print("❌ Error: Missing SSH credentials in .env")
    exit(1)

# 2. Find the .wpress file automatically
files = glob.glob("*.wpress")
if not files:
    print("❌ Error: No .wpress file found in this folder!")
    print("👉 Please move your migration file here and name it 'migration.wpress'")
    exit(1)

# Pick the first one found (or migration.wpress)
local_file = files[0]
remote_folder = "public_html/wp-content/ai1wm-backups"
remote_path = f"{remote_folder}/{local_file}"

print(f"🚀 Found migration file: {local_file}")
print(f"🚀 Connecting to {SSH_HOST}...")

try:
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    ssh.connect(SSH_HOST, username=SSH_USER, password=SSH_PASS)
    sftp = ssh.open_sftp()

    # Ensure the backup folder exists
    try:
        sftp.stat(remote_folder)
    except FileNotFoundError:
        print(f"🔧 Creating backup folder: {remote_folder}")
        sftp.mkdir(remote_folder)

    # Upload
    print(f"⬆️  Uploading {local_file} directly to Backups (This is faster)...")
    
    # Progress callback to show it's working
    def progress(transferred, total):
        percent = (transferred / total) * 100
        print(f"   Now: {percent:.1f}%", end='\r')

    sftp.put(local_file, remote_path, callback=progress)
    
    sftp.close()
    ssh.close()
    
    print(f"\n✅ UPLOAD COMPLETE!")
    print("👉 Go to WP Admin -> All-in-One WP Migration -> BACKUPS")
    print("👉 You will see the file there. Click 'RESTORE' (the cloud icon).")

except Exception as e:
    print(f"❌ Error: {e}")