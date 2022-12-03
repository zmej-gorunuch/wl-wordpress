<?php
/**
 * Helper functions which enhance the theme
 *
 * @package Weblorem_Theme
 */

/**
 * Theme customizer logo filter
 */
add_action('theme_logo', 'customize_theme_logo', 10);
if (! function_exists('customize_theme_logo')) {
    function customize_theme_logo($class = null)
    {
        $logo = get_theme_mod('custom_logo');

        if ($class) {
            $class = array( 'class' => esc_html($class) );
        }

        $image = wp_get_attachment_image($logo, 'full', false, $class);

        if (is_front_page()) {
            $logo = $image;
        } else {
            $logo = '<a href="' . home_url() . '">' . $image . '</a>';
        }

        echo wp_kses_post($logo);
    }
}

/**
 * Phone number formatting to href
 *
 * @param $phone
 */
add_filter('phone_url', 'the_format_phone_url', 10);
if (! function_exists('the_format_phone_url')) {
    function the_format_phone_url($phone)
    {
        if (! empty($phone)) {
            return 'tel:+' . preg_replace('![^0-9]+!', '', $phone);
        } else {
            return null;
        }
    }
}

/**
 * Email address formatting to href
 *
 * @param $email
 */
add_filter('email_url', 'the_format_email_url', 10);
if (! function_exists('the_format_email_url')) {
    function the_format_email_url($email)
    {
        if (! empty($email)) {
            return 'mailto:' . $email;
        } else {
            return null;
        }
    }
}

/**
 * Returns page id by template file name
 *
 * @param string $template name of template file including .php
 */
if (! function_exists('get_page_id_by_template')) {
    function get_page_id_by_template($template)
    {
        $template = 'templates/' . $template . '.php';

        $pages = get_pages(
            array(
                'post_type'  => 'page',
                'meta_key'   => '_wp_page_template',
                'meta_value' => $template
            )
        );

        return $pages[0];
    }
}

/**
 * Get a link to a page with a template
 *
 * templates are located in the template-pages folder
 */
if (! function_exists('get_page_link_by_template')) {
    function get_page_link_by_template($template_name)
    {
        $page = get_pages(
            array(
                'meta_key'     => '_wp_page_template',
                'meta_value'   => 'templates/' . $template_name . '.php',
                'hierarchical' => 0,
            )
        );
        if (! empty($page)) {
            return get_permalink($page[0]->ID);
        }

        return null;
    }
}

/**
 * Get posts list
 */
if (! function_exists('get_query_posts')) {
    function get_query_posts($post_type)
    {
        // WP_Query arguments
        $args = array(
            'post_type'   => $post_type,
            'post_status' => array( 'publish' ),
            'nopaging'    => false,
            'order'       => 'ASC',
            'orderby'     => 'menu_order',
        );

        return new WP_Query($args);
    }
}

/**
 * Get taxonomies list
 *
 * @param string $taxonomy taxonomy name
 */
if (! function_exists('get_terms_list')) {
    function get_terms_list($taxonomy = false)
    {

        if (! $taxonomy) {
            $current_term = get_queried_object();
            $taxonomy     = $current_term->taxonomy;
        }

        return get_terms(
            [
                'taxonomy'               => $taxonomy,
                'orderby'                => 'id',
                'order'                  => 'ASC',
                'hide_empty'             => false,
                'fields'                 => 'all',
                'hierarchical'           => false,
                'parent'                 => $taxonomy->parent ? $taxonomy->parent : $taxonomy->term_id,
                'pad_counts'             => false,
                'update_term_meta_cache' => true,
            ]
        );
    }
}

/**
 * Get attachment image based on wp_get_attachment_image(), without height and width.
 */
if (! function_exists('get_attachment_image')) {
    function get_attachment_image($attachment_id = '', $size = 'thumbnail', $attr = '', $icon = false)
    {
        global $post;

        if ('' === $attachment_id) {
            if (has_post_thumbnail($post->ID)) {
                $attachment_id = get_post_thumbnail_id($post->ID);
            } else {
                return false;
            }
        }

        $html  = '';
        $image = wp_get_attachment_image_src($attachment_id, $size, $icon);
        if ($image) {
            list( $src, $width, $height ) = $image;
            if (is_array($size)) {
                $size = join('x', $size);
            }
            $attachment   = get_post($attachment_id);
            $default_attr = array(
                'src' => $src,
                'alt' => trim(wp_strip_all_tags(get_post_meta($attachment_id, '_wp_attachment_image_alt', true))),
            );
            if (empty($default_attr['alt'])) {
                $default_attr['alt'] = trim(wp_strip_all_tags($attachment->post_excerpt));
            } // If not, Use the Caption
            if (empty($default_attr['alt'])) {
                $default_attr['alt'] = trim(wp_strip_all_tags($attachment->post_title));
            } // Finally, use the title

            $attr = wp_parse_args($attr, $default_attr);
            $attr = apply_filters('wp_get_attachment_image_attributes', $attr, $attachment);
            $attr = array_map('esc_attr', $attr);
            $html = rtrim('<img ');
            foreach ($attr as $name => $value) {
                $html .= " $name=" . '"' . $value . '"';
            }
            $html .= ' />';
        }

        return $html;
    }
}

/**
 * Not show post_tag and category taxonomies in nav_menus
 */
add_filter('register_taxonomy_args', 'my_edit_bb_taxonomy_args', 10, 3);
function my_edit_bb_taxonomy_args($args, $taxonomy, $object_type)
{
    $taxonomies = array( 'post_tag', 'category' );
    if (in_array($taxonomy, $taxonomies, true)) {
        $args['show_in_nav_menus'] = false;
    }

    return $args;
}

/**
 * Show Open Graph meta tags
 */
//add_action( 'wp_head', 'open_graph', 5 );
if (! function_exists('open_graph')) {
    function open_graph()
    {
        global $post;

        if (is_single()) {
            if (has_post_thumbnail($post->ID)) {
                $img_src = wp_get_attachment_image_url(get_post_thumbnail_id($post->ID), 'medium');
            } else {
                $img_src = null;
            } ?>
			<meta property="og:title" content="<?php the_title(); ?>"/>
			<meta property="og:description" content="<?php echo esc_html(wp_trim_words(get_the_content(), 25)); ?>"/>
			<meta property="og:type" content="article"/>
			<meta property="og:url" content="<?php the_permalink(); ?>"/>
			<meta property="og:site_name" content="<?php echo esc_html(get_bloginfo()); ?>"/>
			<meta property="og:image" content="<?php echo esc_url($img_src); ?>"/>
            <?php
        } else {
            return null;
        }
    }
}

/**
 * Minify assets scripts if exist.
 */
if (! function_exists('minify_theme_script')) {
    function minify_theme_script($path, $extension)
    {
        $allow_extension = array('css', 'js');
        if ($extension && in_array($extension, $allow_extension, true)) {
            if ('production' === getenv('WP_ENV')) {
                $absolute_path        = str_replace(get_template_directory_uri(), get_theme_file_path(), $path);
                $absolute_minify_path = str_replace('.' . $extension, '.min.' . $extension, $absolute_path);

                if (file_exists($absolute_minify_path)) {
                    return str_replace('.' . $extension, '.min.' . $extension, $path);
                }
            }
        }

        return $path;
    }
}
