var ajaxClient = new AjaxClient();
var FormOverwriter = Object.create(ajaxClient);

/**
 * Overwrites the default form method with an routine
 */
FormOverwriter.create = function() {
	for (var i = 0; i < document.forms.length; i++) {

		// WARNING!! Here we alters the context of this function to the
		// form!
		document.forms[i].onsubmit = this.sendFunction;
	}
	
	return Object.create(this);
}

/**
 * WARNING!! The context of this function is the form! Not the FormOverwriter
 * object!
 */
FormOverwriter.sendFunction = function(event) {

	//event.defaultPrevented();

	// Data will be send
	var data = "";

	// Retrieves the inputs
	var inputs = this.getElementsByTagName("input");
	for (var i = 0; i < inputs.length; i++) {
		if (inputs[i].name == "")
			continue; // Ignore inputs without name
		data += inputs[i].name + "=" + inputs[i].value + "&";
	}

	// Retrieves the selects
	var selects = this.getElementsByTagName("select");
	for (var i = 0; i < selects.length; i++) {
		if (selects[i].name == "")
			continue;// Ignore selects without name
		data += selects[i].name + "=" + selects[i].value + "&";
	}

	// Due to the new context is the form, we should call the sendRequest
	// function directly
	FormOverwriter.sendRequestTo(this.action, data, false);
	return false;
}
