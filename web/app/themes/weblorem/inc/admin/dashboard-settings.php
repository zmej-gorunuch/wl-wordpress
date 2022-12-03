<?php
/**
 * Functions Dashboards which enhance the administrative part of the theme by hooking into WordPress
 *
 * @package Weblorem
 */

/**
 * Default values for the Menu Item Options.
 */
function theme_default_admin_menus_options() {
	$defaults = array(
		'dashboard_hide'       => '',
		'posts_hide'           => '',
		'media_hide'           => '',
		'pages_hide'           => '',
		'comments_hide'        => '',
		'themes_hide'          => '',
		'plugins_hide'         => '',
		'users_hide'           => '',
		'tools_hide'           => '',
		'options_general_hide' => '',
		'acf_plugin_hide'      => '',
		'cf7_plugin_hide'      => '',
		'polylang_plugin_hide' => '',
	);

	return apply_filters( 'theme_default_admin_menus_options', $defaults );
}

/**
 * Initializes the theme's admin menu options page.
 */
add_action( 'admin_init', 'initialize_theme_admin_menu_options' );
function initialize_theme_admin_menu_options() {
	if ( false == get_option( 'theme_admin_menu_options' ) ) {
		add_option( 'theme_admin_menu_options', apply_filters( 'theme_default_admin_menus_options', theme_default_admin_menus_options() ) );
	}

	add_settings_section(
		'admin_menu_settings_section', // ID used to identify this section and with which to register options
		false, // Title to be displayed on the administration page
		'admin_menu_section_description_callback', // Callback used to render the description of the section
		'theme_admin_menu_options' // Page on which to add this section of options
	);

	add_settings_field(
		'dashboard_hide',
		__( 'Dashboard' ),
		'input_dashboard_hide_callback',
		'theme_admin_menu_options',
		'admin_menu_settings_section',
		array(
			__( 'Hide menu item', THEME_DOMAIN ) . ' ' . __( 'Dashboard' ),
		)
	);

	add_settings_field(
		'posts_hide',
		__( 'Posts' ),
		'input_posts_hide_callback',
		'theme_admin_menu_options',
		'admin_menu_settings_section',
		array(
			__( 'Hide menu item', THEME_DOMAIN ) . ' ' . __( 'Posts' ),
		)
	);

	add_settings_field(
		'media_hide',
		__( 'Media' ),
		'input_media_hide_callback',
		'theme_admin_menu_options',
		'admin_menu_settings_section',
		array(
			__( 'Hide menu item', THEME_DOMAIN ) . ' ' . __( 'Media' ),
		)
	);

	add_settings_field(
		'pages_hide',
		__( 'Pages' ),
		'input_pages_hide_callback',
		'theme_admin_menu_options',
		'admin_menu_settings_section',
		array(
			__( 'Hide menu item', THEME_DOMAIN ) . ' ' . __( 'Pages' ),
		)
	);

	add_settings_field(
		'comments_hide',
		__( 'Comments' ),
		'input_comments_hide_callback',
		'theme_admin_menu_options',
		'admin_menu_settings_section',
		array(
			__( 'Hide menu item', THEME_DOMAIN ) . ' ' . __( 'Comments' ),
		)
	);

	add_settings_field(
		'themes_hide',
		__( 'Appearance' ),
		'input_themes_hide_callback',
		'theme_admin_menu_options',
		'admin_menu_settings_section',
		array(
			__( 'Hide menu item', THEME_DOMAIN ) . ' ' . __( 'Appearance' ),
		)
	);

	add_settings_field(
		'plugins_hide',
		__( 'Plugins' ),
		'input_plugins_hide_callback',
		'theme_admin_menu_options',
		'admin_menu_settings_section',
		array(
			__( 'Hide menu item', THEME_DOMAIN ) . ' ' . __( 'Plugins' ),
		)
	);

	add_settings_field(
		'users_hide',
		__( 'Users' ),
		'input_users_hide_callback',
		'theme_admin_menu_options',
		'admin_menu_settings_section',
		array(
			__( 'Hide menu item', THEME_DOMAIN ) . ' ' . __( 'Users' ),
		)
	);

	add_settings_field(
		'tools_hide',
		__( 'Tools' ),
		'input_tools_hide_callback',
		'theme_admin_menu_options',
		'admin_menu_settings_section',
		array(
			__( 'Hide menu item', THEME_DOMAIN ) . ' ' . __( 'Tools' ),
		)
	);

	add_settings_field(
		'options_general_hide',
		__( 'Settings' ),
		'input_options_general_hide_callback',
		'theme_admin_menu_options',
		'admin_menu_settings_section',
		array(
			__( 'Hide menu item', THEME_DOMAIN ) . ' ' . __( 'Settings' ),
		)
	);

	if ( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) || is_plugin_active( 'advanced-custom-fields/acf.php' ) ) {
		add_settings_field(
			'acf_plugin_hide',
			__( 'Advanced Custom Fields' ),
			'input_acf_plugin_hide_callback',
			'theme_admin_menu_options',
			'admin_menu_settings_section',
			array(
				__( 'Hide menu item', THEME_DOMAIN ) . ' ' . __( 'Advanced Custom Fields' ),
			)
		);
	}

	if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) || is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
		add_settings_field(
			'cf7_plugin_hide',
			__( 'Contact Form 7' ),
			'input_cf7_plugin_hide_callback',
			'theme_admin_menu_options',
			'admin_menu_settings_section',
			array(
				__( 'Hide menu item', THEME_DOMAIN ) . ' ' . __( 'Contact Form 7' ),
			)
		);
	}

	if ( is_plugin_active( 'polylang-pro/polylang.php' ) || is_plugin_active( 'polylang/polylang.php' ) ) {
		add_settings_field(
			'polylang_plugin_hide',
			__( 'Polylang' ),
			'input_polylang_plugin_hide_callback',
			'theme_admin_menu_options',
			'admin_menu_settings_section',
			array(
				__( 'Hide menu item', THEME_DOMAIN ) . ' ' . __( 'Polylang' ),
			)
		);
	}

	register_setting(
		'theme_admin_menu_options',
		'theme_admin_menu_options'
	);
}

/**
 * Simple description for the Menu Options page.
 */
function admin_menu_section_description_callback() {
	echo '<p>' . __( 'Select which menu items you wish to hide in the WordPress dashboard menu.', THEME_DOMAIN ) . '</p><hr>';
}

/**
 * Menu options input fields.
 */
function input_dashboard_hide_callback( $args ) {
	$options = get_option( 'theme_admin_menu_options' );

	$html = '<input type="checkbox" id="dashboard_hide" name="theme_admin_menu_options[dashboard_hide]" value="1" ' . checked( 1, isset( $options['dashboard_hide'] ) ? $options['dashboard_hide'] : 0, false ) . '/>';
	$html .= '<label for="dashboard_hide">&nbsp;' . $args[0] . '</label>';

	echo $html;
}

function input_posts_hide_callback( $args ) {
	$options = get_option( 'theme_admin_menu_options' );

	$html = '<input type="checkbox" id="posts_hide" name="theme_admin_menu_options[posts_hide]" value="1" ' . checked( 1, isset( $options['posts_hide'] ) ? $options['posts_hide'] : 0, false ) . '/>';
	$html .= '<label for="posts_hide">&nbsp;' . $args[0] . '</label>';

	echo $html;
}

function input_media_hide_callback( $args ) {
	$options = get_option( 'theme_admin_menu_options' );

	$html = '<input type="checkbox" id="media_hide" name="theme_admin_menu_options[media_hide]" value="1" ' . checked( 1, isset( $options['media_hide'] ) ? $options['media_hide'] : 0, false ) . '/>';
	$html .= '<label for="media_hide">&nbsp;' . $args[0] . '</label>';

	echo $html;
}

function input_pages_hide_callback( $args ) {
	$options = get_option( 'theme_admin_menu_options' );

	$html = '<input type="checkbox" id="pages_hide" name="theme_admin_menu_options[pages_hide]" value="1" ' . checked( 1, isset( $options['pages_hide'] ) ? $options['pages_hide'] : 0, false ) . '/>';
	$html .= '<label for="pages_hide">&nbsp;' . $args[0] . '</label>';

	echo $html;
}

function input_comments_hide_callback( $args ) {
	$options = get_option( 'theme_admin_menu_options' );

	$html = '<input type="checkbox" id="comments_hide" name="theme_admin_menu_options[comments_hide]" value="1" ' . checked( 1, isset( $options['comments_hide'] ) ? $options['comments_hide'] : 0, false ) . '/>';
	$html .= '<label for="comments_hide">&nbsp;' . $args[0] . '</label>';

	echo $html;
}

function input_themes_hide_callback( $args ) {
	$options = get_option( 'theme_admin_menu_options' );

	$html = '<input type="checkbox" id="themes_hide" name="theme_admin_menu_options[themes_hide]" value="1" ' . checked( 1, isset( $options['themes_hide'] ) ? $options['themes_hide'] : 0, false ) . '/>';
	$html .= '<label for="themes_hide">&nbsp;' . $args[0] . '</label>';

	echo $html;
}

function input_plugins_hide_callback( $args ) {
	$options = get_option( 'theme_admin_menu_options' );

	$html = '<input type="checkbox" id="plugins_hide" name="theme_admin_menu_options[plugins_hide]" value="1" ' . checked( 1, isset( $options['plugins_hide'] ) ? $options['plugins_hide'] : 0, false ) . '/>';
	$html .= '<label for="plugins_hide">&nbsp;' . $args[0] . '</label>';

	echo $html;
}

function input_users_hide_callback( $args ) {
	$options = get_option( 'theme_admin_menu_options' );

	$html = '<input type="checkbox" id="users_hide" name="theme_admin_menu_options[users_hide]" value="1" ' . checked( 1, isset( $options['users_hide'] ) ? $options['users_hide'] : 0, false ) . '/>';
	$html .= '<label for="users_hide">&nbsp;' . $args[0] . '</label>';

	echo $html;
}

function input_tools_hide_callback( $args ) {
	$options = get_option( 'theme_admin_menu_options' );

	$html = '<input type="checkbox" id="tools_hide" name="theme_admin_menu_options[tools_hide]" value="1" ' . checked( 1, isset( $options['tools_hide'] ) ? $options['tools_hide'] : 0, false ) . '/>';
	$html .= '<label for="tools_hide">&nbsp;' . $args[0] . '</label>';

	echo $html;
}

function input_options_general_hide_callback( $args ) {
	$options = get_option( 'theme_admin_menu_options' );

	$html = '<input type="checkbox" id="options_general_hide" name="theme_admin_menu_options[options_general_hide]" value="1" ' . checked( 1, isset( $options['options_general_hide'] ) ? $options['options_general_hide'] : 0, false ) . '/>';
	$html .= '<label for="options_general_hide">&nbsp;' . $args[0] . '</label>';

	echo $html;
}

function input_acf_plugin_hide_callback( $args ) {
	$options = get_option( 'theme_admin_menu_options' );

	$html = '<input type="checkbox" id="acf_plugin_hide" name="theme_admin_menu_options[acf_plugin_hide]" value="1" ' . checked( 1, isset( $options['acf_plugin_hide'] ) ? $options['acf_plugin_hide'] : 0, false ) . '/>';
	$html .= '<label for="acf_plugin_hide">&nbsp;' . $args[0] . '</label>';

	echo $html;
}

function input_cf7_plugin_hide_callback( $args ) {
	$options = get_option( 'theme_admin_menu_options' );

	$html = '<input type="checkbox" id="cf7_plugin_hide" name="theme_admin_menu_options[cf7_plugin_hide]" value="1" ' . checked( 1, isset( $options['cf7_plugin_hide'] ) ? $options['cf7_plugin_hide'] : 0, false ) . '/>';
	$html .= '<label for="cf7_plugin_hide">&nbsp;' . $args[0] . '</label>';

	echo $html;
}

function input_polylang_plugin_hide_callback( $args ) {
	$options = get_option( 'theme_admin_menu_options' );

	$html = '<input type="checkbox" id="polylang_plugin_hide" name="theme_admin_menu_options[polylang_plugin_hide]" value="1" ' . checked( 1, isset( $options['polylang_plugin_hide'] ) ? $options['polylang_plugin_hide'] : 0, false ) . '/>';
	$html .= '<label for="polylang_plugin_hide">&nbsp;' . $args[0] . '</label>';

	echo $html;
}

/** ------------------------------------------------------------------------------------------------------------------ *
 * Theme Settings Dashboard functions
 * ----------------------------------------------------------------------------------------------------------------- **/

/**
 * Disable menu items function
 */
add_action( 'admin_menu', 'disable_admin_menu_items' );
if ( ! function_exists( 'disable_admin_menu_items' ) ) {
	function disable_admin_menu_items() {
		if ( ! empty( get_option( 'theme_admin_menu_options' )['dashboard_hide'] ) ) {
			remove_menu_page( 'index.php' ); // Майстерня
		}
		if ( ! empty( get_option( 'theme_admin_menu_options' )['posts_hide'] ) ) {
			remove_menu_page( 'edit.php' ); // Пости
			add_action(
				'wp_before_admin_bar_render', function () {
				global $wp_admin_bar;
				$wp_admin_bar->remove_menu( 'new-post' );
			}
			);
		}
		if ( ! empty( get_option( 'theme_admin_menu_options' )['media_hide'] ) ) {
			remove_menu_page( 'upload.php' ); // Медіафайли
			add_action(
				'wp_before_admin_bar_render', function () {
				global $wp_admin_bar;
				$wp_admin_bar->remove_menu( 'new-media' );
			}
			);
		}
		if ( ! empty( get_option( 'theme_admin_menu_options' )['pages_hide'] ) ) {
			remove_menu_page( 'edit.php?post_type=page' ); // Сторінки
			add_action(
				'wp_before_admin_bar_render', function () {
				global $wp_admin_bar;
				$wp_admin_bar->remove_menu( 'new-page' );
			}
			);
		}
		if ( ! empty( get_option( 'theme_admin_menu_options' )['comments_hide'] ) ) {
			remove_menu_page( 'edit-comments.php' ); // Коментарі
			add_action(
				'wp_before_admin_bar_render', function () {
				global $wp_admin_bar;
				$wp_admin_bar->remove_menu( 'comments' );
			}
			);
		}
		if ( ! empty( get_option( 'theme_admin_menu_options' )['themes_hide'] ) ) {
			remove_menu_page( 'themes.php' ); // Вигляд
		}
		if ( ! empty( get_option( 'theme_admin_menu_options' )['plugins_hide'] ) ) {
			remove_menu_page( 'plugins.php' ); // Плагіни
		}
		if ( ! empty( get_option( 'theme_admin_menu_options' )['users_hide'] ) ) {
			remove_menu_page( 'users.php' ); // Користувачі
			add_action(
				'wp_before_admin_bar_render', function () {
				global $wp_admin_bar;
				$wp_admin_bar->remove_menu( 'new-user' );
			}
			);
		}
		if ( ! empty( get_option( 'theme_admin_menu_options' )['tools_hide'] ) ) {
			remove_menu_page( 'tools.php' ); // Інструменти
		}
		if ( ! empty( get_option( 'theme_admin_menu_options' )['options_general_hide'] ) ) {
			remove_menu_page( 'options-general.php' ); // Налаштування
		}
		if ( ! empty( get_option( 'theme_admin_menu_options' )['acf_plugin_hide'] ) ) {
			remove_menu_page( 'edit.php?post_type=acf-field-group' ); // Додаткові поля ACF
		}
		if ( ! empty( get_option( 'theme_admin_menu_options' )['cf7_plugin_hide'] ) ) {
			remove_menu_page( 'wpcf7' );   // Contact form 7
		}
		if ( ! empty( get_option( 'theme_admin_menu_options' )['polylang_plugin_hide'] ) ) {
			remove_menu_page( 'mlang' );   // Polylang
		}
	}
}
