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

	/**
	 * @param $view
	 * @param array $args
	 *
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 */
	public static function renderTemplate($view, $args = []) {
		echo self::getTemplate($view, $args);
	}

	/**
	 * Get the contents of a view template
	 *
	 * @param string $view The template file
	 * @param array $args Associative array of data to pass to the view (optional)
	 *
	 * @return string
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 */
	public static function getTemplate($view, $args = []) {
		$loader = new \Twig_Loader_Filesystem(dirname(__DIR__) . '/App/Views');
		$twig = new \Twig_Environment($loader);
		// adds sessions super global to twig
		$twig->addGlobal('session', $_SESSION);
		$twig->addGlobal('current_user', \App\Controllers\Sessions::getCurrentUser());
		$twig->addGlobal('flash_messages', \App\Flash::getMessages());

		return $twig->render($view, $args);
	}

}