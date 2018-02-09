<?php
/**
 * Created by PhpStorm.
 * User: brandon
 * Date: 2/5/18
 * Time: 10:39 PM
 */

namespace App\Models;

use Core\Database;

use App\Controllers\ApplicationController;

class User extends ApplicationController {

	public $errors = [];

	public function __construct($data) {
		foreach ($data as $key => $value) {
			$this->$key = $value;
		}
	}

	public function save() {

		$this->validate();

		if (empty($this->errors)) {

			$password_hash = password_hash( $this->password, PASSWORD_DEFAULT );

			$query = 'INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)';

			$db   = Database::dbConnection();
			$stmt = $db->prepare($query);

			$stmt->bindValue( 'name', $this->name, \PDO::PARAM_STR );
			$stmt->bindValue( 'email', $this->email, \PDO::PARAM_STR );
			$stmt->bindValue( 'password_hash', $password_hash, \PDO::PARAM_STR );


			return $stmt->execute();
		}

		return false;

	}

	protected function validate() {

		if ($this->name == '') {
			$this->errors[] = 'Name is required';
		}

		if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
			$this->errors[] = 'Please enter a valid email';
		}
		if ($this->emailExists($this->email)) {
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
	 * @param $email The email address entered in the form
	 *
	 * @return bool True if the email exists, false otherwise
	 */
	protected function emailExists($email) {
		$query = 'SELECT * FROM users WHERE email = :email';

		$db = Database::dbConnection();
		$stmt = $db->prepare($query);

		$stmt->bindParam('email', $email, \PDO::PARAM_STR);

		$stmt->execute();

		return $stmt->fetch() !== false;
	}

}