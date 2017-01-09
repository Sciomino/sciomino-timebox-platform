MCOW.Model.anotherpage = {

	run: function() {
		MCOW.Model.anotherpage.callback();
	},
	
	callback: function() {
		MCOW.Event.Control.modelCallback();
	}
	
}
MCOW.Model.home = {

	run: function() {
		//alert("Home");
		MCOW.Model.home.callback();
	},
	
	callback: function() {
		MCOW.Event.Control.modelCallback();
	}
	
}
MCOW.Model.menu = {

	run: function() {
		//alert("menu");
		MCOW.Model.menu.callback();
	},
	
	callback: function() {
		MCOW.Event.Control.modelCallback();
	}
	
}
MCOW.Model.page = {

	run: function() {
		MCOW.Session.Response.param["one"] = MCOW.Session.Request.param["one"];
		MCOW.Model.page.callback();
	},
	
	callback: function() {
		MCOW.Event.Control.modelCallback();
	}
	
}
