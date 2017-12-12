<?php

namespace Cron;

use Curl\Curl;

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
		if (file_exists(PURURIN_DATA."/pointer")) {
			$pointer = (int) file_get_contents(PURURIN_DATA."/pointer");
		} else {
			$pointer = 1;
		}
		$ch = new Curl("http://pururin.us/browse/search?q=rape&sType=normal&page=".$pointer);
		$a = explode("<div class=\"gallery-listing\"", $ch->exec(), 2);
		if (! isset($a[1])) {
			return [];
		}
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
		file_put_contents(PURURIN_DATA."/pointer", $pointer+1);
		return $link;
	}
}