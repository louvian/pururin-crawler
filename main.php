<?php

require __DIR__ . "/vendor/autoload.php";

$saveDir = __DIR__ . "/data";

if (! is_dir($saveDir)) {
	mkdir($saveDir);
}

$app = new Pururin\PururinCrawler(
	[
		"save_directory" => $saveDir,
		"manga_url"		 => $mangaUrl
	]
);
$app->run();
