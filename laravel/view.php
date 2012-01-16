<?php namespace Laravel;

use Closure;

class View {

	/**
	 * The name of the view.
	 *
	 * @var string
	 */
	public $view;

	/**
	 * The view data.
	 *
	 * @var array
	 */
	public $data;

	/**
	 * The path to the view on disk.
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * All of the view composers for the application.
	 *
	 * @var array
	 */
	protected static $composers;

	/**
	 * Create a new view instance.
	 *
	 * @param  string  $view
	 * @param  array   $data
	 * @return void
	 */
	public function __construct($view, $data = array())
	{
		$this->view = $view;
		$this->data = $data;
		$this->path = $this->path($view);

		// If a session driver has been specified, we will bind an instance of
		// the validation error message container to every view. If an errors
		// instance exists in the session, we will use that instance.
		//
		// This makes the implementation of the Post/Redirect/Get pattern very
		// convenient since each view can assume it has a message container.
		if (Config::$items['session']['driver'] !== '' and IoC::core('session')->started())
		{
			$this->data['errors'] = IoC::core('session')->get('errors', function()
			{
				return new Messages;
			});
		}
	}

	/**
	 * Get the path to a given view on disk.
	 *
	 * @param  string  $view
	 * @return string
	 */
	protected function path($view)
	{
		$view = str_replace('.', '/', $view);

		foreach (array(EXT, BLADE_EXT) as $extension)
		{
			if (file_exists($path = VIEW_PATH.$view.$extension))
			{
				return $path;
			}
		}

		throw new \RuntimeException("View [$view] does not exist.");
	}

	/**
	 * Create a new view instance.
	 *
	 * The name of the view given to this method should correspond to a view
	 * within your application views directory. Dots or slashes may used to
	 * reference views within sub-directories.
	 *
	 * <code>
	 *		// Create a new view instance
	 *		$view = View::make('home.index');
	 *
	 *		// Create a new view instance with bound data
	 *		$view = View::make('home.index', array('name' => 'Taylor'));
	 * </code>
	 *
	 * @param  string  $view
	 * @param  array   $data
	 * @return View
	 */
	public static function make($view, $data = array())
	{
		return new static($view, $data);
	}

	/**
	 * Create a new view instance from a view name.
	 *
	 * View names are defined in the application composers file.
	 *
	 * <code>
	 *		// Create an instance of the "layout" named view
	 *		$view = View::of('layout');
	 *
	 *		// Create an instance of the "layout" view with bound data
	 *		$view = View::of('layout', array('name' => 'Taylor'));
	 * </code>
	 *
	 * @param  string  $name
	 * @param  array   $data
	 * @return View
	 */
	public static function of($name, $data = array())
	{
		if ( ! is_null($view = static::name($name)))
		{
			return static::make($view, $data);
		}

		throw new \OutOfBoundsException("Named view [$name] is not defined.");
	}

	/**
	 * Find the key for a view by name.
	 *
	 * The view "key" is the string that should be passed into the "make" method and
	 * should correspond with the location of the view within the application views
	 * directory, such as "home.index" or "home/index".
	 *
	 * @param  string  $name
	 * @return string
	 */
	protected static function name($name)
	{
		static::composers();

		foreach (static::$composers as $key => $value)
		{
			if ($name === $value or $name === Arr::get((array) $value, 'name'))
			{
				return $key;
			}
		}
	}

	/**
	 * Call the composer for the view instance.
	 *
	 * @param  View  $view
	 * @return void
	 */
	protected static function compose(View $view)
	{
		static::composers();

		if (isset(static::$composers[$view->view]))
		{
			foreach ((array) static::$composers[$view->view] as $key => $value)
			{
				if ($value instanceof Closure) return call_user_func($value, $view);
			}
		}
	}

	/**
	 * Load the view composers for the application.
	 *
	 * For better testing flexiblity, we load the composers from the IoC container.
	 *
	 * @return void
	 */
	protected static function composers()
	{
		if ( ! is_null(static::$composers)) return;

		static::$composers = require APP_PATH.'composers'.EXT;
	}

	/**
	 * Get the evaluated string content of the view.
	 *
	 * @return string
	 */
	public function render()
	{
		static::compose($this);

		// All nested views and responses are evaluated before the main view.
		// This allows the assets used by the nested views to be added to the
		// asset container before the main view is evaluated and dumps the
		// links to the assets.
		foreach ($this->data as &$data) 
		{
			if ($data instanceof View or $data instanceof Response)
			{
				$data = $data->render();
			}
		}

		ob_start() and extract($this->data, EXTR_SKIP);

		// If the view is Bladed, we need to check the view for modifications
		// and get the path to the compiled view file. Otherwise, we'll just
		// use the regular path to the view.
		$view = (strpos($this->path, BLADE_EXT) !== false) ? $this->compile() : $this->path;

		try { include $view; } catch (\Exception $e) { ob_get_clean(); throw $e; }

		return ob_get_clean();
	}

	/**
	 * Compile the Bladed view and return the path to the compiled view.
	 *
	 * @return string
	 */
	protected function compile()
	{
		// For simplicity, compiled views are stored in a single directory by
		// the MD5 hash of their name. This allows us to avoid recreating the
		// entire view directory structure within the compiled directory.
		$compiled = STORAGE_PATH.'views/'.md5($this->view);

		// The view will only be re-compiled if the view has been modified
		// since the last compiled version of the view was created or no
		// compiled view exists. Otherwise, the path will be returned
		// without re-compiling.
		if ( ! file_exists($compiled) or (filemtime($this->path) > filemtime($compiled)))
		{
			file_put_contents($compiled, Blade::compile($this->path));
		}

		return $compiled;
	}

	/**
	 * Add a view instance to the view data.
	 *
	 * <code>
	 *		// Add a view instance to a view's data
	 *		$view = View::make('foo')->nest('footer', 'partials.footer');
	 *
	 *		// Equivalent functionality using the "with" method
	 *		$view = View::make('foo')->with('footer', View::make('partials.footer'));
	 *
	 *		// Bind a view instance with data
	 *		$view = View::make('foo')->nest('footer', 'partials.footer', array('name' => 'Taylor'));
	 * </code>
	 *
	 * @param  string  $key
	 * @param  string  $view
	 * @param  array   $data
	 * @return View
	 */
	public function nest($key, $view, $data = array())
	{
		return $this->with($key, static::make($view, $data));
	}

	/**
	 * Add a key / value pair to the view data.
	 *
	 * Bound data will be available to the view as variables.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return View
	 */
	public function with($key, $value)
	{
		$this->data[$key] = $value;
		return $this;
	}

	/**
	 * Magic Method for getting items from the view data.
	 */
	public function __get($key)
	{
		return $this->data[$key];
	}

	/**
	 * Magic Method for setting items in the view data.
	 */
	public function __set($key, $value)
	{
		$this->with($key, $value);
	}

	/**
	 * Magic Method for determining if an item is in the view data.
	 */
	public function __isset($key)
	{
		return array_key_exists($key, $this->data);
	}

	/**
	 * Magic Method for removing an item from the view data.
	 */
	public function __unset($key)
	{
		unset($this->data[$key]);
	}

	/**
	 * Get the evaluated string content of the view.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 * Magic Method for handling the dynamic creation of named views.
	 *
	 * <code>
	 *		// Create an instance of the "layout" named view
	 *		$view = View::of_layout();
	 *
	 *		// Create an instance of a named view with data
	 *		$view = View::of_layout(array('name' => 'Taylor'));
	 * </code>
	 */
	public static function __callStatic($method, $parameters)
	{
		if (strpos($method, 'of_') === 0)
		{
			return static::of(substr($method, 3), Arr::get($parameters, 0, array()));
		}

		throw new \BadMethodCallException("Method [$method] is not defined on the View class.");
	}

}