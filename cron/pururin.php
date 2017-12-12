<?php

date_default_timezone_set("Asia/Jakarta");

if (file_exists(__DIR__."/assets/pururin/lock")) {
	print "[".date("Y-m-d H:i:s")."] Locked\n";
	exit(0);
} else {
	file_put_contents(__DIR__."/assets/pururin/lock", 1);
}

require __DIR__ . "/../vendor/autoload.php";

$saveDir  = __DIR__ . "/../assets/pururin";

if (file_exists(__DIR__ . "/assets/pururin/pending_files.txt")) {
	$mangaUrls = explode("\n", __DIR__ . "/assets/pururin/pending_files.txt");
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
				var_dump($st->errorInfo());
			}
		}
	} catch (\Exception $e) {
		file_put_contents(__DIR__ . "/assets/pururin/pending_files.txt", $mangaUrl, FILE_APPEND | LOCK_EX);
		echo "Pending\n";
	}
}
unlink(__DIR__."/assets/pururin/lock");
