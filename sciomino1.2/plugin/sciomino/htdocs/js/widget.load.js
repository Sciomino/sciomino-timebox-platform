// This is the Sciomino Widget Loader (SC_WL)
// - it loads a widget based on a unique identifier (WID)
// - content expected is JSON: {"html":"html string"}

// Namespace
var SC_WL = SC_WL || {};

// The url that serves widgets
SC_WL.url = "http://sciomino1.2/widgets/view";

// utility scripts
// - get parameters from querystring
SC_WL.deparam = function (querystring) {
  // remove any preceding url and split
  querystring = querystring.substring(querystring.indexOf('?')+1).split('&');
  var params = {}, pair, d = decodeURIComponent;
  // march and parse
  for (var i = querystring.length - 1; i >= 0; i--) {
    pair = querystring[i].split('=');
    params[d(pair[0])] = d(pair[1]);
  }
  return params;
};

// - set stylesheet
SC_WL.setCss = function ( css ) {
    var style = document.createElement('style');
    style.type = 'text/css';
    if (style.styleSheet) {
        // IE
        style.styleSheet.cssText = css;
    } else {
        // Other browsers
        style.innerHTML = css;
    }
	// add to the header
    document.getElementsByTagName("head")[0].appendChild( style );
}

// - callback function: 
// - first, set css
// - second fill container with content from server
// - insert container before the initial script tag (=target)
SC_WL.setContent = function(content) {
	SC_WL.setCss (content.css);
	var container = document.createElement('div');
	container.setAttribute('id', 'sciomino_widget_container');
	container.innerHTML = content.html
	SC_WL.target.parentNode.insertBefore(container, SC_WL.target);
}

///////
// INIT
///////

// get target & parameters
// - for now, our target is identified by the element_id of the script tag.
// - => note: there can only be one widget on a page!
// - => this would be perfect: SC_WL.target = document.currentScript;
// - => else make a multiple script solution: use getElementsByTagName("script") && match src attribute with a certain string: js/sciomino/widget.load
// - get params (wid & width) from querystring
SC_WL.target = document.getElementById("sciomino_widget");
SC_WL.params = SC_WL.deparam(SC_WL.target.src);

//////////
// CONTENT
//////////

// get content
// - get JSON from server
// - pass it on to the container

// example contents for SC_WL.content
// - SC_WL.setContent({"html":"Hello World"});

// how does it work?
// - onload of this javascript, triggers the callback function, very cool :-)
// - note to self: this might even be better/faster than XmlHttpRequest...
SC_WL.content = document.createElement("script");
SC_WL.content.setAttribute("type", "text/javascript");
// don't enumerate the params, but pass them all through...
// SC_WL.content.setAttribute("src", SC_WL.url + "?wid=" + SC_WL.params.wid + "&width=" + SC_WL.params.width);
SC_WL.content.setAttribute("src", SC_WL.url + encodeURIComponent(SC_WL.target.src.substring(SC_WL.target.src.indexOf('?'))));
SC_WL.target.appendChild(SC_WL.content);
