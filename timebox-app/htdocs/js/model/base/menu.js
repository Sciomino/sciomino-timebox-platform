MCOW.Model.menu = {

	run: function() {
		//alert("menu");
		MCOW.Model.menu.callback();
	},
	
	callback: function() {
		MCOW.Event.Control.modelCallback();
	}
	
}
