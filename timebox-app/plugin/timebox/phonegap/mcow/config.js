// Namespace
var MCOW = MCOW || {};

MCOW.Config = {};
MCOW.Event = {};
MCOW.Lib = {};
MCOW.Util = {};
MCOW.Connection = {};
MCOW.Session = {};
MCOW.Language = {};
MCOW.Model = {};
MCOW.View = {};
MCOW.Control = {};

MCOW.Model.Timebox = {};
MCOW.View.Timebox = {};

// Main configuration
MCOW.Config = {

// homepage of the app
	'homepage': '/Timebox/home',

// target is 'phonegap' or 'webapp'
	'target': 'phonegap',
	
// initial layout
// webapp: responsive layout for smartphone, tablet and laptop/desktop
// app: app for smartphone (portrait mode)
// game: app for tablet (landscape mode)
	'layout': 'app',

// debug options
	'debug_event': '0',
	'debug_event_cron': '0',
	'debug_credentials': '0',
	'debug_lib': '0',
	'debug_connection': '0',
	
// events
// - dependency: if enable_click_events=1 => cancel_default_events=0
// - dependency: if enable_page_scroll=1 =>cancel_default_events=1
	'enable_page_scroll' : '0',
	'enable_page_scroll_horizontal' : '0',
	'enable_page_scroll_vertical' : '1',
	'enable_page_scroll_element' : 'content',
	'enable_page_events': '0',
	'cancel_default_events': '0',	
	'enable_cron_events': '1',
	'enable_click_events': '0',

// transition
// fast:200, normal: 400, slow: 600
	'trans_timer_setup': '50',
	'trans_timer': '200',
	
// libs
	'enable_lib_js_polyfill': '1',
	'enable_lib_js_menu': '0',
	'enable_lib_css_layout': '0',

// connection
// - use cache? Better not, does not compute...
	'connection_cache':'0',
	
// language
	'default_language' : 'nl',
	'valid_languages' : ['nl','en'],

// preload
	'preload' : 1,
	'preload-model-file' : 'base/model.concat.js',
	'preload-view-file' : 'base/view.concat.js',

// plugins
	'plugins' : ['timebox'],
	'plugins-model-file' : 'model.concat.js',
	'plugins-view-file' : 'view.concat.js',
	'plugins-lib-file' : 'lib.concat.js',
	'plugins-css-file' : 'css.concat.css',
	
// base directories
	'model_base' : 'js/model',
	'view_base' : 'js/view',
	'language_base' : 'js/language',
	
// last (to get rid of the stupid last ',')
	'last' : 'last'	

};
