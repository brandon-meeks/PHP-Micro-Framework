<?php
/**
 * Created by PhpStorm.
 * User: brandon
 * Date: 2/5/18
 * Time: 10:39 PM
 */

namespace App\Models;

use Core\Database;
use Core\Token;
use Core\Mailer;
use Core\Views;


class User {

	public $errors = [];

	public function __construct($data = []) {
		foreach ($data as $key => $value) {
			$this->$key = $value;
		}
	}

	/**
	 * @return bool
	 */
	public function save() {

		$this->validate();

		if (empty($this->errors)) {
			$token = new Token();
			$activation_hash = $token->getHash();
			$this->activation_token = $token->getToken();

			$password_hash = password_hash( $this->password, PASSWORD_DEFAULT );

			$query = 'INSERT INTO users (name, email, password_hash, activation_hash) VALUES (:name, :email, :password_hash, :activation_hash)';

			$db   = Database::dbConnection();
			$stmt = $db->prepare($query);

			$stmt->bindValue( 'name', $this->name, \PDO::PARAM_STR );
			$stmt->bindValue( 'email', $this->email, \PDO::PARAM_STR );
			$stmt->bindValue( 'password_hash', $password_hash, \PDO::PARAM_STR );
			$stmt->bindValue('activation_hash', $activation_hash, \PDO::PARAM_STR);


			return $stmt->execute();
		}

		return false;

	}

	/**
	 * Validates the user's input
	 */
	protected function validate() {

		if ($this->name == '') {
			$this->errors[] = 'Name is required';
		}

		if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
			$this->errors[] = 'Please enter a valid email';
		}
		if ($this->emailExists($this->email, $this->id ?? null)) {
			$this->errors[] = 'Email is already used';
		}

		if (strlen($this->password) < 6) {
			$this->errors[] = 'Password must be at least 6 characters';
		}

		if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
			$this->errors[] = 'Password must contain one letter';
		}

		if (preg_match('/.*\d.*/i', $this->password) == 0) {
			$this->errors[] = 'Password must contain one number';
		}

	}

	/**
	 *
	 * Checks if the email entered already exists in the database
	 *
	 * @param string $email The email address entered in the form
	 *
	 * @return bool True if the email exists, false otherwise
	 */
	public function emailExists($email, $ignore_id = null) {

		$user = self::findByEmail($email);

		if ($user) {
			if ($user->id != $ignore_id) {
				return true;
			}
		}
		return false;

	}

	/**
	 *
	 * Search the database for the user using their email
	 *
	 * @param string $email The Email address of the user
	 *
	 * @return mixed
	 */
	public static function findByEmail($email) {
		$query = 'SELECT * FROM users WHERE email = :email';

		$db = Database::dbConnection();
		$stmt = $db->prepare($query);

		$stmt->bindParam('email', $email, \PDO::PARAM_STR);

		$stmt->setFetchMode(\PDO::FETCH_CLASS, get_called_class());

		$stmt->execute();

		return $stmt->fetch();
	}

	/**
	 *
	 * Authenticates the user by email and password
	 *
	 * @param string $email email address to authenticate
	 * @param string $password password to authenticate
	 *
	 * @return mixed The user object or false if authentication fails
	 */
	public static function authenticate( $email, $password ) {

		$user = self::findByEmail($email);

		if ($user) {
			// verifies the user's entered password against the stored password_hash in the db
			if (password_verify($password, $user->password_hash)) {
				return $user;
				//echo "user found and logged in";
			}
		}

		return false;

	}

	/**
	 * Locate user in the database by their id
	 *
	 * @param int $id user id to search
	 *
	 * @return mixed
	 */
	public static function findById($id) {
		$query = 'SELECT * FROM users WHERE id = :id';

		$db = Database::dbConnection();
		$stmt = $db->prepare($query);

		$stmt->bindParam('id', $id, \PDO::PARAM_INT);

		$stmt->setFetchMode(\PDO::FETCH_CLASS, get_called_class());

		$stmt->execute();

		return $stmt->fetch();
	}

	/**
	 * Send password reset email to the specified user
	 *
	 * @param $email The email address
	 *
	 * @throws \Exception
	 */
	public static function sendPasswordReset( $email ) {

		$user = self::findByEmail($email);

		if ($user) {
			if ($user->createPasswordResetToken()) {
				$user->sendPasswordResetEmail();
			};
		}
	}

	/**
	 * Create a new password reset token and expiry
	 *
	 * @return bool
	 * @throws \Exception
	 */
	protected function createPasswordResetToken() {
		$token = new Token();
		$hashed_token = $token->getHash();
		$this->password_reset_token = $token->getToken();

		$password_exp = time() + 60 * 60 * 2; // 2 hours from now

		$query = 'UPDATE users 
				  SET password_reset_hash = :token_hash, 
				  	  password_hash_exp = :expires_at 
				  WHERE id = :id';

		$db = Database::dbConnection();
		$stmt = $db->prepare($query);

		$stmt->bindValue(':token_hash', $hashed_token, \PDO::PARAM_STR);
		$stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $password_exp), \PDO::PARAM_STR);
		$stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);

		return $stmt->execute();

	}

	/**
	 *
	 */
	protected function sendPasswordResetEmail() {
		$url = 'http://' . $_SERVER['HTTP_HOST'] . '/?users/resetPassword/' . $this->password_reset_token;

		$text = Views::getTemplate('mailer/password_reset.html.twig', ['url' => $url]);
		$html = Views::getTemplate('mailer/password_reset.html.twig', ['url' => $url]);

		Mailer::sendMail($this->email, 'Password Reset Request', $text, $html);
	}

	/**
	 * @param $token
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public static function findByPasswordResetHash($token) {
		$token = new Token($token);
		$hashed_token = $token->getHash();

		$sql = 'SELECT * FROM users
                WHERE password_reset_hash = :token_hash';

		$db = Database::dbConnection();
		$stmt = $db->prepare($sql);

		$stmt->bindValue(':token_hash', $hashed_token, \PDO::PARAM_STR);

		$stmt->setFetchMode(\PDO::FETCH_CLASS, get_called_class());

		$stmt->execute();

		$user = $stmt->fetch();

		if ($user) {
			// Check password reset token hasn't expired
			if (strtotime($user->password_hash_exp) > time()) {
				return $user;
			}
		}
	}

	/**
	 * @param string $password
	 *
	 * @return bool
	 */
	public function updatePassword($password) {
		$this->password = $password;

		$this->validate();

		if (empty($this->errors)) {
			$password_hash = password_hash($this->password, PASSWORD_DEFAULT);

			$query = 'UPDATE users 
					  SET password_hash = :password_hash,
					  password_reset_hash = NULL,
					  password_hash_exp = NULL
					  WHERE id = :id';

			$db = Database::dbConnection();
			$stmt = $db->prepare($query);

			$stmt->bindValue(':password_hash', $password_hash, \PDO::PARAM_STR);
			$stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);

			return $stmt->execute();
		}

		return false;

	}

	public function sendActivationEmail() {
		$url = 'http://' . $_SERVER['HTTP_HOST'] . '/?users/activate/' . $this->activation_token;

		$text = Views::getTemplate('mailer/account_activation.html.twig', ['url' => $url]);
		$html = Views::getTemplate('mailer/account_activation.html.twig', ['url' => $url]);

		Mailer::sendMail($this->email, 'User Account Activation', $text, $html);
	}

	public static function activateUser($token) {
		$token = new Token($token);
		$hashed_token = $token->getHash();

		$sql = 'UPDATE users
				SET is_active = 1,
				activation_hash = NULL
				WHERE activation_hash = :hashed_token';

		$db = Database::dbConnection();
		$stmt = $db->prepare($sql);

		$stmt->bindValue(':hashed_token', $hashed_token, \PDO::PARAM_STR);

		return $stmt->execute();

	}

}