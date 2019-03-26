<?

if(!isset($index) || !$index) {
	http_response_code(404);
	exit();
}

namespace enterprise_web_systems_coursework;

function check_https() {
	if($_SERVER["HTTPS"] != "on") {
		header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
		exit();
	}
}

// todo
function check_url() {
	// base or base/{user} or base/humans.txt
	echo $_SERVER["REQUEST_URI"];
}

function check_method(array $allowed_methods) {
	foreach($allowed_methods as $method) {
		if($_SERVER['REQUEST_METHOD'] == $method)
			return;
	}
	http_response_code(405);
	header('Allow: '.implode(",", $allowed_methods));
	exit();
}
