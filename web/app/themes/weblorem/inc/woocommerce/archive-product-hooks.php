<?php
/**
 * WooCommerce Archive product page functions
 *
 * @package Weblorem
 */

/**
 * Add custom badges function
 */
if ( ! function_exists( 'add_custom_theme_badges' ) ) {
	function add_custom_theme_badges() {
		$custom_badge = '';

		if ( has_term( 'bestsellery', 'product_cat' ) ) {
			$custom_badge = '<span class="bestseller-badge">' . esc_html__( 'Bestseller', THEME_DOMAIN ) . '</span>';
		}

		if ( has_term( 'nowosci', 'product_cat' ) ) {
			$custom_badge = '<span class="new-product-badge">' . esc_html__( 'Nowość', THEME_DOMAIN ) . '</span>';
		}

		if ( has_term( 'wyprzedaz', 'product_cat' ) ) {
			$custom_badge = '<span class="sale-product-badge">' . esc_html__( 'Wyprzedaz', THEME_DOMAIN ) . '</span>';
		}

		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );

		echo '<div class="badges">';

		woocommerce_show_product_loop_sale_flash();

		if ( $custom_badge ) {
			echo $custom_badge;
		}
		echo '</div>';
	}
}
