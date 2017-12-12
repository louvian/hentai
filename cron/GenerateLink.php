<?php

namespace Cron;

class GenerateLink
{
	public static function generate($site)
	{
		switch ($site) {
			case 'pururin':
				return self::pururin();
				break;
			
			default:
				# code...
				break;
		}
	}

	private static function pururin()
	{
		
	}
}