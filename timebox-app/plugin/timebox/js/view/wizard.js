<div id="content">
	<div id="container">
		<form name="wizardForm" onsubmit="if (MCOW.Config['target'] == 'phonegap') { cordova.plugins.Keyboard.close(); }; return false">
		<div class="registerform" id="registerform">
			<h1>Bijna klaar!</h1>
			<p>Vertel ons wie je bent...</p>
			<div id="WIZARD-SCRIPT-FIRSTNAME">				
			</div>
			<script type="text/javascript">
				var html = "";
				var value = "";
				var label = "Voornaam";
				if (typeof MCOW.Session.Response.param["firstname"] != "undefined" && MCOW.Session.Response.param["firstname"] != "") {
					value = MCOW.Session.Response.param["firstname"];
					label = "";
				}
				html = html + "<input type='text' name='firstname' id='firstname' value='" + value + "' />";
				html = html + "<label for='firstname'>" + label + "</label>";
				MCOW.Util.setHTML("WIZARD-SCRIPT-FIRSTNAME",html);
			</script>	
			<div id="WIZARD-SCRIPT-LASTNAME">
			</div>
			<script type="text/javascript">
				var html = "";
				var value = "";
				var label = "Achternaam";
				if (typeof MCOW.Session.Response.param["lastname"] != "undefined" && MCOW.Session.Response.param["lastname"] != "") {
					value = MCOW.Session.Response.param["lastname"];
					label = "";
				}
				html = html + "<input type='text' name='lastname' id='lastname' value='" + value + "' />";
				html = html + "<label for='lastname'>" + label + "</label>";
				MCOW.Util.setHTML("WIZARD-SCRIPT-LASTNAME",html);
			</script>	
			<div id="WIZARD-SCRIPT-GENDER">
			</div>
			<script type="text/javascript">
				var html = "";
				var checkedM = "";
				var checkedV = "";
				if (typeof MCOW.Session.Response.param["gender"] != "undefined") {
					if (MCOW.Session.Response.param["gender"] == "M") { checkedM = "checked"; }
					if (MCOW.Session.Response.param["gender"] == "V") { checkedV = "checked"; }
				}
				html = html + "<fieldset data-role='controlgroup' data-type='horizontal'>";
				html = html + "<input type='radio' name='gender' id='gender-1' value='M' " + checkedM + "/>";
				html = html + "<label for='gender-1'>man</label>";
				html = html + "<input type='radio' name='gender' id='gender-0' value='V' " + checkedV + "/>";
				html = html + "<label for='gender-0'>vrouw</label>";
				html = html + "</fieldset>";
				MCOW.Util.setHTML("WIZARD-SCRIPT-GENDER",html);
			</script>	
			<div id="WIZARD-SCRIPT-DATEOFBIRTH">
			</div>
			<script type="text/javascript">
				var html = "<p>Geboortedatum</p>";
				html = html + "<select name='dateofbirthday'>";
				html = html + "<option>dag</option>";
				for (var i=1;i<=31;i++) {
					var selected = "";
					if (MCOW.Session.Response.param["dateofbirthday"] == i) {
						selected = "SELECTED";
					}
					html = html + "<option " + selected + ">" + i + "</option>";
				}
				html = html + "</select>";
				
				html = html + "<select name='dateofbirthmonth'>";
				html = html + "<option>maand</option>";
				var months = ['Januari','Februari','Maart','April','Mei','Juni','Juli','Augustus','September','Oktober','November','December'];
				for (var i=1;i<=12;i++) {
					var selected = "";
					if (MCOW.Session.Response.param["dateofbirthmonth"] == i) {
						selected = "SELECTED";
					}
					html = html + "<option value='" + i + "'" + selected + ">" + months[i-1] + "</option>";
				}
				html = html + "</select>";
			
				html = html + "<select name='dateofbirthyear'>";
				html = html + "<option>jaar</option>";
				var year = new Date().getFullYear();
				year = year - 15;
				for (var i=year;i>=year-85;i--) {
					var selected = "";
					if (MCOW.Session.Response.param["dateofbirthyear"] == i) {
						selected = "SELECTED";
					}
					html = html + "<option " + selected + ">" + i + "</option>";
				}
				html = html + "</select>";

				MCOW.Util.setHTML("WIZARD-SCRIPT-DATEOFBIRTH",html);
				
				if (typeof MCOW.Session.Response.param["error"] != "undefined" && MCOW.Session.Response.param["error"] != "") {
					$("#registerform > p").addClass("error");
					$("#registerform > p").html(MCOW.Session.Response.param["error"]);
				}
			</script>	
			<div>
				<button type="button" id="playbutton" name="playbutton">Enter TimeBox</button>
			</div>
		</div>
		</form>
	</div>
</div>
