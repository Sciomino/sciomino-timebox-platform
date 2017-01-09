// define the Timebox space
TIMEBOX = {};

TIMEBOX.Status = {};

TIMEBOX.Config = {

	// which server to connect to
	// ontwikkel (for firefox & chrome)
	'timebox-url': 'http://sciomino2.0/',
	// test (for cordova emulate & apps)
	// 'timebox-url': 'http://test.sciomino.nl/',
	// produktie
	// - let op: https!
	// 'timebox-url': 'https://www.sciomino.com/',
	
}

// TIMEBOX events
TIMEBOX.Event = {

	// from device
	devicePause : function() {
		// try to sync before pause
		TIMEBOX.Lib.syncPersonalia();
		TIMEBOX.Lib.syncAvailability();
		TIMEBOX.Lib.syncShares();
	},

	deviceResume : function() {
		TIMEBOX.Lib.fetchAvailability();
		TIMEBOX.Lib.fetchShares();
	},

	deviceOffline : function() {
	},

	deviceOnline : function() {
	},

	// from cron
	cron : function(message) {
		switch (message) {
			case "sync":
				TIMEBOX.Lib.syncPersonalia();
				TIMEBOX.Lib.syncAvailability();
				TIMEBOX.Lib.syncShares();
				break;
			case "fetch-availability":
				TIMEBOX.Lib.fetchAvailability();
				break;
			case "fetch-personalia":
				TIMEBOX.Lib.fetchPersonalia();
				break;
			case "fetch-share":
				TIMEBOX.Lib.fetchShares();
				break;
		}; 
	},
	
	cronFallback : function(message) {
		// fetch the stuff once on startup
		// - sync is done on homepage
		setTimeout(function(){ TIMEBOX.Lib.fetchAvailability(); }, 500);
		setTimeout(function(){ TIMEBOX.Lib.fetchPersonalia(); }, 1000);
		setTimeout(function(){ TIMEBOX.Lib.fetchShares(); }, 1500);
	},

	// from loadFase3
	// - on init
	load : function() {
	},

	// from loadFase4
	// - before transition
	loadTransition : function(stage) {
		// load jQuery Mobile GUI stuff
		if (stage == "load") {
			// this is where it hurts (performance wise)
			// - only to generate button groups on wizard, availability and share pages
			// - <fieldset data-role='controlgroup' data-type='horizontal'>
			$("#page").trigger("create");
		}
		if (stage == "reload") {
			$("#page").trigger("refresh");
		}
		
		// need this funky line to get jquery mobile working on android!?
		// - without this adjustment a link from a page < window.height to a page > window.height won't compute...
		//$('#container').css("min-height", $(window).height()+1);
		// - not needed after introduction of pagescroller, but keep it to 100% for smooth scrolling
		$('#container').css("min-height", $(window).height());

		// prepare pages
		if (MCOW.Session.Response.className == "home") {
			// Set opacity of the first div child of the socialshare elemtn to zero
			$("#socialshare > div:first-child").css({"opacity":0});
			// Move the second div child of the socialshare element down by a mile
			$("#socialshare > div:last-child").css({"bottom":-400});

			// Set opacity of the first div child of the photoselection elemtn to zero
			$("#photoselection > div:first-child").css({"opacity":0});
			// Move the second div child of the photoselection element down by a mile
			$("#photoselection > div:last-child").css({"bottom":-400});
		}
		
		if (MCOW.Session.Response.className == "availability") {		
			// Check if either one of the currentavailability radio buttons have been checked
			if ($("input[name=currentavailability]:checked").length > 0) {
				// Check if the currentavailability is 1 (available)
				if ($("input[name=currentavailability]:checked").val() == "1") {
					// Unhide the currentavailset
					$("#currentavailset").removeClass("hidden");
				}
				// Unhide currentuntillset container
				$("#currentuntillset").removeClass("hidden");
				// Remove the hidden class on the afterthatset
				$("#afterthatset").removeClass("hidden");
			}
			// also check the afteravailability buttons
			if ($("input[name=afteravailability]:checked").length > 0) {
				// Check if the currentavailability is 1 (available)
				if ($("input[name=afteravailability]:checked").val() == "1") {
					// Unhide afteravailset
					$("#afteravailset").removeClass("hidden");
				}
			}
		}
		
	},
	
	// from loadFase5
	// - for every page
	loadPage: function() {
		
		// add events on 'mcow-touchable' in page & topbar
		var touchables = document.getElementById('page').getElementsByClassName("mcow-touchable");
		for (var i=0;i<touchables.length;i++) {
			touchables[i].addEventListener('touchable', TIMEBOX.Event.touchable, false);
		}
		var touchablesTop = document.getElementById('topbar').getElementsByClassName("mcow-touchable");
		for (var i=0;i<touchablesTop.length;i++) {
			touchablesTop[i].addEventListener('touchable', TIMEBOX.Event.touchable, false);
		}

		//set jQuery document ready functions for each page
		// - alternatief is om de DOM weer af te breken als er naar een nieuwe pagina wordt gegaan.
		if (MCOW.Session.Response.className == "home") {
			// only once... this is fired a lot of times by the event handler... why?
			if (TIMEBOX.Status["home"] != 1) {
				// Setup live handlers
				$(document)

				// Socialshare cancel button
				.on("click","#socialshare a.cancel",function(){
					TIMEBOX.Lib.hideSocialShare();
				})
				
				// window.plugins.socialsharing.share(Message, Subject, File, Link, Success, Error) 
				.on('click', '.twitter', function (event) {
					TIMEBOX.Lib.hideSocialShare();
					if (MCOW.Config["target"] == 'phonegap') {
						var message = TIMEBOX.Lib.getAvailabilityMessage("twitter");
						window.plugins.socialsharing.shareViaTwitter(message);
					}
					else {
						alert("Social share only available in phonegap mode.");
					}
				})
				.on('click', '.facebook', function (event) {
					TIMEBOX.Lib.hideSocialShare();
					if (MCOW.Config["target"] == 'phonegap') {
						var message = TIMEBOX.Lib.getAvailabilityMessage("facebook");
						// window.plugins.socialsharing.shareViaFacebook('Message via Facebook', null /* img */, null /* url */, function() {console.log('share ok')}, function(errormsg){alert(errormsg)});
						window.plugins.socialsharing.shareViaFacebookWithPasteMessageHint(message, null /* img */, null /* url */, 'Je kunt je beschikbaarheid direct in je bericht plakken.', function() {}, function() {});
					}
					else {
						alert("Social share only available in phonegap mode.");
					}
				})
				.on('click', '.other', function (event) {
					TIMEBOX.Lib.hideSocialShare();
					if (MCOW.Config["target"] == 'phonegap') {
						var message = TIMEBOX.Lib.getAvailabilityMessage("default");
						window.plugins.socialsharing.share(message);
					}
					else {
						alert("Social share only available in phonegap mode.");
					}
				})
				// photoselection cancel button
				.on("click","#photoselection a.cancel",function(){
					TIMEBOX.Lib.hidePhotoSelection();
				})
				.on('click', '.camera', function (event) {
					TIMEBOX.Lib.hidePhotoSelection();
					if (MCOW.Config["target"] == 'phonegap') {
						navigator.camera.getPicture( TIMEBOX.Lib.phonegapCameraSucces, TIMEBOX.Lib.phonegapCameraFailure, { 
							quality : 45,
							destinationType : Camera.DestinationType.DATA_URL,
							sourceType : Camera.PictureSourceType.CAMERA,
							allowEdit : true,
							encodingType : Camera.EncodingType.JPEG,
							targetWidth : 640,
							targetHeight : 640,
							correctOrientation : true,
							cameraDirection: Camera.Direction.FRONT,
							saveToPhotoAlbum: true } );
					}
					else {
						alert("Photo selection only available in phonegap mode.");
					}
				})					
				.on('click', '.album', function (event) {
					TIMEBOX.Lib.hidePhotoSelection();
					if (MCOW.Config["target"] == 'phonegap') {
						navigator.camera.getPicture( TIMEBOX.Lib.phonegapCameraSucces, TIMEBOX.Lib.phonegapCameraFailure, { 
							quality : 45,
							destinationType : Camera.DestinationType.DATA_URL,
							sourceType : Camera.PictureSourceType.PHOTOLIBRARY,
							allowEdit : true,
							encodingType : Camera.EncodingType.JPEG,
							targetWidth : 640,
							targetHeight : 640,
							correctOrientation : true,
							cameraDirection: Camera.Direction.FRONT } );
					}
					else {
						alert("Photo selection only available in phonegap mode.");
					}
				})							
				.on('click', '.background', function (event) {
					TIMEBOX.Lib.hidePhotoSelection();
					if (MCOW.Config["target"] == 'phonegap') {
						navigator.camera.getPicture( TIMEBOX.Lib.phonegapCameraSuccesBackground, TIMEBOX.Lib.phonegapCameraFailure, { 
							quality : 40,
							destinationType : Camera.DestinationType.DATA_URL,
							sourceType : Camera.PictureSourceType.PHOTOLIBRARY,
							allowEdit : false,
							encodingType : Camera.EncodingType.JPEG,
							//targetWidth : 300,
							//targetHeight : 300,
							correctOrientation : true,
							cameraDirection: Camera.Direction.FRONT } );
					}
					else {
						alert("Photo selection only available in phonegap mode.");
					}
				});								
				
				TIMEBOX.Status["home"] = 1;
			}
		}
		
		if (MCOW.Session.Response.className == "email") {
			// only once... this is fired a lot of times by the event handler... why?
			if (TIMEBOX.Status["email"] != 1) {
				// Setup live handlers
				$(document)
				// Click handler for the list items
				.on("click","#walkthroughcontainer ol li a",function(e){
					// Check if the current element has the selected class already. If so, the current slide has been selected. Halt the script.
					if ($(this).hasClass("selected")) return false;
					// A different element has been selected. Remove all selected classes from the anchors in the order list stack,
					$("#walkthroughcontainer ol li a").removeClass("selected ui-link");
					// Store classname on the current anchor
					var currentclassname = $(this).attr("class");
					// Add selected class
					$(this).addClass("selected");
					// Fade out the selected main element
					var oldclassname = $("#walkthroughcontainer > div.selected").removeClass("selected").attr("class");
					// Remove class from container and add the new one
					$("#walkthroughcontainer").removeClass(oldclassname).addClass(currentclassname);
					// Show new element
					$("#walkthroughcontainer > div." + currentclassname).addClass("selected");
				})
				// Input focus
				.on("focus","input#walkthroughemail",function(){
					// Hide the label
					$("label[for=walkthroughemail]").hide();
				})
				// Input blur
				.on("blur","input#walkthroughemail",function(){
					// Check if the input value is empty to show the label again
					if ($(this).val() == "") $("label[for=walkthroughemail]").show();
				})
				// Swipeleft handler
				.on("swipeleft","body",function(){
					// Try to get the next element
					var nextel = $("#walkthroughcontainer ol li a.selected").parent().next();
					// Check if the next element is a list item.
					if (nextel.prop("tagName") == "LI") $("a",nextel).click();
				})
				// Swiperight handler
				.on("swiperight","body",function(){
					// Try to get the previous element
					var prevel = $("#walkthroughcontainer ol li a.selected").parent().prev();
					// Check if the prev element is a list item.
					if (prevel.prop("tagName") == "LI") $("a",prevel).click();
				})
				
				// Clicks/links added by HERMAN
				.on("click","button#walkthroughbutton",function(){
					// reload page
					MCOW.Event.fire("/Timebox/email?go=1");
				});
				
				TIMEBOX.Status["email"] = 1;
			}
		}

		if (MCOW.Session.Response.className == "pin") {
			// only once... this is fired a lot of times by the event handler... why?
			if (TIMEBOX.Status["pin"] != 1) {
				// Setup live handlers
				$(document)
				// Input focus
				.on("focus","input#registerpin",function(){
					// Hide the label
					$("label[for=registerpin]").hide();
				})
				// Input blur
				.on("blur","input#registerpin",function(){
					// Check if the input value is empty to show the label again
					if ($(this).val() == "") $("label[for=registerpin]").show();
				})
				
				// Clicks/links added by HERMAN
				.on("click","button#registerbutton",function(){
					// reload page
					MCOW.Event.fire("/Timebox/pin?go=1");
				});
				
				TIMEBOX.Status["pin"] = 1;
			}
		}

		if (MCOW.Session.Response.className == "wizard") {
			// only once... this is fired a lot of times by the event handler... why?
			if (TIMEBOX.Status["wizard"] != 1) {
				// Setup live handlers
				$(document)
				// Input focus
				.on("focus","input[type=text]",function(){
					// Hide the connected label
					$("label[for=" + $(this).attr("name") + "]").hide();
				})
				// Input blur
				.on("blur","input[type=text]",function(){
					// Check if the input value is empty to show the label again
					if ($(this).val() == "") $("label[for=" + $(this).attr("name") + "]").show();
				})
				
				// Clicks/links added by HERMAN
				.on("click","button#playbutton",function(){
					// reload page
					MCOW.Event.fire("/Timebox/wizard?go=1");
				});			
				
				TIMEBOX.Status["wizard"] = 1;
			}
		}
		
		if (MCOW.Session.Response.className == "availability") {		
			// only once... this is fired a lot of times by the event handler... why?
			if (TIMEBOX.Status["availability"] != 1) {
				// Setup live handlers
				$(document)
				// currentavailability radio change
				// - hum, buggy... change is actually a click, so this fires always... need to exclude double activation...
				.on("change","input[name=currentavailability]",function(){ var a = TIMEBOX.Lib.getAvailability('current'); if ( ! ($(this).val() == 1 && a["status"] == "available") ) { TIMEBOX.Lib.checkCurAvailChange($(this).val());TIMEBOX.Lib.setAvailabilityInit('current', $(this).val()); } })
				// Current availability selections
				.on("change","input[name=curmaxhours]",function(){TIMEBOX.Lib.checkCurAvailSet();TIMEBOX.Lib.setAvailabilityHours('current', $(this).val());})
				.on("change","#currentavailset input[type=checkbox]",function(){TIMEBOX.Lib.checkCurAvailSet();TIMEBOX.Lib.setAvailabilityDays('current', $("#currentavailset input:checkbox:checked").map(function(){return $(this).val();}).get());})
				// afteravailability radio change
				// - hum, buggy... change is actually a click, so this fires always... need to exclude double activation...
				.on("change","input[name=afteravailability]",function(){ var a = TIMEBOX.Lib.getAvailability('future'); if ( ! ($(this).val() == 1 && a["status"] == "available") ) { TIMEBOX.Lib.checkAfterAvailChange($(this).val());TIMEBOX.Lib.setAvailabilityInit('future', $(this).val());} })
				// After availability selections
				.on("change","input[name=aftermaxhours]",function(){TIMEBOX.Lib.checkAfterAvailSet();TIMEBOX.Lib.setAvailabilityHours('future', $(this).val());})
				.on("change","#afteravailset input[type=checkbox]",function(){TIMEBOX.Lib.checkAfterAvailSet();TIMEBOX.Lib.setAvailabilityDays('future', $("#afteravailset input:checkbox:checked").map(function(){return $(this).val();}).get());})		
				
				TIMEBOX.Status["availability"] = 1;
			}
		}

		if (MCOW.Session.Response.className == "calendar") {
		}

		if (MCOW.Session.Response.className == "share") {
			// only once... this is fired a lot of times by the event handler... why?
			if (TIMEBOX.Status["share"] != 1) {
				// Setup live handlers
				$(document)
				.on('click', '.shareMail', function (event) {
					if (MCOW.Config["target"] == 'phonegap') {
						window.plugins.socialsharing.shareViaEmail('', 'Reactie via de TimeBox app', ['timebox@sciomino.com'], null, null, null);
					}
					else {
						alert("Social share only available in phonegap mode.");
					}
				});											
				TIMEBOX.Status["share"] = 1;
			}
		}
		
	},
	
	// TOUCHABLES
	touchable: function(e) {
		// get details
		var type = e.detail.type;
		var x = e.detail.x;
		var y = e.detail.y;

		var element = MCOW.Util.getEventElement(e);

		// type is tap, swipeleft, swiperight, swipeup, swipedown, press, drag
		// - implemented 'press' to display a menu with actions
		if (type == "tap") {
			// app navigation
			if (element.getAttribute("class").indexOf("openinternalpage") != -1) { MCOW.Event.fire(element.getAttribute("data")); }

			// open external link					
			if (element.getAttribute("class").indexOf("openexternallink") != -1) {
				window.open(element.getAttribute('data'), '_system');
			}		

			// reset credentials
			if (element.getAttribute("class").indexOf("wrongemailaddresstryagain") != -1) {
				TIMEBOX.Lib.resetCredentials();
				MCOW.Event.fire(element.getAttribute("data"));
			}
				
			// edit share
			if (element.getAttribute("class").indexOf("sharecompany") != -1) {
				TIMEBOX.Lib.setShareById(element.getAttribute("data"), element.getAttribute("selection"));
			}
			
			// social share
			if (element.getAttribute("class").indexOf("socialshare") != -1) {
				// Remove the hidden class
				$("#socialshare").removeClass("hidden");
				// Animate the background
				$("#socialshare > div:first-child").animate({"opacity":0.8});
				// Animate the content
				$("#socialshare > div:last-child").animate({"bottom":0});
			}
			
			// set until
			if (element.getAttribute("class").indexOf("setavailabilityuntil") != -1) {
				TIMEBOX.Lib.setAvailabilityUntil('current', element.getAttribute("data"));
				MCOW.Event.fire("/Timebox/availability?MCOW-transition=out");
			}
			
			// open camera
			if (element.getAttribute("class").indexOf("opencamera") != -1) {
				// Remove the hidden class
				$("#photoselection").removeClass("hidden");
				// Animate the background
				$("#photoselection > div:first-child").animate({"opacity":0.8});
				// Animate the content
				$("#photoselection > div:last-child").animate({"bottom":0});
			}
		
		}
		if (type == "swipeleft") {
			// navigate
			// - disable swipe for now
			/*
			if (element.getAttribute("class").indexOf("enteravailabilitypage") != -1) {	MCOW.Event.fire(element.getAttribute("data")); }			
			if (element.getAttribute("class").indexOf("entersharepage") != -1) { MCOW.Event.fire(element.getAttribute("data")); }			
			*/
		}
		if (type == "swiperight") {
			// navigate
			// - disable swipe for now
			/*
			if (element.getAttribute("class").indexOf("leaveavailabilitypagebyswipe") != -1) {	MCOW.Event.fire(element.getAttribute("data")); }
			if (element.getAttribute("class").indexOf("leavesharepagebyswipe") != -1) {	MCOW.Event.fire(element.getAttribute("data")); }
			*/
		}
		if (type == "press") {
		}
		if (type == "drag") {
		}
		
	}
	
}

// the main lib functions
// - here (and only here) is the connection with the database
// - ONLY in the main get & set methods (makes sure 'sync' is possible)
TIMEBOX.Lib = {

	// SKIN
	getSkin : function() {
		var skin = new Array();
		if (localStorage.getItem("TIMEBOX.SKIN") === null) {
			// init skin
			skin = new TIMEBOX.Data.skin();
			TIMEBOX.Lib.setSkin(skin);
		}
		else {
			skin = JSON.parse(localStorage["TIMEBOX.SKIN"]);
		}
		return skin;
	},

	setSkin : function(skin) {
		localStorage["TIMEBOX.SKIN"] = JSON.stringify(skin);
	},

	// CREDENTIALS
	getCredentials : function() {
		var credentials = new Array();
		if (localStorage.getItem("TIMEBOX.CREDENTIALS") === null) {
			// init credentials
			credentials = new TIMEBOX.Data.credentials();
			TIMEBOX.Lib.setCredentials(credentials);
		}
		else {
			credentials = JSON.parse(localStorage["TIMEBOX.CREDENTIALS"]);
		}
		return credentials;
	},

	setCredentials : function(credentials) {
		localStorage["TIMEBOX.CREDENTIALS"] = JSON.stringify(credentials);
	},

	// MORE CREDENTIALS STUFF
	resetCredentials : function() {
		// init credentials
		credentials = new TIMEBOX.Data.credentials();
		TIMEBOX.Lib.setCredentials(credentials);
	},

	// PERSONALIA
	getPersonalia : function() {
		var personalia = new Array();
		if (localStorage.getItem("TIMEBOX.PERSONALIA") === null) {
			// init personalia
			personalia = new TIMEBOX.Data.personalia();
			localStorage["TIMEBOX.PERSONALIA"] = JSON.stringify(personalia);
		}
		else {
			personalia = JSON.parse(localStorage["TIMEBOX.PERSONALIA"]);
		}
		return personalia;
	},

	setPersonalia : function(personalia, sync) {
		personalia["sync"] = sync;
		personalia["timestamp"] = new Date().getTime();		
		localStorage["TIMEBOX.PERSONALIA"] = JSON.stringify(personalia);
	},

	// MORE PERSONALIA STUFF
	// async fetch
	fetchPersonalia: function() {
		var personalia = TIMEBOX.Lib.getPersonalia();
		if (personalia["sync"] == 0) {
			var tokenString = TIMEBOX.Lib.getTokenString();
			var query = "?"+tokenString;
			MCOW.Connection.getResponse(TIMEBOX.Config["timebox-url"] + "mobile/user-personalia-list" + query, function(data) { TIMEBOX.Lib.fetchPersonaliaCallback(data); } );
		}
	},

	fetchPersonaliaCallback: function(data) {
		if (data.error) {
			// be silent...
		}
		else {
			// save
			// - but keep my local picture
			var personalia = TIMEBOX.Lib.getPersonalia();
			data.content["photoStream"] = personalia["photoStream"];
			TIMEBOX.Lib.setPersonalia(data.content, 0);
		}
	},
	
	// async sync :-)
	// - that means, this sync of data can be called from everywhere and will sync the data in the background, not affecting normal operations
	syncPersonalia : function() {
		var personalia = TIMEBOX.Lib.getPersonalia();
		// only sync if updated
		if (personalia["sync"] == 1) {
			// token
			var tokenString = TIMEBOX.Lib.getTokenString();
			var query = "?"+tokenString;
			// personalia
			query = query + "&firstName=" + personalia["firstname"];
			query = query + "&lastName=" + personalia["lastname"];
			query = query + "&gender=" + personalia["gender"];
			query = query + "&dateofbirthday=" + personalia["dateofbirthday"];
			query = query + "&dateofbirthmonth=" + personalia["dateofbirthmonth"];
			query = query + "&dateofbirthyear=" + personalia["dateofbirthyear"];
			
			MCOW.Connection.getResponse(TIMEBOX.Config["timebox-url"] + "mobile/user-personalia-update" + query, function(data) { TIMEBOX.Lib.syncPersonaliaCallback(data); } );
		}
	},

	syncPersonaliaCallback : function(data) {
		if (data.error) {
			// be silent...
		}
		else {
			// succesfull update
			if (data.content["status"] == "1") {
				var personalia = TIMEBOX.Lib.getPersonalia();
				TIMEBOX.Lib.setPersonalia(personalia, 0);
			}
		}
	},

	// AVAILABILITY
	getAvailability : function(period) {
		var availability = new Array();
		if (localStorage.getItem("TIMEBOX." + period) === null) {
			// init availability
			availability = new window["TIMEBOX"]["Data"][period]();
			localStorage["TIMEBOX." + period] = JSON.stringify(availability);
		}
		else {
			availability = JSON.parse(localStorage["TIMEBOX." + period]);
		}
		return availability;
	},
	
	setAvailability : function(period, availability, sync) {
		availability["sync"] = sync;
		availability["timestamp"] = new Date().getTime();		
		localStorage["TIMEBOX." + period] = JSON.stringify(availability);
	},

	// MORE AVAILABILITY STUFF
	getAvailabilityMessage : function(platform) {
		var message = "";
		var availability1 = TIMEBOX.Lib.getAvailability("current");
		var availability2 = TIMEBOX.Lib.getAvailability("future");

		// default 
		// - status: unavailable - unavailable
		// - status: unavailable - unknown
		// - status: unknow - unknown
		message = "Ik ben niet beschikbaar voor werk. #nietbeschikbaar";
		
		// three options
		if (availability1['status'] == 'available' && availability2['status'] == 'available') {
			// Ik ben tot 5 juli nog 8 uur per week beschikbaar voor werk. Daarna 32 uur per week. #beschikbaar #8uur
			message = "Ik ben tot " + TIMEBOX.Lib.getDate(availability1['until']) + " nog " + availability1['hours'] + " uur per week beschikbaar voor werk. Daarna " + availability2['hours'] + " uur per week. #beschikbaar #" + availability1['hours'] + "uur"; 
		}
		if (availability1['status'] == 'available' && availability2['status'] != 'available') {
			// Ik ben tot 5 juli nog 24 uur per week beschikbaar voor werk. #beschikbaar #24uur 
			message = "Ik ben tot " + TIMEBOX.Lib.getDate(availability1['until']) + " nog " + availability1['hours'] + " uur per week beschikbaar voor werk. #beschikbaar #" + availability1['hours'] + "uur"; 
		}
		if (availability1['status'] == 'unavailable' && availability2['status'] == 'available') {
			// Ik ben na 26 augustus weer beschikbaar voor werk. 24 uur per week. #straksbeschikbaar #24uur 
			message = "Ik ben na " + TIMEBOX.Lib.getDate(availability1['until']) + " weer beschikbaar voor werk. " + availability2['hours'] + " uur per week. #straksbeschikbaar #" + availability2['hours'] + "uur"; 
		}

		if (platform == 'twitter') {
			// (via @timeboxnow)
			message = message + " (via @timeboxnow)";
		}
		if (platform == 'facebook') {
			message = message + " (via http://timebox.nu/)";
		}
		if (platform == 'default') {
			message = message + " (via http://timebox.nu/)";
		}
		
		return message;
	},

	setAvailabilityInit : function(period, selection) {
		var availability = TIMEBOX.Lib.getAvailability(period);
		if (selection == '1') {
			availability['status'] = 'available';
			availability['hours'] = '40';
			availability['days'] = '1,2,3,4,5';
			if (period == "current" && availability['until'] == '') {
				var d = new Date();
				d.setDate(d.getDate() + 30 /*days*/);
				var unixTime = Math.round(d.getTime()/1000);
				availability['until'] = unixTime;
				// show the default time
				$("#currentuntillset a").addClass("selected").children("strong").html(TIMEBOX.Lib.getDate(unixTime));
			}
		}
		else {
			availability['status'] = 'unavailable';
			availability['hours'] = '';
			availability['days'] = '';
			if (period == "current" && availability['until'] == '') {
				var d = new Date();
				d.setDate(d.getDate() + 30 /*days*/);
				var unixTime = Math.round(d.getTime()/1000);
				availability['until'] = unixTime;
				// show the default time
				$("#currentuntillset a").addClass("selected").children("strong").html(TIMEBOX.Lib.getDate(unixTime));
			}
		}
		TIMEBOX.Lib.setAvailability(period, availability, 1);
	},
	
	setAvailabilityHours : function(period, selection) {
		var availability = TIMEBOX.Lib.getAvailability(period);
		availability['hours'] = selection;
		TIMEBOX.Lib.setAvailability(period, availability, 1);
	},
	
	setAvailabilityDays : function(period, selection) {
		var availability = TIMEBOX.Lib.getAvailability(period);
		
		var dayString = "";
		for (i=0; i < selection.length; i++) {
			if (i>0) {
				dayString = dayString + ",";
			}
			dayString = dayString + selection[i];
		}
		
		availability['days'] = dayString;
		TIMEBOX.Lib.setAvailability(period, availability, 1);
	},

	setAvailabilityUntil : function(period, selection) {
		var availability = TIMEBOX.Lib.getAvailability(period);
		availability['until'] = selection;
		TIMEBOX.Lib.setAvailability(period, availability, 1);
	},

	compareAvailability: function(availability1, availability2) {
		if (availability1['status'] == availability2['status'] && availability1['hours'] == availability2['hours'] && availability1['days'] == availability2['days'] && availability1['until'] == availability2['until']) {
			return 1;
		}
		return 0;
	},
	
	// async fetch
	fetchAvailability: function() {
		var availability1 = TIMEBOX.Lib.getAvailability("current");
		var availability2 = TIMEBOX.Lib.getAvailability("future");
		if (availability1["sync"] == 0 && availability2["sync"] == 0) {
			var tokenString = TIMEBOX.Lib.getTokenString();
			var query = "?"+tokenString;
			MCOW.Connection.getResponse(TIMEBOX.Config["timebox-url"] + "mobile/user-availability-list" + query, function(data) { TIMEBOX.Lib.fetchAvailabilityCallback(data); } );
		}
	},

	fetchAvailabilityCallback: function(data) {
		if (data.error) {
			// be silent...
		}
		else {
			// previous data
			var sameCurrentAvailability = TIMEBOX.Lib.compareAvailability(TIMEBOX.Lib.getAvailability("current"), data.content["current"]);
			var sameFutureAvailability = TIMEBOX.Lib.compareAvailability(TIMEBOX.Lib.getAvailability("future"), data.content["future"]);

			// save
			TIMEBOX.Lib.setAvailability("current", data.content["current"], 0);
			TIMEBOX.Lib.setAvailability("future", data.content["future"], 0);
			
			// toast & refresh
			// - check for NEW data, dan ALTIJD melding, refresh ONLY when on home page
			if (sameCurrentAvailability == 0 || sameFutureAvailability == 0) {
				if (MCOW.Config["target"] == 'phonegap') {
					window.plugins.toast.show('De beschikbaarheid die je de vorige keer instelde blijkt niet langer actueel. We updaten het startscherm even voor je...', 'short', 'top');
				}
				else {
					alert("De beschikbaarheid die je de vorige keer instelde blijkt niet langer actueel. We updaten het startscherm even voor je...");
				}
				if (MCOW.Session.Response.className == "home") {
					setTimeout(function(){MCOW.Event.fire("/Timebox/home");}, 1500);
				}
				/*
				if (MCOW.Session.Response.className == "availability") {
					setTimeout(function(){MCOW.Event.fire("/Timebox/availability");}, 1000);
				}
				*/
			} 
		}
	},
	
	// async sync :-)
	// - that means, this sync of data can be called from everywhere and will sync the data in the background, not affecting normal operations
	syncAvailability : function() {
		var availability1 = TIMEBOX.Lib.getAvailability("current");
		var availability2 = TIMEBOX.Lib.getAvailability("future");
		// only sync if updated
		if (availability1["sync"] == 1 || availability2["sync"] == 1) {
			var tokenString = TIMEBOX.Lib.getTokenString();
			var query = "?"+tokenString;
			// current
			query = query + "&status=" + availability1["status"];
			query = query + "&hours=" + availability1["hours"];
			query = query + "&days=" + availability1["days"];
			query = query + "&until=" + availability1["until"];
			// future
			query = query + "&future-status=" + availability2["status"];
			query = query + "&future-hours=" + availability2["hours"];
			query = query + "&future-days=" + availability2["days"];
			
			MCOW.Connection.getResponse(TIMEBOX.Config["timebox-url"] + "mobile/user-availability-update" + query, function(data) { TIMEBOX.Lib.syncAvailabilityCallback(data); } );
		}
	},

	syncAvailabilityCallback : function(data) {
		if (data.error) {
			// be silent...
		}
		else {
			// succesfull update
			if (data.content["status"] == "1") {
				var availability1 = TIMEBOX.Lib.getAvailability("current");
				TIMEBOX.Lib.setAvailability("current", availability1, 0);		
				var availability2 = TIMEBOX.Lib.getAvailability("future");
				TIMEBOX.Lib.setAvailability("future", availability2, 0);		
			}
		}
	},

	// SHARES
	getShares : function() {
		var shares = new Array();
		if (localStorage.getItem("TIMEBOX.SHARES") === null) {
			// init shares
			shares = new TIMEBOX.Data.shares();
			localStorage["TIMEBOX.SHARES"] = JSON.stringify(shares);
		}
		else {
			shares = JSON.parse(localStorage["TIMEBOX.SHARES"]);
		}
		return shares;
	},

	setShares : function(shares, sync) {
		shares["sync"] = sync;
		shares["timestamp"] = new Date().getTime();		
		localStorage["TIMEBOX.SHARES"] = JSON.stringify(shares);
	},

	// MORE SHARES STUFF
	setShareById : function(id, selection) {
		var shares = TIMEBOX.Lib.getShares();
		for (var i=0;i<shares["networks"].length;i++) {
			if (shares["networks"][i]["id"] == id) {
				shares["networks"][i]["share"] = selection;
				// track sync status for individual network
				shares["networks"][i]["sync"] = 1;
				break;
			}
		}
		// sync per network, keep this global sync = 0
		TIMEBOX.Lib.setShares(shares, 0);
	},

	countShares : function(networks) {
		var shares = 0;
		for (var i=0;i<networks.length;i++) {
			if (networks[i]["share"] == 1) {
				shares++;
			}
		}
		return shares;
	},

	compareShares: function(shares1, shares2) {
		if (shares1["networks"].length != shares2["networks"].length) {
			return 0;
		}

		// no sorting - breaks android
		// - always assume the json order from server is correct!
		//shares1["networks"].sort();
		//shares2["networks"].sort();
		for (var i = 0; i < shares1["networks"].length; i++) {
			if (shares1["networks"][i]["id"] != shares2["networks"][i]["id"] || shares1["networks"][i]["share"] != shares2["networks"][i]["share"] || shares1["networks"][i]["name"] != shares2["networks"][i]["name"]) {
				return 0;
			}
		}
		
		return 1;
	},

	// async fetch
	fetchShares: function() {
		var shares = TIMEBOX.Lib.getShares();
		if (shares["sync"] == 0) {
			var tokenString = TIMEBOX.Lib.getTokenString();
			var query = "?"+tokenString;
			MCOW.Connection.getResponse(TIMEBOX.Config["timebox-url"] + "mobile/user-availability-list-network" + query, function(data) { TIMEBOX.Lib.fetchSharesCallback(data); } );
		}
	},

	fetchSharesCallback: function(data) {
		if (data.error) {
			// be silent...
		}
		else {
			// check previous data
			var sameShares = TIMEBOX.Lib.compareShares(TIMEBOX.Lib.getShares(), data.content);

			// save
			TIMEBOX.Lib.setShares(data.content, 0);

			// toast & refresh
			// - check for NEW data, dan ALTIJD melding, refresh ONLY when on share page
			if (sameShares == 0) {
				if (MCOW.Config["target"] == 'phonegap') {
					window.plugins.toast.show('De lijst met bedrijven is aangepast.', 'short', 'top');
				}
				else {
					alert("De lijst met bedrijven is aangepast.");
				}
				/*
				if (MCOW.Session.Response.className == "home") {
					setTimeout(function(){MCOW.Event.fire("/Timebox/home");}, 1000);
				}
				*/
				if (MCOW.Session.Response.className == "share") {
					setTimeout(function(){MCOW.Event.fire("/Timebox/share");}, 1000);
				}
			}
		}
	},

	// async sync :-)
	// - that means, this sync of data can be called from everywhere and will sync the data in the background, not affecting normal operations
	syncShares : function() {
		var shares = TIMEBOX.Lib.getShares();
		// sync per network, global sync = 0
		//if (shares["sync"] == 1) {
		for (var i=0;i<shares["networks"].length;i++) {
			// only sync if updated
			if (shares["networks"][i]["sync"] == 1) {
				var shareId = shares["networks"][i]["id"];
				var tokenString = TIMEBOX.Lib.getTokenString();
				var query = "?"+tokenString;
				query = query + "&id=" + shares["networks"][i]["id"];
				query = query + "&value=" + shares["networks"][i]["share"];
				MCOW.Connection.getResponse(TIMEBOX.Config["timebox-url"] + "mobile/user-availability-update-network" + query, function(data) { TIMEBOX.Lib.syncSharesCallback(data); } );
			}
		}
		//}
	},

	syncSharesCallback : function(data) {
		if (data.error) {
			// be silent...
		}
		else {
			// succesfull update
			if (data.content["status"] == "1") {
				var shareId = data.content["network"];
				var shares = TIMEBOX.Lib.getShares();
				for (var i=0;i<shares["networks"].length;i++) {
					if (shares["networks"][i]["id"] == shareId) {
						// reset sync status for individual network
						shares["networks"][i]["sync"] = 0;
						break;
					}
				}
				// sync per network, keep this global sync = 0
				TIMEBOX.Lib.setShares(shares, 0);
			}
		}
	},
	
	// MISC
	getTokenString : function() {
		var credentials = JSON.parse(localStorage["TIMEBOX.CREDENTIALS"]);
		var id = credentials["email"];
		var secret = credentials["secret"];
		var token = credentials["token"];
		for (var nonce = ''; nonce.length < 16;) { nonce += Math.random().toString(36).substr(2, 1); }
		var key = MCOW.Util.sha1(nonce + id + secret);
		var query = "token="+token+"&token_id="+id+"&token_nonce="+nonce+"&token_key="+key;
		return query;
	},

	getDaysString : function(days) {
		var dayArray = days.split(",");
		var dayString = "";
		for (i=0; i < dayArray.length; i++) {
			if (i>0) {
				dayString = dayString + ", ";
			}
			var newDay = "";
			if (dayArray[i] == 1) { newDay = "Ma"; }
			if (dayArray[i] == 2) { newDay = "Di"; }
			if (dayArray[i] == 3) { newDay = "Wo"; }
			if (dayArray[i] == 4) { newDay = "Do"; }
			if (dayArray[i] == 5) { newDay = "Vr"; }
			if (dayArray[i] == 6) { newDay = "Za"; }
			if (dayArray[i] == 7) { newDay = "Zo"; }
			dayString = dayString + newDay;
		}
		return dayString;
	},
	
	getDate : function(timestamp) {
		var date = new Date(timestamp*1000);
		var months = ['Januari','Februari','Maart','April','Mei','Juni','Juli','Augustus','September','Oktober','November','December'];
		var year = date.getFullYear();
		var month = months[date.getMonth()];
		var day = date.getDate();
		var hour = date.getHours();
		var min = date.getMinutes();
		var sec = date.getSeconds();
		
		return day + " " + month;
	},
	
	// FRONTEND
	checkCurAvailChange : function(value){
		if (value == "1") {
			// Create label click event
			$("#currentavailset input[id=curmaxhours-40]").prop('checked', true).checkboxradio('refresh');

			// Click events
			$("#currentavailset input[id=curpreferreddays-mo]").prop('checked', true).checkboxradio('refresh');
			$("#currentavailset input[id=curpreferreddays-tu]").prop('checked', true).checkboxradio('refresh');
			$("#currentavailset input[id=curpreferreddays-we]").prop('checked', true).checkboxradio('refresh');
			$("#currentavailset input[id=curpreferreddays-th]").prop('checked', true).checkboxradio('refresh');
			$("#currentavailset input[id=curpreferreddays-fr]").prop('checked', true).checkboxradio('refresh');

			// Unhide the currentavailset
			$("#currentavailset").removeClass("hidden");
		}
		else {
			// Reset currentavailset
			$("#currentavailset").addClass("hidden");

			// Remove all checked attributed radio items
			$("#currentavailset input[name=curmaxhours]:checked").each(function(){
				// Set element id
				var elid = $(this).attr("id");
				// Execute click event.
				$("#currentavailset input[id=" + elid + "]").prop('checked', false).checkboxradio('refresh');
			});

			// Remove all checked attributed checkbox items
			$("#currentavailset input[type=checkbox]:checked").each(function(){
				// Set element id
				var elid = $(this).attr("id");
				// Execute click event.
				$("#currentavailset input[id=" + elid + "]").prop('checked', false).checkboxradio('refresh');
			});
		}
		// might be necessary for first time...
		// Unhide currentuntillset container
		$("#currentuntillset").removeClass("hidden");
		// Remove the hidden class on the afterthatset
		$("#afterthatset").removeClass("hidden");
	},
	checkCurAvailSet : function(){
		// Check if the curmaxhours has been selected
		if ($("#currentavailset input[name=curmaxhours]:checked").length == 0) return false;
		// Check if the preferred days have been selected
		if ($("#currentavailset input[type=checkbox]:checked").length == 0) return false;
	},
	checkAfterAvailChange : function(value){
		if (value == "1") {	
			// Create label click event
			$("#afteravailset input[id=aftermaxhours-40]").prop('checked', true).checkboxradio('refresh');
			
			// Click events
			$("#afteravailset input[id=afterpreferreddays-mo]").prop('checked', true).checkboxradio('refresh');
			$("#afteravailset input[id=afterpreferreddays-tu]").prop('checked', true).checkboxradio('refresh');
			$("#afteravailset input[id=afterpreferreddays-we]").prop('checked', true).checkboxradio('refresh');
			$("#afteravailset input[id=afterpreferreddays-th]").prop('checked', true).checkboxradio('refresh');
			$("#afteravailset input[id=afterpreferreddays-fr]").prop('checked', true).checkboxradio('refresh');
		
			// Unhide afteravailset
			$("#afteravailset").removeClass("hidden");
		} 
		else {
			// Reset afteravailset
			$("#afteravailset").addClass("hidden");

			// Remove all checked attributed radio items
			$("#afteravailset input[name=aftermaxhours]:checked").each(function(){
				// Set element id
				var elid = $(this).attr("id");
				// Execute click event.
				$("#afteravailset input[id=" + elid + "]").prop('checked', false).checkboxradio('refresh');
			});

			// Remove all checked attributed checkbox items
			$("#afteravailset input[type=checkbox]:checked").each(function(){
				// Set element id
				var elid = $(this).attr("id");
				// Execute click event.
				$("#afteravailset input[id=" + elid + "]").prop('checked', false).checkboxradio('refresh');
			});
		}
	},
	checkAfterAvailSet : function(){
		// Check if the curmaxhours has been selected
		if ($("#afteravailset input[name=aftermaxhours]:checked").length == 0) return false;
		// Check if the preferred days have been selected
		if ($("#afteravailset input[type=checkbox]:checked").length == 0) return false;
	},
	hideSocialShare : function(){
		// Animate the background
		$("#socialshare > div:first-child").animate({"opacity":0});
		// Animate the content
		$("#socialshare > div:last-child").animate({"bottom":-400},400,function(){
			// Add the hidden class again
			$("#socialshare").addClass("hidden");
		});
	},
	hidePhotoSelection : function(){
		// Animate the background
		$("#photoselection > div:first-child").animate({"opacity":0});
		// Animate the content
		$("#photoselection > div:last-child").animate({"bottom":-400},400,function(){
			// Add the hidden class again
			$("#photoselection").addClass("hidden");
		});
	},
	
	// PHONEGAP
	// image.src = "data:image/jpeg;base64," + imageData;
	phonegapCameraSucces : function(picture) {
		if (MCOW.Config["target"] == 'phonegap') {
			// set new image
			var img = new Image();
			var w = 640;
			var h = 640;
			var x = 0;
			var y = 0;
			
			img.onload = function() {
				var canvas = document.createElement('canvas');
				var ctx = canvas.getContext('2d');

				// 640x640 is the bounding box (on android)
				// - make h & w a square
				// - crop in the middle of the original w or h
				var i_w = img.width;
				var i_h = img.height;
				if ( i_w < i_h ) {	y = Math.round((i_h - i_w) / 2); i_h = i_w; }
				if ( i_w > i_h ) {	x = Math.round((i_w - i_h) / 2); i_w = i_h; }
				// set the required size to the image size (we do not scale...)
				w = i_w; 
				h = i_h; 
				
				// We set the dimensions at the wanted size.
				canvas.width = w;
				canvas.height = h;

				// We resize the image with the canvas method drawImage();
				ctx.drawImage(img, x, y, w, h, 0, 0, w, h);

				// get plain data from canvas
				var dataUrl = canvas.toDataURL("image/jpg");
				var dataPlain = dataUrl.replace(/^data:image\/(png|jpg|jpeg);base64,/,"");

				// store resized image
				var personalia = TIMEBOX.Lib.getPersonalia();
				personalia["photoStream"] = dataPlain;
				// no need to sync, because the image stays local
				TIMEBOX.Lib.setPersonalia(personalia, 0);
				
				document.getElementById('timebox-data-avatar').setAttribute( 'src', 'data:image/jpeg;base64, ' + dataPlain);
			};
			
			img.src = "data:image/jpeg;base64," + picture;		
		}
	},

	phonegapCameraSuccesBackground : function(picture) {
		if (MCOW.Config["target"] == 'phonegap') {
			// set new image
			var skin = TIMEBOX.Lib.getSkin();
			skin["background"] = picture;
			TIMEBOX.Lib.setSkin(skin);
			
			document.getElementById('timebox-data-background').setAttribute( 'src', 'data:image/jpeg;base64, ' + picture);
		}
	},
	
	phonegapCameraFailure : function(message) {
		if (MCOW.Config["target"] == 'phonegap') {
			// silence is a virtue
			/*
			navigator.notification.alert(
				message,									// message
				null, 								        // callback
				'No Picture Taken',   				        // title
				'Ok'             						    // buttonName
			);
			*/
		}
	}
}

// data sources & objects
// - the credentials is the list of user credentials
// - NOTE: methods like this.save are not defined here, but in 'lib'
// -       because the JSON.parse cannot parse into this specific object, only in a general javascript object
TIMEBOX.Data = {

	skin : function(background) {
		this.background = "";
	},

	credentials : function(name, email, secret, token) {
		this.name = "timebox";
		this.email = "";
		this.secret = "";
		this.token = "";
	},
	
	personalia : function(firstname, lastname, gender, dateofbirthday, dateofbirthmonth, dateofbirthyear, photoStream) {
		this.firstname = "";
		this.lastname = "";
		this.gender = "";
		this.dateofbirthday = "";
		this.dateofbirthmonth = "";
		this.dateofbirthyear = "";
		this.photoStream = "";
		this.sync = -1;
		this.timestamp = 0;
	},

	current : function(status, hours, days, until) {
		this.status = "unknown";
		this.hours = "";
		this.days = "";
		this.until = "";
		this.sync = -1;
		this.timestamp = 0;
	},

	future : function(status, hours, days) {
		this.status = "unknown";
		this.hours = "";
		this.days = "";
		this.sync = -1;
		this.timestamp = 0;
	},
	
	shares : function(networks) {
		this.networks = new Array();
		this.sync = -1;
		this.timestamp = 0;
	}

}
