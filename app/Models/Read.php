<?php

namespace App\Models;

use PDO;
use System\DB;

class Read
{
	public static function get($id)
	{
		$st = DB::prepare("SELECT * FROM `pururin_main_data` WHERE `id`=:id LIMIT 1;");
		$st->execute([':id' => $id]);
		return $st->fetch(PDO::FETCH_ASSOC);
	}
}