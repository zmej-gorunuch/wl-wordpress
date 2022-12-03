<?php
/**
 * The template for displaying taxonomy pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WebLorem
 */

/**
 * Get current taxonomy object
 */
$current_term = get_queried_object();

/**
 * Get taxonomies list
 */
$terms_list = get_terms_list();

?>

<?php get_header(); ?>

<?php if ( have_posts() ) : ?>
	<h1><?php esc_html_e( $current_term->name ); ?></h1>
	<?php esc_html_e( $current_term->description ); ?>

	<?php
	/* Start the Loop */
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/content', get_post_type() );

	} ?>
<?php else: ?>
	<?php get_template_part( 'template-content/content', 'none' ); ?>
<?php endif; ?>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
