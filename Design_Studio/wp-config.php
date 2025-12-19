<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'i10822544_eztl1' );

/** Database username */
define( 'DB_USER', 'i10822544_eztl1' );

/** Database password */
define( 'DB_PASSWORD', 'S.klM6NsfCYYQVZIpk048' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'V5oPSiJdGRsmS4EjGMmWxdT7TRWNNSXwypr7nWesLtMSCQv2FYIJkJ1bXpMMXle7');
define('SECURE_AUTH_KEY',  'n5mjtZiH9y8rKyNUHGFkloNkBdJrMdDr5nxkwAnHX9xV1pIgKPqXNk0NPCE41sfp');
define('LOGGED_IN_KEY',    'A8RtGU5Uw8Fp8DTlzkNX9NanJZM5tq8PETaMZkw5WDQqjrcoBbkarmiQNXi7GvUM');
define('NONCE_KEY',        'K4l7pvjOXavlodeikk9Mh6Kb8324xtLrqShWOPEP0ifdx1oejqCk5gBi7wVtsA54');
define('AUTH_SALT',        'Ykc7zkWtdoUpV6njdPdiVTlo4Np6p1Ybp0ca5JIqcDoO4qq3y6IGmc7IBfDEtunw');
define('SECURE_AUTH_SALT', 'Im2OmsjmomphKHHg1sjZW5psfAvciDPMH7x4srm6aruvlPL7SHX5kRKgsptr6u2g');
define('LOGGED_IN_SALT',   '6y2RXoyegfDIKPxWgzs7tEPHPDARubqO3JF6asQ16GVo4u8wnw97ohxeVeI5MD5q');
define('NONCE_SALT',       'cyuskuTTkDKViTBSoXQU85zaaPbGwEjbQGavU8TS0aOGU5MDarfAuqu3b215pRIS');

/**
 * Other customizations.
 */
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'n1a5_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */

/* Add any custom values between this line and the "stop editing" line. */


// --- TPO CONFIG START ---\ndefine( 'WP_MEMORY_LIMIT', '256M' );
define( 'WP_MAX_MEMORY_LIMIT', '512M' );
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
// --- TPO CONFIG END ---\n
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
