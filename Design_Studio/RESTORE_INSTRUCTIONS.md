# 🆘 Treasure Point Online - Site Restore Instructions

This document explains how to restore the website to the stable point saved on **December 20, 2025**.

## 📦 What You Should Have
1.  `live_deployment.sql` - The complete database backup (products, pages, settings).
2.  `wp-config_backup.php` - The server configuration file.
3.  `astra_child_backup/` - Folder containing `functions.php` and `style.css` for your theme.
4.  *(Optional)* A full zip of `public_html` if you downloaded it via FTP.

## 🚨 Scenario A: The Site is crashed/broken but files exist
If the site is broken due to a bad plugin update or code change:

1.  **Restore Theme Files:**
    - Upload `astra_child_backup/functions.php` and `style.css` to `/wp-content/themes/astra-child/`.
2.  **Restore Config:**
    - If `wp-config.php` was touched, replace it with `wp-config_backup.php`.
3.  **Restore Database (Last Resort):**
    - If settings are lost, import `live_deployment.sql` using a tool like phpMyAdmin (available in your hosting panel).

## 🚨 Scenario B: Total Loss / New Server
If you are moving to a new host or the site was wiped:

1.  **Install WordPress.**
2.  **Install Plugins:**
    - WooCommerce
    - Astra (Parent Theme)
    - Elementor (if used)
    - etc.
3.  **Install Astra Child Theme:**
    - Create a folder `wp-content/themes/astra-child`.
    - Upload the files from `astra_child_backup/`.
4.  **Import Database:**
    - Import `live_deployment.sql` into your database.
5.  **Connect Config:**
    - Edit `wp-config.php` to match your new database credentials (DB_NAME, DB_USER, etc.).

## 🛠️ Quick Tools (In this Folder)
- `repair_astra_options.php` - Fixes "Array/String" crashes in Astra.
- `nuclear_reset_astra_options.php` - Wipes Astra settings if they are corrupted.
- `db_export.php` - Generates a new database backup.
