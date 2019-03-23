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
*/

// https://secure.php.net/manual/en/functions.arguments.php#functions.arguments.type-declaration
// https://secure.php.net/manual/en/context.http.php
// https://secure.php.net/manual/en/security.database.php
// https://secure.php.net/manual/en/internals2.pdo.php
// https://secure.php.net/manual/en/refs.crypto.php

namespace enterprise_web_systems_coursework;

if($_SERVER["HTTPS"] != "on") {
	header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
	exit();
}

if($_SERVER['REQUEST_METHOD'] != 'POST'
	&& $_SERVER['REQUEST_METHOD'] != 'GET'
	&& $_SERVER['REQUEST_METHOD'] != 'PUT'
	&& $_SERVER['REQUEST_METHOD'] != 'DELETE'
) {
	// send error 405
	exit();
}

trait html_content{
	public function as_html(): string {}
}

class user extends html_content {
	private email = '';
	private password_hash = '';
	private salt = '';
	private $old_feed = array();
	private $new_feed = array();
	private $last_poll = time();

	function __construct(string $_email, string $_password_hash, string $_salt) {
		$email = $_email;
		$password_hash = $_password_hash;
		$salt = $_salt;
	}

	public function get_email(): string {
		return self::email;
	}

	public function get_new_feed(): string {
		return self::new_feed;
	}

	public function get_new_feed(int $index): string {
		$value = new stdClass();
		try {
			$value = self::new_feed[$index];
		}
		catch(OutOfBoundsException $e) {
			echo($e);
			echo(count(self::new_feed)." < ".$index);
		}
		finally {
			return $value;
		}
	}

	public function get_old_feed(): string {
		return self::old_feed;
	}

	public function get_old_feed(int $index): string {
		$value = new stdClass();
		try {
			$value = self::old_feed[$index];
		}
		catch(OutOfBoundsException $e) {
			echo($e);
			echo(count(self::new_feed)." < ".$index);
		}
		finally {
			return $value;
		}
	}
}


class feed extends html_content {

}

class publication extends html_content {

}

session_start([
	'cookie_lifetime' => 86400,
]);

htmlspecialchars($_COOKIE["name"])
$_SESSION
