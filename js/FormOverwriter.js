var uploader = new Uploader(document.forms[0]);
var FormOverwriter = Object.create(uploader);

/**
 * Overwrites the default form method with an routine
 */
FormOverwriter.create = function() {
	for (var i = 0; i < document.forms.length; i++) {
		document.forms[i].addEventListener("submit", function(event) {
			event.preventDefault();
			FormOverwriter.send();
		}, false);
	}

	return Object.create(this);
}