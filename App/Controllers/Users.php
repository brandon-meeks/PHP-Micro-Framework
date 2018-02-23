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
use App\Flash;

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
			$this->redirect('users/success');
		} else {
			Flash::addMessage('Unable to create user', Flash::DANGER);
			Views::renderTemplate('users/new.html.twig', [ 'user' => $user ]);
		}

	}

	/**
	 * Displays the success page upon successful user creation
	 * return void
	 */
	public function success() {
		Views::renderTemplate('users/success.html.twig');
	}

	/**
	 * Displays the Forgot Password page
	 * @return void
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 */
	public function forgotPassword(  ) {
		Views::renderTemplate('users/forgot_password.html.twig');
	}

	/**
	 * @throws \Exception
	 */
	public function resetRequestSent(  ) {
		User::sendPasswordReset($_POST['email']);

		Views::renderTemplate('users/reset_requested.html.twig');
	}

	/**
	 * @throws \Exception
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 */
	public function resetPassword() {
		$token = $this->route_params['token'];
		$user = User::findByPasswordResetHash($token);

		if ($user) {
			Views::renderTemplate('users/reset_password.html.twig', [
				'token' => $token,
				'email' => $user->email
			]);

		} else {
			echo "Unable to reset password at this time.";
		}

	}

	public function updatePassword() {
		$token = $_POST['token'];

		$user = User::findByPasswordResetHash($token);

		if ($user->updatePassword($_POST['password'])) {
			Views::renderTemplate('users/reset_success.html.twig');

		} else {
			Views::renderTemplate('users/reset_password.html.twig', [
				'token' => $token,
				'user'  => $user,
				'email' => $user->email
			]);
		}
	}

}