<?php

$mysqli->set_charset("utf8"); // really necessary ?

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
function get_user_feed(string $email, date $since = time()) : array {
	$database_handle = get_read_connection();

	$statement = $database_handle->prepare(
		'SELECT feed, release_date, title, description, content
		FROM publications
		WHERE ? < release_date AND feed IN (
			SELECT feed FROM subscriptions WHERE user_email = ?
		)
		ORDER BY release_date'
	);
	$statement->bindValue(1, $since, PDO::PARAM_STR);
	$statement->bindValue(2, mysqli_real_escape_string($email), PDO::PARAM_STR);

	return perform_return_transaction($database_handle, $statement);
}

function get_user_subscriptions(string $email) : array {
	$database_handle = get_read_connection();

	$statement = $database_handle->prepare('SELECT feed_url FROM subscriptions WHERE user_email = ?');
	$statement->bindValue(1, mysqli_real_escape_string($email), PDO::PARAM_STR);

	return perform_return_transaction($database_handle, $statement);
}

function user_exists(string $email) : bool {
	$database_handle = get_read_connection();

	$statement = $database_handle->prepare(
		'SELECT CASE WHEN EXISTS (
			SELECT *
			FROM users
			WHERE email = ?
		)
		THEN CAST(1 AS BIT)
		ELSE CAST(0 AS BIT) END'
	);
	$statement->bindValue(1, mysqli_real_escape_string($email), PDO::PARAM_STR);

	return perform_return_transaction($database_handle, $statement);
}

function get_hash(string $email) : string {
	$database_handle = get_read_connection();

	$statement = $database_handle->prepare('SELECT hash FROM users WHERE email = ?');
	$statement->bindValue(1, mysqli_real_escape_string($email), PDO::PARAM_STR);

	return perform_return_transaction($database_handle, $statement);
}

/* WRITE */
function create_account(string $email, string $hash, array $urls) : bool {
	$database_handle = get_write_connection();

	$statement = $database_handle->prepare('INSERT IGNORE INTO users (email, hash, last_connection) VALUES (?, ?, CURRENT_TIMESTAMP)');
	$statement->bindValue(1, mysqli_real_escape_string($email), PDO::PARAM_STR);
	$statement->bindValue(2, mysqli_real_escape_string($hash), PDO::PARAM_STR);

	$result = perform_transaction($database_handle, array($statement));

	foreach($urls as $url) {
		$result &= add_feed($email, $url);
	}

	return $result;
}

function add_feed(string $email, string $feed) : bool {
	$database_handle = get_write_connection();

	$statement = $database_handle->prepare('INSERT IGNORE INTO subscriptions (user_email, feed_url) VALUES (?, ?)');
	$statement->bindValue(1, mysqli_real_escape_string($email), PDO::PARAM_STR);
	$statement->bindValue(2, mysqli_real_escape_string($feed), PDO::PARAM_STR);

	return perform_transaction($database_handle, array($statement));
}

function remove_feed(string $email, string $feed) : bool {
	$database_handle = get_write_connection();

	$statement = $database_handle->prepare('DELETE subscriptions WHERE user_email = ? AND feed_url = ?');
	$statement->bindValue(1, mysqli_real_escape_string($email), PDO::PARAM_STR);
	$statement->bindValue(2, mysqli_real_escape_string($feed), PDO::PARAM_STR);

	$result = perform_transaction($database_handle, array($statement));

	$statement = $database_handle->prepare('DELETE FROM feed WHERE COUNT(subscriptions) = 0 IN subscriptions WHERE feed_url = ?'); // todo
	// check if feed still has subscribers
	// if not, delete it and its publications
	// on delete cascade ?

	return $result;
}

function clean_unsuscibed_feed()

/* TRANSACTION */
function perform_transaction(object $database, array $statements) : bool {
	try {
		$database->beginTransaction();
		foreach($statements as $statement) {
			$statement->execute();
		}
		$database->commit();

		return true;
	} catch(Exception $e) {
		$database->rollBack();
		echo "Failed: " . $e->getMessage();

		return false;
	}
}

function perform_return_transaction(object $database, object $statement) : array {
	try {
		$database->beginTransaction();
		$statement->execute();
		$database->commit();

		return $statement->fetchAll();
	} catch(Exception $e) {
		$database->rollBack();
		echo "Failed: " . $e->getMessage();

		return null;
	}
}
