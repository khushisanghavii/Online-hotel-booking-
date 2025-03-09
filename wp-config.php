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
define( 'DB_NAME', 'mess' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         '93fEnL/O@|<<mMUEGP<}I$eH^Tdho7IKp;N!>Mf=a$q<nWD) *>M22fE:K%<|O~N' );
define( 'SECURE_AUTH_KEY',  'ST,i2rs{B8lR2%exxVn&*m=Y*f5CP}3T-R.Dpp}[.`Uv/ 8HB,uEQbl9n&s/>U@T' );
define( 'LOGGED_IN_KEY',    ']A9gkWmV,eR}dMk|(TUTZesW1mx-j RY3Ea<(7]8(h=d90R)%M{$2ZUPGJ~=ipqY' );
define( 'NONCE_KEY',        'fKoBDX+/6d^nK|W?Nem;y}M4kVc^n(!6_(436k{~m349h/{2vZW={3&<)~sHXnw(' );
define( 'AUTH_SALT',        'Sg=_X+lRMF9Z}F)n[3b_Dc_^zsI)jm$s>hl#<a[dtIg 8*_&_g.(RK<{hT5fBQq~' );
define( 'SECURE_AUTH_SALT', '23@XDrk|lc[m-uEfeJv-D5$>nKh:`;*(d(hH+`nz|d{!cdf<{@NxKfB]Ye>%dlo!' );
define( 'LOGGED_IN_SALT',   'v5/q;@hNg|%[6LlKCIjVcJ`Jnn:}N6Y0Kp~~cyf{]}sj;[lfF|@QSM+0F~-dT}S3' );
define( 'NONCE_SALT',       'x0Z5`KD@+{esUt`~p35sNz)VMzX$n?p?/(Y[+{G6/JmHvjl60e6a<y]?C_u+Bvt(' );

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
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
