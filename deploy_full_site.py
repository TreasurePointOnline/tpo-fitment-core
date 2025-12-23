import os
import zipfile
import subprocess
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

print(f"🚀 Starting Deployment to {SSH_HOST}...")

# ==========================================
# PART 1: SMART ZIP
# ==========================================
def zip_project(output_filename):
    print("📦 Zipping project...")
    zipf = zipfile.ZipFile(output_filename, 'w', zipfile.ZIP_DEFLATED)
    file_count = 0
    for root, dirs, files in os.walk('.'):
        if 'public_html' in dirs: dirs.remove('public_html')
        if 'Design_Studio' in dirs: dirs.remove('Design_Studio')
        if '.git' in dirs: dirs.remove('.git')
        if '.vscode' in dirs: dirs.remove('.vscode')
        if '__pycache__' in dirs: dirs.remove('__pycache__')
        if 'node_modules' in dirs: dirs.remove('node_modules')
        if 'vendor' in dirs: dirs.remove('vendor')
        
        print(f"   📂 Scanning: {root}                   ", end='\r')
        for file in files:
            if file == output_filename or file == "deploy_full_site.py": continue
            if file.endswith('.zip') or file.endswith('.sql') or file.endswith('.log'): continue 
            try:
                zipf.write(os.path.join(root, file), os.path.relpath(os.path.join(root, file), '.'))
                file_count += 1
            except: pass
    zipf.close()
    print(f"\n✅ Zip created: {output_filename} ({file_count} files)")

zip_project('deploy_package.zip')

# ==========================================
# PART 2: DATABASE (Skipped if not installed)
# ==========================================
db_success = False
# We are skipping the DB export notification to keep the output clean since you don't have WP-CLI yet.

# ==========================================
# PART 3: UPLOAD & SYNC
# ==========================================
try:
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    ssh.connect(SSH_HOST, username=SSH_USER, password=SSH_PASS)
    sftp = ssh.open_sftp()

    print("⬆️  Uploading Zip...")
    sftp.put('deploy_package.zip', 'deploy_package.zip')
    sftp.close()

    def run_remote(command):
        print(f"⚡ Remote: {command}")
        stdin, stdout, stderr = ssh.exec_command(command)
        exit_code = stdout.channel.recv_exit_status()
        if exit_code != 0:
            print(f"   ❌ Remote Error: {stderr.read().decode()}")

    # 1. Deploy Files
    remote_plugin_path = "public_html/wp-content/plugins/tpo-fitment-core"
    run_remote(f"mkdir -p {remote_plugin_path}")
    run_remote(f"unzip -o deploy_package.zip -d {remote_plugin_path}")
    
    # 2. Flush Cache (FIXED: Points to public_html)
    run_remote("wp cache flush --path=public_html")
    
    ssh.close()
    print("🎉 DEPLOYMENT FINISHED!")

except Exception as e:
    print(f"\n❌ CONNECTION ERROR: {e}")

# Cleanup
if os.path.exists('deploy_package.zip'): os.remove('deploy_package.zip')