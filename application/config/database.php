<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Default Database Connection
	|--------------------------------------------------------------------------
	|
	| The name of your default database connection.
	|
	| This connection will be the default for all database operations unless a
	| different connection is specified when performing the operation.
	|
	*/

	'default' => 'sqlite',

	/*
	|--------------------------------------------------------------------------
	| Database Connections
	|--------------------------------------------------------------------------
	|
	| All of the database connections used by your application.
	|
	| Supported Drivers: 'mysql', 'pgsql', 'sqlite'.
	|
	| Note: When using the SQLite driver, the path and "sqlite" extention will
	|       be added automatically. You only need to specify the database name.
	|
	| Using a driver that isn't supported? You can still establish a PDO
	| connection. Simply specify a driver and DSN option:
	|
	|		'odbc' => array(
	|			'driver'   => 'odbc',
	|			'dsn'      => 'your-dsn',
	|			'username' => 'username',
	|			'password' => 'password',
	|		)
	|
	| Note: When using an unsupported driver, Eloquent and the fluent query
	|       builder may not work as expected.
	|
	*/

	'connections' => array(

		'sqlite' => array(
			'driver'   => 'sqlite',
			'database' => 'application',
		),

		'mysql' => array(
			'driver'   => 'mysql',
			'host'     => 'localhost',
			'database' => 'database',
			'username' => 'root',
			'password' => 'password',
			'charset'  => 'utf8',
		),

		'pgsql' => array(
			'driver'   => 'pgsql',
			'host'     => 'localhost',
			'database' => 'database',
			'username' => 'root',
			'password' => 'password',
			'charset'  => 'utf8',
		),

	),

	/*
	|--------------------------------------------------------------------------
	| Redis Databases
	|--------------------------------------------------------------------------
	|
	| Redis is an open source, fast, and advanced key-value store. However, it
	| provides a richer set of commands than a typical key-value store such as
	| APC or memcached.
	|
	| Here you may specify the hosts and ports for your Redis databases.
	|
	| For more information regarding Redis, check out: http://redis.io
	|
	*/

	'redis' => array(

		'default' => array('host' => '127.0.0.1', 'port' => 6379),

	),

);