<?php

require __DIR__ . "/../vendor/autoload.php";

use System\DB;
use Cron\GenerateLink;
use Pururin\PururinCrawler;

/**
 * Set timezone
 */
date_default_timezone_set("Asia/Jakarta");

/**
 * Manage lock file.
 */
if (file_exists(PURURIN_DATA."/lock")) {
	print "[".date("Y-m-d H:i:s")."] Locked\n";
	exit(0);
} else {
	file_put_contents(PURURIN_DATA."/lock", 1);
}

/**
 * Manage pending file.
 */
if (file_exists(PURURIN_DATA."/pending_files.txt")) {
	print "Loading pending files...\n";
	$mangaUrls = json_decode(
		file_get_contents(PURURIN_DATA."/pending_files.txt"), 
		true
	);
	if (! empty($mangaUrls)) {
		foreach ($mangaUrls as $key => $val) {
			print "Downloading pending files $key...";
			if (($ex = process($key, $val)) === true) {
				unset($mangaUrls[$key]);
			} else {
				$mangaUrls[$key] = $ex->getPoint();
			}
			file_put_contents(
				PURURIN_DATA."/pending_files.txt", 
				json_encode($mangaUrls), 
				FILE_APPEND | LOCK_EX
			);
		}
	} else {
		normal();
	}
} else {
	normal();
}

function normal()
{
	print "Generating link...\n";
	$mangaUrls = GenerateLink::generate('pururin');
	print "Generated.\n";
	$errors = [];
	foreach ($mangaUrls as $val) {
		print "Downloading $val...\n";
		if (($ex = process($val)) !== true) {
			print "Download error $val\n";
			$errors[$val] = $ex->getPoint();
			file_put_contents(
				PURURIN_DATA."/pending_files.txt", 
				json_encode($errors), 
				FILE_APPEND | LOCK_EX
			);
		}
	}
}


function process($mangaUrl, $val = 1)
{
	try {
		$app = new PururinCrawler(
			[
				"save_directory" => PURURIN_DATA,
				"manga_url"		 => $mangaUrl,
				"offset"		 => 1
			]
		);
		if ($app->run()) {
			$st = DB::prepare(
				"INSERT INTO `pururin_main_data` (`id`, `title`, `info`, `origin_link`, `created_at`, `updated_at`) VALUES (:id, :title, :info, :origin_link, :created_at, :updated_at);"
			);
			$st->execute(
				array_merge(
					$data = $app->getResult(), 
					[
						"created_at" => date("Y-m-d H:i:s"), 
						"updated_at" => null
					]
				)
			);
			$data['info'] = json_decode($data['info'], true);
			if (isset($data['info']['Contents'])) {
				$query = "INSERT INTO `pururin_genres` (`id`,`genre`) VALUES ";
				$i = 0;
				$queryValue = [
					":id" => $data['id']
				];
				foreach ($data['info']['Contents'] as $val) {
					$query .= "(:id, :genre{$i}),";
					$queryValue[':genre'.($i++)] = $val;
				}
				$st = DB::prepare(rtrim($query, ","));
				$exe = $st->execute($queryValue);
			}
		}
		return true;
	} catch (Exception $e) {
		return $e;	
	}
}

unlink(PURURIN_DATA."/lock");
