<?php
/**
 * Functions WooCommerce which enhance the administrative part of the theme by hooking into WordPress
 *
 * @package Weblorem
 */

/**
 * Default values for the WooCommerce Options.
 */
function theme_default_woocommerce_options() {
	$defaults = array(
		'support_gallery_zoom'     => '',
		'support_gallery_lightbox' => '',
		'support_gallery_slider'   => '',
	);

	return apply_filters( 'theme_default_woocommerce_options', $defaults );
}

/**
 * Initializes the theme's WooCommerce options page.
 */
add_action( 'admin_init', 'initialize_theme_woocommerce_options' );
function initialize_theme_woocommerce_options() {
	if ( false == get_option( 'theme_woocommerce_options' ) ) {
		add_option( 'theme_woocommerce_options', apply_filters( 'theme_default_woocommerce_options', theme_default_woocommerce_options() ) );
	}

	add_settings_section(
		'woocommerce_settings_section',
		false,
		'woocommerce_section_description_callback',
		'theme_woocommerce_options'
	);

	add_settings_field(
		'support_gallery_zoom',
		__( 'Enable product image zoom', THEME_DOMAIN ),
		'checkbox_support_gallery_zoom_callback',
		'theme_woocommerce_options',
		'woocommerce_settings_section',
		array(
			__( 'Product image zoom on mouseover for WooCommerce', THEME_DOMAIN ),
		)
	);

	add_settings_field(
		'support_gallery_lightbox',
		__( 'Enable product gallery lightbox', THEME_DOMAIN ),
		'checkbox_support_gallery_lightbox_callback',
		'theme_woocommerce_options',
		'woocommerce_settings_section',
		array(
			__( 'Display the entire product gallery in the default WooCommerce lightbox', THEME_DOMAIN ),
		)
	);

	add_settings_field(
		'support_gallery_slider',
		__( 'Enable product gallery slider', THEME_DOMAIN ),
		'checkbox_support_gallery_slider_callback',
		'theme_woocommerce_options',
		'woocommerce_settings_section',
		array(
			__( 'Product slider in WooCommerce default gallery section', THEME_DOMAIN ),
		)
	);

	register_setting(
		'theme_woocommerce_options',
		'theme_woocommerce_options'
	);

}

/**
 * Simple description for the WooCommerce Options page.
 */
function woocommerce_section_description_callback() {
	echo '<p>' . __( 'Select which WooCommerce settings to make it as easy as possible to configure it to suit your needs.', THEME_DOMAIN ) . '</p><hr>';
}

/**
 * WooCommerce options input fields.
 */
function checkbox_support_gallery_zoom_callback( $args ) {
	$options = get_option( 'theme_woocommerce_options' );

	$html = '<input type="checkbox" id="support_gallery_zoom" name="theme_woocommerce_options[support_gallery_zoom]" value="1" ' . checked( 1, isset( $options['support_gallery_zoom'] ) ? $options['support_gallery_zoom'] : 0, false ) . '/>';
	$html .= '<label for="support_gallery_zoom">&nbsp;' . $args[0] . '</label>';

	echo $html;
}

function checkbox_support_gallery_lightbox_callback( $args ) {
	$options = get_option( 'theme_woocommerce_options' );

	$html = '<input type="checkbox" id="support_gallery_lightbox" name="theme_woocommerce_options[support_gallery_lightbox]" value="1" ' . checked( 1, isset( $options['support_gallery_lightbox'] ) ? $options['support_gallery_lightbox'] : 0, false ) . '/>';
	$html .= '<label for="support_gallery_lightbox">&nbsp;' . $args[0] . '</label>';

	echo $html;

}

function checkbox_support_gallery_slider_callback( $args ) {
	$options = get_option( 'theme_woocommerce_options' );

	$html = '<input type="checkbox" id="support_gallery_slider" name="theme_woocommerce_options[support_gallery_slider]" value="1" ' . checked( 1, isset( $options['support_gallery_slider'] ) ? $options['support_gallery_slider'] : 0, false ) . '/>';
	$html .= '<label for="support_gallery_slider">&nbsp;' . $args[0] . '</label>';

	echo $html;
}

/** ------------------------------------------------------------------------------------------------------------------ *
 * Theme Settings WooCommerce functions
 * ----------------------------------------------------------------------------------------------------------------- **/

/**
 * WooCommerce settings Zoom, Lightbox, Slider in product gallery
 */
add_action( 'after_setup_theme', 'woocommerce_setup_settings' );
if ( ! function_exists( 'woocommerce_setup_settings' ) ) {
	function woocommerce_setup_settings() {
		if ( ! empty( get_option( 'theme_woocommerce_options' )['support_gallery_zoom'] ) ) {
			add_theme_support( 'wc-product-gallery-zoom' );
		}
		if ( ! empty( get_option( 'theme_woocommerce_options' )['support_gallery_lightbox'] ) ) {
			add_theme_support( 'wc-product-gallery-lightbox' );
		}
		if ( ! empty( get_option( 'theme_woocommerce_options' )['support_gallery_slider'] ) ) {
			add_theme_support( 'wc-product-gallery-slider' );
		}
	}
}
