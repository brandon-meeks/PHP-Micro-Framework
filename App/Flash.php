<?php
/**
 * Created by PhpStorm.
 * User: brandon
 * Date: 2/14/18
 * Time: 3:22 PM
 */

namespace App;


class Flash {

	const SUCCESS = 'success';

	const INFO = 'info';

	const WARNING = 'warning';

	const DANGER = 'danger';

	/**
	 * @param string $message The message to be added to the session
	 */
	public static function addMessage($message, $type) {
		if (! isset($_SESSION['flash_messages'])) {
			$_SESSION['flash_messages'] = [];
		}
		// Append the message to the array
		$_SESSION['flash_messages'][] = [
			'body' => $message,
			'type' => $type
		];

	}

	/**
	 * Get all the messages
	 *
	 * @return mixed An array with all the messages, null if none set
	 */
	public static function getMessages() {
		if (isset($_SESSION['flash_messages'])) {
			$messages = $_SESSION['flash_messages'];
			unset($_SESSION['flash_messages']);

			return $messages;
		}
	}
}