<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\OperationTypesController;
use App\Http\Controllers\OperationsController;
use App\Http\Controllers\PhotosController;
use Illuminate\Routing\Router;

/**
 * @var Router $router
 */
$router->get('/', [HomeController::class, 'index']);
$router->auth();

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('dashboard', [DashboardController::class, 'index']);

    /* Photos */
    $router->get('photos', [PhotosController::class, 'index']);
    $router->get('photos/{id}/edit', [PhotosController::class, 'edit']);
    $router->patch('photos/{id}', [PhotosController::class, 'update']);
    $router->delete('photos/{id}', [PhotosController::class, 'destroy']);
    $router->post('photos/upload', [PhotosController::class, 'upload']);
    $router->post('photos/upload/getid', [PhotosController::class, 'uploadAndCreate']);
    $router->post('photos/all/list', [PhotosController::class, 'getAllImagesList']);

    /* Categories */
    $router->get('categories', [CategoriesController::class, 'index']);
    $router->get('categories/create', [CategoriesController::class, 'create']);
    $router->get('categories/{id}/edit', [CategoriesController::class, 'edit']);
    $router->post('categories', [CategoriesController::class, 'store']);
    $router->patch('categories/{id}', [CategoriesController::class, 'update']);
    $router->delete('categories/{id}', [CategoriesController::class, 'destroy']);

    /* Operation Types */
    $router->get('operation-types', [OperationTypesController::class, 'index']);
    $router->get('operation-types/create', [OperationTypesController::class, 'create']);
    $router->get('operation-types/{id}/edit', [OperationTypesController::class, 'edit']);
    $router->post('operation-types', [OperationTypesController::class, 'store']);
    $router->patch('operation-types/{id}', [OperationTypesController::class, 'update']);
    $router->delete('operation-types/{id}', [OperationTypesController::class, 'destroy']);

    /* Items */
    $router->get('items', [ItemsController::class, 'index'])->name('available-items');
    $router->get('items/create', [ItemsController::class, 'create']);
    $router->get('items/{id}/edit', [ItemsController::class, 'edit']);
    $router->get('items/{id}/show', [ItemsController::class, 'show']);
    $router->post('items', [ItemsController::class, 'store']);
    $router->patch('items/{id}', [ItemsController::class, 'update']);
    $router->delete('items/{id}', [ItemsController::class, 'destroy']);

    /* Operations */
    $router->get('operations', [OperationsController::class, 'index']);
    $router->get('operations/create/{id__Item}', [OperationsController::class, 'create']);
    $router->get('operations/{id}/edit', [OperationsController::class, 'edit']);
    $router->post('operations', [OperationsController::class, 'store']);
    $router->patch('operations/{id}', [OperationsController::class, 'update']);
    $router->delete('operations/{id}', [OperationsController::class, 'destroy']);
});
