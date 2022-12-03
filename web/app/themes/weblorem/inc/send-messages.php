<?php
/**
 * Send site messages functions which enhance the theme
 *
 * @package Weblorem_Theme
 */

/**
 * Send all email messages from site forms
 */
if (!function_exists('send_site_messages')) {
	function send_site_messages($form_data)
	{
		$protocols = array('http://', 'http://www.', 'www.', 'https://', 'https://www.');
		$url = str_replace($protocols, '', get_bloginfo('url'));

		$site_email = 'info@' . $url;

		$headers = 'From: ' . get_bloginfo('name') . ' <' . $site_email . '>' . "\r\n";
		$to = array(
			get_bloginfo('admin_email'),
		);

		if (isset($form_data['subject']) && !empty($form_data['subject'])) {
			$subject = $form_data['subject'] . ' ';
		} else {
			$subject = $form_data['form-title'] . ' ';
		}
		$message = $form_data['form-title'] . PHP_EOL . PHP_EOL;
		if (isset($form_data['user-name']) && !empty($form_data['user-name'])) {
			$message .= __('Name', THEME_DOMAIN) . ': ' . $form_data['user-name'] . PHP_EOL;
		}
		if (isset($form_data['email']) && !empty($form_data['email'])) {
			$message .= __('Email', THEME_DOMAIN) . ': ' . $form_data['email'] . PHP_EOL;
		}
		if (isset($form_data['phone']) && !empty($form_data['phone'])) {
			$message .= __('Phone', THEME_DOMAIN) . ': ' . $form_data['phone'] . PHP_EOL;
		}
		if (isset($form_data['message']) && !empty($form_data['message'])) {
			$message .= PHP_EOL . __('Message', THEME_DOMAIN) . PHP_EOL . $form_data['message'] . PHP_EOL;
		}
		$message .= PHP_EOL;

		$attach_file = '';
		if (0 < $form_data['file']['size']) {
			$allowed_extensions = array('jpg', 'png', 'pdf', 'txt');
			$extension = pathinfo($form_data['file']['name'], PATHINFO_EXTENSION);
			if (in_array($extension, $allowed_extensions)) {
				if (move_uploaded_file($form_data['file']['tmp_name'], wp_upload_dir()['basedir'] . '/' . $form_data['file']['name'])) {
					$attach_file = wp_upload_dir()['basedir'] . '/' . $form_data['file']['name'];
				}
			}
		}

		if (wp_mail($to, $subject, $message, $headers, $attach_file)) {
			wp_delete_file($attach_file);
			$result = true;
		} else {
			$result = false;
		}

		return $result;
	}
}

/**
 * Send Telegram messages
 */
if (!function_exists('telegram_sendMessage')) {
	function telegram_sendMessage($token, $chat_id, $message)
	{
		if ($token && $chat_id && $message) {
			$response = array(
				'chat_id' => $chat_id,
				'text' => $message
			);

			$url = 'https://api.telegram.org/bot' . $token . '/sendMessage';

			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => $response,
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_HEADER => 0,
				CURLOPT_URL => $url,
			));

			$server_response = curl_exec($curl);
			curl_close($curl);

			$server_response = json_decode($server_response, 1);

			if (isset($server_response['error'])) {
				return 0;
			} else {
				return $server_response['result'];
			}
		}

		return false;
	}
}

/**
 * Send SMS messages
 */
if (!function_exists('sms_sendMessage')) {
	function sms_sendMessage($phone, $lang = null)
	{
		if ($phone) {
			$phone = preg_replace('![^0-9]+!', '', $phone);
			$url = 'https://gate.smsclub.mobi/http/?';
			$username = '380509710336';   // string User ID (phone number)
			$password = 'EzIRRqziOKwUl6O';// string Password
			$from = 'infomir';        // string, sender id (alpha-name) (as long as your alpha-name is not spelled out, it is necessary to use it)
			$to = $phone;
			if ($lang == 'ru') {
				$text = iconv('utf-8', 'windows-1251', 'Спасибо за заявку! Ссылка на презентацию: clc.to/goldhill');
			} else {
				$text = iconv('utf-8', 'windows-1251', 'Дякуємо за заявку! Посилання на презентацію: clc.to/goldhill');
			}
			$text = urlencode($text);       // string Message
			$url_result = $url . 'username=' . $username . '&password=' . $password . '&from=' . urlencode($from) . '&to=' . $to . '&text=' . $text;

			if ($curl = curl_init()) {
				curl_setopt($curl, CURLOPT_URL, $url_result);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_exec($curl);
				curl_close($curl);

				return true;
			}
		}

		return false;
	}
}
