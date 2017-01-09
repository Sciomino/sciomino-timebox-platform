// a set of utils for MCOW

MCOW.Util = {

	// ################
	// DISPLAY / REDRAW
	// ################

	enableDisplay: function(id) {
			document.getElementById(id).style.display="block";
	},

	enableDisplayInline: function(id) {
			document.getElementById(id).style.display="inline";
	},

	disableDisplay: function(id) {
			document.getElementById(id).style.display="none";
	},

	toggleDisplay: function(idOn, idOff) {
		disableDisplay(idOff);
		enableDisplay(idOn);
	},

	toggleDisplayInline: function(idOn, idOff) {
		disableDisplay(idOff);
		enableDisplayInline(idOn);
	},

	// ################
	// DOCUMENT CONTENT 
	// ################
	
	// html
	setHTML: function(id,html) {
		var e = document.getElementById(id);
		e.innerHTML = html;
	},

	// scripts
	// http://stackoverflow.com/questions/2592092/executing-script-elements-inserted-with-innerhtml
	setScript: function(id) {

		function nodeName(elem, name) {
			return elem.nodeName && elem.nodeName.toUpperCase() === name.toUpperCase();
		};

		// add script element to DOM
		function evalScript(elem, callback) {
			var data = (elem.text || elem.textContent || elem.innerHTML || "" ),
				head = document.getElementsByTagName("head")[0] || document.documentElement;

			var script = document.createElement("script");
			script.type = "text/javascript";
			script.className = "setscript";

			if (elem.src != '') {
				script.src = elem.src;
				head.appendChild(script);
				// Then bind the event to the callback function.
				// There are several events for cross browser compatibility.
				script.onreadystatechange = callback;
				script.onload = callback;
			}
			else {
				try {
					// doesn't work on ie...
					script.appendChild(document.createTextNode(data));      
				} 
				catch(e) {
					// IE has funky script nodes
					script.text = data;
				}
				head.appendChild(script);
				callback();
			}
		};

		// traverse view recursevily
		function walk_children(node) {
			var scripts = [],
			  script,
			  children_nodes = node.childNodes,
			  child,
			  i;

			if (children_nodes === undefined) return;

			for (i = 0; i<children_nodes.length; i++) {
				child = children_nodes[i];
				if (nodeName(child, "script" ) &&
					(!child.type || child.type.toLowerCase() === "text/javascript")) {
					scripts.push(child);
				} 
				else {
					var new_scripts = walk_children(child);
					for(j=0; j<new_scripts.length; j++) {
						scripts.push(new_scripts[j]);
					}
				}
			}

			return scripts;
		}

		// collect scripts in view
		var i = 0;
		function execute_script(i) {
			script = scripts[i];
			if (script.parentNode) {script.parentNode.removeChild(script);}
			evalScript(scripts[i], function() {
				if (i < scripts.length-1) {
					execute_script(++i);
				}                
			});
		}

		// remove previous scripts
		function remove_old_scripts() {
			head = document.getElementsByTagName("head")[0];
			scripts = head.getElementsByClassName("setscript");
			for (var i=scripts.length-1;i>=0;i--) {
				scripts[i].parentNode.removeChild(scripts[i]);
			}
		}

		// main section of function
		remove_old_scripts();
		var e = document.getElementById(id);
		var scripts = walk_children(e);
		
		if (scripts.length > 0) {
			execute_script(i);
		}
		
	},

	// ###########
	// TRANSITIONS
	// ###########

	initTransition: function(id, dir) {
		var e = document.getElementById(id);
		e.classList.add(dir + "-trans-init");
	},
	setTransition: function(id, dir) {
		var e = document.getElementById(id);
		e.classList.add(dir + "-trans");
	},
	resetTransition: function(id, dir) {
		var e = document.getElementById(id);
		e.classList.remove(dir + "-trans-init");
		e.classList.remove(dir + "-trans");
	},
	initReverseTransition: function(id, dir) {
		var e = document.getElementById(id);
		e.classList.add(dir + "-trans-out-init");
	},
	setReverseTransition: function(id, dir) {
		var e = document.getElementById(id);
		e.classList.add(dir + "-trans-out");
	},
	resetReverseTransition: function(id, dir) {
		var e = document.getElementById(id);
		e.classList.remove(dir + "-trans-out-init");
		e.classList.remove(dir + "-trans-out");
	},

	// ########
	// LANGUAGE
	// ########
			
	getLanguage: function(key) {

		var value = key;

		if (typeof MCOW.Language[key] != undefined) {
			value = MCOW.Language[key];
		}

		return (value);
	},

	// ########
	// ELEMENTS
	// ########

	getAbsoluteElementPosition : function(element) {
		var x = 0;
		var y = 0;
		
		while( element && !isNaN( element.offsetLeft ) && !isNaN( element.offsetTop ) ) {
			x += element.offsetLeft - element.scrollLeft;
			y += element.offsetTop - element.scrollTop;
			element = element.offsetParent;
		}
		
		return (new Array(x, y));
	},

	getRelativeElementPosition : function(element) {
		var x = 0;
		var y = 0;
		
		x = element.offsetLeft - element.scrollLeft;
		y = element.offsetTop - element.scrollTop;

		//var offsets = element.getBoundingClientRect();
		//x = offsets.left;
		//y = offsets.top;
		
		return (new Array(x, y));
	},

	// get event element
	getEventElement: function(e) {
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
	},

	getClosestParent: function (element, tag) {
		tag = tag.toUpperCase();
		while (element = element.parentNode) {
			if (element.nodeName === tag) {
				return element;
			}
		} 
		return null;
	},
	
	getParentElementById: function (e, id) {
		id = id.toLowerCase();
		while (e && e.parentNode) {
			e = e.parentNode;
			if (e.id && e.id.toLowerCase() == id) {
				return e;
			}
		}
		
		return null;
	},

	// #####
	// MOUSE
	// #####

 	getMousePosition: function(e) {

		var x = 0;
		var y = 0;	

		if (!e) var e = window.event;

		if (e.pageX || e.pageY) {
			x = e.pageX;
			y = e.pageY;
		}
		else if (e.clientX || e.clientY) 	{
			x = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
			y = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
		}
		
		return (new Array(x, y));
	},

	// ##########
	// PARAMETERS
	// ##########
		
	getParams: function (querystring) {
	  // remove any preceding url and split
	  querystring = querystring.substring(querystring.indexOf('?')+1).split('&');
	  var params = {}, pair, d = decodeURIComponent;
	  // march and parse
	  for (var i = querystring.length - 1; i >= 0; i--) {
		pair = querystring[i].split('=');
		params[d(pair[0])] = d(pair[1]);
	  }
	  return params;
	},

	// ############
	// SHA1 ENCRYPT
	// ############
	
	sha1 : function (str) {
	  //  discuss at: http://phpjs.org/functions/sha1/
	  // original by: Webtoolkit.info (http://www.webtoolkit.info/)
	  // improved by: Michael White (http://getsprink.com)
	  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	  //    input by: Brett Zamir (http://brett-zamir.me)
	  //  depends on: utf8_encode
	  //   example 1: sha1('Kevin van Zonneveld');
	  //   returns 1: '54916d2e62f65b3afa6e192e6a601cdbe5cb5897'

	  var rotate_left = function(n, s) {
		var t4 = (n << s) | (n >>> (32 - s));
		return t4;
	  };

	  /*var lsb_hex = function (val) { // Not in use; needed?
		var str="";
		var i;
		var vh;
		var vl;

		for ( i=0; i<=6; i+=2 ) {
		  vh = (val>>>(i*4+4))&0x0f;
		  vl = (val>>>(i*4))&0x0f;
		  str += vh.toString(16) + vl.toString(16);
		}
		return str;
	  };*/

	  var cvt_hex = function(val) {
		var str = '';
		var i;
		var v;

		for (i = 7; i >= 0; i--) {
		  v = (val >>> (i * 4)) & 0x0f;
		  str += v.toString(16);
		}
		return str;
	  };

	  var blockstart;
	  var i, j;
	  var W = new Array(80);
	  var H0 = 0x67452301;
	  var H1 = 0xEFCDAB89;
	  var H2 = 0x98BADCFE;
	  var H3 = 0x10325476;
	  var H4 = 0xC3D2E1F0;
	  var A, B, C, D, E;
	  var temp;

	  str = MCOW.Util.utf8_encode(str);
	  var str_len = str.length;

	  var word_array = [];
	  for (i = 0; i < str_len - 3; i += 4) {
		j = str.charCodeAt(i) << 24 | str.charCodeAt(i + 1) << 16 | str.charCodeAt(i + 2) << 8 | str.charCodeAt(i + 3);
		word_array.push(j);
	  }

	  switch (str_len % 4) {
		case 0:
		  i = 0x080000000;
		  break;
		case 1:
		  i = str.charCodeAt(str_len - 1) << 24 | 0x0800000;
		  break;
		case 2:
		  i = str.charCodeAt(str_len - 2) << 24 | str.charCodeAt(str_len - 1) << 16 | 0x08000;
		  break;
		case 3:
		  i = str.charCodeAt(str_len - 3) << 24 | str.charCodeAt(str_len - 2) << 16 | str.charCodeAt(str_len - 1) <<
			8 | 0x80;
		  break;
	  }

	  word_array.push(i);

	  while ((word_array.length % 16) != 14) {
		word_array.push(0);
	  }

	  word_array.push(str_len >>> 29);
	  word_array.push((str_len << 3) & 0x0ffffffff);

	  for (blockstart = 0; blockstart < word_array.length; blockstart += 16) {
		for (i = 0; i < 16; i++) {
		  W[i] = word_array[blockstart + i];
		}
		for (i = 16; i <= 79; i++) {
		  W[i] = rotate_left(W[i - 3] ^ W[i - 8] ^ W[i - 14] ^ W[i - 16], 1);
		}

		A = H0;
		B = H1;
		C = H2;
		D = H3;
		E = H4;

		for (i = 0; i <= 19; i++) {
		  temp = (rotate_left(A, 5) + ((B & C) | (~B & D)) + E + W[i] + 0x5A827999) & 0x0ffffffff;
		  E = D;
		  D = C;
		  C = rotate_left(B, 30);
		  B = A;
		  A = temp;
		}

		for (i = 20; i <= 39; i++) {
		  temp = (rotate_left(A, 5) + (B ^ C ^ D) + E + W[i] + 0x6ED9EBA1) & 0x0ffffffff;
		  E = D;
		  D = C;
		  C = rotate_left(B, 30);
		  B = A;
		  A = temp;
		}

		for (i = 40; i <= 59; i++) {
		  temp = (rotate_left(A, 5) + ((B & C) | (B & D) | (C & D)) + E + W[i] + 0x8F1BBCDC) & 0x0ffffffff;
		  E = D;
		  D = C;
		  C = rotate_left(B, 30);
		  B = A;
		  A = temp;
		}

		for (i = 60; i <= 79; i++) {
		  temp = (rotate_left(A, 5) + (B ^ C ^ D) + E + W[i] + 0xCA62C1D6) & 0x0ffffffff;
		  E = D;
		  D = C;
		  C = rotate_left(B, 30);
		  B = A;
		  A = temp;
		}

		H0 = (H0 + A) & 0x0ffffffff;
		H1 = (H1 + B) & 0x0ffffffff;
		H2 = (H2 + C) & 0x0ffffffff;
		H3 = (H3 + D) & 0x0ffffffff;
		H4 = (H4 + E) & 0x0ffffffff;
	  }

	  temp = cvt_hex(H0) + cvt_hex(H1) + cvt_hex(H2) + cvt_hex(H3) + cvt_hex(H4);
	  return temp.toLowerCase();
	},
	
	utf8_encode : function(argString) {
	  //  discuss at: http://phpjs.org/functions/utf8_encode/
	  // original by: Webtoolkit.info (http://www.webtoolkit.info/)
	  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	  // improved by: sowberry
	  // improved by: Jack
	  // improved by: Yves Sucaet
	  // improved by: kirilloid
	  // bugfixed by: Onno Marsman
	  // bugfixed by: Onno Marsman
	  // bugfixed by: Ulrich
	  // bugfixed by: Rafal Kukawski
	  // bugfixed by: kirilloid
	  //   example 1: utf8_encode('Kevin van Zonneveld');
	  //   returns 1: 'Kevin van Zonneveld'

	  if (argString === null || typeof argString === 'undefined') {
		return '';
	  }

	  var string = (argString + ''); // .replace(/\r\n/g, "\n").replace(/\r/g, "\n");
	  var utftext = '',
		start, end, stringl = 0;

	  start = end = 0;
	  stringl = string.length;
	  for (var n = 0; n < stringl; n++) {
		var c1 = string.charCodeAt(n);
		var enc = null;

		if (c1 < 128) {
		  end++;
		} else if (c1 > 127 && c1 < 2048) {
		  enc = String.fromCharCode(
			(c1 >> 6) | 192, (c1 & 63) | 128
		  );
		} else if ((c1 & 0xF800) != 0xD800) {
		  enc = String.fromCharCode(
			(c1 >> 12) | 224, ((c1 >> 6) & 63) | 128, (c1 & 63) | 128
		  );
		} else { // surrogate pairs
		  if ((c1 & 0xFC00) != 0xD800) {
			throw new RangeError('Unmatched trail surrogate at ' + n);
		  }
		  var c2 = string.charCodeAt(++n);
		  if ((c2 & 0xFC00) != 0xDC00) {
			throw new RangeError('Unmatched lead surrogate at ' + (n - 1));
		  }
		  c1 = ((c1 & 0x3FF) << 10) + (c2 & 0x3FF) + 0x10000;
		  enc = String.fromCharCode(
			(c1 >> 18) | 240, ((c1 >> 12) & 63) | 128, ((c1 >> 6) & 63) | 128, (c1 & 63) | 128
		  );
		}
		if (enc !== null) {
		  if (end > start) {
			utftext += string.slice(start, end);
		  }
		  utftext += enc;
		  start = end = n + 1;
		}
	  }

	  if (end > start) {
		utftext += string.slice(start, stringl);
	  }

	  return utftext;
	},

	// ##############
	// ANIMATE SCROLL
	// ##############
	
	// http://stackoverflow.com/questions/8917921/cross-browser-javascript-not-jquery-scroll-to-top-animation
	scrollId : "",

	scroll : function(element, scrollTargetY, speed, easing) {
		// element: the element to be scrolled
		// scrollTargetY: the target scrollY property
		// speed: time in pixels per second
		// easing: easing equation to use

		var scrollY = element.scrollTop,
			scrollTargetY = scrollTargetY || 0,
			speed = speed || 2000,
			easing = easing || 'easeOutSine',
			currentTime = 0;

		// min time .1, max time .8 seconds
		// var time = Math.max(.1, Math.min(Math.abs(scrollY - scrollTargetY) / speed, .8));
		// use speed directly, because we already decided on the speed in the touch handler
		var time = Math.max(.1, Math.min(speed / 1000, .8));

		// easing equations from https://github.com/danro/easing-js/blob/master/easing.js
		var PI_D2 = Math.PI / 2,
			easingEquations = {
				easeOutSine: function (pos) {
					return Math.sin(pos * (Math.PI / 2));
				},
				easeOutQuad: function(pos) {
					return -(Math.pow((pos-1), 2) -1);
				},
				easeOutCubic: function(pos) {
					return (Math.pow((pos-1), 3) +1);
				},
				easeOutQuart: function(pos) {
					return -(Math.pow((pos-1), 4) -1);
				},
				easeOutQuint: function(pos) {
					return (Math.pow((pos-1), 5) +1);
				}
			}
		// add animation loop
		function tick() {
			// a bit smaller frame rate
			currentTime += 1 / 30;

			var p = currentTime / time;
			var t = easingEquations[easing](p);

			if (p < 1) {
				//MCOW.Util.scrollId = window.requestAnimationFrame(tick);
				MCOW.Util.scrollId = setTimeout(tick, 34);
				element.scrollTop = (scrollY + ((scrollTargetY - scrollY) * t));
				//console.log("scroll: scrolling to: " + (scrollY + ((scrollTargetY - scrollY) * t)) + ", pos:" + p);
			} 
			else {
				element.scrollTop = scrollTargetY;
			}
		}

		// call it once to get started
		if (MCOW.Util.scrollId == "") {
			tick();
		}

	},

	scrollStop : function() {
		if (MCOW.Util.scrollId != "") {
			//window.cancelAnimationFrame(MCOW.Util.scrollId);
			clearTimeout(MCOW.Util.scrollId);
			MCOW.Util.scrollId = "";			
		}
	}	

}
