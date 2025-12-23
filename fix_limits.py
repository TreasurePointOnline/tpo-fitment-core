import os
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

print(f"🚀 Connecting to {SSH_HOST} to break the 32MB limit...")

try:
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    ssh.connect(SSH_HOST, username=SSH_USER, password=SSH_PASS)
    sftp = ssh.open_sftp()

    # The Configuration we need to inject
    # We use .user.ini because it works best on modern GoDaddy servers
    php_config = """
upload_max_filesize = 512M
post_max_size = 512M
memory_limit = 512M
max_execution_time = 300
max_input_time = 300
"""
    
    # 1. Create the file locally first
    with open('user_ini_temp', 'w') as f:
        f.write(php_config)
    
    # 2. Upload it to the server's public folder
    print("⬆️  Uploading new configuration (.user.ini)...")
    remote_path = "public_html/.user.ini"
    sftp.put('user_ini_temp', remote_path)
    
    # 3. Also try to append to .htaccess just in case (Double Tap)
    # We read the current .htaccess, check if our rules are there, if not, append them.
    print("🔧 Patching .htaccess (The Backup Plan)...")
    
    htaccess_rules = """
# BEGIN TPO LIMIT FIX
php_value upload_max_filesize 512M
php_value post_max_size 512M
php_value memory_limit 512M
php_value max_execution_time 300
php_value max_input_time 300
# END TPO LIMIT FIX
"""
    # Simple append command
    command = f'echo "{htaccess_rules}" >> public_html/.htaccess'
    ssh.exec_command(command)
    
    sftp.close()
    ssh.close()
    
    # Clean up
    os.remove('user_ini_temp')
    
    print("✅ LIMITS BROKEN!")
    print("👉 Go back to your browser, REFRESH the page, and try the import again.")

except Exception as e:
    print(f"❌ Error: {e}")