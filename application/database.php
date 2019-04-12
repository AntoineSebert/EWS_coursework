<?php

/* GLOBALS */
$GLOBALS["database_read"] = new stdClass();
$GLOBALS["database_write"] = new stdClass();
$GLOBALS["database_user"] = array(
	"ewsc_r" => "t9x.g}sTL|EF",
	"ewsc_w" => "\IiT42_:@VUW",
);

function get_database_connection(object $database, string $username) : object {
	if($database instanceof stdClass) {
		try {
			$database = new PDO('mysql:host=localhost;dbname=ewsc;charset=utf8', $username, $GLOBALS["database_user"][$username], array(PDO::ATTR_PERSISTENT => true));
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
function get_user_feed(string $email, date $since = null) : array {
	if(is_null($since))
		$since = time();
	$database_handle = get_database_connection($GLOBALS["database_read"], "ewsc_r");

	$feed = array();

	$statement = $database_handle->prepare(
		'SELECT feed_url, published, title, summary, source
		FROM publications_atom
		WHERE published > ? AND feed IN (
			SELECT feed_url FROM subscriptions WHERE user_email = ?
		)
		ORDER BY published'
	);
	$statement->bindValue(1, $since, PDO::PARAM_STR);
	$statement->bindValue(2, $email, PDO::PARAM_STR);

	$feed[0] = perform_return_transaction($database_handle, $statement);

	$statement = $database_handle->prepare(
		'SELECT feed_url, pubDate, title, description, textInput
		FROM publications_rss
		WHERE pubDate > ? AND feed IN (
			SELECT feed_url FROM subscriptions WHERE user_email = ?
		)
		ORDER BY pubDate'
	);
	$statement->bindValue(1, $since, PDO::PARAM_STR);
	$statement->bindValue(2, $email, PDO::PARAM_STR);

	$feed[1] = perform_return_transaction($database_handle, $statement);

	return $feed;
}

function get_user_subscriptions(string $email) : array {
	$database_handle = get_database_connection($GLOBALS["database_read"], "ewsc_r");

	$statement = $database_handle->prepare('SELECT feed_url FROM subscriptions WHERE user_email = ?');
	$statement->bindValue(1, $email, PDO::PARAM_STR);

	return perform_return_transaction($database_handle, $statement);
}

function email_exists(string $email) : bool {
	$database_handle = get_database_connection($GLOBALS["database_read"], "ewsc_r");

	$statement = $database_handle->prepare('SELECT email FROM users WHERE email = ?');
	$statement->bindValue(1, $email, PDO::PARAM_STR);

	return sizeof(perform_return_transaction($database_handle, $statement)) == 1;
}

function get_hash(string $email) : string {
	$database_handle = get_database_connection($GLOBALS["database_read"], "ewsc_r");

	$statement = $database_handle->prepare('SELECT hash FROM users WHERE email = ?');
	$statement->bindValue(1, $email, PDO::PARAM_STR);

	return perform_return_transaction($database_handle, $statement)[0][0];
}

function is_subscribed(string $email, string $url) : bool {
	$database_handle = get_database_connection($GLOBALS["database_read"], "ewsc_r");

	$statement = $database_handle->prepare('SELECT * FROM publications WHERE user_email = ? AND feed_url = ?');
	$statement->bindValue(1, $email, PDO::PARAM_STR);
	$statement->bindValue(2, $url, PDO::PARAM_STR);

	return sizeof(perform_return_transaction($database_handle, $statement)) == 1;
}

function get_feeds() : array {
	$database_handle = get_database_connection($GLOBALS["database_read"], "ewsc_r");
	return perform_return_transaction($database_handle, $database_handle->prepare('SELECT * FROM feeds'));
}

/* WRITE */
function create_account(string $email, string $hash) : bool {
	$database_handle = get_database_connection($GLOBALS["database_write"], "ewsc_w");

	$statement = $database_handle->prepare('INSERT IGNORE INTO users (email, hash, last_connection) VALUES (?, ?, CURRENT_TIMESTAMP)');
	$statement->bindValue(1, $email, PDO::PARAM_STR);
	$statement->bindValue(2, $hash, PDO::PARAM_STR);

	return perform_transaction($database_handle, array($statement));
}

function add_feed(string $email, string $feed) : bool {
	$database_handle = get_database_connection($GLOBALS["database_write"], "ewsc_w");

	$statement = $database_handle->prepare('INSERT IGNORE INTO subscriptions (user_email, feed_url) VALUES (?, ?)');
	$statement->bindValue(1, $email, PDO::PARAM_STR);
	$statement->bindValue(2, $feed, PDO::PARAM_STR);

	return perform_transaction($database_handle, array($statement));
}

function remove_feed(string $email, string $feed) {
	$database_handle = get_database_connection($GLOBALS["database_write"], "ewsc_w");

	$statement_0 = $database_handle->prepare('DELETE subscriptions WHERE user_email = ? AND feed_url = ?');
	$statement_0->bindValue(1, $email, PDO::PARAM_STR);
	$statement_0->bindValue(2, $feed, PDO::PARAM_STR);

	perform_transaction($database_handle, array($statement_0));
}

function store_publications() {
	$feeds = get_feeds();
	foreach($feeds as $feed) {
		$publications = array();

		add_publication($feed, $publication);
	}
}

function add_publication(array $feed, array $publication) : bool {
	return true;
}

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

		return array();
	}
}
