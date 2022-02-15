<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});



// Group together routes under same prefix
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('/login', 'AuthController@login');
    $router->post('/register', 'AuthController@register');

    // Product CRUD Endpoints
    $router->get('/products', 'PostController@get_products'); // Get all products
    $router->get('/products/{id}', 'PostController@getSingleProduct'); // Get a single product
    $router->post('/products', 'PostController@createNewProduct'); // Create product on main admin system
    $router->post('/products/{id}', 'PostController@createNewProduct'); //Create product on vendor portal
    $router->delete('/products/{id}', 'PostController@deleteProduct'); // Delete a single product
    $router->patch('/products/{id}', 'PostController@updateProductDetails'); // Update a product
    $router->delete('/products/{id}/{softid}', 'PostController@deleteProduct'); // Soft delete a single product
    $router->get('/productsCsv', 'PostController@exportToCSV'); // Export product CSV
    $router->get('/productsExcel', 'PostController@exportProductToExcel'); // Export product xlsx
    $router->delete('/image/{id}', 'PostController@deleteInMultipleImages'); // delete Multiple Image
    $router->delete('/image/{id}/{imgid}', 'PostController@deleteInMultipleImages'); // delete Single Image

    // Authenticate routs
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->post('api/logout', 'AuthController@logout');
        $router->get('/posts', 'PostController@index');
    });
});
