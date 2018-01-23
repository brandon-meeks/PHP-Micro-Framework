<?php
/**
 * Created by PhpStorm.
 * User: brandon
 * Date: 1/12/18
 * Time: 4:09 PM
 */

namespace Core;

class Router {
	/**
	 * Associative array of routes (the routing table)
	 * @var array
	 */
	protected $routes = [];

	/**
	 * Parameters from the matched route
	 * @var array
	 */
	protected $params = [];

	/**
	 * Add a route to the routing table
	 *
	 * @param string $route The route URL
	 * @param array $params Parameters (controller, actions, etc)
	 *
	 * @return void
	 */
	public function add($route, $params = []) {

		// Convert the route to a regular expression: escape forward slashes
		$route = preg_replace('/\//', '\\/', $route);

		// Convert variables e.g. {controller}
		$route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z]+)', $route);

		// Convert variables with custom regular expressions e.g. {id:\d+}
		$route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

		// Add start and end delimiters, and case insensitive flag
		$route = '/^' . $route . '$/i';

		$this->routes[$route] = $params;
	}

	/**
	 * Get all the routes in the routing table
	 *
	 * @return array
	 */
	public function getRoutes() {
		return $this->routes;
	}

	/**
	 * Match the route to the routes in the routing table, setting the $params
	 * property if a route is found
	 *
	 * @param string $url
	 *
	 * @return bool true if a match is found, false otherwise
	 */
	public function matchRoute($url) {

		// Match the fixed URL format to controller/action
//		$reg_exp = "/^(?P<controller>[a-z-]+)\/(?P<action>[a-z-]+)$/";
		foreach ($this->routes as $route => $params)
		if (preg_match($route, $url, $matches)) {
			// Get named capture groups by name
			//$params = [];

			foreach ($matches as $key => $match) {
				if (is_string($key)) {
					$params[$key] = $match;
				}
			}

			$this->params = $params;
			return true;
		}
		return false;
	}

	/**
	 * Get the currently matched route parameters
	 *
	 * @return array
	 */
	public function getParams() {
		return $this->params;
	}

	/**
	 * Dispatch the URL, creating the controller object and running the action method
	 *
	 * @param string $url The route URL
	 *
	 * @throws \Exception if the requested method contains the word action
	 */
	public function dispatch($url) {

		$url = $this->removeQueryStringVariables($url);

		if ($this->matchRoute($url)) {
			$controller = $this->params['controller'];
			$controller = $this->convertToStudlyCaps($controller);
			// $controller = "App\Controllers\\$controller";
			$controller = $this->getNamespace() . $controller;

			if (class_exists($controller)) {
				$controller_object = new $controller($this->params);

				$action = $this->params['action'];
				$action = $this->convertToCamelCase($action);

				if (preg_match('/action$/i', $action) == 0) {
					$controller_object->$action();
				} else {
					throw new \Exception("The method $action (in controller $controller) does not exist");
				}
			} else {
				//echo "The class " . $controller . " does not exist";
				throw new \Exception("The class $controller does not exist");
			}
		} else {
			//echo "No route matched to " . $url;
			throw new \Exception("No route matched to $url", 404);
		}
	}

	/**
	 * Convert the string with hyphens to StudlyCaps,
	 * e.g. post-authors => PostAuthors
	 *
	 * @param string $string The string to convert
	 *
	 * @return string
	 */
	protected function convertToStudlyCaps($string) {
		return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
	}

	/**
	 * Convert the string with hyphens to camelCase
	 * e.g. add-new => addNew
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	protected function convertToCamelCase($string) {
		return lcfirst($this->convertToStudlyCaps($string));
	}

	/**
	 * @param string $url The full URL
	 *
	 * @return string The URL with the query string removed
	 */
	protected function removeQueryStringVariables($url) {
		if ($url != '') {
			$parts = explode('&', $url, 2);

			if (strpos($parts[0], '=') === false) {
				$url = $parts[0];
			} else {
				$url = '';
			}
		}

		return $url;
	}

	/**
	 *
	 * Get the namespace for the controller class. The namespace defined in the route parameters is added if present
	 *
	 * @return string The request URL
	 */
	protected function getNamespace() {
		$namespace = "App\Controllers\\";

		if (array_key_exists('namespace', $this->params)) {
			$namespace .= $this->params['namespace'] . '\\';
		}

		return $namespace;
	}


}