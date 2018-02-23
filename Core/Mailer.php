<?php
/**
 * Created by PhpStorm.
 * User: brandon
 * Date: 2/19/18
 * Time: 4:17 PM
 */

namespace Core;

use Mailgun\Mailgun;
use App\Config;


class Mailer {

	/**
	 * Send an email
	 *
	 * @param string $to Recipient
	 * @param string $subject Subject of email
	 * @param string $text Text-only content of email
	 * @param string $html HTML content of the email
	 */
	public static function sendMail( $to, $subject, $text, $html ) {
		$mg = Mailgun::create(Config::MAILGUN_API_KEY);
		$domain = Config::EMAIL_DOMAIN;

		$mg->messages()->send($domain, [
			'from'     => Config::SITE_EMAIL,
			'to'       => $to,
			'subject'  => $subject,
			'text'     => $text,
			'html'     => $html
		]);
	}

}