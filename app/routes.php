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

/**
* Password remind controller
*/
Route::controller('password', 'RemindersController');

/**
* Admin routes
*/
Route::group(array('prefix' => 'admin', 'before' => 'auth'), function()
{
	Route::get('/', 'Admin\Controllers\DashboardController@index');
});
