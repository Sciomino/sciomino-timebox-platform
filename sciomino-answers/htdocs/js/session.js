// Start editing to your needs
var SessionWindow = 'sessionPopup';
var SessionWindowData = 'sessionPopupData';

var SessionViewWindow = 'sessionView';
var SessionActivateWindow = 'sessionActivate';
var SessionActivateContinuWindow = 'sessionActivateContinu';
var SessionLogoutWindow = 'sessionView';
var SessionLoginWindow = 'sessionView';
var SessionLoginAlertWindow = 'loginAlertWindow';
var SessionRegisterWindow = 'sessionPopupData';
var SessionRegisterAlertWindow = 'registerAlertWindow';
var SessionPasswordWindow = 'sessionView';
var SessionPasswordAlertWindow = 'passwordAlertWindow';

var SessionRedirectOnWindowCloseUrl = '';

if (XCOW_B['url']) {
      var SessionRedirectRegisterUrl = XCOW_B['url'];
      var SessionRedirectLoginUrl = XCOW_B['url'];
      var SessionRedirectLogoutUrl = XCOW_B['url'];
}
else {
      var SessionRedirectRegisterUrl = '';
      var SessionRedirectLoginUrl = '/web/mypage';
      var SessionRedirectLogoutUrl = '/web/mypage';
}

// STOP editing

//
SessionActivated  = -1;

// transfer with XMLexchange
SessionTransfer = new XMLexchange();

// define Session
if (!window.Session) {
  Session = {};
}

Session.Util = {

  enableDisplay: function(id) {
        document.getElementById(id).style.display="block";
  },

  disableDisplay: function(id) {
        document.getElementById(id).style.display="none";
  }

}

Session.Window = {

  check: function(window) {
	if (window == SessionWindowData) {
		Session.Window.open();
	}
  },

  open: function() {
	Session.Util.enableDisplay(SessionWindow);
  },

  close: function() {
	Session.Util.disableDisplay(SessionWindow);

	if (SessionRedirectOnWindowCloseUrl) {
		document.location = SessionRedirectOnWindowCloseUrl;
	}
	else {
		Session.View.load();
	}
  }

}

Session.View = {

  //
  // view
  //
  load: function() {
	SessionTransfer.request("GET", XCOW_B['url'] + "/session/view", "", "Session.View.load_callback", "TEXT");
  },

  load_callback: function(data) {
	Session.Window.check(SessionViewWindow);
	document.getElementById(SessionViewWindow).innerHTML=data;
	window.scroll(0,0);
  }

}

Session.Activate = {

  //
  // view
  //
  load: function(key) {
	SessionTransfer.request("GET", XCOW_B['url'] + "/session/activate?key="+key, "", "Session.Activate.load_callback", "TEXT");
  },

  load_callback: function(data) {
	Session.Window.check(SessionActivateWindow);
	document.getElementById(SessionActivateWindow).innerHTML=data;
	if (data.indexOf('activateCheck') >= 0) { enableDisplay(SessionActivateContinuWindow); };
	window.scroll(0,0);
  }

}

Session.Logout = {

  //
  // Logout
  //
  load: function() {
	SessionTransfer.request("GET", XCOW_B['url'] + "/session/logout", "", "Session.Logout.load_callback", "TEXT");
  },

  load_callback: function(data) {
	Session.Window.check(SessionLogoutWindow);
	document.getElementById(SessionLogoutWindow).innerHTML=data;
	window.scroll(0,0);

	// bye bye
	if (SessionRedirectLogoutUrl) {
		document.location = SessionRedirectLogoutUrl;
	}
  }

}

Session.Register = {

  //
  // Registreer
  //
  load: function() {
	SessionTransfer.request("GET", XCOW_B['url'] + "/session/new", "", "Session.Register.load_callback", "TEXT");
  },

  load_callback: function(data) {
	Session.Window.check(SessionRegisterWindow);
	document.getElementById(SessionRegisterWindow).innerHTML=data;
	window.scroll(0,0);
  },

  newAction: function() {
	
	u_name = document.getElementById("register_form").user.name;
	u_value = document.getElementById("register_form").user.value;
	p_name = document.getElementById("register_form").pass.name;
	p_value = document.getElementById("register_form").pass.value;
	e_name = document.getElementById("register_form").mail.name;
	e_value = document.getElementById("register_form").mail.value;

        // validate
        stat = 1;
        if (stat == 1 && u_value == '' || p_value == '' || e_value == '') {
                Session.Register.newAction_alert(language('session_form_fill'));
                stat = 0;
        }
        if (stat == 1 ) {
                vars = u_name + '=' + u_value + '&' + p_name + '=' + p_value+ '&' + e_name + '=' + e_value;
                SessionTransfer.request("GET", XCOW_B['url'] + "/session/new?"+vars, "","Session.Register.newAction_callback", "TEXT");
        }

  },

  newAction_alert: function(message) {
	document.getElementById(SessionRegisterAlertWindow).innerHTML=message;
	window.scroll(0,0);
  },

  newAction_callback: function(data) {
	document.getElementById(SessionRegisterWindow).innerHTML=data;
	window.scroll(0,0);

        // go
        if (SessionRedirectRegisterUrl) {
                document.location = SessionRedirectRegisterUrl;
        }

  }

}

Session.Login = {

  //
  // Login
  //
  load: function() {
	SessionTransfer.request("GET", XCOW_B['url'] + "/session/login", "", "Session.Login.load_callback", "TEXT");
  },

  load_callback: function(data) {
	Session.Window.check(SessionLoginWindow);
	document.getElementById(SessionLoginWindow).innerHTML=data;
	window.scroll(0,0);
  },

  newAction: function() {
	
	u_name = document.getElementById("login_form").user.name;
	u_value = document.getElementById("login_form").user.value;
	p_name = document.getElementById("login_form").pass.name;
	p_value = document.getElementById("login_form").pass.value;
	r_name = document.getElementById("login_form").redirect.name;
	r_value = document.getElementById("login_form").redirect.value;
	r_value = secureTransfer(r_value);

	// override - part 1
	if (SessionRedirectLoginUrl) {
		r_value = secureTransfer(SessionRedirectLoginUrl);
	}

        // validate
        stat = 1;
        if (stat == 1 && u_value == '' || p_value == '') {
                Session.Login.newAction_alert(language('session_form_fill'));
                stat = 0;
        }
        if (stat == 1 ) {
                vars = u_name + '=' + u_value + '&' + p_name + '=' + p_value + '&' + r_name + '=' + r_value;
                SessionTransfer.request("POST", XCOW_B['url'] + "/session/login", vars,"Session.Login.newAction_callback", "TEXT");
        }

  },

  newAction_alert: function(message) {
	document.getElementById(SessionLoginAlertWindow).innerHTML=message;
	window.scroll(0,0);
  },

  newAction_callback: function(data) {
	// override - part 2
	if (SessionRedirectLoginUrl && data.substring(1,5) == "html") {
	    document.location = SessionRedirectLoginUrl;
	}
	else {
	    document.getElementById(SessionLoginWindow).innerHTML=data;
	    window.scroll(0,0);
	}
  }

}

Session.Password = {

  //
  // Wachtwoord aanvragen
  //
  load: function() {
	SessionTransfer.request("GET", XCOW_B['url'] + "/session/passNew", "", "Session.Password.load_callback", "TEXT");
  },

  load_callback: function(data) {
	Session.Window.check(SessionPasswordWindow);
	document.getElementById(SessionPasswordWindow).innerHTML=data;
	window.scroll(0,0);
  },

  newAction: function() {
	
	e_name = document.getElementById("pass_form").mail.name;
	e_value = document.getElementById("pass_form").mail.value;

        // validate
        stat = 1;
        if (stat == 1 && e_value == '') {
                Session.Password.newAction_alert(language('session_form_fill'));
                stat = 0;
        }
        if (stat == 1 ) {
                vars = e_name + '=' + e_value;
                SessionTransfer.request("GET", XCOW_B['url'] + "/session/passNew?"+vars, "","Session.Password.newAction_callback", "TEXT");
        }

  },

  newAction_alert: function(message) {
	document.getElementById(SessionPasswordAlertWindow).innerHTML=message;
	window.scroll(0,0);
  },

  newAction_callback: function(data) {
	document.getElementById(SessionPasswordWindow).innerHTML=data;
	window.scroll(0,0);
  },

  //
  // Wachtwoord wijzigen
  //
  updateAction: function() {
	
	p1_name = document.getElementById("pass_form").passOld.name;
	p1_value = document.getElementById("pass_form").passOld.value;
	p2_name = document.getElementById("pass_form").passNew1.name;
	p2_value = document.getElementById("pass_form").passNew1.value;
	p3_name = document.getElementById("pass_form").passNew2.name;
	p3_value = document.getElementById("pass_form").passNew2.value;

        // validate
        stat = 1;
        if (stat == 1 && p1_value == '' || p2_value == '' || p3_value == '') {
                Session.Password.updateAction_alert(language('session_form_fill'));
                stat = 0;
        }
        if (stat == 1 ) {
                vars = p1_name + '=' + p1_value + '&' + p2_name + '=' + p2_value+ '&' + p3_name + '=' + p3_value;
                SessionTransfer.request("GET", XCOW_B['url'] + "/session/passUpdate?"+vars, "","Session.Password.updateAction_callback", "TEXT");
        }

  },

  updateAction_alert: function(message) {
	document.getElementById(SessionPasswordAlertWindow).innerHTML=message;
	window.scroll(0,0);
  },

  updateAction_callback: function(data) {
	document.getElementById(SessionPasswordWindow).innerHTML=data;
	window.scroll(0,0);
  }

}

