MCOW.Model.Timebox.wizard = {

	run: function() {
		// get credentials
		var credentials = TIMEBOX.Lib.getCredentials();

		var go = MCOW.Session.Request.param["go"];

		var form = document.forms["wizardForm"];
		if (go == 1 && form && MCOW.Util.getParentElementById(form, "page") != null) {
			var form_firstname = form.elements["firstname"].value;
			var form_lastname = form.elements["lastname"].value;
			var form_gender = form.elements["gender"].value;
			var form_dateofbirthday = form.elements["dateofbirthday"].value;
			var form_dateofbirthmonth = form.elements["dateofbirthmonth"].value;
			var form_dateofbirthyear = form.elements["dateofbirthyear"].value;
		
			if (form_firstname != "" && form_lastname != "" && form_gender != "" && form_dateofbirthday != "day" && form_dateofbirthmonth != "month" && form_dateofbirthyear != "year") {

				var personalia = TIMEBOX.Lib.getPersonalia();
				personalia["firstname"] = form_firstname;
				personalia["lastname"] = form_lastname;
				personalia["gender"] = form_gender;
				personalia["dateofbirthday"] = form_dateofbirthday;
				personalia["dateofbirthmonth"] = form_dateofbirthmonth;
				personalia["dateofbirthyear"] = form_dateofbirthyear;
				
				TIMEBOX.Lib.setPersonalia(personalia, 1);		
				
				// continue (personalia are synced with server in the background)
				MCOW.Event.fire("/Timebox/home");	
			}
			else {
				MCOW.Session.Response.param["error"] = "Please, fill in the complete form.";
				MCOW.Session.Response.param["firstname"] = form_firstname;
				MCOW.Session.Response.param["lastname"] = form_lastname;
				MCOW.Session.Response.param["gender"] = form_gender;
				MCOW.Session.Response.param["dateofbirthday"] = form_dateofbirthday;
				MCOW.Session.Response.param["dateofbirthmonth"] = form_dateofbirthmonth;
				MCOW.Session.Response.param["dateofbirthyear"] = form_dateofbirthyear;
				MCOW.Model.Timebox.wizard.callback();
			}
		} 
		else {
			MCOW.Model.Timebox.wizard.callback();
		}	
	},
	
	callback: function() {
		MCOW.Event.Control.modelCallback();		
	}

}
