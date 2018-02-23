<?php
/**
 * Created by PhpStorm.
 * User: brandon
 * Date: 2/19/18
 * Time: 4:47 PM
 */

namespace App\Controllers;

use Core\Views;


class Password extends ApplicationController {

	public function forgotPassword(  ) {
		Views::renderTemplate('users/forgot_password.html.twig');
	}

}