<?php
/**
 * Created by PhpStorm.
 * User: brandon
 * Date: 1/13/18
 * Time: 6:14 PM
 */

namespace App\Controllers;

use Core\Views;


class Home extends ApplicationController {

	protected function before() {
		//echo "(before)";
	}

	protected function after() {
		//echo "(after)";
	}

	/**
	 * Displays the index page
	 */
	public function indexAction() {
		//echo "This is the Home Controller's Index method";
		Views::renderTemplate("Home/index.html.twig", [
			'name' => 'Brandon'
		]);

	}

}