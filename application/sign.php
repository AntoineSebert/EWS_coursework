<?php

function sign() : int {
	// $_POST["EMAIL"];
	// $_POST["PASSWORD"];
	if(/*PDO email exists*/true) {
		return /*PDO hash(password + salt) match ? 200 : 401*/200;
	} else {
		// PDO create account
		return 201;
	}
}
