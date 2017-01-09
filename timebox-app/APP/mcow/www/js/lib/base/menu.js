// functions for a menu, to be shown after a press for example

MCOW.Menu = {

	show: function(element, x, y) {	
		var coordinate = MCOW.Util.getAbsoluteElementPosition(element);
		var menuElement = element.getElementsByClassName("menu")[0];
		if (typeof menuElement != "undefined") {
			menuElement.style.top = (y - coordinate[1]) + "px";
			menuElement.style.left = (x - coordinate[0]) + "px";
			menuElement.style.visibility = "visible";
			// beware focus() does not compute on tablets, it would spawn a keyboard...
			menuElement.focus();
		}
	},

	// hide fires on 'blur'
	hide: function(e) {
		var element = MCOW.Util.getEventElement(e);

		element.style.visibility = "hidden";
	},
	
	// cancel is a button within the menu 'div'
	cancel: function(e) {
		var element = MCOW.Util.getClosestParent(MCOW.Util.getEventElement(e), "div");

		element.style.visibility = "hidden";
	}

}
