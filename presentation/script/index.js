/* eslint-env browser */
// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
	"use strict";
	var login_modal = document.getElementById('loginbox'),
		subscription_modal = document.getElementById('subscription_box');
	if (event.target === login_modal) {
		login_modal.style.display = "none";
	} else if (event.target === subscription_modal) {
		subscription_modal.style.display = "none";
	}
};

window.onscroll = function () {
	"use strict";
	// scrollbar
	var winScroll = document.body.scrollTop || document.documentElement.scrollTop,
		height = document.documentElement.scrollHeight - document.documentElement.clientHeight,
		scrolled = (winScroll / height) * 100;
	document.getElementById("myBar").style.width = scrolled + "%";

	// top_button
	if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
		document.getElementById("top_button").style.display = "block";
	} else {
		document.getElementById("top_button").style.display = "none";
	}
};

window.onload = function () {
	"use strict";
	var coll = document.getElementsByClassName("collapsible"),
		i;
	for (i = 0; i < coll.length; i += 1) {
		coll[i].addEventListener("click", function () {
			this.classList.toggle("active");
			var content = this.nextElementSibling;
			if (content.style.maxHeight) {
				content.style.maxHeight = null;
			} else {
				content.style.maxHeight = content.scrollHeight + "px";
				this.parentElement.classList.add("consulted");
			}
		});
	}

	// AJAX triggers & handlers
};

var password_field = document.getElementById("password_field");

// When the user clicks on the password field, show the message box
password_field.onfocus = function () {
	"use strict";
	document.getElementById("message").style.display = "block";
};

// When the user clicks outside of the password field, hide the message box
password_field.onblur = function () {
	"use strict";
	document.getElementById("message").style.display = "none";
};

// When the user starts to type something inside the password field
password_field.onkeyup = function () {
	"use strict";
	var letter = document.getElementById("letter"),
		capital = document.getElementById("capital"),
		number = document.getElementById("number"),
		length = document.getElementById("length");

	function uncheck(element) {
		element.classList.replace("valid", "invalid");
		element.firstElementChild.classList.replace("fa-check", "fa-times");
	}

	function check(element) {
		element.classList.replace("invalid", "valid");
		element.firstElementChild.classList.replace("fa-times", "fa-check");
	}

	// Validate lowercase letters
	if (password_field.value.match(/[a-z]/g)) {
		check(letter);
	} else {
		uncheck(letter);
	}
	// Validate capital letters
	if (password_field.value.match(/[A-Z]/g)) {
		check(capital);
	} else {
		uncheck(capital);
	}
	// Validate numbers
	if (password_field.value.match(/[0-9]/g)) {
		check(number);
	} else {
		uncheck(number);
	}
	// Validate length
	if (password_field.value.length >= 8) {
		check(length);
	} else {
		uncheck(length);
	}
};

document.getElementById("filter_field").onkeyup = function () {
	"use strict";
	var input = document.getElementById('filter_field'),
		filter = input.value.toUpperCase(),
		div = document.getElementsByClassName('entry_content'),
		index,
		txtValue;

	// Loop through all list items, and hide those who don't match the search query
	[].forEach.call(div, function (element) {
		var text = element.firstElementChild.textContent.toUpperCase();
		if (text.includes(filter)) {
			element.previousElementSibling.classList.add("active");
			element.style.maxHeight = element.scrollHeight + "px";
		} else {
			element.previousElementSibling.classList.remove("active");
			element.style.maxHeight = null;
		}
	});
};

document.getElementById("top_button").onclick = function () {
	"use strict";
	window.scrollTo({
		top: 0,
		left: 0,
		behavior: 'smooth'
	});
};

function http_request(method, url, handler) {
	"use strict";
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = handler;
	xhttp.open(method, url, true);
	xhttp.send();
}
