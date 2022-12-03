<?php

if ( ! defined( 'POLYLANG_BASENAME' ) ) {
	return;
}

/**
 * Disable update notification
 */
add_filter( 'site_transient_update_plugins', 'disable_plugin_updates_notification_polylang' );
if ( ! function_exists( 'disable_plugin_updates_notification_polylang' ) ) {
	function disable_plugin_updates_notification_polylang( $value ) {
		if ( $value ) {
			unset( $value->response['polylang-pro/polylang.php'] );
		}

		return $value;
	}
}

/**
 * Disable plugin deactivation
 */
add_filter( 'plugin_action_links', 'disable_plugin_deactivation_polylang', 10, 2 );
if ( ! function_exists( 'disable_plugin_deactivation_polylang' ) ) {
	function disable_plugin_deactivation_polylang( $actions, $plugin_file ) {
		unset( $actions['edit'] );

		$important_plugins = array(
			'polylang/polylang.php',
			'polylang-pro/polylang.php',
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
add_filter( 'admin_print_footer_scripts-plugins.php', 'disable_plugin_deactivation_hide_checkbox_polylang' );
if ( ! function_exists( 'disable_plugin_deactivation_hide_checkbox_polylang' ) ) {
	function disable_plugin_deactivation_hide_checkbox_polylang( $actions ) {
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
 * Remove admin switcher "all languages".
 */
add_filter( 'pll_admin_languages_filter', 'remove_item_polylang_admin_switcher_menu' );
if ( ! function_exists( 'remove_item_polylang_admin_switcher_menu' ) ) {
	function remove_item_polylang_admin_switcher_menu( $admin_bar_languages ) {
		unset( $admin_bar_languages[0] );

		return $admin_bar_languages;
	}
}

/**
 * Polylang custom language switcher
 */
add_action( 'the_language_switcher', 'weblorem_language_switcher', 10, 1 );
if ( ! function_exists( 'weblorem_language_switcher' ) ) {
	function weblorem_language_switcher( $mobile = false ) {
		$current_language = pll_current_language( 'name' );
		$languages        = wp_list_sort( pll_the_languages(
			array(
				'hide_if_empty' => 0,
				'raw'           => 1
			)
		), 'current_lang', 'DESC' );

		if ( ! $mobile ) {
			$menu_html = '<div class="header__language">';
			$menu_html .= '<span>' . $current_language . '</span>';
			$menu_html .= '<ul>';

			if ( $languages ) {
				foreach ( $languages as $lang ) {
					$lang_slug = $lang['slug'];
					$lang_url  = $lang['url'];
					$lang_name = $lang['name'];
					if ( ! $lang['current_lang'] ) {
						$menu_html .= '<li><a href="' . $lang_url . '">' . $lang_name . '</a></li>';
					}
				}
			}
			$menu_html .= '</ul>';
			$menu_html .= '</div>';
		} else {
			$menu_html = '<div class="header__language-mob">';
			$menu_html .= '<ul>';
			if ( $languages ) {
				foreach ( $languages as $lang ) {
					$lang_slug = $lang['slug'];
					$lang_url  = $lang['url'];
					$lang_name = $lang['name'];
					if ( ! $lang['current_lang'] ) {
						$menu_html .= '<li><a href="' . $lang_url . '">' . $lang_name . '</a></li>';
					} else {
						$menu_html .= '<li class="active"><a href="' . $lang_url . '">' . $lang_name . '</a></li>';
					}
				}
			}
			$menu_html .= '</ul>';
			$menu_html .= '</div>';
		}

		echo $menu_html;
	}
}


/** ------------------------------------------------------------------------------------------------------------------ *
 * Polylang translate string
 *
 * Example:
 * pll_register_string( null, 'Text', 'Home' );
 * ----------------------------------------------------------------------------------------------------------------- **/
//*--- Post ---*//
