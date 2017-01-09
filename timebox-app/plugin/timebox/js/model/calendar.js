MCOW.Model.Timebox.calendar = {

	run: function() {
		// get credentials
		var credentials = TIMEBOX.Lib.getCredentials();
	
		// get calendar
		var calendarList = new Array();
		var numberOfMonths = 6;
		
		for (var i=0;i<numberOfMonths;i++) {
			calendarList[i] = MCOW.Model.Timebox.calendar.getNewCalendar(i);
		}

		MCOW.Session.Response.param["calendarList"] = calendarList;
						
		MCOW.Model.Timebox.calendar.callback();
	},
	
	callback: function() {
		MCOW.Event.Control.modelCallback();
	},

	getNewCalendar: function(offset) {

		var calendarListItem = {};
		var calendar = new Array();
		var months = ['Januari','Februari','Maart','April','Mei','Juni','Juli','Augustus','September','Oktober','November','December'];

		// today
		var currentDayBool = 1;
		var currentDate = new Date();
		var currentYear = currentDate.getFullYear();
		var currentMonth = currentDate.getMonth() + offset;
		if (currentMonth >= 12) {
			currentMonth = currentMonth - 12;
			currentYear = currentYear + 1;
		}
		var currentMonthString = months[currentMonth];
		var currentDay = 0;
		if (offset == 0) {
			currentDay = currentDate.getDate();
		}

		// first day of month to calculate the first weekday (mo-su)
		var firstDate = new Date(currentYear, currentMonth, 1);
		var firstWeekDay = firstDate.getDay();
		
		// last day of month to calculate the number of days in the month (28-31)
		var lastDate = new Date(currentYear, currentMonth+1, 0);
		var lastDay = lastDate.getDate();
				
		// zondag is niet de eerste dag van de week, maar maandag...
		if (firstWeekDay == 0) {
			firstWeekDay = 6;
		}
		else {
			firstWeekDay--;
		}
		
		// build a calendar as a matrix of 6 weeks/rows and 7 days/columns
		$count = 1;
		for (var i=0; i<=5; i++) {
			calendar[i] = new Array();
			for (var j=0; j<=6; j++) {
				if ($count <= lastDay) {
					if (i==0) {
						if (j < firstWeekDay) {
							// leading zero's
							calendar[i][j] = 0;
						}
						else {
							calendar[i][j] = $count;
							$count++;
						}
					}
					else {
						calendar[i][j] = $count;
						$count++;
					}
				}
				else {
					// trailing zero's
					calendar[i][j] = 0;
				}
			}
		}

		calendarListItem["year"] = currentYear;
		calendarListItem["month"] = currentMonth;
		calendarListItem["monthString"] = currentMonthString;
		calendarListItem["day"] = currentDay;
		calendarListItem["calendar"] = calendar;
		
		//alert(JSON.stringify(calendarListItem));
		
		return (calendarListItem);		
	}
	
}
