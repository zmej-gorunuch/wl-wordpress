<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Weblorem_Theme
 */

?>
<?php if (current_user_can('administrator')) { ?>
    <div style="position:relative;z-index:1000000;padding:10px;text-align:center">
        <p>
            <a href="<?php echo esc_url(admin_url('options-reading.php')); ?>">
				<?php esc_html_e('Select static homepage in settings', THEME_DOMAIN); ?>
            </a>
        </p>
    </div>
<?php } else { ?>
    <div style="position:relative;z-index:1000000;padding:10px;text-align:center">
        <p><?php esc_html_e('Web site is temporarily down!', THEME_DOMAIN); ?></p>
    </div>
<?php } ?>
