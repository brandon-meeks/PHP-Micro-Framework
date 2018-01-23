<?php
/**
 * Created by PhpStorm.
 * User: brandon
 * Date: 1/13/18
 * Time: 11:48 PM
 */

namespace App\Controllers\Admin;

use App\Controllers\ApplicationController;

class Users extends ApplicationController {

	/**
	 * Before filter
	 *
	 * @return void
	 */
	protected function before() {
		// Check if user is logged in
	}

	/**
	 * Displays the Users index page inside the admin
	 *
	 * @return void
	 */
	public function indexAction() {
		echo "Users section of the admin";
	}

}