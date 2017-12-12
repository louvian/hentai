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
		$a = file_get_contents("../a.tmp");
		$a = explode("<div class=\"gallery-listing\"", $a, 2);
		$a = explode("<a href=\"", $a[1]);
		$link = [];
		foreach ($a as $val) {
			$val = explode("\"", $val, 2);
			if (substr($val[0], 0, 26) === "http://pururin.us/gallery/") {
				$val[0] = explode("/", $val[0]);
				unset($val[0][5]);
				$link[] = implode("/", $val[0]);
			}
		}
		return $link;
	}
}