<?php
/**
 * Theme settings page
 *
 * @package Weblorem
 */

/**
 * Load file Dashboard theme admin settings.
 */
require THEME_DIR . '/inc/admin/dashboard-settings.php';

/**
 * Load file Other theme admin settings.
 */
require THEME_DIR . '/inc/admin/other-settings.php';

/**
 * Load file WooCommerce theme admin settings.
 */
require THEME_DIR . '/inc/admin/woocommerce-settings.php';


/** ------------------------------------------------------------------------------------------------------------------ *
 * Settings Page
 * ----------------------------------------------------------------------------------------------------------------- **/

/**
 * Theme options into the 'Settings' menu 'Theme settings' submenu.
 */
add_action( 'admin_menu', 'register_theme_options_admin_menu' );
if ( ! function_exists( 'register_theme_options_admin_menu' ) ) {
	function register_theme_options_admin_menu() {
		add_menu_page(
			esc_html__('General settings', THEME_DOMAIN),
			esc_html__('Theme', THEME_DOMAIN),
			'edit_posts',
			'theme_options',
			'display_theme_options_page',
			'dashicons-align-left',
			1000
		);
		add_submenu_page(
			'theme_options',
			esc_html__('General settings', THEME_DOMAIN),
			esc_html__('Settings'),
			'manage_options',
			'theme_options'
		);
	}
}

/**
 * Renders a simple page to display for the theme menu.
 */
if ( ! function_exists( 'display_theme_options_page' ) ) {
	function display_theme_options_page( $active_tab = '' ) { ?>
		<div class="wrap">
			<h2><?php esc_attr_e( get_admin_page_title() ); ?></h2>

			<?php if ( isset( $_GET['tab'] ) ) {
				$active_tab = $_GET['tab'];
			} else if ( $active_tab == 'other_options' ) {
				$active_tab = 'other_options';
			} else if ( $active_tab == 'woocommerce_options' ) {
				$active_tab = 'woocommerce_options';
			} else {
				$active_tab = 'admin_menu_options';
			} ?>

			<h2 class="nav-tab-wrapper">
				<a href="?page=theme_options&tab=admin_menu_options"
				   class="nav-tab <?php echo $active_tab == 'admin_menu_options' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e( 'Dashboard menu', THEME_DOMAIN ); ?></a>
				<a href="?page=theme_options&tab=other_options"
				   class="nav-tab <?php echo $active_tab == 'other_options' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e( 'Other settings', THEME_DOMAIN ); ?></a>
				<a href="?page=theme_options&tab=woocommerce_options"
				   class="nav-tab <?php echo $active_tab == 'woocommerce_options' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e( 'WooCommerce settings', THEME_DOMAIN ); ?></a>
			</h2>
			<form method="post" action="options.php">

				<?php if ( $active_tab == 'admin_menu_options' ) {
					settings_fields( 'theme_admin_menu_options' );
					do_settings_sections( 'theme_admin_menu_options' );
				} elseif ( $active_tab == 'woocommerce_options' ) {
					settings_fields( 'theme_woocommerce_options' );
					do_settings_sections( 'theme_woocommerce_options' );
				} else {
					settings_fields( 'theme_other_options' );
					do_settings_sections( 'theme_other_options' );
				} ?>

				<hr>

				<?php submit_button(); ?>

			</form>
		</div>
		<?php
	}
}

/** ------------------------------------------------------------------------------------------------------------------ *
 * Setting Callbacks
 * ----------------------------------------------------------------------------------------------------------------- **/

/**
 * Function validated input options
 *
 * @param $input
 *
 * @return mixed|void
 */
function theme_validate_inputs( $input ) {
	// Create our array for storing the validated options
	$output = array();

	// Loop through each of the incoming options
	foreach ( $input as $key => $value ) {
		// Check to see if the current option has a value. If so, process it.
		if ( isset( $input[ $key ] ) ) {

			// Strip all HTML and PHP tags and properly handle quoted strings
			$output[ $key ] = strip_tags( stripslashes( $input[ $key ] ) );
		}
	}

	// Return the array processing any additional functions filtered by this action
	return apply_filters( 'theme_validate_inputs', $output, $input );
}
