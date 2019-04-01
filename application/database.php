<?php

$database_user = array(
	"ewsc_r" => "t9x.g}sTL|EF",
	"ewsc_w" => "\IiT42_:@VUW",
);

/* HANDLES */
$database_read = null;
$database_write = null;

function get_read_connection() : object {
	return get_database_connection($database_read, "ewsc_r");
}

function get_write_connection() : object {
	return get_database_connection($database_write, "ewsc_w");
}

function get_database_connection(object $database, string $username) : object {
	if($database == null) {
		try {
			$database = new PDO('mysql:host=localhost;dbname=ewsc;charset=utf8', $username, $database_user[$username], array(PDO::ATTR_PERSISTENT => true));
			$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}
	return $database;
}

/* READ */
function get_user_feed(string $email, int $page = 0) : array {
	$database_handle = get_read_connection();
}

function get_user_subscriptions(string $email) : array {
	$database_handle = get_read_connection();
}

function feed_exists(string $email, string $feed) : bool {
	$database_handle = get_read_connection();
}

function user_exists(string $email) : bool {
	$database_handle = get_read_connection();
}

function get_hash(string $email) : bool {
	$database_handle = get_read_connection();
}

/* WRITE */
function create_account(string $email, string $hash, string $subscription = "") : bool {
	$database_handle = get_write_connection();
}

function add_feed(string $email, string $feed) : bool {
	$database_handle = get_write_connection();
}

function remove_feed(string $email, string $feed) : bool {
	$database_handle = get_write_connection();
}

/* TRANSACTION */
function perform_transaction(object $database, array $queries) : bool {
	try {
		$dbh->beginTransaction();
		// PDO requests
		$dbh->commit();
	} catch(Exception $e) {
		$dbh->rollBack();
		echo "Failed: " . $e->getMessage();
	}
}
