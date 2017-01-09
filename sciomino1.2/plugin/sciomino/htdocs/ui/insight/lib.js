jQuery(document).ready(function($) {

	// HERMAN: change this urls
	var AGE_XSL	= new String(XCOW_B['url'] + '/ui/insight/age.xsl').toString();
	// HERMAN:end

	var PREFIX		= new String('puu-').toString();
	var CLS_MONTLY	= PREFIX.concat('months');
	var CLS_BAR		= PREFIX.concat('bar');
	var CLS_AGE		= PREFIX.concat('age');
	var CLS_COMPACT	= PREFIX.concat('compact');
	var CLS_CHANNEL	= PREFIX.concat('channel');

	var EMPTY		= new String('').toString();
	var SPACE		= new String(' ').toString();
	var GT			= new String(' > ').toString();
	var DOT			= new String('.').toString();
	var DASH		= new String('-').toString();
	var UNDER		= new String('_').toString();
	var PX			= new String('px').toString();
	var DIV			= new String('div').toString();
	var TR			= new String('tr').toString();
	var TH			= new String('th').toString();
	var SPAN		= new String('span').toString();
	var ANCHOR		= new String('a').toString();
	var CURSOR		= new String('cursor').toString();
	var POINTER		= new String('pointer').toString();
	var CLICK 		= new String('click').toString();
	var FOCUS 		= new String('focus').toString();
	var UNDEF		= new String('undefined').toString();

	var MONTHS		= new Array(
		{label: "Januari", ends: 31},
		{label: "Februari", ends: 29},
		{label: "Maart", ends: 31},
		{label: "April", ends: 30},
		{label: "Mei", ends: 31},
		{label: "Juni", ends: 30},
		{label: "Juli", ends: 31},
		{label: "Augustus", ends: 31},
		{label: "September", ends: 30},
		{label: "Oktober", ends: 31},
		{label: "November", ends: 30},
		{label: "December", ends: 31}
	);

	var RADIX		= new Number(10);
	var XML_PREFIX	= new String('<?xml version="1.0"?>').toString();

	// HERMAN: ovi map script uit deze js gehaald

	// HERMAN: do not use send_request, but put in a new stylesheet/XML loader
	var send_request = function(url, cb) {
		$.get(url, cb);
	}

	// HERMAN: this is a special loader with type=msxml-document, which is needed for newer ie versions
	function loadXMLDoc(url) {
  		if (typeof XMLHttpRequest !== 'undefined') {
    			var xhr = new XMLHttpRequest();
    			xhr.open('GET', url, false);
    			// request MSXML responseXML for IE
    			try { xhr.responseType = 'msxml-document'; } catch(e){}
    			xhr.send();
    			return xhr.responseXML;
  		}
  		else {
    			try {
      				var xhr = new ActiveXObject('Msxml2.XMLHTTP.3.0');
      				xhr.open('GET', url, false);
      				xhr.send();
      				return xhr.responseXML;
    			} catch (e) {}
  		}
	}

	var xslt = function(xml, xsl, cb) {
		var attr = {
			xml: null,
			xsl: null
		};
		var cf_transform = function() {
			return function(o) {
				if (/xsl/.test(o.documentElement.nodeName) ) {
					attr.xsl = o;
				}
				else {
					attr.xml = o;
				}
				if (attr.xml != null && attr.xsl != null) {
					var result;
					if (window.XSLTProcessor) {
   						xsltProcessor = new XSLTProcessor();
   						xsltProcessor.importStylesheet(attr.xsl);
   						result = xsltProcessor.transformToFragment(
							attr.xml, document
						);
   					}
					else {
						var IEresult;

						//HERMAN: older IE's
						if (typeof (attr.xml.transformNode) != "undefined") {
							IEresult = attr.xml.transformNode(attr.xsl);
						}

						//HERMAN: newer IE's do not have transformNode, use this instead
 						else {
							var xslTemp = new ActiveXObject("Msxml2.XSLTemplate");
							var xslDoc = new ActiveXObject("Msxml2.FreeThreadedDOMDocument");
							xslDoc.loadXML(attr.xsl.xml);
							xslTemp.stylesheet = xslDoc;

							var xslProc = xslTemp.createProcessor();
							xslProc.input = attr.xml;
							xslProc.transform();

							IEresult = xslProc.output;
						}

						result = document.createDocumentFragment();
						var wrapper = document.createElement(DIV);
						wrapper.innerHTML = IEresult;
						while(wrapper.childNodes.length > 0) {
							result.appendChild(wrapper.firstChild);
						}
					}
					cb(result);
				}
			}
		}
		// Assuming a string without a newline is a URL
		var linked = new Array(xml, xsl);
		for (var i = 0; i < linked.length; i++) {
			if (/\n/.test(linked[i])) {
				var o;
				//HERMAN: this parses the XML from a STRING
				if (window.DOMParser) {
					var parser = new DOMParser();
					o = parser.parseFromString(linked[i], "text/xml");
				}
				else {
					o = new ActiveXObject('Microsoft.XMLDOM');
					o.async = 'false';
					// fix IE8- uppercase tagnames:
					var re = /(<\/?)([A-Z]+)\b/gm;
					linked[i] = linked[i].replace(re, function(str, p1, p2) {
						return p1.concat(p2.toLowerCase());
					});
					o.loadXML(linked[i]);
				}
				cf_transform()(o);
			}
			//HERMAN: this fetches the XSL from a FILE
			// why this 'linked' constuction is created is not clear, but keep it for now... transformation is done twice...
			else {
				var o;
				o = loadXMLDoc(xsl);
				cf_transform()(o);

			}
		}
	}

	var show_calendar = function(evt) {
		var CLS_CAL = PREFIX.concat('caldaypicker');
		var CLS_CAL_WRIP = PREFIX.concat('wrip');
		var CLS_MONTH = PREFIX.concat('month');
		var CLS_MONTH_DAYS = PREFIX.concat('days');
		var frag = document.createDocumentFragment();
		var cal = document.createElement(DIV);
		$(cal).attr('class', CLS_CAL);
		var cal_wrip = document.createElement(DIV);
		$(cal_wrip).attr('class', CLS_CAL_WRIP);
		for (var i = 0; i < MONTHS.length; i++) {
			var month = document.createElement(DIV);
			var label = document.createElement('h4');
			$(month).addClass(CLS_MONTH);
			$(month).addClass(CLS_MONTH.concat(UNDER, i));
			var txt = document.createTextNode(MONTHS[i]['label']);
			label.appendChild(txt);
			month.appendChild(label);
			var days = document.createElement(DIV);
			$(days).attr('class', CLS_MONTH_DAYS);
			for (var d = 1; d <= MONTHS[i]['ends']; d++) {
				var day = document.createElement(SPAN);
				txt = document.createTextNode(d);
				day.appendChild(txt);
				days.appendChild(day);
			}
			month.appendChild(days);
			cal_wrip.appendChild(month);
		}
		cal.appendChild(cal_wrip);
		$('.puu-change').closest('h3').append(cal);
		$(DOT.concat(CLS_CAL, SPACE, DOT, CLS_MONTH)).bind(
			CLICK, function(evt) {
				var day = $(evt.target).text();
				// HERMAN month changed, it's not the closest, but one up
				// var month = $('h4', $(evt.target).closest(DIV)).text();
				var month = $('h4', $(evt.target).parents(DIV)[1]).text();
				// HERMAN:end
				for (var i = 0; i < MONTHS.length; i++) {
					if (month == MONTHS[i]['label']) {
						month = new String(i + 1);
					}
				}
				// HERMAN fetch list for this date & change 'tomorrow' text
				// console.log(month.concat(DASH, day));
				ScioMino.InsightsBirthday.load(month + '/' + day + '/2010');
				$('.puu-change-value').text(day + ' ' + MONTHS[month-1]['label']);
				// HERMAN:end
				$(cal).remove();
			}
		);
		return false;
	}

	var grow_monthly = function() {
		var bar = 0;
		var max = 0;
		var FULL_WIDTH = new Number(245);
		var bars = $(DOT.concat(CLS_MONTLY, SPACE, DOT, CLS_BAR));
		bars.each(function() {
			bar = $(this).text();
			if (bar == "") { bar = "0"; }
			max = Math.max(max, parseInt(bar, RADIX));
		});
		bars.each(function() {
			bar = $(this).text();
			if (bar == "") { bar = "0"; }
			$(this).width(FULL_WIDTH * parseInt(bar) / max);	
		});
	}

	var age_pyramid = function() {
		$(DOT.concat(CLS_AGE)).each(function() {
			var island = $(this);
			xslt(island.html(), AGE_XSL, function(result) {
				var spacing = island.width() / $(TR, island).length;
				island.html(result);
				$($(DOT.concat(CLS_AGE, GT, DIV, GT, DIV), island.parent())).each(function(idx) {
					$(this).css('margin-left', EMPTY.concat(idx * spacing, PX));
				});
			});
		});
	}

	var channels = function() {
		$(TH.concat(DOT, CLS_CHANNEL, SPACE, ANCHOR)).each(function() {
			var row = $(this).parent().parent();
			row.css(CURSOR, POINTER);
			rebind(row, CLICK, function() {
				document.location.href = $(ANCHOR, $(this))[0];
			});
		});
	}

	var rebind = function(sel, typ, han) {
		$(sel).unbind(typ, han).bind(typ, han);
	}

	var rig = {
		show_calendar		: function() {
			rebind($('.puu-change'), CLICK, show_calendar);
		},
		grow_monthly		: grow_monthly,
		age_pyramid			: age_pyramid,
		channels			: channels
	};

	var init = function() {
		for (k in rig) {
			rig[k]();
		}
	}

	init();

});
