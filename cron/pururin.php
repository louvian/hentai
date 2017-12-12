<?php

require __DIR__ . "/../vendor/autoload.php";

date_default_timezone_set("Asia/Jakarta");

if (file_exists(PURURIN_DATA."/lock")) {
	print "[".date("Y-m-d H:i:s")."] Locked\n";
	exit(0);
} else {
	file_put_contents(PURURIN_DATA."/lock", 1);
}

$saveDir  = PURURIN_DATA;

if (file_exists(PURURIN_DATA."/pending_files.txt")) {
	$mangaUrls = explode("\n", file_get_contents(PURURIN_DATA."/pending_files.txt"));
}

if (empty($mangaUrls)) {
	$mangaUrls = Cron\GenerateLink::generate('pururin');
}

foreach ($mangaUrls as $mangaUrl) {
	if (! is_dir($saveDir)) {
		mkdir($saveDir);
	}
	try {
		$app = new Pururin\PururinCrawler(
			[
				"save_directory" => $saveDir,
				"manga_url"		 => $mangaUrl
			]
		);
		echo "Downloading $mangaUrl...\n";
		if ($app->run()) {
			$st = \System\DB::prepare("INSERT INTO `pururin_main_data` (`id`, `title`, `info`, `origin_link`, `created_at`, `updated_at`) VALUES (:id, :title, :info, :origin_link, :created_at, :updated_at);");
			$st->execute(array_merge($data = $app->getResult(), ["created_at" => date("Y-m-d H:i:s"), "updated_at" => null]));
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
				$st = \System\DB::prepare(rtrim($query, ","));
				$exe = $st->execute($queryValue);
			}
		}
	} catch (\Exception $e) {
		file_put_contents(PURURIN_DATA."/pending_files.txt", $mangaUrl."\n", FILE_APPEND | LOCK_EX);
		echo "Pending\n";
	}
}
unlink(PURURIN_DATA."/lock");
