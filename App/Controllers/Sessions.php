<?php
/**
 * Created by PhpStorm.
 * User: brandon
 * Date: 2/9/18
 * Time: 4:12 PM
 */

namespace App\Controllers;

use App\Models\User;
use Core\Views;

class Sessions extends ApplicationController {

	public function newAction() {
		Views::renderTemplate('Sessions/new.html.twig');
	}

	/**
	 * Authenticates the user and creates new session
	 */
	public function createAction() {

		$user = User::authenticate($_POST['email'], $_POST['password']);

		if ($user) {
			// assigns user_id to the session
			$_SESSION['user_id'] = $user->id;
			// regenerates the session id
			session_regenerate_id(true);

			$this->redirect('/');
		} else {
			Views::renderTemplate('sessions/new.html.twig', ['email' => $_POST['email']]);
		}

	}

	public function destroy() {

		// Unset all of the session variables.
		$_SESSION = array();

		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
				$params["path"], $params["domain"],
				$params["secure"], $params["httponly"]
			);
		}

		// Finally, destroy the session.
		session_destroy();

		Views::renderTemplate('sessions/logged_out.html.twig');

	}

}