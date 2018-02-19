<?php
/**
 * Created by PhpStorm.
 * User: brandon
 * Date: 1/13/18
 * Time: 7:24 PM
 */

namespace App\Controllers;


abstract class ApplicationController {

	protected $routes;

	protected $route_params = [];

	/**
	 * ApplicationController constructor.
	 *
	 * @param array $route_params contains the parameters from the route
	 *
	 *
	 */
	public function __construct($route_params) {
		$this->route_params = $route_params;
	}

	/**
	 * @param string $name The method name
	 * @param array $args Arguments passed to the method
	 *
	 * @return void
	 * @throws \Exception if method is not found in class
	 */
	public function __call( $name, $args ) {
		$method = $name . 'Action';

		if (method_exists($this, $method)) {
			if ($this->before() !== false) {
				call_user_func_array([$this, $method], $args);
				$this->after();
			}
		} else {
			//echo "Method $method not found in controller " . get_class($this);
			throw new \Exception("Method $method not found in controller " . get_class($this));
		}
	}

	/**
	 * Before filter - called before executing the action method
	 */
	protected function before() {

	}

	/**
	 * After filter - called after executing an action method
	 */
	protected function after() {

	}

	/**
	 * Redirects the user to the new url
	 *
	 * @param string $url to redirect to
	 */
	public function redirect($url) {
		header('Location: http://' . $_SERVER['HTTP_HOST'] . $url, true, 303);
		exit;
	}



}