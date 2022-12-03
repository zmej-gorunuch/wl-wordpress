<?php
/**
 * The template for displaying tags archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Weblorem_Theme
 */

?>
<?php get_header(); ?>

<?php if (have_posts()) { ?>
	<?php
	the_archive_title('<h1>', '</h1>');
	the_archive_description();
	?>

	<?php
	/* Start the Loop */
	while (have_posts()) {
		the_post();
		get_template_part('template-parts/content', get_post_type());
	}
} else {
	get_template_part('template-parts/content', 'none');
} ?>

<?php get_sidebar(); ?>

<?php get_footer();
