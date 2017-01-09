// div on|off
function enableDisplay(id) {
       	document.getElementById(id).style.display="block";
}

function enableDisplayInline(id) {
       	document.getElementById(id).style.display="inline";
}

function disableDisplay(id) {
        document.getElementById(id).style.display="none";
}

function toggleDisplay(idOn, idOff) {
	disableDisplay(idOff);
	enableDisplay(idOn);
}

function toggleDisplayInline(idOn, idOff) {
	disableDisplay(idOff);
	enableDisplayInline(idOn);
}

// clear form values (used on focus)
function clearValue(id) {
	if (document.getElementById(id).value == document.getElementById(id).defaultValue) {
		document.getElementById(id).value = "";
	}
}

//
// check form input
// - check compulsory fields 'com_'
// - create query_string 'vars'
//

// secure the user input
function secureTransfer (string) {
        var out = "";

        //out = toUnicode(string);
        //out = escape(string);
	out = encodeURI(string);

        return (out);
}

function toUnicode (string) {
        var out = "";

        for (var c = 0; c < string.length; c++) {
                out = out + '&#' + string.charCodeAt(c) + ';';
        }

        return (out);
}

function checkFormInput(form_name) {

	checked = "1";
	vars = "";

	for (i=0; i<document.getElementById(form_name).elements.length; i++) {

		element = document.getElementById(form_name).elements[i];

//		alert (element.type + ":" + element.name + "=" + element.value);
		
		switch (element.type) {
			case "text":
			case "textarea":
				vars = vars + "&" + element.name + "=" + secureTransfer(element.value);
				if (element.name.match("^"+"com_")=="com_") {
					if (element.value == "") {
						checked = "0";
					}
				}
				break;
			case "file":
				vars = vars + "&" + element.name + "=" + element.value;
				break;
			case "radio":
			case "checkbox":
				if (element.checked) {
					vars = vars + "&" + element.name + "=" + element.value;
				}										
				break;
			case "select-one":
				if (element.selectedIndex >= 0) {
					vars = vars + '&' + element.name + "=" + element.options[element.selectedIndex].value
				}
				break;
			case "select-multiple":
				for (var i=0; i<element.options.length; i++) {
					if (element.options[i].selected==true) {
						vars = vars + '&' + element.name + "=" + element.options[i].value
					}
				}
				break;
			case "submit":
				break;
			// hidden + password
			default:
				vars = vars + "&" + element.name + "=" + element.value;
				break;
		}
	}
	
	// strip first '&'
	if (vars != "") {
		vars = vars.substr(1);
	}

	return (new Array(checked, vars));

}

//
// language Mapping
//

function language(key) {

	var value = key;

	if (languageMap[key] != undefined) {
		value = languageMap[key];
	}

	return (value);
}


//
// Event Handling
//

// manage onload events
function addLoadEvent(func) { 
	var oldonload = window.onload; 
	if (typeof window.onload != 'function') { 
		window.onload = func; 
	} 
	else { 
		window.onload = function() { 
			if (oldonload) { 
				oldonload(); 
			} 
			func(); 
		} 
	} 
} 

// get mouse position
function getMousePosition(e) {

	var x = 0;
	var y = 0;	

	if (!e) var e = window.event;

	if (e.pageX || e.pageY) 	{
		x = e.pageX;
		y = e.pageY;
	}
	else if (e.clientX || e.clientY) 	{
		x = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
		y = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
	}
	
	return (new Array(x, y));
}

// get element position
function getElementPosition(element) {
	var curleft = curtop = 0;

	if (element.offsetParent) {
		do {
			curleft += element.offsetLeft;
			curtop += element.offsetTop;
		} while (element = element.offsetParent);
	}
	return [curleft,curtop];
}

// get event element
function getEventElement(e) {
	var element;

	if (!e) var e = window.event;

	if (e.target) {
		element = e.target;
	}
	else if (e.srcElement) {
		element = e.srcElement;
	}
	
	// defeat Safari bug
	if (element.nodeType == 3) {
		element = element.parentNode;
	}

	return (element);
}


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
   			var requestTimer = setTimeout(function() { req.abort(); error("ERROR: Connection timeout."); }, 15000);
	
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
// Start editing to your needs
var SessionWindow = 'sessionPopup';
var SessionWindowData = 'sessionPopupData';

var SessionViewWindow = 'sessionView';
var SessionActivateWindow = 'sessionActivate';
var SessionActivateContinuWindow = 'sessionActivateContinu';
var SessionLogoutWindow = 'sessionView';
var SessionLoginWindow = 'sessionRegisterView';
var SessionLoginAlertWindow = 'loginAlertWindow';
var SessionRegisterWindow = 'sessionRegisterView';
var SessionRegisterAlertWindow = 'registerAlertWindow';
var SessionPasswordWindow = 'sessionRegisterView';
var SessionPasswordWindow2 = 'passwordWindow';
var SessionPasswordAlertWindow = 'passwordAlertWindow';

var SessionRedirectOnWindowCloseUrl = '';

if (XCOW_B['url']) {
	var SessionRedirectRegisterUrl = XCOW_B['url'];
	var SessionRedirectLoginUrl = XCOW_B['url'];
	var SessionRedirectLogoutUrl = XCOW_B['url'];
}
else {
	var SessionRedirectRegisterUrl = '/';
	var SessionRedirectLoginUrl = '/';
	var SessionRedirectLogoutUrl = '/';
}
// STOP editing

//
SessionActivated  = -1;

// transfer with XMLexchange
SessionTransfer = new XMLexchange();

// define Session
if (!window.Session) {
  Session = {};
}

Session.Util = {

  enableDisplay: function(id) {
        document.getElementById(id).style.display="block";
  },

  disableDisplay: function(id) {
        document.getElementById(id).style.display="none";
  }

}

Session.Window = {

  check: function(window) {
	if (window == SessionWindowData) {
		Session.Window.open();
	}
  },

  open: function() {
	Session.Util.enableDisplay(SessionWindow);
  },

  close: function() {
	Session.Util.disableDisplay(SessionWindow);

	if (SessionRedirectOnWindowCloseUrl) {
		document.location = SessionRedirectOnWindowCloseUrl;
	}
	else {
		Session.View.load();
	}
  }

}

Session.View = {

  //
  // view
  //
  load: function() {
	SessionTransfer.request("GET", XCOW_B['url'] + "/session/view", "", "Session.View.load_callback", "TEXT");
  },

  load_callback: function(data) {
	Session.Window.check(SessionViewWindow);
	document.getElementById(SessionViewWindow).innerHTML=data;
	window.scroll(0,0);
  }

}

Session.Activate = {

  //
  // view
  //
  load: function(key) {
	SessionTransfer.request("GET", XCOW_B['url'] + "/session/activate?key="+key, "", "Session.Activate.load_callback", "TEXT");
  },

  load_callback: function(data) {
	Session.Window.check(SessionActivateWindow);
	document.getElementById(SessionActivateWindow).innerHTML=data;
	if (data.indexOf('activateCheck') >= 0) { enableDisplay(SessionActivateContinuWindow); };
	window.scroll(0,0);
  }

}

Session.Logout = {

  //
  // Logout
  //
  load: function() {
	SessionTransfer.request("GET", XCOW_B['url'] + "/session/logout", "", "Session.Logout.load_callback", "TEXT");
  },

  load_callback: function(data) {
	Session.Window.check(SessionLogoutWindow);
	document.getElementById(SessionLogoutWindow).innerHTML=data;
	window.scroll(0,0);

	// bye bye
	if (SessionRedirectLogoutUrl) {
		document.location = SessionRedirectLogoutUrl;
	}
  }

}

Session.Register = {

  //
  // Registreer
  //
  load: function() {
	SessionTransfer.request("GET", XCOW_B['url'] + "/session/new", "", "Session.Register.load_callback", "TEXT");
  },

  load_callback: function(data) {
	Session.Window.check(SessionRegisterWindow);
	document.getElementById(SessionRegisterWindow).innerHTML=data;
	//window.scroll(0,0);
  },

  newAction: function() {

	checked = checkFormInput("register_form");

	statusForm = checked[0];
	vars = checked[1];

        // validate
        if (statusForm == 0 ) {
                Session.Register.newAction_alert(language('session_form_fill'));
        }
	else {
                SessionTransfer.request("POST", XCOW_B['url'] + "/session/new", vars,"Session.Register.newAction_callback", "TEXT");
        }

  },

  newAction_alert: function(message) {
	document.getElementById(SessionRegisterAlertWindow).innerHTML=message;
	//window.scroll(0,0);
  },

  newAction_callback: function(data) {
	document.getElementById(SessionRegisterWindow).innerHTML=data;
	//window.scroll(0,0);

	// go
	if (SessionRedirectRegisterUrl) {
		//document.location = SessionRedirectRegisterUrl;
	}

  }

}

Session.Login = {

  //
  // Login
  //
  load: function() {
	SessionTransfer.request("GET", XCOW_B['url'] + "/session/login", "", "Session.Login.load_callback", "TEXT");
  },

  load_callback: function(data) {
	Session.Window.check(SessionLoginWindow);
	document.getElementById(SessionLoginWindow).innerHTML=data;
	// window.scroll(0,0);
  },

  newAction: function() {
	
	u_name = document.getElementById("login_form").user.name;
	u_value = document.getElementById("login_form").user.value;
	p_name = document.getElementById("login_form").pass.name;
	p_value = document.getElementById("login_form").pass.value;
	r_name = document.getElementById("login_form").redirect.name;
	r_value = document.getElementById("login_form").redirect.value;
	r_value = secureTransfer(r_value);

	// override - part 1
	//if (SessionRedirectLoginUrl) {
	//	r_value = secureTransfer(SessionRedirectLoginUrl);
	//}

        // validate
        stat = 1;
        if (stat == 1 && u_value == '' || p_value == '') {
                Session.Login.newAction_alert(language('session_form_fill'));
                stat = 0;
        }
        if (stat == 1 ) {
                vars = u_name + '=' + u_value + '&' + p_name + '=' + p_value + '&' + r_name + '=' + r_value;
		SessionTransfer.request("POST",  XCOW_B['url'] + "/session/login", vars,"Session.Login.newAction_callback", "TEXT");
        }

  },

  newAction_alert: function(message) {
	document.getElementById(SessionLoginAlertWindow).innerHTML=message;
	//window.scroll(0,0);
  },

  newAction_callback: function(data) {
	// override - part 2
	if (SessionRedirectLoginUrl && data.substring(0,5) == "login") {
	    document.location = SessionRedirectLoginUrl;
	}
	else {
	    document.getElementById(SessionLoginWindow).innerHTML=data;
	    //window.scroll(0,0);
	}
  }

}

Session.Password = {

  //
  // Wachtwoord aanvragen
  //
  load: function() {
	SessionTransfer.request("GET", XCOW_B['url'] + "/session/passNew", "", "Session.Password.load_callback", "TEXT");
  },

  load_callback: function(data) {
	Session.Window.check(SessionPasswordWindow);
	document.getElementById(SessionPasswordWindow).innerHTML=data;
	//window.scroll(0,0);
  },

  newAction: function() {

	checked = checkFormInput("pass_form");

	statusForm = checked[0];
	vars = checked[1];

        // validate
        if (statusForm == 0 ) {
                Session.Password.newAction_alert(language('session_form_fill'));
        }
	else {
                SessionTransfer.request("POST", XCOW_B['url'] + "/session/passNew", vars, "Session.Password.newAction_callback", "TEXT");
        }
	
  },

  newAction_alert: function(message) {
	document.getElementById(SessionPasswordAlertWindow).innerHTML=message;
	//window.scroll(0,0);
  },

  newAction_callback: function(data) {
	document.getElementById(SessionPasswordWindow).innerHTML=data;
	//window.scroll(0,0);
  },

  //
  // Wachtwoord wijzigen
  //
  updateLoad: function() {
	SessionTransfer.request("GET", XCOW_B['url'] + "/session/passUpdate", "", "Session.Password.updateLoad_callback", "TEXT");
  },

  updateLoad_callback: function(data) {
	Session.Window.check(SessionPasswordWindow2);
	document.getElementById(SessionPasswordWindow2).innerHTML=data;
	window.scroll(0,0);
  },
  
  updateAction: function() {

	checked = checkFormInput("pass_form");

	statusForm = checked[0];
	vars = checked[1];

        // validate
        if (statusForm == 0 ) {
                Session.Password.updateAction_alert(language('session_form_fill'));
        }
	else {
                SessionTransfer.request("POST", XCOW_B['url'] + "/session/passUpdate", vars, "Session.Password.updateAction_callback", "TEXT");
        }
	
  },

  updateAction_alert: function(message) {
	document.getElementById(SessionPasswordAlertWindow).innerHTML=message;
	window.scroll(0,0);
  },

  updateAction_callback: function(data) {
	document.getElementById(SessionPasswordWindow2).innerHTML=data;
	window.scroll(0,0);
  }

}

