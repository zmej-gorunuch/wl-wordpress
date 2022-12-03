<?php
/**
 * Register custom taxonomies
 *
 * @package Weblorem_Theme
 */

add_action( 'init', 'create_taxonomies' );
if ( ! function_exists( 'create_taxonomies' ) ) {
	function create_taxonomies() {

		/**
		 * TAXONOMY ----------------------------------------------------------------------------------------------------
		 */
		$labels = array(
			'name'          => __( 'TAXONOMY', THEME_DOMAIN ),
			'singular_name' => __( 'TAXONOMY', THEME_DOMAIN ),
			'menu_name'     => __( 'TAXONOMY', THEME_DOMAIN ),
		);

		$rewrite = array(
			'slug'         => 'TAXONOMY_SLUG',
			'hierarchical' => true,
			'with_front'   => false,
		);

		$args = array(
			'labels'             => $labels,
			'hierarchical'       => true,
			'public'             => false,
			'show_ui'            => true,
			'query_var'          => true,
			'show_admin_column'  => true,
			'show_in_nav_menus'  => false,
			'show_tagcloud'      => true,
			'show_in_quick_edit' => true,
			'show_in_rest'       => true,
			'publicly_queryable' => false,
			'rewrite'            => $rewrite,
		);

		//register_taxonomy( 'TAXONOMY', array( 'POST_TYPE' ), $args );
	}
}

/**
 * Change default taxonomies
 */
//add_filter('register_taxonomy_args', 'change_default_taxonomies_param', 10, 2);
if (! function_exists('change_default_taxonomies_param')) {
	function change_default_taxonomies_param($args, $taxonomy)
	{
		/** TAG taxonomy **/
		if ('post_tag' === $taxonomy) {
			$args['rewrite'] = array(
				'slug'         => 'tag',
				'hierarchical' => true,
				'with_front'   => false,
			);
		}

		return $args;
	}
}

flush_rewrite_rules();
