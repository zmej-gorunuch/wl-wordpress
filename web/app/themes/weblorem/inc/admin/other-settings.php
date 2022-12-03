<?php
/**
 * Functions Other which enhance the administrative part of the theme by hooking into WordPress
 *
 * @package Weblorem
 */

/**
 * Default values for the Other Item Options.
 */
function theme_default_other_options()
{
    $defaults = array(
        'remove_archive'    => '',
        'disable_gutenberg' => '',
        'enable_emojis'     => '',
    );

    return apply_filters('theme_default_other_options', $defaults);
}

/**
 * Initializes the theme's other options page.
 */
add_action('admin_init', 'initialize_theme_other_options');
function initialize_theme_other_options()
{
    if (false == get_option('theme_other_options')) {
        add_option('theme_other_options', apply_filters('theme_default_other_options', theme_default_other_options()));
    }

    add_settings_section(
        'other_settings_section',
        false,
        'other_section_description_callback',
        'theme_other_options'
    );

    add_settings_field(
        'remove_archive',
        __('Remove archives', THEME_DOMAIN),
        'checkbox_remove_archive_callback',
        'theme_other_options',
        'other_settings_section',
        array(
            __('Archive pages will be disabled and all links will be redirected to the main page', THEME_DOMAIN),
        )
    );

    add_settings_field(
        'disable_gutenberg',
        __('Disable Gutenberg', THEME_DOMAIN),
        'checkbox_disable_gutenberg_callback',
        'theme_other_options',
        'other_settings_section',
        array(
            __('Replace the Gutenberg editor with the classic WordPress editor', THEME_DOMAIN),
        )
    );

    add_settings_field(
        'enable_emojis',
        __('Enable emojis', THEME_DOMAIN),
        'checkbox_enable_emojis_callback',
        'theme_other_options',
        'other_settings_section',
        array(
            __('Enable Emojis in WordPress theme', THEME_DOMAIN),
        )
    );

    register_setting(
        'theme_other_options',
        'theme_other_options'
    );
}

/**
 * Simple description for the Other Options page.
 */
function other_section_description_callback()
{
    echo '<p>' . __('Select which theme settings to make it as easy as possible to configure it to suit your needs.', THEME_DOMAIN) . '</p><hr>';
}

/**
 * Other options input fields.
 */
function checkbox_remove_archive_callback($args)
{
    $options = get_option('theme_other_options');

    $html = '<input type="checkbox" id="remove_archive" name="theme_other_options[remove_archive]" value="1" ' . checked(1, isset($options['remove_archive']) ? $options['remove_archive'] : 0, false) . '/>';
    $html .= '<label for="remove_archive">&nbsp;' . $args[0] . '</label>';

    echo $html;
}

function checkbox_disable_gutenberg_callback($args)
{
    $options = get_option('theme_other_options');

    $html = '<input type="checkbox" id="disable_gutenberg" name="theme_other_options[disable_gutenberg]" value="1" ' . checked(1, isset($options['disable_gutenberg']) ? $options['disable_gutenberg'] : 0, false) . '/>';
    $html .= '<label for="disable_gutenberg">&nbsp;' . $args[0] . '</label>';

    echo $html;
}

function checkbox_enable_emojis_callback($args)
{
    $options = get_option('theme_other_options');

    $html = '<input type="checkbox" id="enable_emojis" name="theme_other_options[enable_emojis]" value="1" ' . checked(1, isset($options['enable_emojis']) ? $options['enable_emojis'] : 0, false) . '/>';
    $html .= '<label for="enable_emojis">&nbsp;' . $args[0] . '</label>';

    echo $html;
}

/** ------------------------------------------------------------------------------------------------------------------ *
 * Theme Settings Other Options functions
 * ----------------------------------------------------------------------------------------------------------------- **/

/**
 * Remove archives pages function
 *
 * @param $query
 */
add_action('parse_query', 'remove_archives');
if (! function_exists('remove_archives')) {
    function remove_archives($query)
    {
        if (! empty(get_option('theme_other_options')['remove_archive']) && ! is_admin()) {
            if (is_date() || is_category() || is_tag() || is_author() || is_post_type_archive()) {
                wp_redirect(home_url());
                exit;
            }
        }
    }
}

/**
 * Disable Gutenberg editor function
 */
add_filter('use_block_editor_for_post', 'disable_gutenberg_editor', 10, 2);
if (! function_exists('disable_gutenberg_editor')) {
    function disable_gutenberg_editor($can_edit, $post)
    {
        // Page templates array
        $page_templates = array( 'template-pages/home.php' );
        if ($post->post_type == 'page') {
            if (in_array(get_page_template_slug($post->ID), $page_templates)) {
                return false;
            }
        }
        if (! empty(get_option('theme_other_options')['disable_gutenberg'])) {
            return false;
        }

        return $can_edit;
    }
}

/**
 * Disable emoji's function
 */
add_action('init', 'enable_emojis');
if (! function_exists('enable_emojis')) {
    function enable_emojis()
    {
        if (empty(get_option('theme_other_options')['enable_emojis'])) {
            remove_action('wp_head', 'print_emoji_detection_script', 7);
            remove_action('admin_print_scripts', 'print_emoji_detection_script');
            remove_action('wp_print_styles', 'print_emoji_styles');
            remove_action('admin_print_styles', 'print_emoji_styles');
            remove_filter('the_content_feed', 'wp_staticize_emoji');
            remove_filter('comment_text_rss', 'wp_staticize_emoji');
            remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

            /**
             * Filter function used to remove the tinymce emoji plugin.
             */
            add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
            function disable_emojis_tinymce($plugins)
            {
                if (is_array($plugins)) {
                    return array_diff($plugins, array( 'wpemoji' ));
                } else {
                    return array();
                }
            }

            /**
             * Remove emoji CDN hostname from DNS prefetching hints.
             */
            add_filter('wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2);
            function disable_emojis_remove_dns_prefetch($urls, $relation_type)
            {
                if ('dns-prefetch' == $relation_type) {
                    /** This filter is documented in wp-includes/formatting.php */
                    $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');

                    $urls = array_diff($urls, array( $emoji_svg_url ));
                }

                return $urls;
            }
        }
    }
}
