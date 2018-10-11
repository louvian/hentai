<?php
ini_set("display_errors", true);
require __DIR__ . "/../vendor/autoload.php";

$app = new \App\Index();
$app->run();