<?php

/* VALIDATION */
function validate_email(string $email) : bool {
	return preg_match("\A[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\z", $email) === 1
		&& strlen($email) <= 64;
}

function validate_password(string $password) : bool {
	return preg_match("[[:graph:]]{8,32}", $password) === 1
		&& preg_match("[[:lower:]]+", $password) === 1
		&& preg_match("[[:upper:]]+", $password) === 1
		&& preg_match("\d+", $password) === 1
		&& strlen($password) <= 32;
}

/* QUICKCHECKS */
function is_remember() : bool {
	return isset($_POST["remember"]) && $_POST["remember"] === true;
}

function is_signed_in() {
	return isset($_SESSION["email"]) && isset($_SESSION["password"]);
}

/* PRIMARY */
function sign() : int {
	if(is_signed_in()) {
		return 200;
	} elseif(isset($_POST["email"]) && isset($_POST["password"])) {
		if(validate_email($_POST["email"]) && validate_password($_POST["password"])) {
			if(/*PDO check email exists*/true) {
				if(password_verify($_POST["password"], ""/* PDO hash*/)) {
					remember();
					return 200;
				}
				return 401;
			}
			// PDO create account
			password_hash($_POST["email"], PASSWORD_DEFAULT);
			return 201;
		}
	}
	return 400;
}

function remember() {
	if(is_remember()) {
		$_SESSION["email"] = $_POST["email"];
		$_SESSION["password"] = $_POST["password"];
	}
}
