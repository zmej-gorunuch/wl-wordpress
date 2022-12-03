<?php
/**
 * Functions for connecting theme styles and scripts
 *
 * @package Weblorem_Theme
 */

/**
 * Enqueue scripts and styles.
 */
add_action('wp_enqueue_scripts', 'enqueue_theme_scripts');
if (! function_exists('enqueue_theme_scripts')) {
    function enqueue_theme_scripts()
    {
        // Connection styles
        //wp_enqueue_style('wl-styles-name', minify_theme_script(THEME_ASSETS_URL . 'URL', 'css'), array(), THEME_VERSION);

        wp_enqueue_style('wp-styles', minify_theme_script(get_stylesheet_uri(), 'css'), array(), THEME_VERSION);

        // Connection WooCommerce styles
        // wp_enqueue_style( 'wl-woocommerce-styles', get_template_directory_uri() . '/woocommerce.css', array(), THEME_VERSION );

        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }

        // Connection jquery
        // wp_deregister_script( 'jquery' );
        // wp_register_script( 'jquery', 'URL', false, THEME_VERSION, true );
        // wp_enqueue_script( 'jquery' );

        // Connection scripts
        //wp_enqueue_script('wl-scripts-name', minify_theme_script(THEME_ASSETS_URL . 'URL', 'js'), array( 'jquery' ), THEME_VERSION, true);

        wp_enqueue_script('wl-custom-scripts', minify_theme_script(get_template_directory_uri() . '/wl-scripts.js', 'js'), array( 'jquery' ), THEME_VERSION, true);
        wp_localize_script(
            'wl-custom-scripts',
            'php_vars',
            array(
                'assets_url' => THEME_ASSETS_URL
            )
        );
    }
}
