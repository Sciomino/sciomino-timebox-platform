MCOW.Model.page = {

	run: function() {
		MCOW.Session.Response.param["one"] = MCOW.Session.Request.param["one"];
		MCOW.Model.page.callback();
	},
	
	callback: function() {
		MCOW.Event.Control.modelCallback();
	}
	
}
