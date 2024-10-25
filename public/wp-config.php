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
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

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
define( 'AUTH_KEY',          '}@.h%u:xE>z?yZ:~}EBdjNT9yR>Vn{;(Y;[z_ABMvD5bk;<V|!r1JgY/=St4n_A0' );
define( 'SECURE_AUTH_KEY',   'z`({&PFEr]eflwKnAmPj-B4|,_#pmeX$ODBw_a9 p2ig!N8L[_Q3S[1:<9zd{w{U' );
define( 'LOGGED_IN_KEY',     '#XowYmlk#1&nR}Uz}7^tCKm@RRkaK;%+2vV;j3L86a_53GARNU <P=GtnjSWHz0b' );
define( 'NONCE_KEY',         'jq|@*`S`26h(G4jw62z3|Z=XjQUt.wV#Z(AdSmi|fE~$NQ5<P_@VfT_$ZO3j`cdA' );
define( 'AUTH_SALT',         'XpADI,:Q~A3YAkpmZjE2V<V%C`(,rPny!+s&)z1@B}p-ItU4l^;B1L6H$)v$JrH7' );
define( 'SECURE_AUTH_SALT',  'ym9d.kuBOmsx1Em,nEr{5t+qY,(-wkJ!z|,9mE{z3]<gNE.n`I}Z)?*dqDP1,5U2' );
define( 'LOGGED_IN_SALT',    'z%pf^F(%>c#`,-4+G?^;Z].b3GqfNP_=j[?Dw(_TwWS1`j0u(36Iy1wxT~Pqdfk@' );
define( 'NONCE_SALT',        '<S9:((,LaOqb%1)3 O:mut,B(q:0BFM&DtMH(fL{Eh9m%EBV89T:XHV*O!O:>HOC' );
define( 'WP_CACHE_KEY_SALT', 'p;g)$9Q1]Rav+:q`;{$*x|i%$7pmW&jF8?%?DbsA~T#N&fr2et%v^Jdej,{kRFl*' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */
define('CONCATENATE_SCRIPTS', false);


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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
