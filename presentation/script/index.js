// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
	var modal = document.getElementById('loginbox');
	if (event.target === modal) {
		modal.style.display = "none";
	}
};

window.onscroll = function () {
	var winScroll = document.body.scrollTop || document.documentElement.scrollTop,
		height = document.documentElement.scrollHeight - document.documentElement.clientHeight,
		scrolled = (winScroll / height) * 100;
	document.getElementById("myBar").style.width = scrolled + "%";
};

function myFunction() {
	var winScroll = document.body.scrollTop || document.documentElement.scrollTop,
		height = document.documentElement.scrollHeight - document.documentElement.clientHeight,
		scrolled = (winScroll / height) * 100;
	document.getElementById("myBar").style.width = scrolled + "%";
}

function change_password_visibility() {
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
	document.getElementById("message").style.display = "block";
};

// When the user clicks outside of the password field, hide the message box
password_field.onblur = function () {
	document.getElementById("message").style.display = "none";
};

// When the user starts to type something inside the password field
password_field.onkeyup = function () {
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
	var input, filter, ul, li, element;
	input = document.getElementById('filter_field');
	filter = input.value.toUpperCase();
	ul = document.getElementById("content_list");
	li = ul.getElementsByTagName('li');

	// Loop through all list items, and hide those who don't match the search query
	for (element in li) {
		// implement for rss feed
		/*
		var txtValue = a.textContent || a.innerText;
		if (txtValue.toUpperCase().indexOf(filter) > -1) {
			element.style.display = "";
		} else {
			element.style.display = "none";
		}
		*/
	}
};
