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
	//return View::make('start')->with('bodyClass', 'skin-black');
	return Redirect::to('tickets');
});

/**
* Auth routes
*/
Route::get('login/{email}', 'AuthController@login');
Route::get('login', 'AuthController@login');
Route::post('login', 'AuthController@authenticate');
Route::get('logout', 'AuthController@logout');
Route::get('signup', 'AuthController@signup');
Route::get('signup/{email}', 'AuthController@signup');
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
Route::group(array('prefix' => 'admin', 'before' => 'administrator'), function()
{
	//Route::get('/', 'Admin\Controllers\DashboardController@index');
	Route::controller('/', 'Admin\Controllers\AdminController');
});

/**
* Tickets system routes
*/
Route::group(array('prefix' => 'tickets', 'before' => 'tickets'), function(){
	Route::controller('/', 'Tickets\Controllers\TicketsController');
});

/**
* Files routes
* Only auth users can manage files
*/
Route::group(array('before'=>'auth'), function() {   
    Route::resource('files', 'FilesController');
});
