<?php

require __DIR__ . "/../vendor/autoload.php";

if (isset($_GET['page'])) {
	$app = new \App\Api;
	switch ($_GET['page']) {
		case 'newest':
			$app->newest();
			break;
		
		default:
			http_response_code(404);
			break;
	}
}