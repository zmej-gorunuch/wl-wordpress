<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Weblorem_Theme
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( is_singular() ) {
		the_title( '<h1>', '</h1>' );
	} else {
		the_title( '<h2><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' );
	} ?>

</article>
