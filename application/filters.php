<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Filters
	|--------------------------------------------------------------------------
	|
	| Filters provide a convenient method for attaching functionality to your
	| routes. Filters can run either before or after a route is exectued.
	|
	| The built-in "before" and "after" filters are called before and after
	| every request to your application; however, you may create other filters
	| that can be attached to individual routes.
	|
	| Filters also make common tasks such as authentication and CSRF protection
	| a breeze. If a filter that runs before a route returns a response, that
	| response will override the route action.
	|
	| Let's walk through an example...
	|
	| First, define a filter:
	|
	|		'simple_filter' => function()
	|		{
	|			return 'Filtered!';
	|		}
	|
	| Next, attach the filter to a route:
	|
	|		'GET /' => array('before' => 'simple_filter', function()
	|		{
	|			return 'Hello World!';
	|		})
	|
	| Now every requests to http://example.com will return "Filtered!", since
	| the filter is overriding the route action by returning a value.
	|
	| To make your life easier, we have built authentication and CSRF filters
	| that are ready to attach to your routes. Enjoy.
	|
	*/

	'before' => function()
	{
		if ($user = Auth::user())
		{
			User::$timestamps = false;
			$user->seen_at = date('Y-m-d H:i:s');
			$user->save();

			User::$timestamps = true;
		}
	},


	'after' => function($response)
	{
		// Do stuff after every request to your application.
	},


	'auth' => function()
	{
		if (Auth::guest()) return Response::error(403);
	},

	'role' => function($role)
	{
		if (Auth::guest()) return Response::error(403);

		if ($role === 'admin')
		{
			if ( ! Auth::user()->admin)
			{
				return Response::error(403);
			}
		}
		else
		{
			throw new UnexpectedValueException("Unknown role \"$role\"");
		}
	},

	'csrf' => function()
	{
		if (Request::forged())
		{
			throw new RuntimeException('Request forged');
		}
	},

);