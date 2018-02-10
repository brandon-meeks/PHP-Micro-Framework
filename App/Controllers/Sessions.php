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

	public function createAction() {

		$user = User::authenticate($_POST['email'], $_POST['password']);

		if ($user) {
			$this->redirect('/');
		} else {
			Views::renderTemplate('sessions/new.html.twig', ['email' => $_POST['email']]);
		}

	}

}