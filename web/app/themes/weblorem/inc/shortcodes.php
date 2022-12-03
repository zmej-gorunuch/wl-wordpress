<?php
/**
 * Register theme shortcodes
 *
 * @package Weblorem
 */

/**
 * Tooltip editor shortcode
 *
 * use [tooltip placement="top" title="Tooltip title" content="Tooltip content"]
 */
add_shortcode( 'tooltip', 'add_tooltip' );
if ( ! function_exists( 'add_tooltip' ) ) {
	function add_tooltip( $atts ) {

		// get the attributes
		$atts = shortcode_atts(
			array(
				'placement' => 'top',
				'title'     => '',
				'content'   => ''
			),
			$atts,
			'tooltip'
		);

		$title   = $atts['title'] ? esc_html( $atts['title'] ) : '';
		$content = $atts['content'] ? esc_html( $atts['content'] ) : '';

		// return HTML
		return '<span class="tooltipspan">' . $title . '<div class="tooltipspantext">' . $content . '</div></span>';
	}
}
