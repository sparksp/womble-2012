<?php namespace Laravel;

class Redirect extends Response {

	/**
	 * Create a redirect response.
	 *
	 * <code>
	 *		// Create a redirect response to a location within the application
	 *		return Redirect::to('user/profile');
	 *
	 *		// Create a redirect with a 301 status code
	 *		return Redirect::to('user/profile', 301);
	 *
	 *		// Create a redirect response to a location outside of the application
	 *		return Redirect::to('http://google.com');
	 * </code>
	 *
	 * @param  string    $url
	 * @param  int       $status
	 * @param  bool      $https
	 * @return Redirect
	 */
	public static function to($url, $status = 302, $https = false)
	{
		return static::make('', $status)->header('Location', URL::to($url, $https));
	}

	/**
	 * Create a redirect response to a HTTPS URL.
	 *
	 * @param  string    $url
	 * @param  int       $status
	 * @return Response
	 */
	public static function to_secure($url, $status = 302)
	{
		return static::to($url, $status, true);
	}

	/**
	 * Add an item to the session flash data.
	 *
	 * This is useful for passing status messages or other temporary data to the next request.
	 *
	 * <code>
	 *		// Create a redirect response and flash something to the session
	 *		return Redirect::to('user/profile')->with('message', 'Welcome Back!');
	 * </code>
	 *
	 * @param  string          $key
	 * @param  mixed           $value
	 * @return Response
	 */
	public function with($key, $value)
	{
		if (Config::get('session.driver') == '')
		{
			throw new \LogicException('A session driver must be set before setting flash data.');
		}

		IoC::core('session')->flash($key, $value);

		return $this;
	}

	/**
	 * Flash the old input to the session and return the Redirect instance.
	 *
	 * Once the input has been flashed, it can be retrieved via the Input::old method.
	 *
	 * <code>
	 *		// Redirect and flash all of the input data to the session
	 *		return Redirect::to_login()->with_input();
	 *
	 *		// Redirect and flash only a few of the input items
	 *		return Redirect::to_login()->with_input('only', array('email', 'username'));
	 *
	 *		// Redirect and flash all but a few of the input items
	 *		return Redirect::to_login()->with_input('except', array('password', 'ssn'));
	 * </code>
	 *
	 * @param  string    $filter
	 * @param  array     $items
	 * @return Redirect
	 */
	public function with_input($filter = null, $items = array())
	{
		Input::flash($filter, $items);
		return $this;
	}

	/**
	 * Flash a Validator's errors to the session data.
	 *
	 * This method allows you to conveniently pass validation errors back to views.
	 *
	 * <code>
	 *		// Redirect and flash a validator's errors the session
	 *		return Redirect::to('register')->with_errors($validator);
	 *
	 *		// Redirect and flash a message container to the session
	 *		return Redirect::to('register')->with_errors($messages);
	 * </code>
	 *
	 * @param  Validator|Messages  $container
	 * @return Redirect
	 */
	public function with_errors($container)
	{
		$errors = ($container instanceof Validator) ? $container->errors : $container;

		return $this->with('errors', $errors);
	}

	/**
	 * Magic Method to handle creation of redirects to named routes.
	 *
	 * <code>
	 *		// Create a redirect response to the "profile" named route
	 *		return Redirect::to_profile();
	 *
	 *		// Create a redirect response to a named route using HTTPS
	 *		return Redirect::to_secure_profile();
	 *
	 *		// Create a redirect response to a named route with wildcard parameters
	 *		return Redirect::to_profile(array($username));
	 * </code>
	 */
	public static function __callStatic($method, $parameters)
	{
		// Extract the parameters that should be placed in the URL. These parameters
		// are used to fill all of the wildcard slots in the route URI definition.
		// They are passed as the first parameter to this magic method.
		$wildcards = (isset($parameters[0])) ? $parameters[0] : array();

		$status = (isset($parameters[1])) ? $parameters[1] : 302;

		if (strpos($method, 'to_secure_') === 0)
		{
			return static::to(URL::to_route(substr($method, 10), $wildcards, true), $status);
		}

		if (strpos($method, 'to_') === 0)
		{
			return static::to(URL::to_route(substr($method, 3), $wildcards), $status);
		}

		throw new \BadMethodCallException("Method [$method] is not defined on the Redirect class.");
	}

}