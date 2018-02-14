<?php
/**
 * Created by PhpStorm.
 * User: brandon
 * Date: 1/18/18
 * Time: 4:12 PM
 */

namespace Core;


class Views {

	/**
	 * Renders the view file
	 *
	 * @param string $view The path to the view file
	 * @param array $args The data passed to the view
	 *
	 * @return void
	 */
	public static function render($view, $args=[]) {
		$args = extract($args, EXTR_SKIP);

		$file = "../App/Views/$view"; // relative to Core directory

		if (is_readable($file)) {
			require $file;
		} else {
			//echo "$file not found";
			throw new \Exception("View file $file not found!");
		}
	}

	public static function renderTemplate($view, $args = []) {
		$loader = new \Twig_Loader_Filesystem(dirname(__DIR__) . '/App/Views');
		$twig = new \Twig_Environment($loader);
		// adds sessions super global to twig
		$twig->addGlobal('session', $_SESSION);
		$twig->addGlobal('current_user', \App\Controllers\Sessions::getCurrentUser());

		echo $twig->render($view, $args);
	}

}