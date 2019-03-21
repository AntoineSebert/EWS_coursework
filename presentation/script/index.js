// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
	"use strict";
	var modal = document.getElementById('loginbox');
	if (event.target === modal) {
		modal.style.display = "none";
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

function change_password_visibility() {
	"use strict";
	document.getElementById("message").style.display = "block";
	var x = document.getElementById("password_field");
	if (x.type === "password") {
		x.type = "text";
	} else {
		x.type = "password";
	}
}

var password_field = document.getElementById("password_field");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");

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

	function uncheck(element) {
		element.classList.replace("valid", "invalid");
		element.firstElementChild.classList.replace("fa-check", "fa-times");
	}

	function check(element) {
		element.classList.replace("invalid", "valid");
		element.firstElementChild.classList.replace("fa-times", "fa-check");
	}

	// Validate lowercase letters
	password_field.value.match(/[a-z]/g) ? check(letter) : uncheck(letter);

	// Validate capital letters
	password_field.value.match(/[A-Z]/g) ? check(capital) : uncheck(capital);

	// Validate numbers
	password_field.value.match(/[0-9]/g) ? check(number) : uncheck(number);

	// Validate length
	password_field.value.length >= 8 ? check(length) : uncheck(length);
};

document.getElementById("filter_field").onkeyup = function () {
	"use strict";
	var input, filter, ul, li, element;
	input = document.getElementById('filter_field');
	filter = input.value.toUpperCase();
	ul = document.getElementById("content_list_last_login");
	li = ul.getElementsByTagName('li');

	// Loop through all list items, and hide those who don't match the search query
	for (element of li) {
		var txtValue = element.firstElementChild.textContent;
		if (txtValue.toUpperCase().indexOf(filter) > -1) {
			element.style.display = "";
		} else {
			element.style.display = "none";
		}
	}
};

document.getElementById("top_button").onclick = function () {
	"use strict";
	/*
	document.body.scrollTop = 0; // For Safari
	document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
	*/
	window.scrollTo({
		top: 0,
		left: 0,
		behavior: 'smooth'
	});
};

var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i += 1) {
	"use strict";
	coll[i].addEventListener("click", function () {
		this.classList.toggle("active");
		var content = this.nextElementSibling;
		if (content.style.maxHeight) {
			content.style.maxHeight = null;
		} else {
			content.style.maxHeight = content.scrollHeight + "px";
		}
	});
}
