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

}