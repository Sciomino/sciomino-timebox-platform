// Load & Register events
// - Cordova event
// - touch event
// - control event (the mcow controller)

MCOW.Event = {

	// setup cron
	cronWorker : "",

	// Start loading after window.onload
	// - only used in index.html
	AddOnload: function(func) {
		var oldonload = window.onload; 
		if (typeof window.onload != 'function') { 
			window.onload = func; 
		} 
		else { 
			window.onload = function() { 
				if (oldonload) { 
					oldonload(); 
				} 
				func(); 
			} 
		} 
	},
  
	// Fire is mcow's own event to switch between pages
	// - https://developer.mozilla.org/en-US/docs/Web/API/CustomEvent
	fire: function(url) {
		// CustomEvent is polyfilled
		var event = new CustomEvent("fire", { detail: { "url": url }, bubbles: true, cancelable: true });

		if (document.createEvent) {
			document.dispatchEvent(event);
		} else {
			document.fireEvent("on" + event.eventType, event);
		}		
	},

	// phonegap loader, check if we are ready to rock
	loadFase1: function() {
		document.addEventListener("deviceready", MCOW.Event.Cordova.DeviceReady, false);
	},

	// phonegap events to interact with the device
	loadFase2: function() {
		document.addEventListener("pause", MCOW.Event.Cordova.Pause, false);
		document.addEventListener("resume", MCOW.Event.Cordova.Resume, false);
		document.addEventListener("online", MCOW.Event.Cordova.Online, false);
		document.addEventListener("offline", MCOW.Event.Cordova.Offline, false);
	},

	// mcow control events
	// - load only first time
	loadFase3: function() {
		// add mcow's own fire event to force changes
		document.addEventListener("fire", MCOW.Event.Control.fire, false);

		// load first time setup for plugins
		for (var i = 0; i < MCOW.Config["plugins"].length; ++i) {
			var pluginName = MCOW.Config["plugins"][i];
			window[pluginName.toUpperCase()]["Event"]["load"]();
		};
		
		// cron event listener
		if (MCOW.Config["enable_cron_events"] == 1) {
			// android before 4.4 does not support Workers :-(
			if( window.Worker ) {
				MCOW.Event.cronWorker = new Worker('js/control/cron.js');

				MCOW.Event.cronWorker.addEventListener('message', function(e) {
						var data = JSON.parse(e.data);

						if (MCOW.Config["debug_event_cron"] == 1) { console.log(data.message); }

						// pass message to plugins
						for (var i = 0; i < MCOW.Config["plugins"].length; ++i) {
							var pluginName = MCOW.Config["plugins"][i];
							window[pluginName.toUpperCase()]["Event"]["cron"](data.message);
						};

					}, false);
			
				setTimeout(function(){ MCOW.Event.cronWorker.postMessage('{"message":"start"}'); }, 1000);		
				// test stopping of worker
				// setTimeout(function() {MCOW.Event.cronWorker.postMessage('{"message":"stop"}');}, 30000);
			}
			else {
				// cron fallback function
				for (var i = 0; i < MCOW.Config["plugins"].length; ++i) {
					var pluginName = MCOW.Config["plugins"][i];
					window[pluginName.toUpperCase()]["Event"]["cronFallback"]();
				};
			}
		}

	},

	// prepare transition 
	// - actions performed here are BEFORE transition (and do not show on the page)
	loadFase4: function(page, stage) {
		// load transition setup for plugins
		for (var i = 0; i < MCOW.Config["plugins"].length; ++i) {
			var pluginName = MCOW.Config["plugins"][i];
			window[pluginName.toUpperCase()]["Event"]["loadTransition"](stage);
		}
	},

	// Handle user input with touch
	// - load on every page, right AFTER transition
	//
	// there is more to touch then meets the eye, uh finger...
	// - touch devices/browsers should use touchstart, touchend, touchmove... 
	// - other devices use mousedown, mouseup, mousemove...  
	// - sometimes touch devices fallback here as well
	// - only click on an anchor element
	// - BEWARE: 
	// - ON THE PAGE: after touch, the mouse (& click) events are fired, this way forms and anchors etc. work as default
	// - ON A TOUCHABLE: don't fire both, once touch is recognized disable the mouse events
	loadFase5: function(page) {
		var e = document.getElementById(page);

		// add touch & mouse events on page
		if (MCOW.Config["enable_page_events"] == '1') {
			e.addEventListener("touchstart", MCOW.Event.Touch.start, false);
			e.addEventListener("touchend", MCOW.Event.Touch.end, false);
			e.addEventListener("touchmove", MCOW.Event.Touch.move, false);
			e.addEventListener("mousedown", MCOW.Event.Touch.mousedown, false);
			e.addEventListener("mouseup", MCOW.Event.Touch.mouseup, false);
			e.addEventListener("mousemove", MCOW.Event.Touch.mousemove, false);
		}
		
		// add touch & mouse events to touchables of the page & topbar
		var touchables = e.getElementsByClassName("mcow-touchable");
		for (var i=0;i<touchables.length;i++) {
			touchables[i].addEventListener("touchstart", MCOW.Event.Touch.start, false);
			touchables[i].addEventListener("touchend", MCOW.Event.Touch.end, false);
			touchables[i].addEventListener("touchmove", MCOW.Event.Touch.move, false);
			touchables[i].addEventListener("mousedown", MCOW.Event.Touch.mousedown, false);
			touchables[i].addEventListener("mouseup", MCOW.Event.Touch.mouseup, false);
			touchables[i].addEventListener("mousemove", MCOW.Event.Touch.mousemove, false);
		}
		var touchablesTop = document.getElementById("topbar").getElementsByClassName("mcow-touchable");
		for (var i=0;i<touchablesTop.length;i++) {
			touchablesTop[i].addEventListener("touchstart", MCOW.Event.Touch.start, false);
			touchablesTop[i].addEventListener("touchend", MCOW.Event.Touch.end, false);
			touchablesTop[i].addEventListener("touchmove", MCOW.Event.Touch.move, false);
			touchablesTop[i].addEventListener("mousedown", MCOW.Event.Touch.mousedown, false);
			touchablesTop[i].addEventListener("mouseup", MCOW.Event.Touch.mouseup, false);
			touchablesTop[i].addEventListener("mousemove", MCOW.Event.Touch.mousemove, false);
		}

		// add click event on anchors of the page
		if (MCOW.Config["enable_click_events"] == '1') {
			var anchors = e.getElementsByTagName("a");
			for (var i=0;i<anchors.length;i++) {
				if (anchors[i].getAttribute("class") == "mcow-transition-out") {
					anchors[i].addEventListener('click', MCOW.Event.Control.clickOut, false);
				}
				else {
					anchors[i].addEventListener('click', MCOW.Event.Control.click, false);
				}
			}
		}

		// add cancel events for menu's of the page
		var menus = e.getElementsByClassName("menu");
		for (var i=0;i<menus.length;i++) {
			menus[i].addEventListener('blur', MCOW.Menu.hide, false);
		}
		var menus = document.getElementById('page').getElementsByClassName("menu-cancel");
		for (var i=0;i<menus.length;i++) {
			menus[i].addEventListener('click', MCOW.Menu.cancel, false);
		}

		// load page setup for plugins
		for (var i = 0; i < MCOW.Config["plugins"].length; ++i) {
			var pluginName = MCOW.Config["plugins"][i];
			window[pluginName.toUpperCase()]["Event"]["loadPage"]();
		}

	}
	
}

// Cordova events
MCOW.Event.Cordova = {

  // let's go from here!
  DeviceReady: function() {
  	// fase 2 for the device stuff
  	MCOW.Event.loadFase2();

	// fase 3 to start MCOW
	MCOW.Event.loadFase3();

	// init phonegap plugins
	// - if necessary to differentiate between platforms, use: cordova.platformId
	if (MCOW.Config["target"] == 'phonegap') {
		StatusBar.overlaysWebView(false);
	}
	
	// go
	// - fire a custom url (on app startup) or go home (and fire custom url from handleOpenURL function)
	if (typeof PHONEGAP_CUSTOM_URL_PAGE != "undefined" && PHONEGAP_CUSTOM_URL_PAGE != "") {
		MCOW.Event.fire(PHONEGAP_CUSTOM_URL_PAGE);
		PHONEGAP_CUSTOM_URL_PAGE = "";
	}
	else {
		MCOW.Event.fire(MCOW.Config["homepage"]);
		PHONEGAP_CUSTOM_URL_FIRE = 1;
	}
	
  	// show in 0.1 sec
  	setTimeout(function(){ navigator.splashscreen.hide(); }, 100);
  },
  
  // we are put in the background and back in business
  Pause: function() {
		// pass event to plugins
		for (var i = 0; i < MCOW.Config["plugins"].length; ++i) {
			var pluginName = MCOW.Config["plugins"][i];
			window[pluginName.toUpperCase()]["Event"]["devicePause"]();
		};
 		if (MCOW.Config["enable_cron_events"] == 1) {
			if( window.Worker ) {
				MCOW.Event.cronWorker.postMessage('{"message":"stop"}');
			}
		}
  },
  
  Resume: function() {
		// pass event to plugins
		for (var i = 0; i < MCOW.Config["plugins"].length; ++i) {
			var pluginName = MCOW.Config["plugins"][i];
			window[pluginName.toUpperCase()]["Event"]["deviceResume"]();
		};
 		if (MCOW.Config["enable_cron_events"] == 1) {
			if( window.Worker ) {
				MCOW.Event.cronWorker.postMessage('{"message":"stop"}');
				setTimeout(function(){ MCOW.Event.cronWorker.postMessage('{"message":"start"}'); }, 1000);		
			}
		}
  },

  // we are online or offline
  Online: function() {
		// pass event to plugins
		for (var i = 0; i < MCOW.Config["plugins"].length; ++i) {
			var pluginName = MCOW.Config["plugins"][i];
			window[pluginName.toUpperCase()]["Event"]["deviceOnline"]();
		};
 		if (MCOW.Config["enable_cron_events"] == 1) {
			if( window.Worker ) {
				MCOW.Event.cronWorker.postMessage('{"message":"stop"}');
				setTimeout(function(){ MCOW.Event.cronWorker.postMessage('{"message":"start"}'); }, 1000);		
			}
		}
  },
  
  Offline: function() {
		// pass event to plugins
		for (var i = 0; i < MCOW.Config["plugins"].length; ++i) {
			var pluginName = MCOW.Config["plugins"][i];
			window[pluginName.toUpperCase()]["Event"]["deviceOffline"]();
		};
 		if (MCOW.Config["enable_cron_events"] == 1) {
				if( window.Worker ) {
				MCOW.Event.cronWorker.postMessage('{"message":"stop"}');
			}
		}
  }
    
}


// Touch events
// - for one finger only!
// order of events
// - (1) check for touchstart, touchmove & touchend ==> preventdefault on touchend
// - (2) check for mouseup, mousemove, mousedown ==> preventdefault on mouseup
// - (3) click, on anchors only
// > click on an anchor is the main mcow event (enable_click_events = 1)
// > swipe fires on all pages for next & prev
// > tap, swipe, drag & press fire on selected touch and mouse elements: "class=touchable"
//
// scrolling from: http://www.hnldesign.nl/work/code/momentum-scrolling-using-jquery/
// - if cancel_default_events = 0 ==> preventdefault always, (this MUST be used to disable scolling when enable_page_scroll=1)
// - else only preventdefault on 'end' to disable click events on 'touchable' class
MCOW.Event.Touch = {

	gesture : "",
	direction : "",

	// touchable
	startX : 0,
	startY : 0,
	distanceX : 0,
	distanceY : 0,
	minDistance : 10,
	deviationDistance: 30,
	accelerationX : 0,
	accelerationY : 0,
	startTime : 0,
	elapsedTime : 0,
	maxTime : 300,
	
	touchableTop: 0,
	touchableLeft: 0,
	touchableStart : 0,
	
	// scrollable
	scrollW: 0,
	scrollH: 0,
	scrollX: 0,
	scrollY: 0,
	scrollTop: 0,
	scrollLeft: 0,
	scrollDurationX: window.innerwidth * 1.5, // pixels in ms?
	scrollDurationY: window.innerheight * 1.5, // pixels in ms?
	maxScrollSpeed: 1.2,
	minOffset: 30,

	reset: function() {
		MCOW.Event.Touch.startX = 0;
		MCOW.Event.Touch.startY = 0;
		MCOW.Event.Touch.distanceX = 0;
		MCOW.Event.Touch.distanceY = 0;
		MCOW.Event.Touch.accelerationX = 0;
		MCOW.Event.Touch.accelerationY = 0;
		MCOW.Event.Touch.startTime = 0;
		MCOW.Event.Touch.elapsedTime = 0;
	},

	fire : function(element) {
		if (MCOW.Event.Touch.gesture == "swipe") {
			if (MCOW.Event.Touch.direction == "left") {
				if (MCOW.Session.Response.next != "none") {
					MCOW.Event.fire("http://mcow/" + MCOW.Session.Response.next + "?MCOW-transition=next");
				}
			}
			else if (MCOW.Event.Touch.direction == "right") {
				if (MCOW.Session.Response.prev != "none") {
					MCOW.Event.fire("http://mcow/" + MCOW.Session.Response.prev + "?MCOW-transition=prev");
				}
			}
		}
	},
	
	// start
	start: function(e) {
		var element = MCOW.Util.getEventElement(e);
		// don't know why?
		if (MCOW.Config["enable_page_events"] == '0') {
			element = this;
		}

		// stop default event handling on touchables (not on page!) 
		if (element.getAttribute("class") != null && element.getAttribute("class").indexOf("mcow-touchable") != -1) {
			if (MCOW.Config["cancel_default_events"] == '1') {
				e.preventDefault();
			}
		}

		// get position and start time
		var touchobj = e.changedTouches[0];
		MCOW.Event.Touch.startX = touchobj.pageX;
		MCOW.Event.Touch.startY = touchobj.pageY;
		MCOW.Event.Touch.startTime = new Date().getTime(); 

		if (MCOW.Config["debug_event"] == '1') {console.log("start: touch(x,y): " + MCOW.Event.Touch.startX + "," + MCOW.Event.Touch.startY);}
		
		// go
		MCOW.Event.Touch.startTouchable(element);
		MCOW.Event.Touch.startScrollable(element);
	},

	// end
	end: function(e) {
		var element = MCOW.Util.getEventElement(e);
		// don't know why?
		if (MCOW.Config["enable_page_events"] == '0') {
			element = this;
		}

		// stop default event handling on touchables (not on page!) 
		if (element.getAttribute("class") != null && element.getAttribute("class").indexOf("mcow-touchable") != -1) {
			e.preventDefault();
		}

		// get distance and elapsedtime
		var touchobj = e.changedTouches[0];
		MCOW.Event.Touch.distanceX = touchobj.pageX - MCOW.Event.Touch.startX;
		MCOW.Event.Touch.distanceY = touchobj.pageY - MCOW.Event.Touch.startY;
		MCOW.Event.Touch.elapsedTime = new Date().getTime() - MCOW.Event.Touch.startTime;

		// get direction & gesture
		MCOW.Event.Touch.calculateDirection();
		MCOW.Event.Touch.calculateGesture();
		
		// go
		MCOW.Event.Touch.endTouchable(element);
		MCOW.Event.Touch.endScrollable(element);
			  
		//reset
		MCOW.Event.Touch.reset();
	},

	// move
	move: function(e) {
		var element = MCOW.Util.getEventElement(e);
		// don't know why?
		if (MCOW.Config["enable_page_events"] == '0') {
			element = this;
		}

		// stop default event handling on touchables (not on page!) 
		if (element.getAttribute("class") != null && element.getAttribute("class").indexOf("mcow-touchable") != -1) {
			if (MCOW.Config["cancel_default_events"] == '1') {
				e.preventDefault();
			}
		}
			
		// get distance, elapsedtime and acceleration
		var touchobj = e.changedTouches[0];
		MCOW.Event.Touch.distanceX = touchobj.pageX - MCOW.Event.Touch.startX;
		MCOW.Event.Touch.distanceY = touchobj.pageY - MCOW.Event.Touch.startY;
		MCOW.Event.Touch.elapsedTime = new Date().getTime() - MCOW.Event.Touch.startTime;
		MCOW.Event.Touch.accelerationX = MCOW.Event.Touch.distanceX / MCOW.Event.Touch.elapsedTime;
		MCOW.Event.Touch.accelerationY = MCOW.Event.Touch.distanceY / MCOW.Event.Touch.elapsedTime;
		
		// get direction & gesture
		MCOW.Event.Touch.calculateDirection();
		MCOW.Event.Touch.calculateGesture();

		// go
		MCOW.Event.Touch.moveTouchable(element);
		MCOW.Event.Touch.moveScrollable(element);
	},

	// start
	mousedown: function(e) {
		var element = MCOW.Util.getEventElement(e);
		// don't know why?
		if (MCOW.Config["enable_page_events"] == '0') {
			element = this;
		}

		// stop default event handling on touchables (not on page!) 
		if (element.getAttribute("class") != null && element.getAttribute("class").indexOf("mcow-touchable") != -1) {
			if (MCOW.Config["cancel_default_events"] == '1') {
				e.preventDefault();
			}
		}
		
		// get position and start time
		var coordinate = MCOW.Util.getMousePosition(e);
		MCOW.Event.Touch.startX = coordinate[0];
		MCOW.Event.Touch.startY = coordinate[1];
		MCOW.Event.Touch.startTime = new Date().getTime(); 

		if (MCOW.Config["debug_event"] == '1') {console.log("down: mouse(x,y): " + MCOW.Event.Touch.startX + "," + MCOW.Event.Touch.startY);}
		
		// go
		MCOW.Event.Touch.startTouchable(element);
		MCOW.Event.Touch.startScrollable(element);
	},

	// end
	mouseup: function(e) {
		var element = MCOW.Util.getEventElement(e);
		// don't know why?
		if (MCOW.Config["enable_page_events"] == '0') {
			element = this;
		}

		// stop default event handling on touchables (not on page!) 
		if (element.getAttribute("class") != null && element.getAttribute("class").indexOf("mcow-touchable") != -1) {
			e.preventDefault();
		}

		// get distance and elapsedtime
		var coordinate = MCOW.Util.getMousePosition(e);
		MCOW.Event.Touch.distanceX = coordinate[0] - MCOW.Event.Touch.startX;
		MCOW.Event.Touch.distanceY = coordinate[1] - MCOW.Event.Touch.startY;
		MCOW.Event.Touch.elapsedTime = new Date().getTime() - MCOW.Event.Touch.startTime;

		// get direction & gesture
		MCOW.Event.Touch.calculateDirection();
		MCOW.Event.Touch.calculateGesture();
			  
		// go
		MCOW.Event.Touch.endTouchable(element);
		MCOW.Event.Touch.endScrollable(element);

		//reset	
		MCOW.Event.Touch.reset();
	},

	// move
	mousemove: function(e) {
		var element = MCOW.Util.getEventElement(e);
		// don't know why?
		if (MCOW.Config["enable_page_events"] == '0') {
			element = this;
		}

		// stop default event handling on touchables (not on page!) 
		if (element.getAttribute("class") != null && element.getAttribute("class").indexOf("mcow-touchable") != -1) {
			if (MCOW.Config["cancel_default_events"] == '1') {
				e.preventDefault();
			}
		}

		// get distance, elapsedtime and acceleration
		var coordinate = MCOW.Util.getMousePosition(e);
		MCOW.Event.Touch.distanceX = coordinate[0] - MCOW.Event.Touch.startX;
		MCOW.Event.Touch.distanceY = coordinate[1] - MCOW.Event.Touch.startY;
		MCOW.Event.Touch.elapsedTime = new Date().getTime() - MCOW.Event.Touch.startTime;
		MCOW.Event.Touch.accelerationX = MCOW.Event.Touch.distanceX / MCOW.Event.Touch.elapsedTime;
		MCOW.Event.Touch.accelerationY = MCOW.Event.Touch.distanceY / MCOW.Event.Touch.elapsedTime;

		// get direction & gesture
		MCOW.Event.Touch.calculateDirection();
		MCOW.Event.Touch.calculateGesture();

		// go
		MCOW.Event.Touch.moveTouchable(element);
		MCOW.Event.Touch.moveScrollable(element);
	},

	// calculate direction, used by touch & mouse
	calculateDirection: function() {
		if (Math.abs(MCOW.Event.Touch.distanceX) > Math.abs(MCOW.Event.Touch.distanceY)){
			if (MCOW.Event.Touch.distanceX < 0) {
				MCOW.Event.Touch.direction =  'left';
			}
			else {
				MCOW.Event.Touch.direction = 'right';
			}
		}
		else {
			if (MCOW.Event.Touch.distanceY < 0) {
				MCOW.Event.Touch.direction =  'up';
			}
			else {
				MCOW.Event.Touch.direction = 'down';
			}
		}
	},

	// calculate gesture, used by touch & mouse
	calculateGesture: function() {
		// 1.tap or swipe
		if (MCOW.Event.Touch.elapsedTime <= MCOW.Event.Touch.maxTime) {
			if (Math.abs(MCOW.Event.Touch.distanceX) < MCOW.Event.Touch.minDistance && Math.abs(MCOW.Event.Touch.distanceY) < MCOW.Event.Touch.minDistance) {
				MCOW.Event.Touch.gesture = "tap";
			}
			else {
				// horizontal
				if (Math.abs(MCOW.Event.Touch.distanceX) >= MCOW.Event.Touch.minDistance && Math.abs(MCOW.Event.Touch.distanceY) <= MCOW.Event.Touch.deviationDistance) {
					MCOW.Event.Touch.gesture = "swipe";
				}
				// vertical
				else if (Math.abs(MCOW.Event.Touch.distanceY) >= MCOW.Event.Touch.minDistance && Math.abs(MCOW.Event.Touch.distanceX) <= MCOW.Event.Touch.deviationDistance) {
					MCOW.Event.Touch.gesture = "swipe";
				}
				else {
					MCOW.Event.Touch.gesture = "unknown";
				}
			}
		}
		// 2.press or drag
		else {
			if (Math.abs(MCOW.Event.Touch.distanceX) < MCOW.Event.Touch.minDistance && Math.abs(MCOW.Event.Touch.distanceY) < MCOW.Event.Touch.minDistance) {
				MCOW.Event.Touch.gesture = "press";
			}
			else {
				MCOW.Event.Touch.gesture = "drag";
			}
		}
	},
  
	// touchable
	startTouchable : function(element) {
		// only init 'touchable' class
		//var element = MCOW.Util.getEventElement(e);
		if (element.getAttribute("class") != null && element.getAttribute("class").indexOf("mcow-touchable") != -1) {
			MCOW.Event.Touch.touchableStart = 1;
			// set Top & Left to start position of element
			var coordinate = MCOW.Util.getRelativeElementPosition(element);
			MCOW.Event.Touch.touchableTop = coordinate[1];
			MCOW.Event.Touch.touchableLeft = coordinate[0];
			if (MCOW.Config["debug_event"] == '1') {console.log("startTouchable: element: " + coordinate[1] + "," + coordinate[0]);}
		}	
	},
 
	endTouchable : function(element) {
		// detect tap, press & swipe of 'touchable' class
		//var element = MCOW.Util.getEventElement(e);
		if (element.getAttribute("class") != null && element.getAttribute("class").indexOf("mcow-touchable") != -1) {
			if (MCOW.Event.Touch.touchableStart == 1) {
				if (MCOW.Event.Touch.gesture == "tap") {
					// default is tap
					var event = new CustomEvent("touchable", { detail: { "type":MCOW.Event.Touch.gesture, "x":MCOW.Event.Touch.startX, "y":MCOW.Event.Touch.startY }, bubbles: true, cancelable: true });
					element.dispatchEvent(event);
				}
				if (MCOW.Event.Touch.gesture == "swipe") {
					// include direction for swipe, so the type becomes swipeleft, swiperight, swipeup, swipedown
					var event = new CustomEvent("touchable", { detail: { "type":MCOW.Event.Touch.gesture+MCOW.Event.Touch.direction, "x":MCOW.Event.Touch.startX, "y":MCOW.Event.Touch.startY }, bubbles: true, cancelable: true });
					element.dispatchEvent(event);
				}
				if (MCOW.Event.Touch.gesture == "press") {
					// set element to start position (position of touch point)
					var event = new CustomEvent("touchable", { detail: { "type":MCOW.Event.Touch.gesture, "x":MCOW.Event.Touch.startX, "y":MCOW.Event.Touch.startY }, bubbles: true, cancelable: true });
					element.dispatchEvent(event);
				}

			}
		}

		// reset always, because this can fire outside the 'touchable' class
		MCOW.Event.Touch.touchableStart = 0;
		MCOW.Event.Touch.touchableTop = 0;
		MCOW.Event.Touch.touchableLeft = 0;

		// fire for default tap & default swipe left/right of 'page' 
		//var element = MCOW.Util.getEventElement(e);
		if (element.getAttribute("class") == null || element.getAttribute("class").indexOf("mcow-touchable") == -1) {
			MCOW.Event.Touch.fire(element);
		}	
	},

	moveTouchable : function(element) {
		// only move 'touchable' class
		//var element = MCOW.Util.getEventElement(e);
		if (element.getAttribute("class") != null && element.getAttribute("class").indexOf("mcow-touchable") != -1) {
			if (MCOW.Event.Touch.touchableStart == 1) {
				
				// detect 'press' or 'drag'
				if (MCOW.Event.Touch.gesture == "press") {
					// set element to start position (position of touch point)
					var event = new CustomEvent("touchable", { detail: { "type":MCOW.Event.Touch.gesture, "x":MCOW.Event.Touch.startX, "y":MCOW.Event.Touch.startY }, bubbles: true, cancelable: true });
					element.dispatchEvent(event);
				}
				if (MCOW.Event.Touch.gesture == "drag") {
					// set element to touchable position (position of element which is touched) + distance travelled
					var event = new CustomEvent("touchable", { detail: { "type":MCOW.Event.Touch.gesture, "x":(MCOW.Event.Touch.touchableLeft + MCOW.Event.Touch.distanceX), "y":(MCOW.Event.Touch.touchableTop + MCOW.Event.Touch.distanceY) }, bubbles: true, cancelable: true });
					element.dispatchEvent(event);
					if (MCOW.Config["debug_event"] == '1') {console.log("moveTouchable: distance: " + MCOW.Event.Touch.distanceX + "," + MCOW.Event.Touch.distanceY);}
				}

			}
		}
	},

	// scrollable
	// - currently the scrollable element is defined in config.js
	// - this could be a scrollable class, just as touchable...
	startScrollable : function(element) {
		if (MCOW.Config["enable_page_scroll"] == '1') {
			var scrollElt = document.getElementById(MCOW.Config["enable_page_scroll_element"]);

			// init scrollable
			MCOW.Event.Touch.scrollW = scrollElt.offsetWidth;
			MCOW.Event.Touch.scrollH = scrollElt.offsetHeight;
			MCOW.Event.Touch.scrollX = !!((scrollElt.offsetWidth < scrollElt.scrollWidth && MCOW.Config["enable_page_scroll_horizontal"] == '1'));
			MCOW.Event.Touch.scrollY = !!((scrollElt.offsetHeight < scrollElt.scrollHeight && MCOW.Config["enable_page_scroll_vertical"] == '1'));
			MCOW.Event.Touch.scrollTop = scrollElt.scrollTop;
			MCOW.Event.Touch.scrollLeft = scrollElt.scrollLeft;

			if (MCOW.Config["debug_event"] == '1') {console.log("startScrollable: scrollX: " + scrollElt.offsetWidth + "," + scrollElt.scrollWidth + "," + MCOW.Event.Touch.scrollLeft + ", scrollY: " + scrollElt.offsetHeight + "," + scrollElt.scrollHeight + "," + MCOW.Event.Touch.scrollTop);}
			
			// end running animation
			MCOW.Util.scrollStop();
		}
	},
 
	endScrollable : function(element) {
		if (MCOW.Config["enable_page_scroll"] == '1') {
			var scrollElt = document.getElementById(MCOW.Config["enable_page_scroll_element"]);

			if (MCOW.Event.Touch.scrollX == '1') {
				// scroll a little bit further, momentum style: an offset + animation
				var maxOffset = MCOW.Event.Touch.scrollW * MCOW.Event.Touch.maxScrollSpeed;
				var offset = Math.round(Math.pow(MCOW.Event.Touch.accelerationX, 2) * MCOW.Event.Touch.scrollW);
				if (offset > maxOffset) { offset = maxOffset; }
				if (MCOW.Config["debug_event"] == '1') {console.log("endScrollable: maxOffset: current: " + scrollElt.scrollLeft + ", " + maxOffset + ", offset: " + offset);}
				
				// scroll it!
				var scrollTarget = scrollElt.scrollLeft - offset;
				if (MCOW.Event.Touch.distanceX < 0) { 
					scrollTarget = scrollElt.scrollLeft + offset;
					if (scrollTarget > (scrollElt.scrollWidth - scrollElt.offsetWidth)) { scrollTarget = (scrollElt.scrollWidth - scrollElt.offsetWidth); }
				}
				else {
					if (scrollTarget < 0) { scrollTarget = 0; }
				}
				if (scrollElt.scrollLeft != scrollTarget) {
					// Note: this util is only capable of scrolling Y, not yet X, implement when needed...
					//MCOW.Util.scroll(scrollElt, scrollTarget, MCOW.Event.Touch.scrollDurationX, 'easeOutQuart');
				}

			}
			if (MCOW.Event.Touch.scrollY == '1') {
				// scroll a little bit further, momentum style: an offset + animation
				var maxOffset = MCOW.Event.Touch.scrollH * MCOW.Event.Touch.maxScrollSpeed;
				var offset = Math.round(Math.pow(MCOW.Event.Touch.accelerationY, 2) * MCOW.Event.Touch.scrollH);
				if (offset > maxOffset) { offset = maxOffset; }
				if (MCOW.Config["debug_event"] == '1') {console.log("endScrollable: current: " + scrollElt.scrollTop + ", maxOffset: " + maxOffset + ", offset: " + offset);}

				// scroll it!
				if (offset > MCOW.Event.Touch.minOffset) {
					var scrollTarget = scrollElt.scrollTop - offset;
					if (MCOW.Event.Touch.distanceY < 0) { 
						scrollTarget = scrollElt.scrollTop + offset;
						if (scrollTarget > (scrollElt.scrollHeight - scrollElt.offsetHeight)) { scrollTarget = (scrollElt.scrollHeight - scrollElt.offsetHeight); }
					}
					else {
						if (scrollTarget < 0) { scrollTarget = 0; }
					}
					if (scrollElt.scrollTop != scrollTarget) {
						MCOW.Util.scroll(scrollElt, scrollTarget, MCOW.Event.Touch.scrollDurationY, 'easeOutQuart');
					}
				}
			}	
				
			// reset scroll
			MCOW.Event.Touch.scrollW = 0;
			MCOW.Event.Touch.scrollX = 0;
			MCOW.Event.Touch.scrollLeft = 0;
			MCOW.Event.Touch.scrollH = 0;
			MCOW.Event.Touch.scrollY = 0;
			MCOW.Event.Touch.scrollTop = 0;

		}		
	},

	moveScrollable : function(element) {
		if (MCOW.Config["enable_page_scroll"] == '1') {
			var scrollElt = document.getElementById(MCOW.Config["enable_page_scroll_element"]);
			
			// move scrollable together with touch
			if (MCOW.Event.Touch.scrollX == '1') {
				scrollElt.scrollLeft = MCOW.Event.Touch.scrollLeft - MCOW.Event.Touch.distanceX;
			}
			if (MCOW.Event.Touch.scrollY == '1') {
				scrollElt.scrollTop = MCOW.Event.Touch.scrollTop - MCOW.Event.Touch.distanceY;
			}	
		}

	}
 
}


// Control events
MCOW.Event.Control = {

	fire: function(e) {
		//stop default behaviour
		e.preventDefault(); 
		
		// get element (=request)
		var element = e.detail.url;
		MCOW.Event.Control.model(element);
		
	},
	
	click: function(e) {
		//stop default behaviour
		e.preventDefault(); 
		
		// get element (=request)
		var element = MCOW.Util.getEventElement(e);
		MCOW.Event.Control.model(element);
		
	},
	
	// skip model for mcow-transition-out
	clickOut: function(e) {
		//stop default behaviour
		e.preventDefault(); 
		
		MCOW.Session.Response.direction = "out";
		MCOW.Event.Control.view();
		
	},

	// every fire/click events results in calling this model!
	model: function(url) {
		// 1. initialize 
		// - already done in index.html
		if (MCOW.Config["debug_event"] == '1') {console.log("========== GO ==========");}
		if (MCOW.Config["debug_event"] == '1') {console.log("Event, Model: url = " + url);}

		// 2. build data structures
		// - preserve previous state
		// a. the original page is the stored request: RequestStore, ResponseStore, DataStore, DataHTMLStore
		// b. the page that transitioned in the original page, is the old request: RequestOld, ResponseOld, DataOld, DataHTMLOld
		// c. return to the original page with 'transition=out' with the current request: Request, Response, Data, DataHTML
		if (typeof MCOW.Session.Store == "undefined") {
			MCOW.Session.Store = {};
		}
		MCOW.Session.RequestPrev = {};
		MCOW.Session.ResponsePrev = {};
		MCOW.Session.DataPrev = "";
		MCOW.Session.DataHTMLPrev = "";
		if (typeof MCOW.Session.Data != "undefined") {
			if (MCOW.Config["debug_event"] == '1') {console.log("Event, Model: remember prevClassName = " + MCOW.Session.Response.className);}
			MCOW.Session.Store["prevClassName"] = MCOW.Session.Response.className;

			MCOW.Session.RequestPrev = MCOW.Session.Request;
			MCOW.Session.ResponsePrev = MCOW.Session.Response;
			MCOW.Session.DataPrev = MCOW.Session.Data;
			MCOW.Session.DataHTMLPrev = MCOW.Session.DataHTML;			
		}
		MCOW.Session.Request = {};
		MCOW.Session.Response = {};
		MCOW.Session.Data = "";
		MCOW.Session.DataHTML = "";
		
		// 3. parse url
		// - the DOM takes care of this :-)
		var urlParser = document.createElement('a');
		urlParser.href = url;
		MCOW.Session.Request.url = url;
		MCOW.Session.Request.protocol = urlParser.protocol;
		MCOW.Session.Request.host = urlParser.host;
		MCOW.Session.Request.hostname = urlParser.hostname;
		MCOW.Session.Request.port = urlParser.port;
		MCOW.Session.Request.path_info = urlParser.pathname;
		MCOW.Session.Request.hash = urlParser.hash;
		MCOW.Session.Request.query_string = urlParser.search;

		// - get params
		// TODO: strip parameters! stripslashes and striptags
		MCOW.Session.Request.param = MCOW.Util.getParams(MCOW.Session.Request.query_string);
		MCOW.Session.Response.param = {};
		
		// - remove leading slash
		// - stupid chrome on windows adds /c:/ to the path?
        MCOW.Session.Request.path_info = MCOW.Session.Request.path_info.replace(/^\/C:\//, '')
		MCOW.Session.Request.path_info = MCOW.Session.Request.path_info.replace(/^\//, '')

		// 4. check CONTROL config
		var controller = MCOW.Control;
		// page not found
		var controlElement = MCOW.Control["error404"];
		MCOW.Session.Response.className = "error404";
		MCOW.Session.Response.plugin = "";
		if (MCOW.Control.hasOwnProperty(MCOW.Session.Request.path_info)) {
			var controlElement = MCOW.Control[MCOW.Session.Request.path_info];
			MCOW.Session.Response.className = MCOW.Session.Request.path_info;
			var pluginIndex = MCOW.Session.Response.className.indexOf("/");
			if (pluginIndex  != -1) {
				var pluginLength = MCOW.Session.Response.className.length;
				MCOW.Session.Response.plugin = MCOW.Session.Response.className.substr(0,pluginIndex);
				MCOW.Session.Response.className = MCOW.Session.Response.className.substr(pluginIndex + 1, pluginLength + 1);
				
			}
		}
		
		MCOW.Session.Response.model = controlElement["model"];
		MCOW.Session.Response.view = controlElement["view"];
		MCOW.Session.Response.transition = controlElement["transition"];
		MCOW.Session.Response.direction = "in";
		MCOW.Session.Response.redirect = "";
		MCOW.Session.Response.reload = 0;
		MCOW.Session.Response.next = controlElement["next"];
		MCOW.Session.Response.prev = controlElement["prev"];
		MCOW.Session.Response.database = controlElement["database"];
		MCOW.Session.Response.access = controlElement["access"];
		// different transitions for next & prev
		// - use this also to override default direction & transition & redirection & reload
		if (MCOW.Session.Request.param["MCOW-transition"] == "next") {
			MCOW.Session.Response.transition = controlElement["transition-next"];
		}
		if (MCOW.Session.Request.param["MCOW-transition"] == "prev") {
			MCOW.Session.Response.transition = controlElement["transition-prev"];
		}	
		if (MCOW.Session.Request.param["MCOW-transition"] == "out") {
			MCOW.Session.Response.direction = "out";
		}	
		if (MCOW.Session.Request.param["MCOW-transition"] == "none" || MCOW.Session.Request.param["MCOW-transition"] == "right" || MCOW.Session.Request.param["MCOW-transition"] == "left" || MCOW.Session.Request.param["MCOW-transition"] == "up" || MCOW.Session.Request.param["MCOW-transition"] == "down") {
			MCOW.Session.Response.transition = MCOW.Session.Request.param["MCOW-transition"];
		}	
		if (typeof MCOW.Session.Request.param["MCOW-redirect"] != 'undefined') {
			MCOW.Session.Response.redirect = MCOW.Session.Request.param["MCOW-redirect"];
		}
		if (MCOW.Session.Request.param["MCOW-reload"] == 1) {
			MCOW.Session.Response.reload = MCOW.Session.Request.param["MCOW-reload"];
		}
		
		// 5. verify access (session)
		// - our database is the LocalStorage object.
		
		// -check for session token in localstorage
		// - SessionUser
		// - SessionEmail
		// - SessionDisplay
		// - SessionKey
		// - SessionAccessLevel
		// - SessionCreated
		// - SessionTimestamp
		
		// -if not present fetch token from webserver
		
		// 6. run MODEL
		// - read language from localstorage, which is set before
		// - it is convenient to have this variable as well
		MCOW.Session.Response.language = localStorage["Language"];

		// localStorage IS the database
		// - there is no need for initialization, for debuging purposes we set a storage flag
		// - just use Session.Response.database as a prefix of the localstorage key!
		if (! (MCOW.Session.Response.database == "" || MCOW.Session.Response.database == "none" ) ) {
			localStorage["Database"] = 1;
		}
		else {
			localStorage["Database"] = 0;
		}
		
		// Load model on demand
		// - very pretty :-)
		// - na laden van het model, wordt de callback functie aangeroepen en die runt het model
		// - plugins zijn altijd preloaded...
		if (! (MCOW.Config["preload"]) && MCOW.Session.Response.plugin == "") {
			MCOW.Lib.Js.loadFileWithCallback(MCOW.Config["model_base"] + "/" + MCOW.Session.Response.model + ".js", function() { MCOW.Model[MCOW.Session.Response.className].run(); });
		} 
		// Or preload in lib and just run it
		else {
			if (MCOW.Session.Response.plugin != "") {
				MCOW.Model[MCOW.Session.Response.plugin][MCOW.Session.Response.className].run(); 
			}
			else {
				MCOW.Model[MCOW.Session.Response.className].run(); 
			}
		}

	},

	// give control to the model to allow async fetching of external data.
	modelCallback: function() {
		MCOW.Event.Control.view();
	},
	
	// make sure all javascript from the model is loaded before presenting the view
	// that's why this is a callback function...
	view: function() {
		// 7. present VIEW
			
		// make sure the topbar is cleared
		if (document.getElementById("topbar").classList.length == 1) {
			MCOW.Util.disableDisplay("topbar");
			document.getElementById("topbar").classList.remove(document.getElementById("topbar").classList.item(0));
			MCOW.Util.setHTML("topbar","");
		}
		// also clear the overlay (it's out of sight, so no need to disable)
		MCOW.Util.setHTML("overlay","");

		// Load view on demand
		// - also pretty :-)
		// - now the content is loaded (setHTML) and excecuted (setScript)
		// - the content becomes visible using a transition (setTransition)
		// - the loading can be reversed with the 'out' direction
		if (! (MCOW.Config["preload"]) ) {
			MCOW.Lib.Data.loadFileWithCallback(MCOW.Config["view_base"] + "/" + MCOW.Session.Response.view + ".js", function() { 
					if ((httpRequest.readyState === 4) && (httpRequest.status === 200)) {
						MCOW.Event.Control.viewFinish(httpRequest.responseText);
					}
				});
		} 
		// Or preload in lib and show it
		else {
			var property = MCOW.Session.Response.className;
			var data = "";
			if (MCOW.Session.Response.plugin != "") {
				data = atob(MCOW.View[MCOW.Session.Response.plugin]["preload"][property]);
			}
			else {
				data = atob(MCOW.View["preload"][property]); 
			}
			MCOW.Event.Control.viewFinish(data);
		}

	},
	
	viewFinish : function (data) {
		var currentClassName = MCOW.Session.Response.className;
		if (MCOW.Config["debug_event"] == '1') {console.log("Event, View: className = " + currentClassName);}

		// store Data
		// - current data for 'page', so transition-out works correctly
		// - html data for 'store', so the correct background is displayed for a transition
		MCOW.Session.Data = data;
		MCOW.Session.DataHTMLOld = document.getElementById('page').innerHTML;

		// init store with current classname
		if (typeof MCOW.Session.Store[currentClassName] == "undefined") {
			if (MCOW.Config["debug_event"] == '1') {console.log("Event, View: init store for className = " + MCOW.Session.Response.className);}
			MCOW.Session.Store[currentClassName] = {};
		}

		// store old session for this classname
		if (MCOW.Config["debug_event"] == '1') {console.log("Event, View: save 'old' of className = " + currentClassName);}
		MCOW.Session.Store[currentClassName]["RequestOld"] = MCOW.Session.RequestPrev;
		MCOW.Session.Store[currentClassName]["ResponseOld"] = MCOW.Session.ResponsePrev;
		MCOW.Session.Store[currentClassName]["DataOld"] = MCOW.Session.DataPrev;
		MCOW.Session.Store[currentClassName]["DataHTMLOld"] = MCOW.Session.DataHTMLPrev;
		
		// save redirect for redirect after transition-out
		var redirect = MCOW.Session.Response.redirect;
		
		// out is only possible after a previous in
		// - the previous in page is locate in 'store'
		// - the direction to be transitioned out is 'old'
		if (MCOW.Session.Response.direction == "out") {
			if (MCOW.Config["debug_event"] == '1') {console.log("Event, View: OUT + TRANSITION = (reverse of) " + MCOW.Session.Store[currentClassName]["ResponseOld"].transition);}
			// reverse loading of transition
			MCOW.Util.initReverseTransition("transition", MCOW.Session.Store[currentClassName]["ResponseOld"].transition);
			MCOW.Util.setHTML("transition", document.getElementById('page').innerHTML); 

			// set correct height before displaying transition
			document.getElementById("transition").style.marginTop = (0 - document.getElementById("pagescroller").scrollTop) + "px";
			MCOW.Util.enableDisplay("transition");

			// restore previous page from store
			MCOW.Util.setHTML("page", MCOW.Session.Store[currentClassName]["DataStore"]);
			// - enable this for correct loading of page after page out
			MCOW.Util.setScript("page"); 
			MCOW.Event.loadFase4("page", "load");

			// set topbar if required
			if (document.getElementById("topbar").classList.length == 1) {
				MCOW.Util.enableDisplay("topbar");
			}
			// reset the pagescroller
			document.getElementById("pagescroller").scrollTop = 0;
			window.scrollTo(0,0);


			// allow time for reflow & transform (2ms)
			setTimeout(function() {
				MCOW.Util.setReverseTransition("transition", MCOW.Session.Store[currentClassName]["ResponseOld"].transition); 
				// restore session after transistion
				// - set transition time here (200ms)
				setTimeout(function() {
					// hide transition
					MCOW.Util.disableDisplay("transition");
					MCOW.Util.setHTML("transition", ""); 
					MCOW.Util.resetReverseTransition("transition", MCOW.Session.Store[currentClassName]["ResponseOld"].transition); 

					// restore previous session
					if (MCOW.Config["debug_event"] == '1') {console.log("Event, View: Restore 'store' of className = " + currentClassName);}
					MCOW.Session.Request = MCOW.Session.Store[currentClassName]["RequestStore"];
					MCOW.Session.Response = MCOW.Session.Store[currentClassName]["ResponseStore"];
					MCOW.Session.Data = MCOW.Session.Store[currentClassName]["DataStore"];
					MCOW.Session.DataHTML = MCOW.Session.Store[currentClassName]["DataHTMLStore"];
					
					// reset store
					MCOW.Session.Store[currentClassName]["RequestStore"] = {};
					MCOW.Session.Store[currentClassName]["ResponseStore"] = {};
					MCOW.Session.Store[currentClassName]["DataStore"] = "";
					MCOW.Session.Store[currentClassName]["DataHTMLStore"] = "";
					MCOW.Util.setHTML("store", "");

					// reload page
					MCOW.Event.loadFase5("page");

					// redirect if required
					// - redirects are automatically reloaded
					if (redirect != "") {
						if (redirect.indexOf("?") == -1) {
							redirect = redirect + "?MCOW-reload=1";
						}
						else {
							redirect = redirect + "&MCOW-reload=1";
						}
						MCOW.Event.fire(redirect);
					}

					}, MCOW.Config["trans_timer"]);
				}, MCOW.Config["trans_timer_setup"]);						
		}
		else {
			// do a simple reload of a page without a transition when the same url is called again
			if (MCOW.Session.Response.reload == 1 || (MCOW.Session.Store[currentClassName]["RequestOld"] && MCOW.Session.Request.path_info == MCOW.Session.Store[currentClassName]["RequestOld"].path_info)) {
				if (MCOW.Config["debug_event"] == '1') {console.log("Event, View: RELOAD = " + MCOW.Session.Response.reload + ", PATH_INFO = " + MCOW.Session.Store[currentClassName]["RequestOld"].path_info);}
				MCOW.Util.initTransition("page", "none");
				MCOW.Util.setHTML("page", data); 
				MCOW.Util.setScript("page"); 
				MCOW.Event.loadFase4("page", "load");

				// set topbar if required
				if (document.getElementById("topbar").classList.length == 1) {
					MCOW.Util.enableDisplay("topbar");
				}
				// reset the pagescroller
				document.getElementById("pagescroller").scrollTop = 0;
				window.scrollTo(0,0);

				setTimeout(function() {
					MCOW.Util.setTransition("page", "none"); 
					// page is transistion
					// - set transition time here (200ms)
					setTimeout(function() {		

						MCOW.Event.loadFase5("page");

						// reset transition
						MCOW.Util.resetTransition("page", "none");

						}, MCOW.Config["trans_timer"]);
					}, MCOW.Config["trans_timer_setup"]);

			}
			else {
				// load a new page with a transition
				if (MCOW.Config["debug_event"] == '1') {console.log("Event, View: IN + TRANSITION = " + MCOW.Session.Response.transition);}

				// backup previous 'data' in 'store' (if available)
				if (MCOW.Session.Store[currentClassName]["DataOld"] != "") {
					// only set Store on a new transitioned-in page
					if (MCOW.Config["debug_event"] == '1') {console.log("Event, View: transfer 'old' of className = " + currentClassName + " in 'store' of className = " + MCOW.Session.Store["prevClassName"]);}
					MCOW.Session.Store[MCOW.Session.Store["prevClassName"]]["RequestStore"] = MCOW.Session.Store[currentClassName]["RequestOld"];
					MCOW.Session.Store[MCOW.Session.Store["prevClassName"]]["ResponseStore"] = MCOW.Session.Store[currentClassName]["ResponseOld"];
					MCOW.Session.Store[MCOW.Session.Store["prevClassName"]]["DataStore"] = MCOW.Session.Store[currentClassName]["DataOld"];
					MCOW.Session.Store[MCOW.Session.Store["prevClassName"]]["DataHTMLStore"] = MCOW.Session.Store[currentClassName]["DataHTMLOld"];
				
					// HTMLOld is background during transition
					MCOW.Util.setHTML("store", MCOW.Session.DataHTMLOld);
					
					// set correct height before displaying store
					document.getElementById("store").style.marginTop = (0 - document.getElementById("pagescroller").scrollTop) + "px";
					MCOW.Util.enableDisplay("store");
				}

				// 20150203: disabled the transition page, do the transition on the page itself!
				// hide loading of new transition
				MCOW.Util.initTransition("page", MCOW.Session.Response.transition);
				MCOW.Util.setHTML("page", data); 
				MCOW.Util.setScript("page");
				MCOW.Event.loadFase4("page", "load");
				
				// Store HTML data (used in the initialization of the next request)
				// MCOW.Session.DataHTML = document.getElementById('page').innerHTML;
				MCOW.Session.DataHTML = data;

				// set topbar if required
				if (document.getElementById("topbar").classList.length == 1) {
					MCOW.Util.enableDisplay("topbar");
				}
				// reset the pagescroller
				document.getElementById("pagescroller").scrollTop = 0;
				window.scrollTo(0,0);
				
				// allow time for reflow & transform (2ms)
				setTimeout(function() {
					MCOW.Util.setTransition("page", MCOW.Session.Response.transition); 
					// page is transistion
					// - set transition time here (200ms)
					setTimeout(function() {		
						// remove store and set new page
						if (MCOW.Session.Store[currentClassName]["DataOld"] != "") {
							MCOW.Util.disableDisplay("store");
						}

						MCOW.Event.loadFase5("page");

						// reset transition
						MCOW.Util.resetTransition("page", MCOW.Session.Response.transition);

						}, MCOW.Config["trans_timer"]);
					}, MCOW.Config["trans_timer_setup"]);
			}
		}
	}
	
}

