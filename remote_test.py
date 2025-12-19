import paramiko
from server_config import SSH_HOST, SSH_USER, SSH_PASS

def remote_wp_cli_test():
    if not SSH_HOST or not SSH_USER or not SSH_PASS:
        print("Error: SSH credentials are not set in server_config.py")
        print("Please fill in SSH_HOST, SSH_USER, and SSH_PASS in Design_Studio\\server_config.py")
        return

    print(f"Attempting to connect to {SSH_HOST} via SSH...")
    try:
        client = paramiko.SSHClient()
        client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
        client.connect(hostname=SSH_HOST, username=SSH_USER, password=SSH_PASS)
        print("SSH connection established.")

        # Run WP-CLI command to create a draft page
        wp_cli_command = "wp post create --post_type=page --post_title='AI_Connectivity_Test' --post_status=draft --porcelain --path=/home/f5t3lfykbikk/public_html"
        print(f"Executing command: {wp_cli_command}")
        stdin, stdout, stderr = client.exec_command(wp_cli_command)

        output = stdout.read().decode().strip()
        error = stderr.read().decode().strip()

        if error:
            print(f"Error executing WP-CLI command: {error}")
        elif output.isdigit():
            print(f"Success! Created Page ID: {output}")
            print(f"You can verify this by checking your WordPress admin under Pages (Drafts).")
            print("Remember to delete the 'AI_Connectivity_Test' page after verification.")
        else:
            print(f"Unexpected WP-CLI output: {output}")

    except paramiko.AuthenticationException:
        print("Authentication failed. Please check SSH_USER and SSH_PASS in server_config.py.")
    except paramiko.SSHException as e:
        print(f"SSH connection error: {e}")
    except Exception as e:
        print(f"An unexpected error occurred: {e}")
    finally:
        if 'client' in locals() and client.get_transport() is not None:
            client.close()
            print("SSH connection closed.")

if __name__ == "__main__":
    remote_wp_cli_test()
