<?php

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

if (! is_plugin_active('autodescription/autodescription.php')) {
    return;
}

/**
 * Change Archive pages title
 */
add_filter('the_seo_framework_title_from_generation', 'add_custom_archive_title', 10, 2);
if (! function_exists('add_custom_archive_title')) {
    function add_custom_archive_title($title = '', $args = array())
    {
        if (is_post_type_archive('my_post_type_name')) {
            $title = 'My custom title';
        }

        return $title;
    }
}

/**
 * Move Yoast Meta Box to bottom.
 */
add_filter('wpseo_metabox_prio', 'yoast_to_bottom');
if (function_exists('yoast_to_bottom')) {
    function yoast_to_bottom()
    {
        return 'low';
    }
}
