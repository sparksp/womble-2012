<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Application Routes
	|--------------------------------------------------------------------------
	|
	| Simply tell Laravel the HTTP verbs and URIs it should respond to. It's a
	| piece of cake to create beautiful applications using the elegant RESTful
	| routing available in Laravel.
	|
	| Let's respond to a simple GET request to http://example.com/hello:
	|
	|		'GET /hello' => function()
	|		{
	|			return 'Hello World!';
	|		}
	|
	| You can even respond to more than one URI:
	|
	|		'GET /hello, GET /world' => function()
	|		{
	|			return 'Hello World!';
	|		}
	|
	| It's easy to allow URI wildcards using (:num) or (:any):
	|
	|		'GET /hello/(:any)' => function($name)
	|		{
	|			return "Welcome, $name.";
	|		}
	|
	*/

	// Auth related routes
	'GET /login' => array('name' => 'login', function()
	{
		if (!Auth::check())
		{
			// The /login page itself is not Forbidden (403) and should be served
			// with status 200, so we make a view of the form rather than serve a
			// Response::error.  The only difference is the status code.
			return View::make('error.403');
		}
		else
		{
			return Redirect::to('')
				->with('message', '<strong>Log in:</strong> You are already logged in.');
		}
	}),
	'POST /login' => array('before' => 'csrf', function()
	{
		if (Auth::attempt(Input::get('email'), Input::get('password'), Input::get('remember', 'no') == 'yes'))
		{
			$to = Input::has('from') ? URL::to(Input::get('from')) : URL::to('');
			return Redirect::to($to)->with('success', '<strong>Log in:</strong> Welcome to Womble!');
		}
		else
		{
			Session::flash('error', 'E-mail or password wrong, please try again.');
			return Response::error(403);
		}
	}),
	'GET /logout' => array('name' => 'logout', function()
	{
		Auth::logout();
		return Redirect::to('')->with('success', '<strong>Log out:</strong> Goodbye, we\'ll miss you!');
	}),

	// These routes are needed to bypass the next 'catch-all' route
	'GET /booking' => 'booking@index',
	'GET /booking/new' => 'booking@new',
	'GET /booking/(:any)' => 'booking@index',
	'PUT /booking/(:any)' => 'booking@index',

	'GET /' => function()
	{
		return View::make('master')
			->nest('content', 'index');
	},

	// Catch all route
	'GET /(:any), GET /activities/(:any)' => function($path = 'index')
	{
		$path = ltrim(Laravel\Request::uri(), '/') ?: 'index';
		$page = Page::find($path);

		if ($page !== false)
		{
			return View::make('master', array(
				'content' => $page->toHTML()
			));
		}
		else
		{
			return Laravel\Response::error(404);
		}
	},

);
