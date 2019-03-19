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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'escapist_dev' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

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
define( 'AUTH_KEY',         'R)f7N6+Yq9kBd$}ZkfQJB,n*@WxpP^o}r|uBIye<#HwG;L3gzZ|qTxa?nH}T!=3/' );
define( 'SECURE_AUTH_KEY',  ';>N3HghQ=^.~^BOo?K4rBfk0g|;@r.jr[)%Vi2AAx.}SK}rFXjX[47?YZ16Rc!5K' );
define( 'LOGGED_IN_KEY',    ',zzXA>MzYZLvKWWeB/QfxR/B29^2#=k@[4JcVV><<8&}E^yO{#L D)}798W%aER1' );
define( 'NONCE_KEY',        '`uB2cYIZO6_LUL6fCow.uNQ:^B$FJ_iI0893@N&7tqdB,7qNtycK?{eW.L[J|M &' );
define( 'AUTH_SALT',        '/Rj0l4ND NXfHfwZN2>a7KAXY?v}|fs,-20n8#p%?{E%]KMEY4?Q/eEX7vA+%{y^' );
define( 'SECURE_AUTH_SALT', '99Ul{Q9r%u,vC?2sCg5QZ4p7UY*3yaHga1G.,ia+HJ?^B@d9Fp <PU|ry8~#}SwK' );
define( 'LOGGED_IN_SALT',   '_$IsF2rw>4%d[^JDO{~:ZQ*dVh3h+A%tCST~*zv6fM!$vi~btB5(X:%L,xmx?SiQ' );
define( 'NONCE_SALT',       '-XsH$j((p!Z4ONd-Y)wV5is.-Xixr_yQ-Za0h/m1L?J_:.HBOMm$=n@weL~p7]ag' );

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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
