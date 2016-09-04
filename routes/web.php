<?php

use Illuminate\Routing\Router;
/**
 * @var Router $router
 */
$router->get('/', 'HomeController@index');
$router->auth();

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('dashboard', 'DashboardController@index');
});
