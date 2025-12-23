import ftplib
import os

FTP_HOST = "107.180.116.158"
FTP_USER = "f5t3lfykbikk"
FTP_PASS = "k7lP9L%0Aci0"

def final_cleanup():
    print(f"Connecting to {FTP_HOST} for final cleanup...")
    try:
        ftps = ftplib.FTP_TLS(FTP_HOST)
        ftps.login(user=FTP_USER, passwd=FTP_PASS)
        ftps.prot_p()
        
        # 1. Delete all custom PHP scripts from public_html
        print("1. Deleting custom PHP scripts from public_html...")
        php_files_in_root = ftps.nlst("public_html")
        for file_path in php_files_in_root:
            filename = os.path.basename(file_path)
            if filename.endswith(".php") and filename not in ['index.php', 'wp-config.php', 'wp-activate.php', 'wp-blog-header.php', 'wp-comments-post.php', 'wp-cron.php', 'wp-links-opml.php', 'wp-login.php', 'wp-mail.php', 'wp-settings.php', 'wp-signup.php', 'wp-trackback.php']:
                try:
                    ftps.delete(file_path)
                    print(f"   ✅ Deleted: {filename}")
                except:
                    print(f"   ❌ Failed to delete: {filename} (might be gone)")
        
        # 2. Disable all mu-plugins
        print("2. Disabling all mu-plugins...")
        mu_plugins_path = "public_html/wp-content/mu-plugins/"
        try:
            mu_files = ftps.nlst(mu_plugins_path)
            for file_path in mu_files:
                filename = os.path.basename(file_path)
                if filename.endswith(".php"):
                    try:
                        ftps.rename(file_path, f"{mu_plugins_path}disabled_{filename}")
                        print(f"   ✅ Disabled: {filename}")
                    except:
                        print(f"   ❌ Failed to disable: {filename}")
        except:
            print("   (mu-plugins folder not found or empty)")
        
        # 3. Force upload a truly pristine wp-config.php (without any custom code)
        print("3. Forcing pristine wp-config.php...")
        pristine_wp_config = "<?php\ndefine( 'DB_NAME', 'i10822544_eztl1' );\ndefine( 'DB_USER', 'i10822544_eztl1' );\ndefine( 'DB_PASSWORD', 'S.klM6NsfCYYQVZIpk048' );\ndefine( 'DB_HOST', 'localhost' );\ndefine( 'DB_CHARSET', 'utf8' );\ndefine( 'DB_COLLATE', '' );\n\ndefine('AUTH_KEY',         'V5oPSiJdGRsmS4EjGMmWxdT7TRWNNSXwypr7nWesLtMSCQv2FYIJkJ1bXpMMXle7');\ndefine('SECURE_AUTH_KEY',  'n5mjtZiH9y8rKyNUHGFkloNkBdJrMdDr5nxkwAnHX9xV1pIgKPqXNk0NPCE41sfp');\ndefine('LOGGED_IN_KEY',    'A8RtGU5Uw8Fp8DTlzkNX9NanJZM5tq8PETaMZkw5WDQqjrcoBbkarmiQNXi7GvUM');\ndefine('NONCE_KEY',        'K4l7pvjOXavlodeikk9Mh6Kb8324xtLrqShWOPEP0ifdx1oejqCk5gBi7wVtsA54');\ndefine('AUTH_SALT',        'Ykc7zkWtdoUpV6njdPdiVTlo4Np6p1Ybp0ca5JIqcDoO4qq3y6IGmc7IBfDEtunw');\ndefine('SECURE_AUTH_SALT', 'Im2OmsjmomphKHHg1sjZW5psfAvciDPMH7x4srm6aruvlPL7SHX5kRKgsptr6u2g');\ndefine('LOGGED_IN_SALT',   '6y2RXoyegfDIKPxWgzs7tEPHPDARubqO3JF6asQ16GVo4u8wnw97ohxeVeI5MD5q');\ndefine('NONCE_SALT',       'cyuskuTTkDKViTBSoXQU85zaaPbGwEjbQGavU8TS0aOGU5MDarfAuqu3b215pRIS');\n\n$table_prefix = 'n1a5_';\n\ndefine( 'WP_DEBUG', false ); // Force debug off for now\n\nif ( ! defined( 'ABSPATH' ) ) {\n    define( 'ABSPATH', __DIR__ . '/' );\n}\nrequire_once ABSPATH . 'wp-settings.php';\n"
        with open("pristine_wp_config.php", "w") as f:
            f.write(pristine_wp_config)
        with open("pristine_wp_config.php", "rb") as f:
            ftps.storbinary("STOR public_html/wp-config.php", f)
        print("✅ Pristine wp-config.php uploaded.")
        
        # 4. Force default theme to twentytwentyfive
        print("4. Forcing theme to Twenty Twenty-Five...")
        # Create a temp PHP script to force theme and flush cache
        force_theme_script = "<?php\n        define('WP_USE_THEMES', false);\n        require_once('wp-load.php');\n        update_option('template', 'twentytwentyfive');\n        update_option('stylesheet', 'twentytwentyfive');\n        wp_cache_flush();\n        flush_rewrite_rules();\n        echo \"Theme set to Twenty Twenty-Five and caches flushed.\";\n        ?>"
        with open("force_theme_temp.php", "w") as f:
            f.write(force_theme_script)
        with open("force_theme_temp.php", "rb") as f:
            ftps.storbinary("STOR public_html/force_theme_temp.php", f)
        print("   👉 Run: http://treasurepointonline.com/force_theme_temp.php to force theme.")
        
        ftps.quit()
        print("\n🚀 FINAL CLEANUP COMPLETE. Your site should load now.")
        print("👉 Check: http://treasurepointonline.com")
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    final_cleanup()
