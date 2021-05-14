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


Route::namespace('App\Http\Controllers\Api')->middleware(['json.response', 'cors'])->group(function () {
    Route::post('/register', 'AuthController@register');
    Route::post('/login', 'AuthController@login');
    Route::get('/statusapi', 'AuthController@betaSystems');
});

Route::namespace('App\Http\Controllers\Api')->middleware(['json.response', 'cors', 'auth:sanctum'])->group(function () {
    Route::post('/logout', 'AuthController@logout');

    Route::prefix('/products')->group(function () {
        Route::get('/', 'ProductController@index');
        Route::get('/{id}', 'ProductController@show');
        Route::post('/', 'ProductController@store');
        Route::put('/{id}', 'ProductController@update');
        Route::delete('/{id}', 'ProductController@delete');
    });
    Route::prefix('/employees')->group(function () {
        Route::get('/', 'EmployeeController@index');
        Route::get('/{id}', 'EmployeeController@show');
        Route::post('/', 'EmployeeController@store');
        Route::put('/{id}', 'EmployeeController@update');
        Route::delete('/{id}', 'EmployeeController@delete');
    });

    // Route::prefix('/users')->group(function () {
    //     Route::post('/tryToLogin', 'UserController@isUser');
    //     Route::post('/signUp', 'UserController@newUser');
    //     Route::post('/addProductToShoppingCart', 'UserController@addToShoppingCart');
    // });
    // Route::prefix('/shoppingCart')->group(function () {
    //     Route::get('/listProducts/{userId}', 'ProductsPerUserController@listItemsShoppingCart');
    //     Route::delete('/removeItemCart/{itemId}', 'ProductsPerUserController@removeItemShoppingCart');
    // });
});
