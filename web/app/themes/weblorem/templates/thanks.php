<?php
/*
Template Name: Thanks Page
*/

/**
 * ACF fields
 */


/**
 * Only POST request
 */
if (isset($_POST['method']) && ( $_POST['method'] == 'post' || $_POST['method'] == 'POST' )) { ?>
	<?php get_header(); ?>

	<?php get_sidebar(); ?>

	<?php get_footer(); ?>

<?php } else {
	// Redirect to error page
	global $wp_query;
	$wp_query->set_404();
	status_header(404);
	get_template_part(404);
	exit();
}
