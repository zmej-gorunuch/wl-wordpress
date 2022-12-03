<?php
/**
 * Functions which add or edit the pagination
 *
 * @package Weblorem_Theme
 */

/**
 * Custom theme pagination
 */
add_action('theme_pagination', 'function_theme_pagination', 1);
if (! function_exists('function_theme_pagination')) {
	function function_theme_pagination($query = false)
	{
		if (!$query) {
			global $wp_query;
			$query = $wp_query;
		}

		if ($query->max_num_pages <= 1) {
			return;
		}

		$big = 999999999; // need an unlikely integer

		$pages = paginate_links(array(
			'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
			'format'    => '?paged=%#%',
			'current'   => max(1, get_query_var('paged')),
			'total'     => $query->max_num_pages,
			'type'      => 'array',
			'prev_next' => true,
			'prev_text' => false,
			'next_text' => false,
		));

		if (is_array($pages)) {
			$paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');

			$html = '<div class="pagination"><ul>';
			foreach ($pages as $key => $page) {
				if ($paged == strip_tags($page)) {
					// Active page
					$html .= '<li><a class="active" href="#">' . strip_tags($page) . '</a></li>';
				} else {
					if ($key == count($pages) - 1) {
						// Next page
						$html .= '<li>' . str_replace('class="next page-numbers"', 'class="pagnext"', $page) . '</li>';
					} elseif ($paged != 1 && $key == 0) {
						// Previous page
						$html .= '<li>' . str_replace('class="prev page-numbers"', 'class="pagprev"', $page) . '</li>';
					} else {
						// Default page
						$html .= '<li>' . str_replace('class="page-numbers"', '', $page) . '</li>';
					}
				}
			}
			$html .= '</ul></div>';

			echo $html;
		}
	}
}
