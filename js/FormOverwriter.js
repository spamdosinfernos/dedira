/**
 * Holds the callback function
 */
var formOverwriterCallback = null;
/**
 * Overwrites the default form method with an routine
 */
function FormOverwriter(callbackFunction) {

	/**
	 * Top contructors
	 */
	formOverwriterCallback = callbackFunction;

	/**
	 * WARNING!! The context of this function is the form! Not the
	 * FormOverwriter object!
	 */
	this.sendFunction = function() {

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

		var ac = new AjaxClient();
		ac.sendRequestTo(this.action, formOverwriterCallback, data);
		return false;
	}

	/**
	 * Overwrites the default form method with an routine
	 */
	this.overwriteFormMethod = function() {
		for (var i = 0; i < document.forms.length; i++) {
			document.forms[i].onsubmit = this.sendFunction;
		}
	}

	/**
	 * Bottom constructors
	 */
	this.overwriteFormMethod();
}