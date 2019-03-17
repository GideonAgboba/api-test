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

Route::middleware('auth:api')->group(function () {
    Route::get('/user', 'UserController@details');
});

Route::post('/register', 'UserAuthController@create');
Route::post('/login', 'UserAuthController@login');

// social auth
Route::group(['middleware' => ['web']], function () {
	Route::get('login/facebook', 'Auth\LoginController@redirectToProvider');
	Route::get('login/facebook/callback', 'Auth\LoginController@handleProviderCallback');
	Route::get('register/facebook', 'Auth\RegisterController@redirectToProvider');
	Route::get('register/facebook/callback', 'Auth\RegisterController@handleProviderCallback');
});