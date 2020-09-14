<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
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
define( 'DB_NAME', 'ramircapit_wp322' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'j@q.|{?|Kc0gcgoe+BF5a[-xhw9Nr.`j$xbA`{d4qk|<mDr )0SgHNa,d}E+e&h~' );
define( 'SECURE_AUTH_KEY',  's(pQcWp!/n>){6f=z(Q!=,E-))I`Dp{B(xR1SfPLf]B&GlC3^A>%8CM*z^-0)]U|' );
define( 'LOGGED_IN_KEY',    'q0LvB%Dgzy0}VWt;+=%iLF:lmMEl:LG{1Z[X)m2$<VO,?_!z|41R1/c2@x](zY_A' );
define( 'NONCE_KEY',        'x|Vl-;ibDsUFw?14?gW7ulmO8]-_8w#VWSN5AL@KZ 3hZL*`($<=010aS(K$FTHw' );
define( 'AUTH_SALT',        '%Vp~bwC6k}C]OTSd5Q7vxJ KZ2ys/TH]p;^(r?G~_w#?=.54O=c~F&57`e%Uz5LA' );
define( 'SECURE_AUTH_SALT', '_iSbuGckjdiyZ6</?*!F~B6T#:-m;.I]7B28<Lj{Bfn(GV>V:2)cF)u:[ST]G^aZ' );
define( 'LOGGED_IN_SALT',   's !:ZG$bHuv0}/1/i-Q@+6)i-5*!>FvF|loYp=M,($QR(U*pDq8u0R^_n7AokGcp' );
define( 'NONCE_SALT',       '/_2~?,OvjeLK<5!Vd%^~ZX_f6h?j` 636aD|C*0Ys1G>7Ek]LScxEA]>V9A2P,!w' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
