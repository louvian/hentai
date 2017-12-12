<?php

namespace App;

class Index
{
	public function run()
	{
		return view("index", 
			[
				"data" => \App\Models\Index::get()
			]
		);
	}
}