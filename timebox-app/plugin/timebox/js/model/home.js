MCOW.Model.Timebox.home = {

	run: function() {
		// get credentials
		var credentials = TIMEBOX.Lib.getCredentials();

		// switch pages if the credentials are not known (else continu to homepage)
		// - to email page
		if (typeof credentials["email"] == 'undefined' || credentials["email"] == "") {		
			MCOW.Event.fire("/Timebox/email");
			return;
		}
		// - to pin page
		if (typeof credentials["secret"] == 'undefined' || credentials["secret"] == "") {
			MCOW.Event.fire("/Timebox/pin");
			return;
		}
		
		// we are in, now get stuff from this user
		// in sequence: skin & init personalia, init availability, init shares

		// get skin
		var skin = TIMEBOX.Lib.getSkin();
		MCOW.Session.Response.param["background"] = skin["background"];

		MCOW.Model.Timebox.home.initPersonalia();
	},
	
	callback: function() {
		MCOW.Event.Control.modelCallback();
	},

	// 1. personalia
	initPersonalia: function() {
		var personalia = TIMEBOX.Lib.getPersonalia();

		// only first time
		if (personalia["sync"] == -1) {
			var tokenString = TIMEBOX.Lib.getTokenString();
			var query = "?"+tokenString;
			MCOW.Connection.getResponse(TIMEBOX.Config["timebox-url"] + "mobile/user-personalia-list" + query, function(data) { MCOW.Model.Timebox.home.callbackPersonalia(data); } );
		}
		else {
			MCOW.Session.Response.param["photoStream"] = personalia["photoStream"];
			
			// try to sync each time the homepage is shown, when the worker is not running
			if( ! window.Worker ) {
				TIMEBOX.Lib.syncPersonalia();
			}

			MCOW.Model.Timebox.home.initAvailability();
		}
	},
	
	callbackPersonalia: function(data) {
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
			MCOW.Session.Response.param["photoStream"] = data.content["photoStream"];
			
			// save
			TIMEBOX.Lib.setPersonalia(data.content, 0);
		}
		
		MCOW.Model.Timebox.home.initAvailability();
	},

	// 2. availability
	initAvailability: function() {
		var availability1 = TIMEBOX.Lib.getAvailability("current");
		var availability2 = TIMEBOX.Lib.getAvailability("future");
		
		// only first time
		if (availability1["sync"] == -1 && availability2["sync"] == -1) {
			var tokenString = TIMEBOX.Lib.getTokenString();
			var query = "?"+tokenString;
			MCOW.Connection.getResponse(TIMEBOX.Config["timebox-url"] + "mobile/user-availability-list" + query, function(data) { MCOW.Model.Timebox.home.callbackAvailability(data); } );
		}
		else {
			MCOW.Session.Response.param["current"] = availability1;
			MCOW.Session.Response.param["future"] = availability2;		

			// try to sync each time the homepage is shown, when the worker is not running
			if( ! window.Worker ) {
				TIMEBOX.Lib.syncAvailability();
			}
			
			MCOW.Model.Timebox.home.initShares();
		}
	},

	callbackAvailability: function(data) {
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
			MCOW.Session.Response.param["current"] = data.content["current"];
			MCOW.Session.Response.param["future"] = data.content["future"];		

			// save
			TIMEBOX.Lib.setAvailability("current", data.content["current"], 0);
			TIMEBOX.Lib.setAvailability("future", data.content["future"], 0);
		}
		
		MCOW.Model.Timebox.home.initShares();
	},

	// 3. shares
	initShares: function() {
		var shares = TIMEBOX.Lib.getShares();
		
		if (shares["sync"] == -1) {
			var tokenString = TIMEBOX.Lib.getTokenString();
			var query = "?"+tokenString;
			MCOW.Connection.getResponse(TIMEBOX.Config["timebox-url"] + "mobile/user-availability-list-network" + query, function(data) { MCOW.Model.Timebox.home.callbackShares(data); } );
		}
		else {
			MCOW.Session.Response.param["networks"] = shares["networks"];
			MCOW.Session.Response.param["networkCount"] = TIMEBOX.Lib.countShares(shares["networks"]);

			// try to sync each time the homepage is shown, when the worker is not running
			if( ! window.Worker ) {
				TIMEBOX.Lib.syncShares();
			}

			MCOW.Event.Control.modelCallback();
		}
	},

	callbackShares: function(data) {
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
			MCOW.Session.Response.param["networks"] = data.content["networks"];
			MCOW.Session.Response.param["networkCount"] = TIMEBOX.Lib.countShares(data.content["networks"]);
			
			// save	
			TIMEBOX.Lib.setShares(data.content, 0);
		}
		
		MCOW.Event.Control.modelCallback();
	}

}
