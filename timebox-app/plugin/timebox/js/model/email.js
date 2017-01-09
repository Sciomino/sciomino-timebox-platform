MCOW.Model.Timebox.email = {

	run: function() {
		// get credentials
		var credentials = TIMEBOX.Lib.getCredentials();

		var go = MCOW.Session.Request.param["go"];

		var form = document.forms["emailForm"];
		if (go == 1 && form && MCOW.Util.getParentElementById(form, "page") != null) {
			$("#loader").removeClass("hidden");
			var form_name = form.elements["walkthroughemail"].value;

			// store values
			// - every input is stored, even if it is not an emailadress, should not do this... but then again... it can be undone on the next page...
			credentials["email"] = form_name;
			TIMEBOX.Lib.setCredentials(credentials);
		
			// try to authorize
			var query = "?name=" + credentials["name"];
			query = query + "&email=" + credentials["email"];
			MCOW.Connection.getResponse(TIMEBOX.Config["timebox-url"] + "mobile/authorize" + query, function(data) { MCOW.Model.Timebox.email.callbackFromUrl(data); } );
		} 
		else {
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
					null, 								        // callback
					'No Network',   					        // title
					'Ok'             						    // buttonName
				);
			}
			else {
				alert("error:" + data["error"]);
			}
		}
		else {
			if (data.content["status"] == "1") {
				// continue
				MCOW.Event.fire("/Timebox/pin");
			}
			else {
				TIMEBOX.Lib.resetCredentials();
				MCOW.Session.Response.param["error"] = data.content["message"];
				MCOW.Event.Control.modelCallback();
			}
		}
	}
	
}
