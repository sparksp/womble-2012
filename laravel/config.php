<?php namespace Laravel; use Closure;

class Config {

	/**
	 * All of the loaded configuration items.
	 *
	 * The configuration arrays are keyed by their owning file name.
	 *
	 * @var array
	 */
	public static $items = array();

	/**
	 * The paths to the configuration files.
	 *
	 * @var array
	 */
	public static $paths = array(SYS_CONFIG_PATH, CONFIG_PATH, ENV_CONFIG_PATH);

	/**
	 * Determine if a configuration item or file exists.
	 *
	 * <code>
	 *		// Determine if the "session" configuration file exists
	 *		$exists = Config::has('session');
	 *
	 *		// Determine if the "timezone" option exists in the "application" configuration
	 *		$exists = Config::has('application.timezone');
	 * </code>
	 *
	 * @param  string  $key
	 * @return bool
	 */
	public static function has($key)
	{
		return ! is_null(static::get($key));
	}

	/**
	 * Get a configuration item.
	 *
	 * If no item is requested, the entire configuration array will be returned.
	 *
	 * <code>
	 *		// Get the "session" configuration array
	 *		$session = Config::get('session');
	 *
	 *		// Get the "timezone" option from the "application" configuration file
	 *		$timezone = Config::get('application.timezone');
	 * </code>
	 *
	 * @param  string  $key
	 * @param  string  $default
	 * @return array
	 */
	public static function get($key, $default = null)
	{
		list($file, $key) = static::parse($key);

		if ( ! static::load($file))
		{
			return ($default instanceof Closure) ? call_user_func($default) : $default;
		}

		$items = static::$items[$file];

		// If a specific configuration item was not requested, the key will be null,
		// meaning we need to return the entire array of configuration item from the
		// requested configuration file. Otherwise we can return the item.
		return (is_null($key)) ? $items : Arr::get($items, $key, $default);
	}

	/**
	 * Set a configuration item's value.
	 *
	 * <code>
	 *		// Set the "session" configuration array
	 *		Config::set('session', $array);
	 *
	 *		// Set the "timezone" option in the "application" configuration file
	 *		Config::set('application.timezone', 'UTC');
	 * </code>
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return void
	 */
	public static function set($key, $value)
	{
		list($file, $key) = static::parse($key);

		static::load($file);

		if (is_null($key))
		{
			Arr::set(static::$items, $file, $value);
		}
		else
		{
			Arr::set(static::$items[$file], $key, $value);
		}
	}

	/**
	 * Parse a configuration key and return its file and key segments.
	 *
	 * The first segment of a configuration key represents the configuration
	 * file, while the remaining segments represent an item within that file.
	 * If no item segment is present, null will be returned for the item value
	 * indicating that the entire configuration array should be returned.
	 *
	 * @param  string  $key
	 * @return array
	 */
	protected static function parse($key)
	{
		$segments = explode('.', $key);

		if (count($segments) >= 2)
		{
			return array($segments[0], implode('.', array_slice($segments, 1)));
		}
		else
		{
			return array($segments[0], null);
		}
	}

	/**
	 * Load all of the configuration items from a configuration file.
	 *
	 * @param  string  $file
	 * @return bool
	 */
	public static function load($file)
	{
		if (isset(static::$items[$file])) return true;

		$config = array();

		// Configuration files cascade. Typically, the system configuration array is
		// loaded first, followed by the application array, providing the convenient
		// cascading of configuration options from system to application.
		foreach (static::$paths as $directory)
		{
			if ($directory !== '' and file_exists($path = $directory.$file.EXT))
			{
				$config = array_merge($config, require $path);
			}
		}

		if (count($config) > 0) static::$items[$file] = $config;

		return isset(static::$items[$file]);
	}

}