<?php

require __DIR__ . "/../vendor/autoload.php";

use System\DB;
use Pururin\Validator;

$st = DB::prepare("SELECT * FROM `pururin_main_data` ORDER BY `created_at` ASC;");
$st->execute();

while ($dt = $st->fetch(PDO::FETCH_ASSOC)) {
	print "Validating $dt[id]...\n";
	$dt['info'] = json_decode($dt['info'], true);
	$app = new Validator(
		$dt['origin_url'], 
		$dt['info']['Pages'], 
		PURURIN_DATA."/".$dt['id']
	);
	$run = $app->run();
	if ($run === "Valid") {
		print "Valid $dt[origin_link]\n";
	}
}

