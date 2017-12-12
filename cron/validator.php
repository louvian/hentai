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
	print "Checking $dt[id]...\n";
	if (! file_exists($path."/".$dt['info']['Pages'].".jpg")) {
		print "Invalid data $dt[id]!\n";
		$i = 1;
		print "Repairing data $dt[id]...\n";
		while ($i <= $dt['info']['Pages']) {
			if (file_exists($path."/".$i.".jpg")) {
				echo "Asset $i validated\n";
			} else {
				shell_exec("cd ".$path. "&& wget http://pururin.us/assets/images/data/".$dt['id']."/".$i.".jpg >> /dev/null 2>&1");
				if (file_exists($path."/".$i.".jpg")) {
					print "Asset $i $dt[id] repaired!\n";
				} else {
					shell_exec("cd ".$path. "&& wget http://pururin.us/assets/images/data/".$dt['id']."/".$i.".png -O ".$i.".jpg >> /dev/null 2>&1");
					if (file_exists($path."/".$i.".jpg")) {
						print "Asset $i $dt[id] repaired successfully!\n";
					} else {
						print "Asset $i $dt[id] failed to repair!\n";
					}
				}
			}
			$i++;
		}
	} else {
		print "Valid $dt[id]\n";
	}
	print "\n\n";
}


