<?php
/**
 * WooCommerce Single product page functions
 *
 * @package Weblorem
 */

/**
 * Select default first product attribute option
 */
add_filter( 'woocommerce_dropdown_variation_attribute_options_args', 'select_default_attribute_option', 10, 1 );
if ( ! function_exists( 'select_default_attribute_option' ) ) {
	function select_default_attribute_option( $args ) {
		if ( count( $args['options'] ) > 0 ) {
			$args['selected'] = $args['options'][0];
		}

		return $args;
	}
}
