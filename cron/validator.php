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
	print "Checking ID ".$dt['id']."...\n";
	$i = 0;
	while ($i <= $dt['info']['Pages']) {
		if ($i === 0) {
			if (! is_valid_file($path."/cover.jpg")) {
				print "Invalid cover.jpg\n";
				print "Downloading cover.jpg...\n";
				shell_exec("cd $path && wget http://pururin.us/assets/images/data/".$dt['id']."/cover.jpg >> /dev/null 2>&1");
				if (! is_valid_file($path."/cover.jpg")) {
					print "Invalid cover.jpg\n";
					shell_exec("cd $path && wget http://pururin.us/assets/images/data/".$dt['id']."/cover.png -O cover.jpg >> /dev/null 2>&1");
					if (! is_valid_file($path."/cover.jpg")) {
						print "Invalid cover absolutely\n";
					} else {
						print "cover.jpg has been repaired with cover.png\n";
					}
				} else {
					print "cover.jpg has been repaired!\n";
				}
			} else {
				print "cover.jpg is valid!\n";
			}
		} else {
			if (! is_valid_file($path."/".$i.".jpg")) {
				print "Invalid $i.jpg\n";
				print "Downloading $i.jpg...\n";
				shell_exec("cd $path && wget http://pururin.us/assets/images/data/".$dt['id']."/$i.jpg >> /dev/null 2>&1");
				if (! is_valid_file($path."/$i.jpg")) {
					print "Invalid $i.jpg\n";
					shell_exec("cd $path && wget http://pururin.us/assets/images/data/".$dt['id']."/$i.png -O $i.jpg >> /dev/null 2>&1");
					if (! is_valid_file($path."/$i.jpg")) {
						print "Invalid $i.jpg absolutely\n";
					} else {
						print "$i.jpg has been repaired with $i.png\n";
					}
				} else {
					print "$i.jpg has been repaired!\n";
				}
			} else {
				print "$i.jpg is valid!\n";
			}
		}
		$i++;
	}
}


function is_valid_file($file)
{
	return file_exists($file) and (
		(filesize($file) > 0) or unlink($file)
	);
}