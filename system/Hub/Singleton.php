<?php

namespace System\Hub;

trait Singleton
{	
	private static $__instance;

	public static function getInstance(...$parameters)
	{
		if (self::$__instance === null) {
			self::$__instance = new self(...$parameters);
		}
		return self::$__instance;
	}
}