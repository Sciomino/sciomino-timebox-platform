//Load different kinds of data
// - js files
// - css files
// - language files
// - model files (preload)
// - plugin files
// - external data

MCOW.Lib = {
	
	// track loading of javascript files
	loading: 0,
	loaded: 0,
	
	load: function() {
		MCOW.Lib.Css.load();
		MCOW.Lib.Language.load();
		MCOW.Lib.Js.load();
		if (MCOW.Config["preload"]) {
			MCOW.Lib.Model.load();
			MCOW.Lib.View.load();
		}
		if (MCOW.Config["plugins"].length > 0) {
			MCOW.Lib.Css.loadPlugins();
			MCOW.Lib.Js.loadPlugins();
			// the plugin model is always preloaded, the views are not
			MCOW.Lib.Model.loadPlugins();
			if (MCOW.Config["preload"]) {
				MCOW.Lib.View.loadPlugins();
			}
		}
		
		MCOW.Lib.waitForLoad();
	},
	
	// duh... settimeout is async, so should use a callback here to make sure everything is loaded
	waitForLoad: function() {
		if (MCOW.Config["debug_lib"] == '1') {console.log("Lib, WaitForLoad: loading = " + MCOW.Lib.loading + ", loaded = " + MCOW.Lib.loaded);}
		if (MCOW.Lib.loading != MCOW.Lib.loaded) {
			setTimeout(MCOW.Lib.waitForLoad, 100);
		}
	}

},

MCOW.Lib.Js = {

	// Js.loadFile("FILENAME")
	load: function() {
		if (MCOW.Config["enable_lib_js_polyfill"] == 1) { MCOW.Lib.Js.loadFileWithCallback("js/lib/base/polyfills.js", function() {MCOW.Lib.Js.loadCallback(); }); }
		if (MCOW.Config["enable_lib_js_menu"] == 1) { MCOW.Lib.Js.loadFileWithCallback("js/lib/base/menu.js", function() {MCOW.Lib.Js.loadCallback(); }); }
		MCOW.Lib.Js.loadFileWithCallback("js/lib/base/connection.js", function() {MCOW.Lib.Js.loadCallback(); });
		MCOW.Lib.Js.loadFileWithCallback("js/lib/base/utils.js", function() {MCOW.Lib.Js.loadCallback(); });
		MCOW.Lib.Js.loadFileWithCallback("js/control/control.js", function() {MCOW.Lib.Js.loadCallback(); });
	},

	// load javascript 
	loadFile: function ( file ) {
		var script = document.createElement('script');
		script.type = 'text/javascript';
		script.src = file;
		// add to the header
		document.getElementsByTagName("head")[0].appendChild( script );
	},

	loadPlugins: function() {
		for (var i = 0; i < MCOW.Config["plugins"].length; ++i) {
			MCOW.Lib.Js.loadFileWithCallback("js/lib/" + MCOW.Config["plugins"][i] + "/" + MCOW.Config["plugins-lib-file"], function() {MCOW.Lib.Js.loadCallback(); });
		}
	},
	
	// use load with callback to track the loading
	loadFileWithCallback: function ( file, callback ) {
		MCOW.Lib.loading++;
		var script = document.createElement('script');
		script.type = 'text/javascript';
		script.src = file;

		// callback
		script.onreadystatechange = callback;
        script.onload = callback;

		// add to the header
		document.getElementsByTagName("head")[0].appendChild( script );
	},
	
	loadCallback: function() {
		MCOW.Lib.loaded++;
	},
	
},

MCOW.Lib.Css = {

	// Css.loadFile("FILENAME")
	load: function() {
		MCOW.Lib.Css.loadFile("css/"+MCOW.Config.layout+"/page.css");
		MCOW.Lib.Css.loadFile("css/"+MCOW.Config.layout+"/design.css");
		if (MCOW.Config["enable_lib_css_layout"] == 1) { MCOW.Lib.Css.loadFile("css/"+MCOW.Config.layout+"/layout.css"); }
	},

	// load stylesheet
	loadFile: function ( file ) {
		var style = document.createElement('link');
		style.rel = 'stylesheet';
		style.type = 'text/css';
		style.href = file;
		// add to the header
		document.getElementsByTagName("head")[0].appendChild( style );
	},

	loadPlugins: function() {
		for (var i = 0; i < MCOW.Config["plugins"].length; ++i) {
			MCOW.Lib.Css.loadFile("css/" + MCOW.Config["plugins"][i] + "/" + MCOW.Config["plugins-css-file"]);
		}
	}
	
},

MCOW.Lib.Language = {
	
	// - read language from localstorage
	// - otherwise set default language in Local store
	// - TODO: language should be set on load by operating systems language (no language options in app!)
	load: function() {	
		var language = "";
		if (typeof localStorage["Language"] != 'undefined') {
			language = localStorage["Language"];
			if (MCOW.Config.valid_languages.indexOf(language) == -1) {
				language = MCOW.Config.default_language;
			}
		}
		else {
			language = MCOW.Config.default_language;
		}
		// read language file
		MCOW.Lib.Js.loadFileWithCallback(MCOW.Config["language_base"] + "/" + language + "/language.js", function() {MCOW.Lib.Js.loadCallback(); });
		// (re)set language
		localStorage["Language"] = language;
	}
}

MCOW.Lib.Model = {
	load: function() {
		// hum, problem... MCOW.Control is not loaded yet... Could use callback, but why not use concat file...
		/*
		for (var key in MCOW.Control) {
			if (MCOW.Control.hasOwnProperty(key)) {
				MCOW.Lib.Js.loadFile(MCOW.Config["model_base"] + "/" + MCOW.Control[key]["model"] + ".js");
			}
		}
		*/
		//now... try to load concatenated file
		MCOW.Lib.Js.loadFileWithCallback(MCOW.Config["model_base"] + "/" + MCOW.Config["preload-model-file"], function() {MCOW.Lib.Js.loadCallback(); });
	},

	loadPlugins: function() {
		for (var i = 0; i < MCOW.Config["plugins"].length; ++i) {
			MCOW.Lib.Js.loadFileWithCallback(MCOW.Config["model_base"] + "/" + MCOW.Config["plugins"][i] + "/" + MCOW.Config["plugins-model-file"], function() {MCOW.Lib.Js.loadCallback(); });
		}
	}
	
},

MCOW.Lib.View = {
	load: function() {
		MCOW.Lib.Js.loadFileWithCallback(MCOW.Config["view_base"] + "/" + MCOW.Config["preload-view-file"], function() {MCOW.Lib.Js.loadCallback(); });
	},

	loadPlugins: function() {
		for (var i = 0; i < MCOW.Config["plugins"].length; ++i) {
			MCOW.Lib.Js.loadFileWithCallback(MCOW.Config["view_base"] + "/" + MCOW.Config["plugins"][i] + "/" + MCOW.Config["plugins-view-file"], function() {MCOW.Lib.Js.loadCallback(); });
		}
	}
	
},

// general ajax loader
// - but something is missing...
// - better use data2
MCOW.Lib.Data = {
	
	httpRequest : "",
	
	loadFileWithCallback: function ( file, callback ) {
		if (window.XMLHttpRequest) { 
			httpRequest = new XMLHttpRequest();
		} 
		else if (window.ActiveXObject) { 
		    try {
				httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
			} 
			catch (e) {
				try {
					httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
				} 
				catch (e) {}
			}
		}

		if (!httpRequest) {
			if (MCOW.Config["target"] == 'phonegap') {
				navigator.notification.alert(
					'There is currently no network available',  // message
					null, 								        // callback
					'No Network',   					        // title
					'Ok'             						    // buttonName
				);
			}
			else {
				alert("No Network.");
			}
		    return false;
		}
		httpRequest.onreadystatechange = callback(httpRequest);
		httpRequest.open('GET', file, true);
		httpRequest.send('');
	},
	
	// callback example
	callback: function (httpRequest) {
		if (httpRequest.readyState === 4) {
			if (httpRequest.status === 200) {
				alert(httpRequest.responseText);
			} 
			else {
				if (MCOW.Config["target"] == 'phonegap') {
					navigator.notification.alert(
						'There is currently no network available',  // message
						null, 								        // callback
						'No Network',   					        // title
						'Ok'             						    // buttonName
					);
				}
				else {
					alert("No Network.");
				}
			}
		}
	}
	  
},

MCOW.Lib.Data2 = {

	// quirksmode:http://www.quirksmode.org/js/xmlhttp.html
	loadFileWithCallback: function (url,callback,postData) {
		var req = MCOW.Lib.Data2.createXMLHTTPObject();
		if (!req) return;
		var method = (postData) ? "POST" : "GET";
		req.open(method,url,true);
		//req.setRequestHeader('User-Agent','XMLHTTP/1.0');
		if (postData)
			req.setRequestHeader('Content-type','application/x-www-form-urlencoded');
		req.onreadystatechange = function () {
			if (req.readyState != 4) return;
			if (req.status != 200 && req.status != 304) {
	//			alert('HTTP error ' + req.status);
				return;
			}
			callback(req);
		}
		if (req.readyState == 4) return;
		req.send(postData);
	},

	createXMLHTTPObject: function() {

		var XMLHttpFactories = [
			function () {return new XMLHttpRequest()},
			function () {return new ActiveXObject("Msxml2.XMLHTTP")},
			function () {return new ActiveXObject("Msxml3.XMLHTTP")},
			function () {return new ActiveXObject("Microsoft.XMLHTTP")}
		];

		var xmlhttp = false;
		for (var i=0;i<XMLHttpFactories.length;i++) {
			try {
				xmlhttp = XMLHttpFactories[i]();
			}
			catch (e) {
				continue;
			}
			break;
		}
		return xmlhttp;
	},

}

