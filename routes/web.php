<?php

use Illuminate\Routing\Router;
/**
 * @var Router $router
 */

$router->auth();
$router->get('/', 'HomeController@index');
