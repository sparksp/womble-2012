<?php namespace Laravel\Cache;

use Laravel\Redis;
use Laravel\Config;
use Laravel\Memcached;

class Manager {

	/**
	 * All of the active cache drivers.
	 *
	 * @var array
	 */
	protected static $drivers = array();

	/**
	 * Get a cache driver instance.
	 *
	 * If no driver name is specified, the default cache driver will
	 * be returned as defined in the cache configuration file.
	 *
	 * <code>
	 *		// Get the default cache driver instance
	 *		$driver = Cache::driver();
	 *
	 *		// Get a specific cache driver instance by name
	 *		$driver = Cache::driver('memcached');
	 * </code>
	 *
	 * @param  string        $driver
	 * @return Cache\Driver
	 */
	public static function driver($driver = null)
	{
		if (is_null($driver)) $driver = Config::get('cache.driver');

		if ( ! array_key_exists($driver, static::$drivers))
		{
			return static::$drivers[$driver] = static::factory($driver);
		}

		return static::$drivers[$driver];
	}

	/**
	 * Create a new cache driver instance.
	 *
	 * @param  string  $driver
	 * @return Driver
	 */
	protected static function factory($driver)
	{
		switch ($driver)
		{
			case 'apc':
				return new Drivers\APC(Config::get('cache.key'));

			case 'file':
				return new Drivers\File(CACHE_PATH);

			case 'memcached':
				return new Drivers\Memcached(Memcached::instance(), Config::get('cache.key'));

			case 'redis':
				return new Drivers\Redis(Redis::db());

			default:
				throw new \DomainException("Cache driver {$driver} is not supported.");
		}
	}

	/**
	 * Pass all other methods to the default cache driver.
	 *
	 * Passing method calls to the driver instance provides a convenient API
	 * for the developer when always using the default cache driver.
	 *
	 * <code>
	 *		// Call the "get" method on the default driver
	 *		$name = Cache::get('name');
	 *
	 *		// Call the "put" method on the default driver
	 *		Cache::put('name', 'Taylor', 15);
	 * </code>
	 */
	public static function __callStatic($method, $parameters)
	{
		return call_user_func_array(array(static::driver(), $method), $parameters);
	}

}
