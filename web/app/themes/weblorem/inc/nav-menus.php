<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Weblorem_Theme
 */

/**
 * Register menu location.
 */
add_action('after_setup_theme', 'weblorem_register_nav_menus');
if (! function_exists('weblorem_register_nav_menus')) {
	function weblorem_register_nav_menus()
	{
		register_nav_menus(
			array(
				'main-menu' => esc_html__('Main menu', THEME_DOMAIN),
			)
		);
	}
}

/**
 * Set nav menu arguments
 */
add_filter('wp_nav_menu_args', 'weblorem_wp_nav_menu_args');
if (! function_exists('weblorem_wp_nav_menu_args')) {
	function weblorem_wp_nav_menu_args($args)
	{
		include_once(THEME_DIR . '/inc/walkers/class-walker-nav-menu.php');

		// Main menu
		if ('main-menu' === $args['theme_location']) {
			$args['container']            = 'div';
			$args['container_class']      = '';
			$args['container_id']         = '';
			$args['container_aria_label'] = '';
			$args['menu_class']           = 'menu';
			$args['menu_id']              = '';
			$args['echo']                 = true;
			$args['before']               = '';
			$args['after']                = '';
			$args['link_before']          = '';
			$args['link_after']           = '';
			$args['items_wrap']           = '<ul id="%1$s" class="%2$s">%3$s</ul>';
			$args['item_spacing']         = 'preserve';
			$args['depth']                = 0;
			$args['walker']               = new Theme_Walker_Nav_Menu();
			$args['fallback_cb']          = '__return_empty_string';
		}

		return $args;
	}
}

/**
 * Set nav submenu ul classes
 */
add_filter('nav_menu_submenu_css_class', 'change_wp_nav_submenu_class', 10, 3);
if (! function_exists('change_wp_nav_submenu_class')) {
	function change_wp_nav_submenu_class($classes, $args, $depth)
	{
		// Main menu
		if ('main-menu' === $args->theme_location) {
			$classes[] = '';
		}

		return $classes;
	}
}

/**
 * Set nav menu li item classes
 */
add_filter('nav_menu_css_class', 'change_wp_nav_menu_item_class', 10, 4);
if (! function_exists('change_wp_nav_menu_item_class')) {
	function change_wp_nav_menu_item_class($classes, $item, $args, $depth)
	{
		// Main menu
		if ('main-menu' === $args->theme_location) {
			$classes[] = '';

			// Active item class
			if (in_array('current-menu-item', $classes, true)) {
				$classes[] = 'active';
			}
		}

		return $classes;
	}
}

/**
 * Set nav menu a item classes
 */
add_filter('nav_menu_link_attributes', 'change_wp_nav_menu_item_link_class', 10, 4);
if (! function_exists('change_wp_nav_menu_item_link_class')) {
	function change_wp_nav_menu_item_link_class($atts, $item, $args, $depth)
	{
		// Main menu
		if ('main-menu' === $args->theme_location) {
			$atts['class'] = '';
		}

		return $atts;
	}
}
