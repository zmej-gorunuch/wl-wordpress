<?php
/**
 * Weblorem Theme WooCommerce functions and definitions
 *
 * @link https://woocommerce.com/
 *
 * @package Weblorem
 */

/** ------------------------------------------------------------------------------------------------------------------ *
 * Do not edit anything in this file unless you know what you're doing
 * ----------------------------------------------------------------------------------------------------------------- **/

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

/**
 * Functions for WooCommerce Archive product page custom actions and filters.
 */
require_once THEME_DIR . 'inc/woocommerce/archive-product-hooks.php';

/**
 * Functions for WooCommerce Single product page custom actions and filters.
 */
require_once THEME_DIR . 'inc/woocommerce/single-product-hooks.php';

/**
 * Functions for WooCommerce Cart page custom actions and filters.
 */
require_once THEME_DIR . 'inc/woocommerce/cart-hooks.php';

/**
 * Functions for WooCommerce Checkout page custom actions and filters.
 */
require_once THEME_DIR . 'inc/woocommerce/checkout-hooks.php';

/**
 * Functions for WooCommerce My Account page custom actions and filters.
 */
require_once THEME_DIR . 'inc/woocommerce/my-account-hooks.php';

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)
 * @link https://github.com/woocommerce/woocommerce/wiki/Declaring-WooCommerce-support-in-themes
 *
 * @return void
 */
add_action( 'after_setup_theme', 'weblorem_woocommerce_setup' );
if ( ! function_exists( 'weblorem_woocommerce_setup' ) ) {
	function weblorem_woocommerce_setup() {
		add_theme_support(
			'woocommerce',
			array(
				'thumbnail_image_width'         => 200,
				'gallery_thumbnail_image_width' => 200,
				'single_image_width'            => 499,
				'product_grid'                  => array(
					'default_rows'    => 4,
					'min_rows'        => 1,
					'default_columns' => 3,
					'min_columns'     => 1,
					'max_columns'     => 3,
				),
			)
		);
	}
}

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
//add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 *
 * @return array $args related products args.
 */
add_filter( 'woocommerce_output_related_products_args', 'weblorem_woocommerce_related_products_args' );
if ( ! function_exists( 'weblorem_woocommerce_related_products_args' ) ) {
	function weblorem_woocommerce_related_products_args( $args ) {
		$defaults = array(
			'posts_per_page' => 3,
			'columns'        => 3,
		);

		$args = wp_parse_args( $defaults, $args );

		return $args;
	}
}

/**
 * Get products attributes list
 */
if ( ! function_exists( 'get_product_attributes_list' ) ) {
	function get_product_attributes_list( $slug = 'size' ) {
		global $product;

		$attributes = null;

		if ( ! empty( $product->get_attribute( 'pa_' . $slug ) ) && $product->is_type( 'variable' ) ) {

			$attributes = $product->get_available_variations( 'objects' );

			ob_start();

			?>
			<ul class="product_card__sizes">
				<?php foreach ( $attributes as $attr ) {
					$not_in_stock = null;

					if ( $attr->stock_status == 'outofstock' ) {
						$not_in_stock = ' not-in-stock';
					} ?>

					<li class="product_card__size<?php echo $not_in_stock; ?>">
						<?php echo wc_strtoupper( $attr->attributes[ 'pa_' . $slug ] ); ?>
					</li>

				<?php } ?>
			</ul>
			<?php

			$attributes = ob_get_clean();
		}

		return $attributes;
	}
}

/**
 * Get child categories by parent category
 *
 * @retun array with categories objects
 */
add_filter( 'child_categories', 'get_child_categories' );
if ( ! function_exists( 'get_child_categories' ) ) {
	function get_child_categories( $term ) {
		if ( is_object( $term ) ) {
			$term = $term->term_id;
		}

		$main_category = get_term_by( 'id', $term, 'product_cat' );
		$cat_id        = $main_category->term_id;

		return get_categories(
			array(
				'taxonomy'   => 'product_cat',
				'parent'     => $cat_id,
				'hide_empty' => false,
			)
		);
	}
}

/**
 * Change a currency symbol
 */
add_filter('woocommerce_currency_symbol', 'change_existing_currency_symbol', 10, 2);
if ( ! function_exists( 'change_existing_currency_symbol' ) ) {
	function change_existing_currency_symbol( $currency_symbol, $currency ) {
		switch( $currency ) {
			case 'UAH': $currency_symbol = 'грн.'; break;
		}
		return $currency_symbol;
	}
}
