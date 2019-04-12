/* eslint-env browser */

function http_request(method, url, handler, params = "") {
	"use strict";
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = handler;
	xhttp.open(method, url, false); // synchronous permitted because of webworker context
	xhttp.send(params);
}

// GET user feed
//document.getElementById()

// POST add subscription
document.getElementById("submit_subscription").onclick = function () {
	"use strict";
	var input_value = document.getElementById("subscription_box").getElementsByClassName("input-field")[0].value;
	http_request(
		"POST",
		"http://ewsc/" + "username",
		function () {
			if (this.readyState === XMLHttpRequest.DONE) {
				switch (this.status) {
					case 200:
						break;
					case 201:
						break;
					case 400:
						break;
					case 401:
						break;
				}
			}
		},
		"action=add&feed=" + input_value,
	);
};

// POST remove subscription
