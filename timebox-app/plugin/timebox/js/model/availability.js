MCOW.Model.Timebox.availability = {

	run: function() {
		// get credentials
		var credentials = TIMEBOX.Lib.getCredentials();

		var newDate = MCOW.Session.Request.param["date"];

		// get availability
		MCOW.Session.Response.param["newDate"] = newDate;
		MCOW.Session.Response.param["current"] = TIMEBOX.Lib.getAvailability("current");
		MCOW.Session.Response.param["future"] = TIMEBOX.Lib.getAvailability("future");

		MCOW.Model.Timebox.availability.callback();
	},

	
	callback: function() {
		MCOW.Event.Control.modelCallback();
	}
	
}
