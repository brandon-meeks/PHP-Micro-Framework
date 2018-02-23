<?php
/**
 * Created by PhpStorm.
 * User: brandon
 * Date: 2/20/18
 * Time: 3:56 PM
 */

namespace Core;

use App\Config;

class Token {

	/*
	 * The token array
	 * @var array
	 */
	protected $token;

	/**
	 * Token constructor.
	 *
	 * @param string $token_value (optional) A token value
	 *
	 * @return string a 32-character token
	 *
	 * @throws \Exception
	 */
	public function __construct( $token_value = null ) {
		if ($token_value) {
			$this->token = $token_value;
		} else {
			$this->token = bin2hex(random_bytes(16)); // 16 bytes = 128 bits = 32 hex characters
		}


	}

	/**
	 * Get the token value
	 *
	 * @return string The value
	 */
	public function getToken() {
		return $this->token;
	}

	public function getHash() {
		return hash_hmac('sha256', $this->token, Config::SECRET_KEY);
	}

}