<?php

date_default_timezone_set("Asia/Jakarta");

if (file_exists(__DIR__."/assets/pururin/lock")) {
	print "[".date("Y-m-d H:i:s")."] Locked\n";
	exit(0);
}



require __DIR__ . "/../vendor/autoload.php";
http://pururin.us/browse/search?q=rape&sType=normal&page=1
$saveDir  = __DIR__ . "/../assets/pururin";
$mangaUrl = "http://pururin.us/gallery/35052/";

if (! is_dir($saveDir)) {
	mkdir($saveDir);
}

$app = new Pururin\PururinCrawler(
	[
		"save_directory" => $saveDir,
		"manga_url"		 => $mangaUrl
	]
);

if ($app->run()) {
	$st = \System\DB::prepare("INSERT INTO `pururin` (`id`, `title`, `info`, `origin_link`, `created_at`, `updated_at`) VALUES (:id, :title, :info, :origin_link, :created_at, :updated_at);");
	$st->execute(array_merge($data = $app->getResult(), ["created_at" => date("Y-m-d H:i:s"), "updated_at" => null]));
	$data['info'] = json_decode($data['info']);
	if (isset($data['info']['Content'])) {
		$query = "INSERT INTO `pururin_genres` (`id`,`genre`) VALUES ";
		$i = 0;
		$queryValue = [
			":id" => $data['id']
		];
		foreach ($data['Content'] as $val) {
			$query .= "(:id, :genre{$i}),";
			$data[':genre'.$i] = $val;
		}
		$st = DB::prepare(rtrim($query, ","));
		$st->execute($queryValue);
	}
}