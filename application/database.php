<?php

try {
	$database = new PDO('mysql:host=localhost;dbname=ewsc', "root", "", array(PDO::ATTR_PERSISTENT => true)); //;charset=UTF-8
}
catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}

function check_user_exists(string $username) : bool {
	// PDO request
	//$sth = $dbh->query('SELECT * FROM foo');
}
