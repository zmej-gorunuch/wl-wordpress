<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Weblorem_Theme
 */

?>

<?php get_header(); ?>

<?php while (have_posts()) {
	the_post();
	get_template_part('template-parts/content', get_post_type());

	the_post_navigation(
		array(
			'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', THEME_DOMAIN) . '</span> <span class="nav-title">%title</span>',
			'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', THEME_DOMAIN) . '</span> <span class="nav-title">%title</span>',
		)
	);

	// If comments are open or we have at least one comment, load up the comment template.
	if (comments_open() || get_comments_number()) {
		comments_template();
	}
} ?>

<?php get_sidebar(); ?>

<?php get_footer();
