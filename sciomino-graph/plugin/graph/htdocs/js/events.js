// Load & Register events
// Cordoba 1.9.0: android + iOs
Event = {

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

  loadFase1: function() {
	document.addEventListener("deviceready", Cordova.DeviceReady, false);
  },

  loadFase2: function() {
	document.addEventListener("pause", Cordova.Pause, false);
	document.addEventListener("resume", Cordova.Resume, false);
	document.addEventListener("online", Cordova.Online, false);
	document.addEventListener("offline", Cordova.Offline, false);
	document.addEventListener("batterycritical", Cordova.BatteryCritical, false);
	document.addEventListener("batterylow", Cordova.BatteryLow, false);
	document.addEventListener("batterystatus", Cordova.BatteryStatus, false);
  },

  // touchStart=mouseDown, touchEnd=mouseUp, touchMove=mouseMove
  loadFase3: function() {
	document.addEventListener("touchStart", Touch.Start, false);
	document.addEventListener("touchEnd", Touch.End, false);
	document.addEventListener("touchMove", Touch.Move, false);
  },
  
  loadFase4: function() {
    // alert("swipe events");
    // jQuery
    $(document).ready();
    //$('#page').on('tap',function(event) { alert("tap"); });
    //$('#page').on('taphold',function(event) { alert("hold"); });
    $('#page').on('swipeleft',function(event) { window.location.href = $('#next > a:first-child').attr('href'); });
    $('#page').on('swiperight',function(event) { window.location.href = $('#prev > a:first-child').attr('href'); });
		
    // Hammer
    //var hammer = new Hammer(document.getElementById("page"));
    //hammer.ontap = function(ev) { alert("tap"); };
    //hammer.onhold = function(ev) { alert("hold"); };
    //hammer.onswipe = function(ev) { alert("swipe"); };
    
    // QuoJS
   	//$$('body').on('tap', function() { alert("tap"); });
   	//$$('body').on('hold', function() { alert("hold"); });
   	//$$('body').on('swipeLeft', function() { alert("swipe left"); });
   	//$$('body').on('swipeRight', function() { alert("swipe right"); });
  },
  
  loadFase5: function() {
    $(window).live("orientationchange", Misc.OrientationChange);
  }

}

// Cordova events
Cordova = {

  // let's go from here!
  DeviceReady: function() {
	alert ("ready");
	
  	// now fase 2
  	Event.loadFase2();

	// now fase 3 default touch events
	// and fase 4 library gestures
	Event.loadFase3();
	Event.loadFase4();
	
	// fase 5 misc events
	Event.loadFase5();
	
  	// go for user in 3 secs.
  	setTimeout(function() { navigator.splashscreen.hide(); }, 3000);
  	//cordova.exec(null, null, “SplashScreen”, “hide”, []) 
  },
  
  // we are put in the background and back in business
  Pause: function() {
  },
  Resume: function() {
  },

  // we are online or offline
  Online: function() {
  },
  Offline: function() {
  },

  // battery stuff
  BatteryCritical: function(info) {
  },
  BatteryLow: function(info) {
  },   
  BatteryStatus: function(info) {
  }
    
}

// Touch events
Touch = {

  // start
  start: function() {
  },

  // end
  end: function() {
  },

  // move
  move: function() {
  		// prevent to move the page with your finger
  	    event.preventDefault();
  }
      
}

// Misc events
Misc = {

  // orientation
  OrientationChange: function(e) {
    if(e.orientation == "landscape") {
    	alert("landscape");
    }
    else {
    	alert("portrait");
    }
  }
      
}

// get mouse position
function getMousePosition(e) {

	var x = 0;
	var y = 0;	

	if (!e) var e = window.event;

	if (e.pageX || e.pageY) 	{
		x = e.pageX;
		y = e.pageY;
	}
	else if (e.clientX || e.clientY) 	{
		x = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
		y = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
	}
	
	return (new Array(x, y));
}

// get event element
function getEventElement(e) {
	var element;

	if (!e) var e = window.event;

	if (e.target) {
		element = e.target;
	}
	else if (e.srcElement) {
		element = e.srcElement;
	}
	
	// defeat Safari bug
	if (element.nodeType == 3) {
		element = element.parentNode;
	}

	return (element);
}
