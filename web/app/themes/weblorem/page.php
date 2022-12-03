<?php
/**
 * The template for displaying all default pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Weblorem_Theme
 */

?>

<?php get_header(); ?>

<?php while ( have_posts() ) {
	the_post();

	// Check if page have template
	if ( ! is_page_template() ) {
		dd_console( esc_html__( 'Page is not assigned template!', THEME_DOMAIN ) );
	}

	the_title( '<h1>', '</h1>' );
	the_content();

	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
} ?>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
