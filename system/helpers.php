<?php

if (! function_exists("view")) {
	function view($___file, $___variables = []) {
		foreach ($___variables as $___k => $___v) {
			$$___k = $___v;
		}
		return require realpath(__DIR__."/../app/Views/".$___file.".php");
	}
}