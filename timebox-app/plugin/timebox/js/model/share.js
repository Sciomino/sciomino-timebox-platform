MCOW.Model.Timebox.share = {

	run: function() {
		// get credentials
		var credentials = TIMEBOX.Lib.getCredentials();

		MCOW.Session.Response.param["email"] = credentials["email"];

		var shares = TIMEBOX.Lib.getShares();
		MCOW.Session.Response.param["networks"] = shares["networks"];

		MCOW.Model.Timebox.share.callback();
	},
	
	callback: function() {
		MCOW.Event.Control.modelCallback();
	}
	
}
