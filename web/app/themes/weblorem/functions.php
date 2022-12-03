<?php
/**
 * Weblorem Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Weblorem_Theme
 */

/** ------------------------------------------------------------------------------------------------------------------ *
 * Do not edit anything in this file unless you know what you're doing
 * ----------------------------------------------------------------------------------------------------------------- **/

$wp_theme = wp_get_theme();

/**
 * Add theme name constant
 *
 * Get theme domain written in your style.css
 */
if ( ! defined( 'THEME_DOMAIN' ) ) {
	define( 'THEME_DOMAIN', $wp_theme->get( 'TextDomain' ) );
}

/**
 * Add theme version constant
 *
 * Get version written in your style.css
 */
if ( ! defined( 'THEME_VERSION' ) ) {
	define( 'THEME_VERSION', $wp_theme->get( 'Version' ) );
}

/**
 * Add theme path constant
 */
if ( ! defined( 'THEME_DIR' ) ) {
	define( 'THEME_DIR', get_stylesheet_directory() . '/' );
}

/**
 * Add theme assets url constant
 */
if ( ! defined( 'THEME_ASSETS_URL' ) ) {
	define( 'THEME_ASSETS_URL', get_template_directory_uri() . '/assets/' );
}

/**
 * Add theme url constant
 */
if ( ! defined( 'THEME_URL' ) ) {
	define( 'THEME_URL', get_template_directory_uri() . '/' );
}

/**
 * Add parent theme styles
 */
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
	$parenthandle = 'parent-style';
	$theme        = wp_get_theme();
	wp_enqueue_style( $parenthandle,
		get_template_directory_uri() . '/style.css',
		array(),
		$theme->parent()->get( 'Version' )
	);
	wp_enqueue_style( 'child-style',
		get_stylesheet_uri(),
		array( $parenthandle ),
		$theme->get( 'Version' )
	);
}

/**
 * Helper functions which enhance the theme.
 */
require_once THEME_DIR . 'inc/theme-functions.php';

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
require_once THEME_DIR . 'inc/setup-theme.php';

/**
 * Functions which enhance the theme admin by hooking into WordPress.
 */
require_once THEME_DIR . 'inc/admin/admin-functions.php';

/**
 * Functions for connecting theme styles and scripts.
 */
require_once THEME_DIR . 'inc/assets.php';

/**
 * Register custom post types.
 */
require_once THEME_DIR . 'inc/post-types.php';

/**
 * Register custom taxonomies.
 */
require_once THEME_DIR . 'inc/taxonomies.php';

/**
 * Functions which add or edit the nav_menus by hooking into WordPress.
 */
require_once THEME_DIR . 'inc/nav-menus.php';

/**
 * Functions which add or edit the sidebars by hooking into WordPress.
 */
require_once THEME_DIR . 'inc/sidebars.php';

/**
 * Functions which add or edit the pagination by hooking into WordPress.
 */
require_once THEME_DIR . 'inc/pagination.php';

/**
 * Functions which add or edit the breadcrumbs by hooking into WordPress.
 */
require_once THEME_DIR . 'inc/breadcrumbs.php';

/**
 * Ajax functions which enhance the theme.
 */
require_once THEME_DIR . 'inc/ajax.php';

/**
 * Shortcodes functions which enhance the theme.
 */
require_once THEME_DIR . 'inc/shortcodes.php';

/**
 * Load Advanced Custom Fields compatibility file.
 */
require_once THEME_DIR . 'inc/acf.php';

/**
 * Load Contact Form 7 compatibility file.
 */
require_once THEME_DIR . 'inc/cf7.php';

/**
 * Load Polylang compatibility file.
 */
require_once THEME_DIR . 'inc/polylang.php';

/**
 * Load The SEO Framework compatibility file.
 */
require_once THEME_DIR . 'inc/seo.php';

/**
 * Load send messages compatibility file.
 */
require_once THEME_DIR . 'inc/send-messages.php';

/**
 * Load WooCommerce compatibility file.
 */
require_once THEME_DIR . 'inc/woocommerce/woocommerce.php';

/**
 * Load WooCommerce compatibility admin file.
 */
require_once THEME_DIR . 'inc/admin/admin-woocommerce-functions.php';
