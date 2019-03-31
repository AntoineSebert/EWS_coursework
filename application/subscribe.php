<?php

/* QUICKCHECKS */
function is_xml(string $candidate) : bool {
	try {
		$rss = new SimpleXmlElement($candidate);
		return true;
	}
	catch(Exception $e){
		return false;
	}
}

/* PRIMARY */
function manage_subscription() : int {
	if(isset($_POST["action"]) && isset($_POST["feed"])) {
		if(($_POST["action"] === "remove" || $_POST["action"] === "add") && is_xml($_POST["feed"])) {
			return $_POST["action"] === "add" ? add_subscription() : remove_subscription();
		}
	}
	return 400;
}

/* SECONDARY */
function add_subscription() : int {
	if(/* PDO feeds exists for user*/false) {
		return 200;
	} else {
		// PDO insert
		return 201;
	}
}

function remove_subscription() : int {
	// PDO delete if exists
	return 200;
}
