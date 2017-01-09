
///////////////////////////
// XMLexchange
///////////////////////////

function XMLexchange() {

        this.debugMode = true;
        this.async = true;

	// public methods
        this.submit = function(url, form) {
		this.request("SUBMIT", url, form, "", "");
	},

        this.get = function(url, callback) {
		this.request("GET", url, "", callback, "TEXT");
	},

        this.post = function(url, XMLin, callback) {
		this.request("Post", url, XMLin, callback, "TEXT");
	},

	this.request = function(method, url, XMLin, callback, returnType) {

		// file upload needs special care
		// -> submit the url to the frame which is specified in the callback function
		if (method == "SUBMIT") {

			// set properties
			XMLin.action = url;
			//XMLin.enctype = "multipart/form-data";
			XMLin.encoding = "multipart/form-data";
			XMLin.method = "POST";
			XMLin.target = "submitFrame";

			XMLin.submit();
		}
		else {
			var req = newXMLHttpRequest();
   			var requestTimer = setTimeout(function() { req.abort(); error("ERROR: Connection timeout."); }, 5000);
	
			// setup a response handler
        		req.onreadystatechange = function() { responseHandler(req, requestTimer, callback, returnType); }

			// setup request
        		req.open(method, url, this.async);
	
			// do request
                	if (method == "GET") {
				req.send("");
                	}
			else if (method == "POST") {
				req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
				req.send(XMLin);
			}
			else {
				error("ERROR: Method not supported.");
			}
		}

	},

	// private methods
	newXMLHttpRequest = function() {
		try { return new ActiveXObject("Msxml2.XMLHTTP"); } catch (e) {}
		try { return new ActiveXObject("Microsoft.XMLHTTP"); } catch (e) {}
		try { return new XMLHttpRequest(); } catch(e) {}
		error("ERROR: Your browser does not support XMLHttpRequest.");
		return null;
	},

	responseHandler = function(request, requestTimer, callback, returnType) {
		if (request.readyState == 4) {
			clearTimeout(requestTimer);
			if (request.status == 200) {
				var response;
				if (returnType = "TEXT") {
					response = request.responseText;
				}
				else {
					response = request.responseXml;
				}
				// LET OP: waarom werkt dit niet?
				//callback(response);
				eval(callback+'('+'response'+');');
			}
			else {
				error("ERROR: HTTP error " + request.status);
			}
		}
	};

	// an error is a debug message
	error = function(msg) {
		debug(msg);
	},

	debug = function(msg) {
		if (this.debugMode) {
			alert (msg);
		}
	}

};
