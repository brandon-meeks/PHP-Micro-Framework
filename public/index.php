<?php


/**
 * Autoloader
 */
require_once "../vendor/autoload.php";


// Error Handler
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

// Sessions
session_start();

$router = new Core\Router();
//
// Add routes to Router
$router->add('', ['controller' => 'home', 'action' => 'index']);
$router->add('posts', ['controller' => 'posts', 'action' => 'index']);
$router->add('users/activate/{token:[\da-f]+}', ['controller' => 'users', 'action' => 'activate']);
$router->add('login', ['controller' => 'sessions', 'action' => 'create']);
$router->add('logged_out', ['controller' => 'sessions', 'action' => 'destroy']);
$router->add('users/resetPassword/{token:[\da-f]+}', ['controller' => 'users', 'action' => 'resetPassword']);
//$router->add('posts', ['namespace' => 'Post']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);



$router->dispatch($_SERVER['QUERY_STRING']);