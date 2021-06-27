<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
    User Routes
*/
$router->group(['prefix' => 'user'], function () use ($router) {
    $router->post('create', ['uses' => 'UserController@store']);
    $router->post('login', ['uses' => 'UserController@login']);

    $router->group(['middleware' => 'auth.jwt'], function () use ($router) {
        $router->get('/', ['uses' => 'UserController@index']);
        $router->get('me', ['uses' => 'UserController@showUser']);
        $router->post('update', ['uses' => 'UserController@update']);
        $router->delete('/{id}', ['uses' => 'UserController@destroy']);
        $router->get('logout', ['uses' => 'UserController@logout']);
    });
});

/*
    Asset Routes
*/
$router->group(['middleware' => 'auth.jwt'], function () use ($router) {

    $router->group(['prefix' => 'asset'], function () use ($router) {
        $router->get('/', ['uses' => 'AssetController@index']);
        $router->post('create', ['uses' => 'AssetController@store']);
        $router->get('/{id}', ['uses' => 'AssetController@showAsset']);
        $router->post('{id}/update', ['uses' => 'AssetController@update']);
        $router->delete('/{id}', ['uses' => 'AssetController@destroy']);
    });
});

/*
    Vendor Routes
*/
$router->group(['middleware' => 'auth.jwt'], function () use ($router) {

    $router->group(['prefix' => 'vendor'], function () use ($router) {
        $router->get('/', ['uses' => 'VendorController@index']);
        $router->post('create', ['uses' => 'VendorController@store']);
        $router->get('/{id}', ['uses' => 'VendorController@showVendor']);
        $router->post('{id}/update', ['uses' => 'VendorController@update']);
        $router->delete('/{id}', ['uses' => 'VendorController@destroy']);
    });
});
/*
    Asset Assignment Routes
*/
$router->group(['middleware' => 'auth.jwt'], function () use ($router) {

    $router->group(['prefix' => 'assgn'], function () use ($router) {
        $router->get('/', ['uses' => 'AssetAssigmentController@index']);
        $router->post('create', ['uses' => 'AssetAssignmentController@store']);
        $router->get('/{id}', ['uses' => 'AssetAssignmentController@showAssignment']);
        $router->post('/{id}/update', ['uses' => 'AssetAssignmentController@update']);
        $router->delete('/{id}', ['uses' => 'AssetAssignmentController@destroy']);
    });
});
