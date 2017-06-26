/**
 * An ajax client that do post and gets automatilly
 */
function AjaxClient() {
	
	/* properties */
	this.xhttp = null;

	/* Holds the callback functions if any */
	AjaxClient.callbackResponse = function(){};
	AjaxClient.callbackConnected = function(){};
	AjaxClient.callbackProcessing = function(){};
	AjaxClient.callbackRequestSend = function(){};

	/* top constructors */
	if (window.XMLHttpRequest) {
		// code for modern browsers
		this.xhttp = new XMLHttpRequest();
	} else {
		// code for IE6, IE5
		this.xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
}

/* callback setters */
AjaxClient.prototype.setOnProcessing = function(callbackFunction){
	AjaxClient.callbackProcessing = callbackFunction;
}

AjaxClient.prototype.setOnConnection = function(callbackFunction){
	AjaxClient.callbackConnected = callbackFunction;
}

AjaxClient.prototype.setOnRequestSend = function(callbackFunction){
	AjaxClient.callbackRequestSend = callbackFunction;
}

AjaxClient.prototype.setOnResponse = function(callbackFunction){
	AjaxClient.callbackResponse = callbackFunction;
}

/* methods */
AjaxClient.prototype.sendRequestTo = function (url, request = null, cached = false ) {

	// Get the server response
	this.xhttp.onreadystatechange = function(){

		if (this.status != "" && this.status != 200) alert(this.statusText); 
		
		switch (this.readyState) {
		case 1:
			AjaxClient.callbackConnected(this);
			break;
		case 2:
			AjaxClient.callbackRequestSend(this);
			break;
		case 3:
			AjaxClient.callbackProcessing(this);
			break;
		case 4:
			AjaxClient.callbackResponse(this);
			break;
		}
	};
	
	// if no cache add a unique ID
	if(!cached){
		url += url.indexOf("?") > 0 ? "&rnd=" + Math.random() : "?rnd=" + Math.random();
	}
	
	// Requests are made only by POST
	if(request === null){
		this.xhttp.open("GET", url, true);
		this.xhttp.send();
		return;
	}
	this.xhttp.open("POST", url, true);
	this.xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	this.xhttp.send(request);
}