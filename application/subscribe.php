<?php

function add_subscription() : int {
	if(/* feed exists*/true) {
		// request to database once all other checks have been performed (reduce DB load)
		if(/* PDO feeds exists for user*/false) {
			return 200;
		} else {
			// PDO insert
			return 201;
		}
	} else {
		// feed does not exists
		return 404;
	}
}

function remove_subscription() : int {
	// PDO delete if exists
	return 200;
}
