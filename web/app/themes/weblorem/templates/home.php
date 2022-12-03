<?php
/*
Template Name: Home page
*/

/**
 * ACF fields
 */

?>
<?php get_header(); ?>

<?php get_template_part('template-parts/sections/section', 'custom-block', array( 'page_id' => get_the_ID() )); ?>

<?php get_sidebar(); ?>

<?php get_footer();
