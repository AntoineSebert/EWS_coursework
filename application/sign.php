<?php

/* VALIDATION */
function validate_email(string $email) : bool {
	return filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email) <= 64;
}

function validate_password(string $password) : bool {
	return 7 < strlen($password) && strlen($password) < 33
		&& preg_match("/[a-z]/", $password)
		&& preg_match("/[A-Z]/", $password)
		&& preg_match("/[0-9]/", $password);
}

/* QUICKCHECKS */
function is_remember() : bool {
	return isset($_POST["remember"]) && $_POST["remember"] == true;
}

function is_signed_in() : bool {
	return isset($_SESSION["email"]) && isset($_SESSION["password"]);
}

function post_credentials_exists() : bool {
	return isset($_POST["email"]) && isset($_POST["password"]);
}

/* SECONDARY */
function start_session() {
	session_name($_POST["email"]);
	is_remember() ? session_start() : session_start(['cookie_lifetime' => 86400]);
	$_SESSION["email"] = $_POST["email"];
	$_SESSION["password"] = $_POST["password"];
}

function attempt_session_with_password() : bool {
	if(password_verify($_POST["password"], get_hash($_POST["email"]))) {
		start_session();
		return true;
	}
	return false;
}

function get_username() : string {
	return explode('@', $_SESSION["email"])[0];
}

/* PRIMARY */
function sign() : int {
	if(post_credentials_exists() && validate_email($_POST["email"]) && validate_password($_POST["password"])) {
		if(email_exists($_POST["email"])) {
			return attempt_session_with_password() ? 200 : 401;
		}
		create_account($_POST["email"], password_hash($_POST["password"], PASSWORD_DEFAULT));
		start_session();
		return 201;
	}
	return 400;
}
