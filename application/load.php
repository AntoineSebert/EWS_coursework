<?php

function get_publications() : array {
	fetch_new();
	$publications = array();
	if(isset($_GET["page"])) {
		if(is_int($_GET["page"])) {
			$publications = array_slice(
				get_user_feed(is_remember() ? $_SESSION["email"] : $_POST["email"], 0),
				($_GET["page"] - 1 * 50),
				50
			);
		} else {
			$publications = get_user_feed(is_remember() ? $_SESSION["email"] : $_POST["email"]);
		}
	}
	return $publications;
}

function fetch_new() {

}
