<?php

namespace App\Models;

use System\DB;

class ShowNewest
{
	public static function get($limit)
	{
		$st = DB::prepare("SELECT * FROM `pururin_main_data` ORDER BY `created_at` DESC LIMIT {$limit};");
		$st->execute();
		return $st->fetchAll();
	}
}