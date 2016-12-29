var ac = new AjaxClient();

var loadPage = function () {
	if (this.readyState == 4 && this.status == 200) {
		document.getElementById("main").innerHTML = this.responseText;
	}
}

function request(url, req = null ){
	ac.sendRequestTo(url, loadPage, req );
}
