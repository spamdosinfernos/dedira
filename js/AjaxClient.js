/**
 * An ajax client that do post and gets automatilly
 */
function AjaxClient() {
	
	/* properties */
	this.xhttp = null;

	/* top constructors */
	if (window.XMLHttpRequest) {
		// code for modern browsers
		this.xhttp = new XMLHttpRequest();
	} else {
		// code for IE6, IE5
		this.xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	/* methods */
	this.sendRequestTo = function sendRequestTo(url, callbackFunction, request = null, cached = false ) {
		this.xhttp.onreadystatechange = callbackFunction;
		
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
}