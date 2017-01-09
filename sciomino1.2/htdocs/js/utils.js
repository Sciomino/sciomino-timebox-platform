// div on|off
function enableDisplay(id) {
       	document.getElementById(id).style.display="block";
}

function enableDisplayInline(id) {
       	document.getElementById(id).style.display="inline";
}

function disableDisplay(id) {
        document.getElementById(id).style.display="none";
}

function toggleDisplay(idOn, idOff) {
	disableDisplay(idOff);
	enableDisplay(idOn);
}

function toggleDisplayInline(idOn, idOff) {
	disableDisplay(idOff);
	enableDisplayInline(idOn);
}

// clear form values (used on focus)
function clearValue(id) {
	if (document.getElementById(id).value == document.getElementById(id).defaultValue) {
		document.getElementById(id).value = "";
	}
}

//
// check form input
// - check compulsory fields 'com_'
// - create query_string 'vars'
//

// secure the user input
function secureTransfer (string) {
        var out = "";

        //out = toUnicode(string);
        //out = escape(string);
	out = encodeURI(string);

        return (out);
}

function toUnicode (string) {
        var out = "";

        for (var c = 0; c < string.length; c++) {
                out = out + '&#' + string.charCodeAt(c) + ';';
        }

        return (out);
}

function checkFormInput(form_name) {

	checked = "1";
	vars = "";

	for (i=0; i<document.getElementById(form_name).elements.length; i++) {

		element = document.getElementById(form_name).elements[i];

//		alert (element.type + ":" + element.name + "=" + element.value);
		
		switch (element.type) {
			case "text":
			case "textarea":
				vars = vars + "&" + element.name + "=" + secureTransfer(element.value);
				if (element.name.match("^"+"com_")=="com_") {
					if (element.value == "") {
						checked = "0";
					}
				}
				break;
			case "file":
				vars = vars + "&" + element.name + "=" + element.value;
				break;
			case "radio":
			case "checkbox":
				if (element.checked) {
					vars = vars + "&" + element.name + "=" + element.value;
				}										
				break;
			case "select-one":
				if (element.selectedIndex >= 0) {
					vars = vars + '&' + element.name + "=" + element.options[element.selectedIndex].value
				}
				break;
			case "select-multiple":
				for (var i=0; i<element.options.length; i++) {
					if (element.options[i].selected==true) {
						vars = vars + '&' + element.name + "=" + element.options[i].value
					}
				}
				break;
			case "submit":
				break;
			// hidden + password
			default:
				vars = vars + "&" + element.name + "=" + element.value;
				break;
		}
	}
	
	// strip first '&'
	if (vars != "") {
		vars = vars.substr(1);
	}

	return (new Array(checked, vars));

}

//
// language Mapping
//

function language(key) {

	var value = key;

	if (languageMap[key] != undefined) {
		value = languageMap[key];
	}

	return (value);
}


//
// Event Handling
//

// manage onload events
function addLoadEvent(func) { 
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

// get element position
function getElementPosition(element) {
	var curleft = curtop = 0;

	if (element.offsetParent) {
		do {
			curleft += element.offsetLeft;
			curtop += element.offsetTop;
		} while (element = element.offsetParent);
	}
	return [curleft,curtop];
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

