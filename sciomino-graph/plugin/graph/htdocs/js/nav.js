// dates = [startYear, endYear, curYear, startMonth, endMonth, curMonth];

function loadNav(app, dates) {
	var Selected = "";
	var dataString = "<ul class='index linklist'>";
	
	var startYear = dates[0];
	var endYear = dates[1];
	var curYear = dates[2];
	var startMonth = dates[3];
	var endMonth = dates[4];
	var curMonth = dates[5];
	
	var data = new Array();
	for (var y = startYear; y <= endYear; y++) {
		for (var m = 1; m <= 12; m++) {
			// begin with startMonth
			if (y == startYear && m < startMonth) {
				continue;
			}

			// current date?
			Selected = "";
			if (y == curYear && m == curMonth) {
				Selected = " class=\"selected\"";
			}
			// create item
			// beware: this navigation is only for stats...
			data.push("<li" + Selected + ">" + "<a rel=\"external\" href=\"/stats/status?app=" + app + "&year=" + y + "&month=" + m + "\">" + language('month_'+ m) + " " + y + "</a>" + "</li>");
			
			// end on endMonth
			if (y == endYear && m == endMonth) {
				break;
			}
		}
	}
	
	data.reverse();
	dataString += data.join(' ');
	dataString += "</ul>";

	document.getElementById('main-nav-top').innerHTML=dataString;
	document.getElementById('main-nav-bottom').innerHTML=dataString;
}
			
				
				
				
				
			
