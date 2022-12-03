<?php
/**
 * Register custom post types and change default
 *
 * @package Weblorem_Theme
 */

add_action('init', 'create_post_type');
if (! function_exists('create_post_type')) {
	function create_post_type()
	{

		/**
		 * POST_TYPE ---------------------------------------------------------------------------------------------------
		 */
		$labels = array(
			'name'               => __('POST_TYPE', THEME_DOMAIN),
			'singular_name'      => __('POST_TYPE', THEME_DOMAIN),
			'add_new_item'       => __('Add POST_TYPE'),
			'edit_item'          => __('Edit'),
			'new_item'           => __('New Post'),
			'view_item'          => __('Preview'),
			'search_items'       => __('Search'),
			'not_found'          => __('No posts found.'),
			'not_found_in_trash' => __('No posts found in Trash.'),
			'menu_name'          => __('POST_TYPES', THEME_DOMAIN),
			'all_items'          => __('All Posts'),
			'add_new'            => __('Add'),
			'featured_image'     => _x('Featured image', 'post'),
		);

		$rewrite = array(
			'slug'         => 'POST_TYPE_SLUG',
			'hierarchical' => true,
			'with_front'   => false,
		);

		$args = array(
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			// title, editor, author, thumbnail, excerpt, trackbacks, custom-fields, comments, revisions, page-attributes
			'taxonomies'          => array(),
			'hierarchical'        => false,
			'has_archive'         => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-admin-appearance',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest'        => true,
			//'rewrite'             => $rewrite,
		);

		//register_post_type( 'POST_TYPE', $args );
	}
}

/**
 * Change default post types
 */
add_filter('register_post_type_args', 'change_default_post_type_post_param', 0, 2);
if (! function_exists('change_default_post_type_post_param')) {
	function change_default_post_type_post_param($args, $post_type)
	{
		/** POST post_type **/
		if ('post' === $post_type) {
			$args['menu_icon'] = 'dashicons-welcome-write-blog';
		}

		/** PAGE post_type **/
		if ('page' === $post_type) {
			// Remove editor in page with template
			add_action('admin_enqueue_scripts', 'remove_editor_from_pages');
			function remove_editor_from_pages()
			{
				global $post;
				if (isset($post->ID)) {
					$current_page_template_slug = basename(get_page_template_slug($post->ID));
					if (! empty($current_page_template_slug)) {
						remove_post_type_support('page', 'editor');
					}
				}
			}

			// Remove thumbnail in page with template
			add_action('init', 'remove_page_thumbnail_support');
			function remove_page_thumbnail_support()
			{
				remove_post_type_support('page', 'thumbnail');
				remove_post_type_support('page', 'excerpt');
			}
		}

		/** Remove taxonomies from post type "POST" **/
		add_action('init', 'unregister_taxonomies', 99);
		if (! function_exists('unregister_taxonomies')) {
			function unregister_taxonomies()
			{
				// Editor
				//remove_post_type_support( 'post', 'editor' );
				// Tags
				//unregister_taxonomy_for_object_type( 'post_tag', 'post' );
				// Category
				//unregister_taxonomy_for_object_type( 'category', 'post' );
				// Comments
				//remove_post_type_support( 'post', 'comments' );
				// Authors
				//remove_post_type_support( 'post', 'author' );
			}
		}

		return $args;
	}
}

flush_rewrite_rules();
