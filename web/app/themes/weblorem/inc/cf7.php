<?php

if ( ! defined( 'WPCF7_VERSION' ) ) {
	return;
}

/**
 * Disable plugin deactivation
 */
add_filter( 'plugin_action_links', 'disable_plugin_deactivation_cf7', 10, 2 );
if ( ! function_exists( 'disable_plugin_deactivation_cf7' ) ) {
	function disable_plugin_deactivation_cf7( $actions, $plugin_file ) {
		unset( $actions['edit'] );

		$important_plugins = array(
			'contact-form-7/wp-contact-form-7.php',
		);

		if ( in_array( $plugin_file, $important_plugins ) ) {
			unset( $actions['deactivate'] );
			$actions['info'] = '<b class="musthave_js">' . esc_html__( 'Plugin is required for the site', THEME_DOMAIN ) . '</b>';
		}

		return $actions;
	}
}

/**
 * Delete group actions: deactivate and delete
 */
add_filter( 'admin_print_footer_scripts-plugins.php', 'disable_plugin_deactivation_hide_checkbox_cf7' );
if ( ! function_exists( 'disable_plugin_deactivation_hide_checkbox_cf7' ) ) {
	function disable_plugin_deactivation_hide_checkbox_cf7( $actions ) {
		?>
        <script>
            jQuery(function ($) {
                $('.musthave_js').closest('tr').find('input[type="checkbox"]').remove();
            });
        </script>
		<?php
	}
}

/**
 * Disable auto-tagging <p> of forms
 */
add_filter( 'wpcf7_autop_or_not', '__return_false' );

/**
 * Shortcode for transferring the URL of the page to the contact form
 *
 * add a shortcode [current_page_url current_url] to the form
 */
wpcf7_add_form_tag( 'current_page_url', 'wpcf7_current_page_url_shortcode_handler', true );
if ( ! function_exists( 'wpcf7_current_page_url_shortcode_handler' ) ) {
	function wpcf7_current_page_url_shortcode_handler( $tag ) {
		if ( ! is_object( $tag ) ) {
			return '';
		}

		$name = $tag['name'];
		if ( empty( $name ) ) {
			return '';
		}

		$html = '<input type="hidden" name="' . $name . '" value="http://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"] . '" />';

		return $html;
	}
}

/**
 * CF7 redirect to thanks page
 */
add_action( 'wp_footer', 'cf7_footer_thanks_script', 30 );
function cf7_footer_thanks_script() {
	$thanks_page_url = 'Page URL'; ?>
	<script>
		document.addEventListener( 'wpcf7mailsent', function () {
			send_hidden_form( '<?php echo esc_url( $thanks_page_url ); ?>', {method: 'post'} );
		}, false );
	</script>
<?php }
