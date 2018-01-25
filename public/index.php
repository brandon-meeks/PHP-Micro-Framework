<?php


/**
 * Autoloader
 */
require_once "../vendor/autoload.php";

spl_autoload_register(function ($class) {
	$root = dirname(__DIR__);   // get the parent directory
	$file = $root . '/' . str_replace('\\', '/', $class) . '.php';
	if (is_readable($file)) {
		require $root . '/' . str_replace('\\', '/', $class) . '.php';
	} else {
		echo "Unable to read $file";
	}
});

// Error Handler
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


$router = new Core\Router();
//
// Add routes to Router
$router->add('', ['controller' => 'home', 'action' => 'index']);
$router->add('posts', ['controller' => 'posts', 'action' => 'index']);
//$router->add('posts/new', ['controller' => 'posts', 'action' => 'new']);
//$router->add('posts', ['namespace' => 'Post']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);



$router->dispatch($_SERVER['QUERY_STRING']);