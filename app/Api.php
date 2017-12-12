<?php

namespace App;

class Api
{
	public function newest()
	{
		$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;
		header("Content-type:application/json");
		print json_encode(\App\Models\ShowNewest::get($limit), JSON_UNESCAPED_SLASHES);
	}
}