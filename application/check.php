<?php

function check_method(array $allowed_methods) : bool {
	foreach($allowed_methods as $method) {
		if($_SERVER['REQUEST_METHOD'] == $method)
			return true;
	}
	return false;
}

function check_user_exists(string $username) : bool {
	// PDO request

}
