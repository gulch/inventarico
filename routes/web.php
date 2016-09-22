<?php

use Illuminate\Routing\Router;

/**
 * @var Router $router
 */
$router->get('/', 'HomeController@index');
$router->auth();

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('dashboard', 'DashboardController@index');

    /* Photos */
    $router->get('photos', 'PhotosController@index');
    $router->get('photos/{id}/edit', 'PhotosController@edit');
    $router->patch('photos/{id}', 'PhotosController@update');
    $router->delete('photos/{id}', 'PhotosController@destroy');
    $router->post('photos/upload', 'PhotosController@upload');

    /* Categories */
    $router->get('categories', 'CategoriesController@index');
    $router->get('categories/create', 'CategoriesController@create');
    $router->get('categories/{id}/edit', 'CategoriesController@edit');
    $router->post('categories', 'CategoriesController@store');
    $router->patch('categories/{id}', 'CategoriesController@update');
    $router->delete('categories/{id}', 'CategoriesController@destroy');

    /* Operation Types */
    $router->get('operation-types', 'OperationTypesController@index');
    $router->get('operation-types/create', 'OperationTypesController@create');
    $router->get('operation-types/{id}/edit', 'OperationTypesController@edit');
    $router->post('operation-types', 'OperationTypesController@store');
    $router->patch('operation-types/{id}', 'OperationTypesController@update');
    $router->delete('operation-types/{id}', 'OperationTypesController@destroy');
});
