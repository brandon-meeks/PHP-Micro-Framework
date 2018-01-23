<?php
/**
 * Created by PhpStorm.
 * User: brandon
 * Date: 1/22/18
 * Time: 3:44 PM
 */

namespace Core;


class Error {

	/**
	 * Error handler. Convert all errors to Exceptions by throwing an ErrorException
	 *
	 * @param int $level Error Level
	 * @param string $message Error Message
	 * @param string $file Filename the error was raised in
	 * @param int $line Line number in the file
	 *
	 * @return true
	 * @throws \Exception
	 *
	 *
	 */
	public static function errorHandler($level, $message, $file, $line) {
		if (error_reporting() !== 0) { // to keep the @ operator working
			throw new \Exception($message, 0, $level, $file, $line);
		}

		return true;
	}

	/**
	 * Exception Handler
	 *
	 * @param \Exception $exception The exception
	 *
	 * return void
	 */
	public static function exceptionHandler($exception) {

		// Code is 404 (not found) or 500 (internal server error)
		$code = $exception->getCode();
		if ($code != 404) {
			$code = 500;
		}
		http_response_code($code);

		if (\App\Config::SHOW_ERRORS) {
			echo "<h1>Fatal error</h1>";
			echo "<p>Uncaught exception: '" . get_class( $exception ) . "'</p>\n";
			echo "<p>Message: '" . $exception->getMessage() . "'</p>\n";
			echo "<p>Stack trace:<pre>" . $exception->getTraceAsString() . "</pre></p>\n";
			echo "<p>Thrown in: '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";
		} else {
			$log = dirname(__DIR__) . '/logs/' . date('Y-m-d') . '.txt';
			ini_set('error_log', $log);
			$message = "Uncaught exception: '" . get_class($exception) . "'";
			$message .= " with message '" . $exception->getMessage() . "'";
			$message .= "\nStack trace: " . $exception->getTraceAsString() . "'";
			$message .= "\nThrown in '" . $exception->getFile() . "' on line " . $exception->getLine();

			error_log($message);
			if ($code == 404) {
				echo "<h1>Page Not Found</h1>";
			} else {
				echo "<h1>Oops! An Error Has Occurred</h1>";
				echo "<p>Please contact the website administrator.</p>";
			}
		}
	}

}