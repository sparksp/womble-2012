<?php namespace Laravel\Routing;

use Laravel\Arr;
use RecursiveIteratorIterator as Iterator;
use RecursiveDirectoryIterator as DirectoryIterator;

class Loader {

	/**
	 * The location of the base routes file.
	 *
	 * @var string
	 */
	protected $base;

	/**
	 * The directory containing nested route files.
	 *
	 * @var string
	 */
	protected $nest;

	/**
	 * A cache for all of the routes defined for the entire application.
	 *
	 * @var array
	 */
	protected $everything;

	/**
	 * Create a new route loader instance.
	 *
	 * @param  string  $base
	 * @param  string  $nest
	 * @return void
	 */
	public function __construct($base, $nest)
	{
		$this->base = $base;
		$this->nest = $nest;
	}

	/**
	 * Load the applicable routes for a given URI.
	 *
	 * @param  string  $uri
	 * @return array
	 */
	public function load($uri)
	{
		$segments = Arr::without(explode('/', $uri), '');

		return array_merge($this->nested($segments), require $this->base.'routes'.EXT);
	}

	/**
	 * Get the appropriate routes from the routes directory for a given URI.
	 *
	 * This method works backwards through the URI segments until we find the
	 * deepest possible matching route directory. Once the deepest directory
	 * is found, all of the applicable routes will be returend.
	 *
	 * @param  array  $segments
	 * @return array
	 */
	protected function nested($segments)
	{
		foreach (array_reverse($segments, true) as $key => $value)
		{
			$path = $this->nest.implode('/', array_slice($segments, 0, $key + 1)).EXT;

			if (file_exists($path)) return require $path;
		}

		return array();
	}

	/**
	 * Get every route defined for the application.
	 *
	 * The entire routes directory will be searched recursively to gather
	 * every route for the application. Of course, the routes in the root
	 * routes file will be returned as well.
	 *
	 * @return array
	 */
	public function everything()
	{
		if ( ! is_null($this->everything)) return $this->everything;

		$routes = array();

		// First, we'll grab the base routes from the application directory.
		// Once we have these, we'll merge all of the nested routes in the
		// routes directory into this array of routes.
		if (file_exists($path = $this->base.'routes'.EXT))
		{
			$routes = array_merge($routes, require $path);
		}

		if ( ! is_dir($this->nest)) return $routes;

		$iterator = new Iterator(new DirectoryIterator($this->nest), Iterator::SELF_FIRST);

		foreach ($iterator as $file)
		{
			// Since some Laravel developers may place HTML files in the route
			// directories, we will check for the PHP extension before merging
			// the file. Typically, the HTML files are present in installations
			// that are not using mod_rewrite and the public directory.
			if (filetype($file) === 'file' and strpos($file, EXT) !== false)
			{
				$routes = array_merge(require $file, $routes);
			}
		}

		return $this->everything = $routes;
	}

}