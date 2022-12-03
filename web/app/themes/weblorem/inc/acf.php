<?php
/**
 * Advanced Custom Field functions which enhance the theme by hooking into WordPress
 *
 * @package Weblorem
 */

if (! class_exists('ACF')) {
    return;
}

/**
 * Disable update notification
 */
add_filter('site_transient_update_plugins', 'disable_plugin_updates_notification_acf');
if (! function_exists('disable_plugin_updates_notification_acf')) {
    function disable_plugin_updates_notification_acf($value)
    {
        if ($value) {
            unset($value->response['advanced-custom-fields-pro/acf.php']);
        }

        return $value;
    }
}

/**
 * Disable plugin deactivation
 */
add_filter('plugin_action_links', 'disable_plugin_deactivation_acf', 10, 2);
if (! function_exists('disable_plugin_deactivation_acf')) {
    function disable_plugin_deactivation_acf($actions, $plugin_file)
    {
        unset($actions['edit']);

        $important_plugins = [
            'advanced-custom-fields-pro/acf.php',
        ];

        if (in_array($plugin_file, $important_plugins)) {
            unset($actions['deactivate']);
            $actions['info'] = '<b class="musthave_js">' . esc_html__('Plugin is required for the site', THEME_DOMAIN) . '</b>';
        }

        return $actions;
    }
}

/**
 * Delete group actions: deactivate and delete
 */
add_filter('admin_print_footer_scripts-plugins.php', 'disable_plugin_deactivation_hide_checkbox_acf');
if (! function_exists('disable_plugin_deactivation_hide_checkbox_acf')) {
    function disable_plugin_deactivation_hide_checkbox_acf($actions)
    {
        ?>
		<script>
			jQuery(function ($) {
				$('.musthave_js').closest('tr').find('input[type="checkbox"]').remove();
			});
		</script>
        <?php
    }
}

/**
 * ACF options page "Theme settings" in admin menu
 */
add_action('init', 'acf_init_options_page');
if (! function_exists('acf_init_options_page')) {
    function acf_init_options_page()
    {
        if (function_exists('acf_add_options_page')) {
            if (! function_exists('pll_the_languages')) {
                acf_add_options_sub_page(array(
                    'page_title'      => esc_html__( 'Theme Settings', THEME_DOMAIN ),
                    'menu_title'      => esc_html__( 'Edit Theme', THEME_DOMAIN),
                    'menu_slug'       => "theme-general-settings",
                    'capability'      => 'edit_posts',
                    'update_button'   => __('Update', 'acf'),
                    'updated_message' => __("Options Updated", 'acf'),
                    'parent'          => 'theme_options',
                    'position'        => 1,
                ));
            } else {
                if (pll_default_language()) {
                    $languages = wp_list_sort(pll_the_languages(
                        array(
                            'hide_if_empty' => 0,
                            'raw'           => 1
                        )
                    ), 'current_lang', 'DESC');

                    foreach ($languages as $lang) {
                        $lang_slug = $lang['slug'];
                        $lang_name = $lang['name'];

                        acf_add_options_sub_page(array(
                            'page_title'      => esc_html__('Theme Settings', THEME_DOMAIN) . ' (' . $lang_name . ')',
                            'menu_title'      => esc_html__('Edit Theme', THEME_DOMAIN) . ' (' . $lang_name . ')',
                            'menu_slug'       => "theme-general-settings-${lang_slug}",
                            'capability'      => 'edit_posts',
                            'update_button'   => __('Update', 'acf'),
                            'updated_message' => __("Options Updated", 'acf'),
                            'post_id'         => $lang_slug,
                            'parent'          => 'theme_options'
                        ));
                    }
                }
            }
        }
    }
}

/**
 * Get ACF option field with Polylang
 *
 * @param bool $selector
 *
 * @return false|mixed
 */
function get_option_field($selector = false)
{
    if ($selector) {
        if (function_exists('pll_current_language')) {
            $value = get_field($selector, pll_current_language('slug'));
        } else {
            $value = get_field($selector, 'option');
        }

        return $value;
    }

    return false;
}

/**
 * Output thumbnails in the Relationship type field from the ACF
 */
//add_filter( 'acf/fields/relationship/result', 'acf_relationship_thumbnails', 10, 4 );
function acf_relationship_thumbnails($title, $post, $field, $the_post)
{
    $image = '';
    if ($post->post_type == 'attachment') {
        $image = wp_get_attachment_image($post->ID, 'acf-thumb');
    } else {
        if ($post->post_type == 'project') {
            $image = wp_get_attachment_image_url(get_field('imgs', $post->ID)[0]);
            $image = '<img src="' . $image . '" alt="">';
        } else {
            $image = get_the_post_thumbnail($post->ID, 'acf-thumb');
        }
    }
    $title = preg_replace("/(.*\<div class=\"thumbnail\"\>)(.*)(\<\/div\>.*)/", "$1$image$3", $title);

    return $title;
}

/**
 * Custom ACF css styles
 */
add_action('acf/input/admin_footer', 'acf_admin_custom_styles');
if (! function_exists('acf_admin_custom_styles')) {
    function acf_admin_custom_styles()
    {
        ?>
		<style type="text/css">

			/* Editor */
			.acf-editor-wrap iframe {
				height: 200px !important;
				min-height: 200px;
			}

			/* Gallery */
			.acf-gallery {
				height: 230px !important;
			}

			.acf-flexible-content .layout .acf-fc-layout-handle {
				/*background-color: #00B8E4;*/
				background-color: #202428;
				color: #eee;
			}

			.imageUpload img {
				width: 75px;
			}

		</style>
        <?php
    }
}
