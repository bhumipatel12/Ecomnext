<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'Ecomnext' );

/** MySQL database username */
define( 'DB_USER', 'Ecomnext' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Krishiv10721#' );

/** MySQL hostname */
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
define( 'AUTH_KEY',         'So7}h})9BBN`=)y6=|}U(3tW7v+-BB_V<^WGv2%rV+`uU=0DaFE&?/nea,#8C*4.' );
define( 'SECURE_AUTH_KEY',  '.cR#%mga}X|zMkhP3;eaj3U3SxIhhXfSxI+w@m~c)A8TEopO{#>}6a(-w-+DW07D' );
define( 'LOGGED_IN_KEY',    'E>j}KsCvb[qh9uEbOh):9/-unf{8!nT55LhL?{Wi3(&|`z^o,f>1/(+yMquZx>Z:' );
define( 'NONCE_KEY',        '+91*xp:R[yB5aO;!)r19JVgtC2RB7 xhB]ZQv~i|<nd{xLt9LL~&yc,xmsy4#L:i' );
define( 'AUTH_SALT',        'M`)maI_Qdd)2Mz0VGh%UTiUOO_T9qJ3QA0auYhM|HembW#G5rG_bdznO{`xX}f)v' );
define( 'SECURE_AUTH_SALT', 'lfXyXB}qA.(kSMN^pd Qs4!p>KhyWyghB;<%=Zq?!^kF,/<K%?pZng<@EA4NKeSV' );
define( 'LOGGED_IN_SALT',   'Vl,A@Mjh<(hZ)/0a);rtTb0I`:d0-Elj#VjU:kS1z8:6z;d%FL>KwC?Hxug`9iej' );
define( 'NONCE_SALT',       '3)1U-DFK]b%(x{}lB$y3LtpAu#[Z_-EKzFY1hG/GPPL&Mwy S8Ixn#gxm`;))F,.' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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
