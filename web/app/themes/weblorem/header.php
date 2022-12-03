<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Weblorem_Theme
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<?php
wp_body_open();
?>

<div class="page-wrap">

    <header class="site-header">

        <nav class="top-navbar" role="navigation">

            <div class="inner-wrap">

                <div class="header_social">
					<?php dynamic_sidebar('header_social'); ?>

                </div>

                <div id="navbar-top">

					<?php if (has_nav_menu( 'secondary' )) {
						wp_nav_menu( array(
							'menu_class'     => 'navbar-wpz dropdown sf-menu',
							'theme_location' => 'secondary'
						) );
					} ?>

                </div><!-- #navbar-top -->

            </div><!-- ./inner-wrap -->

        </nav><!-- .navbar -->

        <div class="clear"></div>


        <div class="inner-wrap">

            <div class="navbar-brand-wpz">

	            <?php do_action('theme_logo'); ?>

                <p class="site-description"><?php bloginfo('description')  ?></p>

            </div><!-- .navbar-brand -->

        </div>


        <nav class="main-navbar" role="navigation">

            <div class="inner-wrap">

                <div id="sb-search" class="sb-search">
					<?php get_search_form(); ?>
                </div>


                <div class="navbar-header-main">
					<?php if ( has_nav_menu( 'mobile' ) ) {
						wp_nav_menu( array(
							'container_id'   => 'menu-main-slide',
							'theme_location' => 'mobile'
						) );
					} elseif ( has_nav_menu( 'primary' ) ) {
						wp_nav_menu( array(
							'container_id'   => 'menu-main-slide',
							'theme_location' => 'primary'
						) );
					} ?>

                </div>

                <div id="navbar-main">

					<?php if ( has_nav_menu( 'primary' ) ) {
						wp_nav_menu( array(
							'menu_class'     => 'navbar-wpz dropdown sf-menu',
							'theme_location' => 'primary'
						) );
					} ?>

                </div><!-- #navbar-main -->

            </div><!-- ./inner-wrap -->

        </nav><!-- .main-navbar -->

        <div class="clear"></div>

    </header><!-- .site-header -->

    <div class="inner-wrap">
