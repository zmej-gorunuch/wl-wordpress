<?php
/**
 * Functions which enhance the administrative part of the theme by hooking into Woocommerce
 *
 * @package Weblorem
 */

/** ------------------------------------------------------------------------------------------------------------------ *
 * Theme Woocommerce admin functions
 * ----------------------------------------------------------------------------------------------------------------- **/
/**
 * Remove virtual and downloadable products and downloads menu item in my account
 */
add_filter( 'product_type_options', 'remove_product_type_options' );
add_filter( 'woocommerce_account_menu_items', 'remove_downloads_menu_item_my_account', 999 );
if ( ! function_exists( 'remove_product_type_options' ) ) {
	/**
	 * Remove virtual and downloadable products
	 *
	 * @param $options
	 *
	 * @return mixed
	 */
	function remove_product_type_options( $options ) {
		// remove "Virtual" checkbox
		if ( isset( $options['virtual'] ) ) {
			unset( $options['virtual'] );
		}
		// remove "Downloadable" checkbox
		if ( isset( $options['downloadable'] ) ) {
			unset( $options['downloadable'] );
		}

		return $options;
	}
}
if ( ! function_exists( 'remove_downloads_menu_item_my_account' ) ) {
	/**
	 * Remove downloads menu item in my account
	 *
	 * @param $items
	 *
	 * @return mixed
	 */
	function remove_downloads_menu_item_my_account( $items ) {
		unset( $items['downloads'] );

		return $items;
	}
}

/**
 * Remove product type menu
 */
//add_filter( 'product_type_selector', 'remove_product_types' );
if ( ! function_exists( 'remove_product_types' ) ) {
	function remove_product_types( $types ) {
		unset( $types['grouped'] );
		unset( $types['external'] );
		unset( $types['variable'] );

		return $types;
	}
}

/**
 * Remove product admin tabs
 */
//add_filter( 'woocommerce_product_data_tabs', 'remove_woo_tabs', 10, 1 );
if ( ! function_exists( 'remove_woo_tabs' ) ) {
	function remove_woo_tabs( $tabs ) {
		//unset($tabs['general']);
		//unset($tabs['inventory']);
		//unset($tabs['advanced']);
		//unset( $tabs['linked_product'] );
		//unset($tabs['shipping']);
		//unset($tabs['attribute']);
		//unset($tabs['variations']);
		return ( $tabs );
	}
}

/**
 * Remove product tags
 */
//add_action( 'admin_menu', 'remove_product_tag' );
if ( ! function_exists( 'remove_product_tag' ) ) {
	function remove_product_tag() {
		remove_submenu_page( 'edit.php?post_type=product', 'edit-tags.php?taxonomy=product_tag&amp;post_type=product' );
		remove_meta_box( 'tagsdiv-product_tag', 'product', 'side' );


		function remove_product_tag_widget() {
			unregister_widget( 'remove_product_tag_widget' );
		}

		add_action( 'widgets_init', 'remove_product_tag_widget' );

		function remove_product_tag_column( $product_columns ) {
			unset( $product_columns['product_tag'] );

			return $product_columns;
		}

		add_filter( 'manage_product_posts_columns', 'remove_product_tag_column' );

		function remove_product_tag_quick_edit( $show, $taxonomy_name ) {
			if ( 'product_tag' == $taxonomy_name ) {
				$show = false;
			}

			return $show;
		}

		add_filter( 'quick_edit_show_taxonomy', 'remove_product_tag_quick_edit', 10, 2 );
	}
}

/**
 * Remove product type menu
 */
//add_filter( 'product_type_selector', 'remove_product_types' );
if ( ! function_exists( 'remove_product_types' ) ) {
	function remove_product_types( $types ) {
		unset( $types['grouped'] );
		unset( $types['external'] );
		unset( $types['variable'] );

		return $types;
	}
}

/** ------------------------------------------------------------------------------------------------------------------ *
 * Custom field
 * ----------------------------------------------------------------------------------------------------------------- **/

/**
 * Create new fields date for product
 *
 */
//add_action( 'woocommerce_product_options_advanced', 'date_setting_field', 10, 3 );
if ( ! function_exists( 'date_setting_field' ) ) {
	function date_setting_field() {

		// Composition Textarea
		echo '<div class="options_group">';
		woocommerce_wp_text_input(
			array(
				'id'            => 'course-date',
				'wrapper_class' => 'hasDatepicker',
				'label'         => __( 'Datę szkolenia', THEME_DOMAIN ),
				'description'   => 'Dodaj datę szkolenia',
				'desc_tip'      => true,
				'value'         => get_post_meta( get_the_ID(), 'course-date', true ),
			)
		);
		echo '</div>';

		// Register main datepicker jQuery plugin script
		add_action( 'wp_enqueue_scripts', 'enabling_date_picker' );
		function enabling_date_picker() {
			// Only on front-end and checkout page
			if ( is_admin() || ! is_checkout() ) {
				return;
			}

			// Load the datepicker jQuery-ui plugin script
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_style( 'jquery-ui', "http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/smoothness/jquery-ui.css", '', '', false );
		}

		// Jquery: Enable the Datepicker
		?>
        <script language="javascript">
            jQuery(function ($) {
                let a = '#<?php echo 'course-date' ?>';
                $(a).datepicker({
                    dateFormat: 'yy-mm-dd', // ISO formatting date
                });
            });
        </script>
		<?php
	}
}

/**
 * Save new fields date for product
 *
 */
//add_action( 'woocommerce_process_product_meta', 'save_date_setting_field', 10, 2 );
if ( ! function_exists( 'save_date_setting_field' ) ) {
	function save_date_setting_field( $post_id ) {
		$text = $_POST['course-date'];
		if ( ! empty( $text ) ) {
			update_post_meta( $post_id, 'course-date', esc_attr( $text ) );
		}
	}
}


