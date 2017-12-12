<?php

namespace App\Models;

use System\DB;

class ShowNewest
{
	public static function get()
	{
		$st = DB::prepare("SELECT * FROM `pururin_main_data` ORDER BY `created_at` LIMIT 10;");
		$st->execute();
		return $st->fetchAll();
	}
}