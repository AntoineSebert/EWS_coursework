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
function manage_subscriptions() : int {
	if(isset($_POST["action"]) && isset($_POST["feed"])) {
		if(($_POST["action"] === "remove" || $_POST["action"] === "add") && is_string($_POST["feed"])) {
			$email = (is_remember() ? $_SESSION["email"] : $_POST["email"]);
			$function = ($_POST["action"] === "add" ? add_subscription : remove_subscription);
			return $function($email, $_POST["feed"]);
		}
	}
	return 400;
}

/* SECONDARY */
function add_subscription($email, $url) : int {
	if(is_subscribed($email, $url)) {
		return 200;
	} else {
		add_feed($email, $url);
		return 201;
	}
}

function remove_subscription($email, $url) : int {
	remove_feed($email, $url);
	return 200;
}
