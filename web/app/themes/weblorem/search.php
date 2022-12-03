<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Weblorem_Theme
 */

?>

<?php get_header(); ?>

<?php if ( have_posts() ) { ?>

	<h1><?php printf( esc_html__( 'Search Results for: %s', THEME_DOMAIN ), '<span>' . get_search_query() . '</span>' ); ?></h1>

	<?php
	/* Start the Loop */
	while ( have_posts() ) {
		the_post();

		the_title( sprintf( '<h2><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' );
		the_excerpt();
	}
	the_posts_navigation();
} else {
	get_template_part( 'template-parts/content', 'none' );
} ?>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
