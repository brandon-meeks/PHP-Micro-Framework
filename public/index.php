<?php

//require '../Core/Router.php';
//require '../App/Controllers/Home.php';

//echo "Requested URL = " . $_SERVER['QUERY_STRING'];

/**
 * Autoloader
 */
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

// Display the routing table
//echo '<pre>';
//var_dump($router->getRoutes());
//echo htmlspecialchars(print_r($router->getRoutes(), true));
//echo '</pre>';

// Match the requested route
//$url = $_SERVER['QUERY_STRING'];
//
//if ($router->matchRoute($url)) {
//	echo '<pre>';
//	var_dump($router->getParams());
//	echo '</pre>';
//} else {
//	echo 'No route found for URL ' . $url;
//}

$router->dispatch($_SERVER['QUERY_STRING']);