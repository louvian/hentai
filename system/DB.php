<?php

namespace System;

use PDO;
use System\Hub\Singleton;

class DB
{
	use Singleton;

	/**
	 * @var \PDO
	 */
	private $pdo;

	/**
	 * PDO Constructor.
	 */
	public function __construct()
	{
		$this->pdo = new PDO(
			"mysql:host=".DB_HOST.";dbname=".DB_NAME.";port=".DB_PORT, 
			DB_USER, 
			DB_PASS
		);
	}

	/**
	 * __callStatic
	 *
	 * @param string $method
	 * @param array  $parameters
	 */
	public static function __callStatic($method, $parameters)
	{
		return self::getInstance()->pdo->{$method}(...$parameters);
	}
}