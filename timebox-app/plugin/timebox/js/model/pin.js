MCOW.Model.Timebox.pin = {

	run: function() {
		// get credentials
		var credentials = TIMEBOX.Lib.getCredentials();
		MCOW.Session.Response.param["email"] = credentials["email"];

		var go = MCOW.Session.Request.param["go"];
		var code = MCOW.Session.Request.param["code"];

		var form = document.forms["pinForm"];
		if (go == 1 && form && MCOW.Util.getParentElementById(form, "page") != null) {
			$("#loader").removeClass("hidden");
			var form_name = form.elements["registerpin"].value;
		
			// try to get token
			var key = MCOW.Util.sha1(credentials["name"]+credentials["email"]+form_name);
			var query = "?name=" + credentials["name"];
			query = query + "&email=" + credentials["email"];
			query = query + "&key=" + key;
			MCOW.Connection.getResponse(TIMEBOX.Config["timebox-url"] + "mobile/token" + query, function(data) { MCOW.Model.Timebox.pin.callbackFromUrl(data); } );
		} 
		else {
			MCOW.Session.Response.param["code"] = code;
			MCOW.Model.Timebox.email.callback();
		}	
	},
	
	callback: function() {
		MCOW.Event.Control.modelCallback();
	},

	callbackFromUrl: function(data) {
		if (data.error) {
			if (MCOW.Config["target"] == 'phonegap') {
				navigator.notification.alert(
					'There is currently no network available',  // message
					null,         								// callback
					'No Network',       					    // title
					'Ok'              					   		// buttonName
				);
			}
			else {
				alert("error:" + data["error"]);
			}
		}
		else {
			if (data.content["status"] == "1") {
				// set secret & token
				var credentials = TIMEBOX.Lib.getCredentials();
				var form = document.forms["pinForm"];
				var form_name = form.elements["registerpin"].value;
				credentials["secret"] = form_name;
				credentials["token"] = data.content["token"];
				if (MCOW.Config["debug_credentials"] == '1') {console.log("Timebox.pin credentials: " + JSON.stringify(credentials));}
				TIMEBOX.Lib.setCredentials(credentials);
				
				// continue
				if (data.content["new"] == "1") {
					MCOW.Event.fire("/Timebox/wizard");
				}
				else {
					MCOW.Event.fire("/Timebox/home");
				}
			}
			else {
				MCOW.Session.Response.param["error"] = data.content["message"];
				MCOW.Event.Control.modelCallback();
			}
		}
	}
	
}
