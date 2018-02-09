<?php
/**
 * Created by PhpStorm.
 * User: brandon
 * Date: 2/1/18
 * Time: 8:00 PM
 */

namespace App\Controllers;


use Core\Views;
use App\Models\User;

class Users extends ApplicationController {

	/**
	 * Displays users signup form
	 *
	 * @return void
	 */
	public function newAction() {
		Views::renderTemplate('users/new.html.twig');

	}

	/**
	 * Creates new user on form submission
	 *
	 * @return void
	 */
	public function createAction() {
		$user = new User($_POST);

		if ($user->save()) {
			header('Location: http://' . $_SERVER['HTTP_HOST'] . '?users/success', true, 303);
			exit;
		} else {
			Views::renderTemplate('users/new.html.twig', [ 'user' => $user ]);
		}

	}

	public function success() {
		Views::renderTemplate('users/success.html.twig');
	}

}