MCOW.Model.home = {

	run: function() {
		//alert("Home");
		MCOW.Model.home.callback();
	},
	
	callback: function() {
		MCOW.Event.Control.modelCallback();
	}
	
}
