<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

/**
* Auth routes
*/
Route::get('login', 'AuthController@login');
Route::post('login', 'AuthController@authenticate');
Route::get('logout', 'AuthController@logout');
Route::get('signup', 'AuthController@signup');
Route::post('signup', 'AuthController@registerUser');
Route::get('confirm/{code}', 'AuthController@confirmUser');

/**
* Password remind controller
*/
Route::controller('password', 'RemindersController');

/**
* Public API route
*/
Route::controller('api', 'PublicApiController');

/**
* Admin routes
*/
Route::group(array('prefix' => 'admin', 'before' => 'auth'), function()
{
	Route::get('/', 'Admin\Controllers\DashboardController@index');
});
