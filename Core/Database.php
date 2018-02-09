<?php
/**
 * Created by PhpStorm.
 * User: brandon
 * Date: 1/19/18
 * Time: 11:23 PM
 */

namespace Core;


class Database {

	public static function dbConnection() {
		$host = '127.0.0.1';
		$dbName = 'mvclogin';
		$user = 'root';
		$pass = '';

		try {
			$conn = new \PDO( "mysql:host=$host;dbname=$dbName", $user, $pass );
			return $conn;
		} catch (\PDOException $e) {
			echo "Error!:" . $e->getMessage() . "<br/>";
			die();
		}

	}

	/**
	 * @param string $statement SQL statement
	 *
	 * @return array of query results
	 */
	public static function queryDb($statement) {
		$conn = self::dbConnection();

		$stmt = $conn->prepare($statement);

		$results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		return $results;
	}

}