<?php

if(!isset($index) || !$index) {
	http_response_code(404);
	exit();
}

function check_method(array $allowed_methods) {
	foreach($allowed_methods as $method) {
		if($_SERVER['REQUEST_METHOD'] == $method)
			return;
	}
	http_response_code(405);
	header('Allow: '.implode(",", $allowed_methods));
	exit();
}
