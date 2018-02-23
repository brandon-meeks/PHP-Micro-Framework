<?php

namespace App;

class Config {
	/*
	 * Show or hide error messages on screen
	 * @var boolean
	 */
	const SHOW_ERRORS = true;

	/*
	 * API Key for your Mailgun account
	 * @var string
	 */
	const MAILGUN_API_KEY = 'key-09fa4e019e12218dba37255c2c9b51db';

	/*
	 * Website email Address to be used when sending email
	 * @var string
	 */
	const SITE_EMAIL = 'site@example.com';

	/*
	 * Domain configured in Mailgun for sending email
	 * @var string
	 */
	const EMAIL_DOMAIN = 'sandbox747000d4ef80455b8397f99224337624.mailgun.org';

	/*
	 * Secret key for hashing
	 * @var boolean
	 */
	const SECRET_KEY='L6CIC2DDSORf9N3leEK1uwMraO7rVAOz';

}