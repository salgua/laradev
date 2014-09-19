<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('login');
		}
	}
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});


/**
* Administrator filter
*/

Route::filter('administrator', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('login');
		}
	}

	$administrator_role = Auth::user()->role()->where('title', '=', 'administrator')->get();

	if (!count($administrator_role))
	{
		return Redirect::guest('login')->with('error', 'You are not an administrator!');;
	}
	
});

/**
* Tickets filter
*/
Route::filter('tickets', function()
{
	if (
			!Request::is('tickets/guest') && 
			!Request::is('tickets/save') && 
			!Request::is('tickets/code/*') &&
			!Request::is('tickets/comment')
		) //open routes
	{
			//if you are not logged in, go to ticket guest page
			if (Auth::guest()) return Redirect::guest('tickets/guest');

			//if you are not a ticket manager, go to my-tickets page
			$tickets_mamager_role = Auth::user()->role()->where('title', '=', 'tickets manager')->first();
			if (!$tickets_mamager_role)
			{
				return Redirect::to('tickets/my-tickets');
			}
	}
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});
