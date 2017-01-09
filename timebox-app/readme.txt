framework: mcow
title: Mobile COntent for the Web
description: a javascript framework for building webapps and hybrid apps with phonegap
version: 0.1
date: 29 mei 2015
author: Herman van Dompseler (herman@dompseler.nl)


Reality check: prepare to dive in deep, it takes some effort building a hybrid app, but it might be worth your while.
Note: this is the first version, it is 'as is', use at your own risk :-)


ABOUT
-----

This is an attempt to build a javascript framework suitable for
1. webapps (responsive designed websites)
2. apps (hybrid smartphone apps with phonegap)
3. games (hybrid tablet apps with phonegap)

The framework is about:
- architecture, events & touch gestures
- the directory structure and configuration
- offline availability
- deployment & requirements
- use of plugins
- design of pages

The framework especially turned out to be a good companion in the deployment proces of an app.

This framework is build for and currently in use by 
- 1 app: TimeBox
- 1 game: CURP
- 0 webapps


Architecture
------------

Keep in mind: there is only one index.html page (it's an APP :-)

The system is event driven, each event is handled by the event handler. By far, the most important file of this platform is 'htdocs/js/control/event.js'.

First, this file is responsible for the loading of the APP (and pages). Five stages of loading are distinguished
- loadfase 1: the bare loading of the app, results in 'deviceready'
- loadfase 2: binding of device events, pause, resume etc.
- loadfase 3: one-time initialization of mcow, including cron
- loadfase 4: loading of a page BEFORE transition/display
- loadfase 5: loading of a page AFTER transition/display

Fase 3, 4 and 5 have hooks to add code
- fase 3: PLUGIN.event.load()
- fase 3: PLUGIN.event.cron(MESSAGE) & PLUGIN.event.cronFallback()
- fase 4: PLUGIN.event.loadTransition(STAGE)
- fase 5: PLUGIN.event.loadPage()

Second, the event handler responses to multiple sources of an event
- device events (pause, resume, online, offline)
- user/touch events (click, taps, swipes etc.)
- cron events ('htdocs/js/control/cron.js' fires events at regular intervals)

Third, it handles each event as a 'request' (just like an http request)
- every event therefore results in a new url
- the url is passed to the event controller
- the controller is initialized by 'htdocs/js/control/control.js'
- the controller constructs the session (and keeps track of previous pages)
- the controller fires a model: MCOW.model.CLASS.run()
- and the controller displays a view: MCOW.View["preload"][CLASS]

About the model:
- the js model has access to the local storage (as php has access to mysql)
- the model has connections to an underlying API and expects JSON to be returned (no XML)
- the API can be anything, that's the beauty

About the view:
- the view updates only portions of the index page, mostly the 'page'
--- there is NEVER a reload of the whole page
- various transitions bring views to the screen
- multiple languages are supported


Directories & Configuration
---------------------------

The tree: 
	docs 
	htdocs (framework code)
		css
			app (default for portrait smartphone)
			game (default for landscape tablet)
			webapp (default styling for responsive website)
		fonts
		gfx
		js
			control (controller & event handling)
			etc (configuration)
			language
			lib
				base (js libraries)
			model
				base (example model code)
			view
				base (example view pages)
	phonegap (hybrid app extensions)
		icons
		mcow
		splash
	plugin (placeholder for application code)


1. HTDOCS is the framework code

The main config file for mcow is 'htdocs/js/etc/config.js'.
The one and only html file is 'htdocs/index.html'.

For each 'page' there should be:
- an entry in 'htdocs/js/control/control.js'
- a model in 'htdocs/js/model/base/PAGE.js'
- a view in 'htdocs/js/view/base/PAGE.js'

Reusable portions of code go in the library: 'htdocs/js/lib/base/*.js'


2. The PHONEGAP directory includes placeholders for hybrid app data


3. The PLUGIN directory is where your own code resides in its own directory

This directory has a similar layout, with:
- css
- docs
- fonts
- gfx
- js
--- control
--- etc
--- language
--- lib
--- model
--- view
- phonegap
--- icons
--- mcow
--- splash

Where to go from here?
1. you could install mcow and play with the main code and replace the sample pages with your own pages
2. OR you could install your stuff in your own plugin directory and take it from there


offline availability
--------------------

This one is tackled differently as suspected before starting this project. The first idea was to build a deploy process around the cache manifest file, where this file would be automatically generated during deployment of the product. With cache manifest the browser would take care of offline availability. It didn't work out like that, at all. The framework ended up with this availability setup:
1. All APP code is available from the beginnning
- each view is a seperate file and could be fetched when appropriate (no offline availability now)
- in the install process concat ALL the views in one .js file and preload this file. Now all views are alway present
- these means the whole APP code is loaded at startup and every function is available from the beginning.
- it became very convenient that no webserver is needed to serve the APP code, loading with file://.../index.html is equally good
- So, keep your APP smart and simple to make use of this!
2. Use an API for synchronisation of your APP data
- APP data is fetched from an API, therefore the API should be available. And if it's not, do not worry, because:
- a. if the API is available, you use it to sync your data
- b. if the API is not available, keep your data local, keep on working and when it becomes available again, restart syncing of the data.
- in fact the API is a remote synchronisation of your local APP data.
3. A connection cache keeps track of data fetched in every connection
- with this cache, you can also use third party data, which can be cached, for offline availability
 
 
deployment & requirements
-------------------------

One of the most important things to consider when building apps is the development and deployment cycle. This was hard to tackle. 

So what's required for development on linux with android emulation:
- a place for your webappcode (for example /var/www/YOURSITE)
- mcow installed in YOURSITE
- a place for your appcode (for example /var/www/YOURAPP)
- cordova installed in YOURAPP
- plugins enabled on cordova
- the android platform enabled for cordova
- the android sdk installed with some emulators (AVD) created
- access to github & phonegapbuild

Now try this:
1. development of webapp (in YOURSITE)
- developing in your favorite editor, a must :-)
2. testing (1) of webapp on browser
- testing development with firefox so you can fully make use of firebug and other web development tools by just pointing to file://var/www/YOURSITE/htdocs/index.html
- double testing with chrome
3. testing (2) of app on android emulator
- the webapp above is copied (with install.sh) to a new app location where cordova was first installed (in YOURAPP)
- with the 'monitor' tool from the android sdk an android virtual device can be created, launched and debugged
- running 'cordova emulate android' builds and installs the app for the emulator
4. using 'git' to store code
- the code from the cordova /www directory is saved using git
5. use phonegap build to create the app files
- on phonegap build an app can be created from the /www source in git
- this results in .ipa for iOS and .apk for Android
6. testing (3) on a physical device
- for iOS browse with iPhone to phonegap build en click on .ipa (ipa needs a key for your device to function correctly)
- for Android browse with smartphone to phonegap build and click on .apk


plugins
-------

the framework uses phonegap plugins to enrich the app experience, some are required by the framework itself others are tested in applications build around mcow

1. required cordova plugins:
- dialog: org.apache.cordova.dialogs
- splash: org.apache.cordova.splashscreen 
- browser: org.apache.cordova.inappbrowser
- network: org.apache.cordova.network-information
- statusbar: org.apache.cordova.statusbar
- device: (NOT YET USED)
- globalization: (NOT YET USED)

2. required third party plugins
- custom url: nl.x-services.plugins.launchmyapp

3. optional & tested cordova plugins:   
- camera: cordova plugin add org.apache.cordova.camera

4. optional & tested third party plugins:   
- toast: nl.x-services.plugins.toast
- social sharing: nl.x-services.plugins.socialsharing

and there are a lot more plugins out there...


design
------

To scroll apps with a native feeling and enable transitions, it was necessary to redesign the page, the main components of a page are:
- the top bar, could be used as permanent navigation bar
- the pagescroller, containing the page, transition & store, this is where the content is
- the overlay, could be used as a 'popup' window

in html code:
	<div id="topbar">
	</div>
	<div id="pagescroller">
		<div id="page">
		</div>
		<div id="transition">
		</div>
		<div id="store">
		</div>
	</div>
	<div id="overlay">
	</div>

some notes on this design:
- topbar and overlay are optional
- the pagescroller is only for scrolling of the content. This is hardware accelerated and gives native scrolling on iOS
- the 'page' is the active content. This is the content loaded in loadfase4 & loadfase5
- about transition in: when a new page is transitioned in, the previous page is put in 'store'. Store is displayed on the background, page transforms on top of that until it reached it final location.
- about transition out: when a page is transitioned out, the current page is copied into 'transition', which is shown on top of page. Now the store is pulled in page, which is in place directly. transition transforms on top of the page, until it reveals the page completely

Three css files are involved in this design:
- css/*/page.css (where the page is setup)
- css/*/design.css (where the basic design is done)
- css/*/layout.css (where layout for different screensizes is located)

these three files are specified voor app, game and webapp. Where
- app is portrait smartphone design
- game is landscape tablet design
- webapp is responsive deisgn for all kinds of devices

note: these designs are still under construction :-)


have fun and enjoy :-),
Herman.
