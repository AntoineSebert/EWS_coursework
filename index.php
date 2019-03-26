<?

/*
ROUTES

base = http://csdm-webdev.rgu.ac.uk/1705851/enterprise_web_systems_coursework
	POST
		handler location
			sign.php
		field
			email, password, remember
		response
			200 = sign in
			201 = sign up
			401 = if email exists but password does not match

	ALL EXCEPT HEAD/GET
		response
			405

user = base/{user} (first part of email)
	GET
		handler location
			load.php
		field
			all(default)/page/subscriptions list
		response
			200
			400 = page in not a number
			401 = not connected or wrong user
			404 = page not found

	PUT
		handler location
			subscribe.php
		field
			feed
		response
			200 = already exists
			201 = inserted
			400 = not an rss feed
			401 = not connected or wrong user
			404 = feed not found

	DELETE
		handler location
			subscribe.php
		field
			feed
		response
			200 = deleted
			400 = not a feed
			401 = not connected or wrong user
			404 = feed not found

	ALL EXCEPT HEAD/GET
		response
			405

database
	User
		email : string unique PK
		password_hash : string
		salt : datetime
		subscriptions : array feed FK
		last_connection : datetime
	Feed
		url : string unique PK
		publications : array publications FK
	Publication
		release : datetime
		title : string
		description : string
		content : string

https://app.netlify.com/sites/ewsc/overview
*/

// https://secure.php.net/manual/en/functions.arguments.php#functions.arguments.type-declaration
// https://secure.php.net/manual/en/context.http.php
// https://secure.php.net/manual/en/security.database.php
// https://secure.php.net/manual/en/internals2.pdo.php
// https://secure.php.net/manual/en/refs.crypto.php

namespace enterprise_web_systems_coursework;

$index = true;
$directory = "application";
require($directory."/check.php");
check_https();
check_url();
// build array in functino of url
check_method(array());

session_start([
	'cookie_lifetime' => 86400,
]);

//$_SESSION
