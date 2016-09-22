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
    $router->post('photos/upload/getid', 'PhotosController@uploadAndCreate');
    $router->post('photos/all/list', 'PhotosController@getAllImagesList');

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

    /* Items */
    $router->get('items', 'ItemsController@index');
    $router->get('items/create', 'ItemsController@create');
    $router->get('items/{id}/edit', 'ItemsController@edit');
    $router->post('items', 'ItemsController@store');
    $router->patch('items/{id}', 'ItemsController@update');
    $router->delete('items/{id}', 'ItemsController@destroy');
});
