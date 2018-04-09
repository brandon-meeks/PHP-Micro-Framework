<?php

namespace App;

class Config {
	/*
	 * Show or hide error messages on screen
	 * @var boolean
	 */
	const SHOW_ERRORS = true;
	
	/*
	* Database Credentials
	*/
	const DB_HOST = 'Your Host';
	
	const DB_NAME = 'Database Name';
	
	const DB_USER = 'DB Username';
	
	const DB_PASS = 'DB User Password';

	/*
	 * API Key for your Mailgun account
	 * @var string
	 */
	const MAILGUN_API_KEY = 'Your API Key';

	/*
	 * Website email Address to be used when sending email
	 * @var string
	 */
	const SITE_EMAIL = 'site@example.com';

	/*
	 * Domain configured in Mailgun for sending email
	 * @var string
	 */
	const EMAIL_DOMAIN = 'Your domain';

	/*
	 * Secret key for hashing
	 * @var boolean
	 */
	const SECRET_KEY='L6CIC2DDSORf9N3leEK1uwMraO7rVAOz';

}
