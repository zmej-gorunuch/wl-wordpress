<?php
/**
 * Functions which enhance the administrative part of the theme by hooking into WordPress
 *
 * @package Weblorem
 */

/** ------------------------------------------------------------------------------------------------------------------ *
 * Theme Admin Page functions
 * ----------------------------------------------------------------------------------------------------------------- **/

/**
 * Load file theme admin settings page.
 */
require THEME_DIR . '/inc/admin/settings-page.php';

/**
 * Function debug code.
 *
 * @param $value
 * @param bool $exit
 */
if (! function_exists('dd')) {
    function dd($data, $exit = true, $print_r = false)
    {
        if (! current_user_can('administrator')) {
            return;
        }
        echo '<pre style="position:relative;z-index:1000000;background-color:#425364;color:white;padding:10px">';
        if ($print_r) {
            print_r($data);
        } else {
            var_dump($data);
        }
        echo '</pre>';
        if ($exit) {
            exit();
        }
    }
}

/**
 * Function show data in console.
 *
 * @param $value
 * @param bool $exit
 */
if (! function_exists('dd_console')) {
    function dd_console($data)
    {
        if (! current_user_can('administrator')) {
            return;
        }
        $output = $data;
        if (is_array($output)) {
            $output = implode(',', $output);
        }

        echo "<script>console.log('Theme Debug: " . $output . "' );</script>";
    }
}

/**
 * Set revision limit.
 */
add_filter('wp_revisions_to_keep', function () {
    return 3;
});

/**
 * Applied a limit of "3" post revisions.
 */
if (! defined('WP_POST_REVISIONS')) {
    define('WP_POST_REVISIONS', 3);
}

/**
 * Own logo in the admin panel.
 */
if (has_custom_logo()) {
    if (! function_exists('change_admin_logo')) {
        function change_admin_logo()
        {
            $custom_logo_url = wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'medium'); ?>
			<style type="text/css">
				.login h1 a {
					background: url("<?php echo $custom_logo_url; ?>") center /contain no-repeat !important;
					max-width: 300px !important;
					width: 300px !important;
				}
			</style>
        <?php }
    }
    add_action('login_enqueue_scripts', 'change_admin_logo');

    if (! function_exists('change_admin_logo_url')) {
        function change_admin_logo_url()
        {
            return home_url('/');
        }
    }
    add_filter('login_headerurl', 'change_admin_logo_url');
}

/**
 * Disable admin bar items.
 */
add_action('wp_before_admin_bar_render', 'disable_admin_bar_items');
if (! function_exists('disable_admin_bar_items')) {
    function disable_admin_bar_items()
    {
        global $wp_admin_bar;

        // Disable items
        $wp_admin_bar->remove_menu('about');
        $wp_admin_bar->remove_menu('wporg');
        $wp_admin_bar->remove_menu('support-forums');
        $wp_admin_bar->remove_menu('feedback');
    }
}

/**
 * Create menu pages.
 */
add_action('admin_menu', 'create_menu_pages');
if (! function_exists('create_menu_pages')) {
    function create_menu_pages()
    {
        add_menu_page(
            esc_html__('Menu'),
            esc_html__('Menu'),
            'edit_themes',
            'nav-menus.php',
            '',
            'dashicons-menu-alt',
            59
        );
    }
}

/**
 * Disable widgets on the admin page (Dashboard).
 */
add_action('wp_dashboard_setup', 'clear_dashboard', 99);
if (! function_exists('clear_dashboard')) {
    function clear_dashboard()
    {
        $side   = &$GLOBALS['wp_meta_boxes']['dashboard']['side']['core'];
        $normal = &$GLOBALS['wp_meta_boxes']['dashboard']['normal']['core'];

        $remove = [
            'dashboard_primary',
            'dashboard_quick_press',
        ];
        foreach ($remove as $id) {
            unset($side[ $id ], $normal[ $id ]);
        }

        remove_action('welcome_panel', 'wp_welcome_panel');
    }
}

/**
 * Add a widget to the dashboard.
 *
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */
function wporg_add_dashboard_widgets()
{
    wp_add_dashboard_widget(
        'wporg_dashboard_widget',                          // Widget slug.
        esc_html__('Example Dashboard Widget', 'wporg'), // Title.
        'wporg_dashboard_widget_render'                    // Display function.
    );
}
add_action('wp_dashboard_setup', 'wporg_add_dashboard_widgets');

/**
 * Create the function to output the content of our Dashboard Widget.
 */
function wporg_dashboard_widget_render()
{
    ?>
	<p style="text-align: center">
		<span class="dashicons dashicons-groups" style="font-size: 50px;width:100%;height: 100%"></span>
		<span class="update-plugins count-1"><span class="update-count">1</span></span>
		<span style="font-size: 20px">(4)</span>
	</p>

	<ul>
		<li class="post-count">
			<span class="dashicons dashicons-groups" style="font-size: 50px;width:100px;height: 100%"></span> - 1 запис
		</li>
	</ul>


	<table class="form-table">
		<tr valign="top">
			<td scope="row">
				<label for="tablecell">
					<span class="dashicons dashicons-groups" style="font-size: 50px;width:100%;height: 100%"></span>
				</label>
			</td>
			<td>
				<span class="update-plugins count-1"><span class="update-count">1</span></span>
			</td>
		</tr>
	</table>

	<br class="clear" />

    <?php
}

/**
 * Adding custom post type counts in 'Right now' Dashboard widget.
 */
add_action('dashboard_glance_items', 'custom_post_type_glance_items');
if (! function_exists('custom_post_type_glance_items')) {
    function custom_post_type_glance_items()
    {
        $glances = array();

        $args = array(
            'public'   => true,  // Showing public post types only
            '_builtin' => false,  // Except the build-in wp post types (page, post, attachments)
        );

        // Getting your custom post types
        $post_types = get_post_types($args, 'object', 'and');

        foreach ($post_types as $post_type) {
            $num_posts = wp_count_posts($post_type->name);
            $num       = number_format_i18n($num_posts->publish);
            $text      = _n($post_type->labels->singular_name, $post_type->labels->name, intval($num_posts->publish));

            if (current_user_can('edit_posts')) {
                $glance = '<a class="' . $post_type->name . '-count" href="' . admin_url('edit.php?post_type=' . $post_type->name) . '">' . $num . ' ' . $text . '</a>';
            } else {
                $glance = '<span class="' . $post_type->name . '-count">' . $num . ' ' . $text . '</span>';
            }

            $glances[] = $glance;
        }

        return $glances;
    }
}

/**
 * Change copyright text in the footer admin panel.
 */
add_filter('admin_footer_text', 'change_admin_footer_copyright_text');
if (! function_exists('change_admin_footer_copyright_text')) {
    function change_admin_footer_copyright_text()
    {
        echo sprintf(__('Theme developer: %1$s. Powered by %2$s.', THEME_DOMAIN), '<a href="http://weblorem.com" target="_blank">WebLorem</a>', '<a href="http://wordpress.org" target="_blank">WordPress</a>');
    }
}

/**
 * Additional column with the image in the admin panel.
 */
//add_action( 'admin_init', 'add_thumbnail_image_column' );
if (! function_exists('add_thumbnail_image_column')) {
    function add_thumbnail_image_column()
    {
        $post_type = 'project';
        // Add new column
        add_filter('manage_' . $post_type . '_posts_columns', function ($columns) {
            $new_columns = [];
            $i           = 0;
            foreach ($columns as $key => $value) {
                if ($i == 1) {
                    $new_columns['post_thumbs'] = __('Image');
                }
                $new_columns[ $key ] = $value;
                $i ++;
            }

            return $new_columns;
        });
        // Add image
        add_action('manage_' . $post_type . '_posts_custom_column', function ($column_name, $id) {
            if ($column_name === 'post_thumbs') {
                $image_url = wp_get_attachment_image_url(get_field('imgs', $id)[0], 'thumbnail');
                echo '<img src="' . $image_url . '" height=40 />';
            }
        }, 10, 2);
        //Add column style
        add_action('admin_head', function () {
            echo '<style type="text/css">#post_thumbs{text-align: center}.column-post_thumbs{text-align:center;width:7%!important;overflow:hidden}</style>';
        });
    }
}

/**
 * Change checkbox to radio in taxonomies.
 *
 * allows the selection of only one taxonomy
 */
//add_action( 'admin_print_footer_scripts', 'change_checkbox_to_radio_in_taxonomies', 99 );
if (! function_exists('change_checkbox_to_radio_in_taxonomies')) {
    function change_checkbox_to_radio_in_taxonomies()
    {
        $taxonomies = [ 'category' ]; // для яких таксономій застосовувати
        $cs         = get_current_screen();
        if ($cs->base === 'post' && ( $taxonomies = array_intersect(get_object_taxonomies($cs->post_type), $taxonomies) )) {
            $taxonomy[] = implode($taxonomies);
            ?>
			<script>
                <?= json_encode($taxonomy) ?>.forEach( function (taxname) {
					jQuery( '#' + taxname + 'div input[type="checkbox"]' ).prop( 'type', 'radio' );
				} )
			</script>
            <?php
        }
    }
}

/**
 * Do not show the selected taxonomy at the top of the list.
 */
add_filter('wp_terms_checklist_args', 'not_show_checked_tax_on_top_list', 10);
if (! function_exists('not_show_checked_tax_on_top_list')) {
    function not_show_checked_tax_on_top_list($args)
    {
        if (! isset($args['checked_ontop'])) {
            $args['checked_ontop'] = false;
        }

        return $args;
    }
}

/**
 * Hide admin bar (Toolbar) and to hover show into left-top corner.
 */
add_action('admin_bar_init', function () {
    remove_action('wp_head', '_admin_bar_bump_cb'); // html margin bumps
    add_action('wp_before_admin_bar_render', function () {
        if (is_admin()) {
            return;
        }
        ob_start();
        ?>
		<style>
			#wpadminbar {
				background: 0 0;
				float: left;
				width: auto;
				height: auto;
				bottom: 0;
				min-width: 0 !important
			}

			#wpadminbar > * {
				float: left !important;
				clear: both !important
			}

			#wpadminbar .ab-top-menu li {
				float: none !important
			}

			#wpadminbar .ab-top-secondary {
				float: none !important
			}

			#wpadminbar .ab-top-menu > .menupop > .ab-sub-wrapper {
				top: 0;
				left: 100%;
				white-space: nowrap
			}

			#wpadminbar .quicklinks > ul > li > a {
				padding-right: 17px
			}

			#wpadminbar .ab-top-secondary .menupop .ab-sub-wrapper {
				left: 100%;
				right: auto
			}

			html {
				margin-top: 0 !important
			}

			#wpadminbar {
				overflow: hidden;
				width: 33px;
				height: 30px
			}

			#wpadminbar:hover {
				overflow: visible;
				width: auto;
				height: auto;
				background: rgba(102, 102, 102, .9)
			}

			#wp-admin-bar-wp-logo {
				display: none
			}

			#wp-admin-bar-search {
				display: none
			}
		</style>
        <?php
        $styles = ob_get_clean();

        echo preg_replace('/[\n\t]/', '', $styles) . "\n";
    });
});

/**
 * Change post types wp_editor settings.
 */
if (! function_exists('change_wp_editor_settings')) {
    add_filter('wp_editor_settings', 'change_wp_editor_settings');
    function change_wp_editor_settings($settings)
    {
        $current_screen = get_current_screen();

        // Post types for which not should be change settings.
        $post_types = array( 'post', 'page' );

        if (! $current_screen || in_array($current_screen->post_type, $post_types, true)) {
            return $settings;
        }

        $settings['media_buttons'] = false; //remove media buttons
        $settings['teeny'] = true; //minimal editor config

        return $settings;
    }
}
