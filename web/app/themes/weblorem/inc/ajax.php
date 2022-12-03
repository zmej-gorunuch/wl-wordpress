<?php
/**
 * Ajax functions which enhance the theme by hooking into WordPress
 *
 * @package Weblorem
 */

/**
 * Connecting Ajax scripts to the frontend
 */
add_action('wp_enqueue_scripts', 'ajax_script_to_frontend', 99);
if (!function_exists('ajax_script_to_frontend')) {
	function ajax_script_to_frontend()
	{
		wp_localize_script(
			'wl-custom-scripts',
			'ajax_script',
			array(
				'url' => admin_url('admin-ajax.php'),
			)
		);
	}
}

/**
 * Test ajax function
 */
add_action('wp_ajax_test_ajax', 'function_test_ajax');
add_action('wp_ajax_nopriv_test_ajax', 'function_test_ajax');
if (!function_exists('function_test_ajax')) {
	function function_test_ajax()
	{
		$data['test_data'] = trim(strip_tags($_POST['test_data']));

		if (empty($data['test_data'])) {
			$errors['test_data'] = 'Empty test_data!';
		}

		if (empty($errors)) {
			$result['result'] = 'Ajax ok!';

			wp_send_json_success($result);
		} else {
			$errors['message'] = __('Ajax Error!', THEME_DOMAIN);
			wp_send_json_error($errors);
		}
	}
}

/**
 * Ajax send form messages
 */
add_action('wp_ajax_form_send_message', 'form_send_message_function');
add_action('wp_ajax_nopriv_form_send_message', 'form_send_message_function');
function form_send_message_function()
{
	// Parse serialize form data to array
	parse_str($_POST['value'], $form_data);

	$data['form-title'] = trim(strip_tags($form_data['form-title']));
	$data['user-name'] = trim(strip_tags($form_data['user-name']));
	$data['phone'] = trim(strip_tags($form_data['phone']));
	$data['message'] = trim(strip_tags($form_data['message']));
	$data['lang'] = trim(strip_tags($form_data['lang']));

	if (empty($data['user-name'])) {
		$errors['user-name'] = __('Required field.', THEME_DOMAIN);
	}

	if (empty($data['phone'])) {
		$errors['phone'] = __('Required field.', THEME_DOMAIN);
	} elseif (strlen(preg_replace('![^0-9]+!', '', $data['phone'])) != 10) {
		$errors['phone'] = __('Check phone number.', THEME_DOMAIN);
	}

	if (empty($errors)) {
		if (defined('POLYLANG_BASENAME')) {
			$thanks_url = get_page_link(pll_get_post(get_page_id_by_template('thanks'), pll_current_language()));
		} else {
			$thanks_url = get_page_link_by_template("thanks") ? get_page_link_by_template("thanks") : home_url();
		}

		$thanks_url = get_page_link_by_template("thanks") ? get_page_link_by_template("thanks") : home_url();

		if (send_site_messages($data)) {
			$result = array(
				'message' => __('Message sent!', THEME_DOMAIN),
				'url' => $thanks_url,
			);
			wp_send_json_success($result);
		} else {
			$result = array(
				'message' => __('Error sending message!', THEME_DOMAIN),
			);
			wp_send_json_error($result);
		}
	} else {
		$errors['message'] = __('Check that the form data is filled in correctly.', THEME_DOMAIN);
		wp_send_json_error($errors);
	}
}
