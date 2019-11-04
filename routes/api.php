<?php

use Illuminate\Http\Request;

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

Route::namespace('api')->name('.api')->group(function(){
    Route::prefix('users')->group(function() {

        Route::get('', 'UserController@index')->name('users');
        Route::get('/{id}', 'UserController@show')->name('show.user');
        Route::post('/', 'RegisterController@store')->name('create.user');
        Route::put('/{id}', 'UserController@update')->name('update.user');
        Route::delete('/{id}', 'UserController@destroy')->name('delete.user');  

        // Verify user
        Route::get('/user/verify/{token}', 'RegisterController@verifyUser');
        Route::get('/user/token', 'RegisterController@getToken');

    });
});
