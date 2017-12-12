<?php

namespace App;

class Reader
{
	public function __construct()
	{
	}

	public function run()
	{
		if (! isset($_GET['id'])) {
			http_response_code(400);
			exit("parameter required");
		}
		$dt = \App\Models\Read::get($_GET['id']);
		if (! $dt) {
			http_response_code(404);
			exit("Not found");
		}
		$dt['info'] = json_decode($dt['info'], true);
		return view("read", ["dt" => $dt]);
	}
}