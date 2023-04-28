<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'selamoo' );

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
define( 'AUTH_KEY',         'pKfbY}o!G]9;YAG`cLuNm4zY.t,+g8Uz+w{$:$/#ged%3?Q/)^h7_4rZj0gi.S_M' );
define( 'SECURE_AUTH_KEY',  'F 2!q65;._MV|B;{iI8=uzuxv]ES!Ne>JK J Jdui&%I_!?JZ~eHLCfIFap6=;f?' );
define( 'LOGGED_IN_KEY',    '1i9>(aA<l&+-VN= uaBaAkC0t?*~?|UJpH6D8Lo<r6FgNPvp<Q/U(N%NGh=k}4HN' );
define( 'NONCE_KEY',        'Z2@wkN?:k,.Eh+7: t95S;gj-_jn 0afwSOHU(+D JeS~g`,+^37Q[i9?Uw~7Pc=' );
define( 'AUTH_SALT',        'K>Quj=uMiW8jt&TaVhaM;cW_hyT@p[!n~_4q&qt5}B#5d[6#NRgaE,M$Bg|Y|QAu' );
define( 'SECURE_AUTH_SALT', 'HhM2Neex:75htNa[G;yd[lcL-9yrPF@hc.vPDQvz`~ NQ(X?[rd]!<v#hrjn&6z~' );
define( 'LOGGED_IN_SALT',   '^1Tb7SDAa{K}wXCvT%u@.xNe_tG7X}~L3( pujo-#kmC9nks$6FLlY_)v!m0xOn&' );
define( 'NONCE_SALT',       '4@1BbSG~ZF`~;=yRlYjF>f?U98>l*iP;^24%F`^m7pP/$9*AnkJf%B!h3Rq`<t4Z' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'sel_';

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

define( 'REACTIVATE_WP_RESET', true );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
