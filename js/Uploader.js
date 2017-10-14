/*
 * Copyright (c) 2008-2009, Ionut Gabriel Stan. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *    * Redistributions of source code must retain the above copyright notice,
 *      this list of conditions and the following disclaimer.
 *
 *    * Redistributions in binary form must reproduce the above copyright notice,
 *      this list of conditions and the following disclaimer in the documentation
 *      and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

var Uploader = function(form) {
    this.form = form;
};

Uploader.prototype = {
    headers : {},
    /**
	 * @return String
	 */
    generateBoundary : function() {
        return "---------------------------" + (new Date).getTime();
    },
    
    /**
	 * @return Array
	 */
    getElements : function() {
        var fields = [];

        // gather INPUT elements
        var inputs = this.form.getElementsByTagName("INPUT");
        for (var l=inputs.length, i=0; i<l; i++) {
            fields.push(inputs[i]);
        }

        // gather SELECT elements
        var selects = this.form.getElementsByTagName("SELECT");
        for (var l=selects.length, i=0; i<l; i++) {
            fields.push(selects[i]);
        }
        return fields;
    },

    /**
	 * @param Array
	 *            elements
	 * @param String
	 *            boundary
	 * @return String
	 */
    buildMessage : function(elements, boundary) {
        var CRLF  = "\r\n";
        var parts = [];

        elements.forEach(function(element, index, all) {
            var part = "";
            var type = "TEXT";

            if (element.nodeName.toUpperCase() === "INPUT") {
                type = element.getAttribute("type").toUpperCase();
            }

            if (type === "FILE" && element.files.length > 0) {
                var fieldName = element.name;
                var fileName  = element.files[0].fileName;

                /*
				 * Content-Disposition header contains name of the field used to
				 * upload the file and also the name of the file as it was on
				 * the user's computer.
				 */
                part += 'Content-Disposition: form-data; ';
                part += 'name="' + fieldName + '"; ';
                part += 'filename="'+ fileName + '"' + CRLF;

                /*
				 * Content-Type header contains the mime-type of the file to
				 * send. Although we could build a map of mime-types that match
				 * certain file extensions, we'll take the easy approach and
				 * send a general binary header: application/octet-stream.
				 */
                part += "Content-Type: application/octet-stream" + CRLF + CRLF;

                /*
				 * File contents read as binary data, obviously
				 */
                // var reader = new FileReader();
                // reader.readAsDataURL(
                part += element.files[0] + CRLF;
            } else {
                /*
				 * In case of non-files fields, Content-Disposition contains
				 * only the name of the field holding the data.
				 */
                part += 'Content-Disposition: form-data; ';
                part += 'name="' + element.name + '"' + CRLF + CRLF;

                /*
				 * Field value
				 */
                part += element.value + CRLF;
            }

            parts.push(part);
        });

        var request = "--" + boundary + CRLF;
            request+= parts.join("--" + boundary + CRLF);
            request+= "--" + boundary + "--" + CRLF;

        return request;
    },

    generateXhttp : function(){
    	if (window.XMLHttpRequest) {
    		// code for modern browsers
    		return new XMLHttpRequest();
    	} else {
    		// code for IE6, IE5
    		return new ActiveXObject("Microsoft.XMLHTTP");
    	}
    },
    
    
    /**
	 * @return null
	 */
    send : function(cached = true) {
        var boundary = this.generateBoundary();
        var xhr      = this.generateXhttp();

    	// if no cache add a unique ID
    	if(!cached){
    		this.form.action += this.form.action.indexOf("?") > 0 ? "&rnd=" + Math.random() : "?rnd=" + Math.random();
    	}
        
        xhr.open("POST", this.form.action, true);
        xhr.onreadystatechange = function() {
        	
        	if (this.status != "" && this.status != 200) alert(this.statusText); 
    		
    		switch (this.readyState) {
    		case 1:
    			Uploader.prototype.callbackConnected(this);
    			break;
    		case 2:
    			Uploader.prototype.callbackRequestSend(this);
    			break;
    		case 3:
    			Uploader.prototype.callbackProcessing(this);
    			break;
    		case 4:
    			Uploader.prototype.callbackResponse(this);
    			break;
    		}
        };
        var contentType = "multipart/form-data; boundary=" + boundary;
        xhr.setRequestHeader("Content-Type", contentType);

        for (var header in this.headers) {
            xhr.setRequestHeader(header, headers[header]);
        }

        // finally send the request as binary data
        xhr.send(this.buildMessage(this.getElements(), boundary));
    },
    
	/* Holds the callback functions if any */
	callbackResponse : function(){},
	callbackConnected : function(){},
	callbackProcessing : function(){},
	callbackRequestSend : function(){},
	
	/* callback setters */
	setOnProcessing : function(callbackFunction){
		Uploader.prototype.callbackProcessing = callbackFunction;
	},

	setOnConnection : function(callbackFunction){
		Uploader.prototype.callbackConnected = callbackFunction;
	},

	setOnRequestSend : function(callbackFunction){
		Uploader.prototype.callbackRequestSend = callbackFunction;
	},

	setOnResponse : function(callbackFunction){
		Uploader.prototype.callbackResponse = callbackFunction;
	}
};
