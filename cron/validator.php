<?php

require __DIR__ . "/../vendor/autoload.php";

use System\DB;
use Pururin\Validator;

$st = DB::prepare("SELECT * FROM `pururin_main_data` ORDER BY `created_at` ASC;");
$st->execute();

while ($dt = $st->fetch(PDO::FETCH_ASSOC)) {
	/*print "Validating $dt[id]...\n";
	$dt['info'] = json_decode($dt['info'], true);
	$app = new Validator(
		$dt['origin_link'], 
		$dt['info']['Pages'], 
		PURURIN_DATA."/".$dt['id']
	);
	$run = $app->run();
	if ($run === "Valid") {
		print "Valid $dt[origin_link]\n";
	}*/
	$dt['info'] = json_decode($dt['info'], true);
	$path = PURURIN_DATA."/".$dt['id'];
	if (! file_exists($path."/".$dt['info']['Pages'].".jpg")) {
		$i = 1;
		while (! file_exists($path."/".$i.".jpg") && $i <= $dt['info']['Pages']) {
			shell_exec("cd ".$path. "&& wget http://pururin.us/assets/images/data/".$i.".jpg");
			$i++;
		}
	}
}


