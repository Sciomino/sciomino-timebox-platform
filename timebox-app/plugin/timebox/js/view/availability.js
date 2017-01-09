<script type="text/javascript">
var html = "";
html = html + "<h2>";
html = html + "<a href='#' data='/Timebox/home?MCOW-transition=out' class='backbtn white mcow-touchable openinternalpage'>&lt;</a>";
html = html + "Beschikbaarheid instellen";
html = html + "</h2>";
MCOW.Util.setHTML("topbar",html);
document.getElementById("topbar").classList.add("availabilityHeader");
</script>
<div id="content">
	<div id="container">
		<div class="availability">
			<form>
				<!--<div id="currentset" class="mcow-touchable leaveavailabilitypagebyswipe" data="/Timebox/home?MCOW-transition=out">-->
				<div id="currentset">
				</div>
				<script type="text/javascript">
					// status
					var checked1 = "";
					var checked2 = "";
					var value = 0;
					if (MCOW.Session.Response.param["current"]["status"] == "available") {
						checked1 = "checked";
						value = 1;
					}
					if (MCOW.Session.Response.param["current"]["status"] == "unavailable") {
						checked2 = "checked";
					}
					var html = "";
					html = html + "<fieldset data-role='controlgroup' data-type='horizontal'>";
					html = html + "<legend>ik ben nu</legend>";
					html = html + "<input type='radio' name='currentavailability' id='currentavailability-1' value='1' " + checked1 + "/>";
					html = html + "<label for='currentavailability-1'>Beschikbaar</label>";
					html = html + "<input type='radio' name='currentavailability' id='currentavailability-0' value='0' " + checked2 + "/>";
					html = html + "<label for='currentavailability-0'>Niet beschikbaar</label>";
					html = html + "</fieldset>";
					MCOW.Util.setHTML("currentset",html);
					//TIMEBOX.Lib.checkCurAvailChange(value);
				</script>	

				<!--<div id="currentavailset" class="hidden mcow-touchable leaveavailabilitypagebyswipe" data="/Timebox/home?MCOW-transition=out">-->
				<div id="currentavailset" class="hidden">
				</div>
				<script type="text/javascript">
					// hours
					var checked8 = "";
					var checked16 = "";
					var checked24 = "";
					var checked32 = "";
					var checked40 = "";
					var checked48 = "";
					var checked56 = "";
					if (MCOW.Session.Response.param["current"]["hours"] == "8") {checked8 = "checked";}
					if (MCOW.Session.Response.param["current"]["hours"] == "16") {checked16 = "checked";}
					if (MCOW.Session.Response.param["current"]["hours"] == "24") {checked24 = "checked";}
					if (MCOW.Session.Response.param["current"]["hours"] == "32") {checked32 = "checked";}
					if (MCOW.Session.Response.param["current"]["hours"] == "40") {checked40 = "checked";}
					if (MCOW.Session.Response.param["current"]["hours"] == "48") {checked48 = "checked";}
					if (MCOW.Session.Response.param["current"]["hours"] == "56") {checked56 = "checked";}
					var html = "";
					html = html + "<fieldset data-role='controlgroup' data-type='horizontal' class='maxhoursset'>";
					html = html + "<legend>aantal uren</legend>";
					html = html + "<input type='radio' name='curmaxhours' id='curmaxhours-8' value='8' " + checked8 + "/>";
					html = html + "<label for='curmaxhours-8'>8</label>";
					html = html + "<input type='radio' name='curmaxhours' id='curmaxhours-16' value='16' " + checked16 + "/>";
					html = html + "<label for='curmaxhours-16'>16</label>";
					html = html + "<input type='radio' name='curmaxhours' id='curmaxhours-24' value='24' " + checked24 + "/>";
					html = html + "<label for='curmaxhours-24'>24</label>";
					html = html + "<input type='radio' name='curmaxhours' id='curmaxhours-32' value='32' " + checked32 + "/>";
					html = html + "<label for='curmaxhours-32'>32</label>";
					html = html + "<input type='radio' name='curmaxhours' id='curmaxhours-40' value='40' " + checked40 + "/>";
					html = html + "<label for='curmaxhours-40'>40</label>";
					html = html + "<input type='radio' name='curmaxhours' id='curmaxhours-48' value='48' " + checked48 + "/>";
					html = html + "<label for='curmaxhours-48'>48</label>";
					html = html + "<input type='radio' name='curmaxhours' id='curmaxhours-56' value='56' " + checked56 + "/>";
					html = html + "<label for='curmaxhours-56'>56</label>";
					html = html + "</fieldset>";
					
					// days
					var checked_day_1 = "";
					var checked_day_2 = "";
					var checked_day_3 = "";
					var checked_day_4 = "";
					var checked_day_5 = "";
					var checked_day_6 = "";
					var checked_day_7 = "";
					if (typeof MCOW.Session.Response.param["current"]["days"] != 'undefined') {
						var dayArray = MCOW.Session.Response.param["current"]["days"].split(",");
						for (i=0; i < dayArray.length; i++) {
							if (dayArray[i] == 1) { checked_day_1 = "checked"; }
							if (dayArray[i] == 2) { checked_day_2 = "checked"; }
							if (dayArray[i] == 3) { checked_day_3 = "checked"; }
							if (dayArray[i] == 4) { checked_day_4 = "checked"; }
							if (dayArray[i] == 5) { checked_day_5 = "checked"; }
							if (dayArray[i] == 6) { checked_day_6 = "checked"; }
							if (dayArray[i] == 7) { checked_day_7 = "checked"; }
						}
					}
					html = html + "<fieldset data-role='controlgroup' data-type='horizontal' class='preferreddaysset'>";
					html = html + "<legend>bij voorkeur op</legend>";
					html = html + "<input type='checkbox' name='curpreferreddays[]' id='curpreferreddays-mo' value='1' " + checked_day_1 + "/>";
					html = html + "<label for='curpreferreddays-mo'>ma</label>";
					html = html + "<input type='checkbox' name='curpreferreddays[]' id='curpreferreddays-tu' value='2' " + checked_day_2 + "/>";
					html = html + "<label for='curpreferreddays-tu'>di</label>";
					html = html + "<input type='checkbox' name='curpreferreddays[]' id='curpreferreddays-we' value='3' " + checked_day_3 + "/>";
					html = html + "<label for='curpreferreddays-we'>wo</label>";
					html = html + "<input type='checkbox' name='curpreferreddays[]' id='curpreferreddays-th' value='4' " + checked_day_4 + "/>";
					html = html + "<label for='curpreferreddays-th'>do</label>";
					html = html + "<input type='checkbox' name='curpreferreddays[]' id='curpreferreddays-fr' value='5' " + checked_day_5 + "/>";
					html = html + "<label for='curpreferreddays-fr'>vr</label>";
					html = html + "<input type='checkbox' name='curpreferreddays[]' id='curpreferreddays-sa' value='6' " + checked_day_6 + "/>";
					html = html + "<label for='curpreferreddays-sa'>za</label>";
					html = html + "<input type='checkbox' name='curpreferreddays[]' id='curpreferreddays-su' value='7' " + checked_day_7 + "/>";
					html = html + "<label for='curpreferreddays-su'>zo</label>";
					html = html + "</fieldset>";
					
					MCOW.Util.setHTML("currentavailset",html);
					//TIMEBOX.Lib.checkCurAvailSet();
				</script>	

				<div id="currentuntillset" class="hidden">
					<a href="#" data="/Timebox/calendar" class="mcow-touchable openinternalpage">tot en met<strong>kies</strong><span>&gt;</span></a>
				</div>
				<script type="text/javascript">
					// until (from store)
					var date = "kies";
					if (typeof MCOW.Session.Response.param["current"]["until"] != "undefined" && MCOW.Session.Response.param["current"]["until"] != "") {
						date = TIMEBOX.Lib.getDate(MCOW.Session.Response.param["current"]["until"]);
					}
					// new until from date parameter
					if (typeof MCOW.Session.Response.param["newDate"] != "undefined" && MCOW.Session.Response.param["newDate"] != "") {
						date = TIMEBOX.Lib.getDate(MCOW.Session.Response.param["newDate"]);
					}
					$("#currentuntillset a").addClass("selected").children("strong").html(date);
				</script>	

				<!--<div id="afterthatset" class="hidden mcow-touchable leaveavailabilitypagebyswipe" data="/Timebox/home?MCOW-transition=out">-->
				<div id="afterthatset" class="hidden">
				</div>
				<script type="text/javascript">
					// status 2
					var checked1 = "";
					var checked2 = "";
					var value = 0;
					if (MCOW.Session.Response.param["future"]["status"] == "available") {
						checked1 = "checked";
						value = 1;
					}
					if (MCOW.Session.Response.param["future"]["status"] == "unavailable") {
						checked2 = "checked";
					}
					var html = "";
					html = html + "<fieldset data-role='controlgroup' data-type='horizontal'>";
					html = html + "<legend>daarna ben ik</legend>";
					html = html + "<input type='radio' name='afteravailability' id='afteravailability-1' value='1' " + checked1 + "/>";
					html = html + "<label for='afteravailability-1'>Beschikbaar</label>";
					html = html + "<input type='radio' name='afteravailability' id='afteravailability-0' value='0' " + checked2 + "/>";
					html = html + "<label for='afteravailability-0'>Niet beschikbaar</label>";
					html = html + "</fieldset>";
					MCOW.Util.setHTML("afterthatset",html);
					//TIMEBOX.Lib.checkAfterAvailChange(value);
				</script>	

				<!--<div id="afteravailset" class="hidden mcow-touchable leaveavailabilitypagebyswipe" data="/Timebox/home?MCOW-transition=out">-->
				<div id="afteravailset" class="hidden">
				</div>
				<script type="text/javascript">
					// hours 2
					var checked8 = "";
					var checked16 = "";
					var checked24 = "";
					var checked32 = "";
					var checked40 = "";
					var checked48 = "";
					var checked56 = "";
					if (MCOW.Session.Response.param["future"]["hours"] == "8") {checked8 = "checked";}
					if (MCOW.Session.Response.param["future"]["hours"] == "16") {checked16 = "checked";}
					if (MCOW.Session.Response.param["future"]["hours"] == "24") {checked24 = "checked";}
					if (MCOW.Session.Response.param["future"]["hours"] == "32") {checked32 = "checked";}
					if (MCOW.Session.Response.param["future"]["hours"] == "40") {checked40 = "checked";}
					if (MCOW.Session.Response.param["future"]["hours"] == "48") {checked48 = "checked";}
					if (MCOW.Session.Response.param["future"]["hours"] == "56") {checked56 = "checked";}
					var html = "";
					html = html + "<fieldset data-role='controlgroup' data-type='horizontal' class='maxhoursset'>";
					html = html + "<legend>aantal uren</legend>";
					html = html + "<input type='radio' name='aftermaxhours' id='aftermaxhours-8' value='8' " + checked8 + "/>";
					html = html + "<label for='aftermaxhours-8'>8</label>";
					html = html + "<input type='radio' name='aftermaxhours' id='aftermaxhours-16' value='16' " + checked16 + "/>";
					html = html + "<label for='aftermaxhours-16'>16</label>";
					html = html + "<input type='radio' name='aftermaxhours' id='aftermaxhours-24' value='24' " + checked24 + "/>";
					html = html + "<label for='aftermaxhours-24'>24</label>";
					html = html + "<input type='radio' name='aftermaxhours' id='aftermaxhours-32' value='32' " + checked32 + "/>";
					html = html + "<label for='aftermaxhours-32'>32</label>";
					html = html + "<input type='radio' name='aftermaxhours' id='aftermaxhours-40' value='40' " + checked40 + "/>";
					html = html + "<label for='aftermaxhours-40'>40</label>";
					html = html + "<input type='radio' name='aftermaxhours' id='aftermaxhours-48' value='48' " + checked48 + "/>";
					html = html + "<label for='aftermaxhours-48'>48</label>";
					html = html + "<input type='radio' name='aftermaxhours' id='aftermaxhours-56' value='56' " + checked56 + "/>";
					html = html + "<label for='aftermaxhours-56'>56</label>";
					html = html + "</fieldset>";
					
					// days 2
					var checked_day_1 = "";
					var checked_day_2 = "";
					var checked_day_3 = "";
					var checked_day_4 = "";
					var checked_day_5 = "";
					var checked_day_6 = "";
					var checked_day_7 = "";
					if (typeof MCOW.Session.Response.param["future"]["days"] != 'undefined') {
						var dayArray = MCOW.Session.Response.param["future"]["days"].split(",");
						for (i=0; i < dayArray.length; i++) {
							if (dayArray[i] == 1) { checked_day_1 = "checked"; }
							if (dayArray[i] == 2) { checked_day_2 = "checked"; }
							if (dayArray[i] == 3) { checked_day_3 = "checked"; }
							if (dayArray[i] == 4) { checked_day_4 = "checked"; }
							if (dayArray[i] == 5) { checked_day_5 = "checked"; }
							if (dayArray[i] == 6) { checked_day_6 = "checked"; }
							if (dayArray[i] == 7) { checked_day_7 = "checked"; }
						}
					}
					html = html + "<fieldset data-role='controlgroup' data-type='horizontal' class='preferreddaysset'>";
					html = html + "<legend>bij voorkeur op</legend>";
					html = html + "<input type='checkbox' name='afterpreferreddays[]' id='afterpreferreddays-mo' value='1' " + checked_day_1 + "/>";
					html = html + "<label for='afterpreferreddays-mo'>ma</label>";
					html = html + "<input type='checkbox' name='afterpreferreddays[]' id='afterpreferreddays-tu' value='2' " + checked_day_2 + "/>";
					html = html + "<label for='afterpreferreddays-tu'>di</label>";
					html = html + "<input type='checkbox' name='afterpreferreddays[]' id='afterpreferreddays-we' value='3' " + checked_day_3 + "/>";
					html = html + "<label for='afterpreferreddays-we'>wo</label>";
					html = html + "<input type='checkbox' name='afterpreferreddays[]' id='afterpreferreddays-th' value='4' " + checked_day_4 + "/>";
					html = html + "<label for='afterpreferreddays-th'>do</label>";
					html = html + "<input type='checkbox' name='afterpreferreddays[]' id='afterpreferreddays-fr' value='5' " + checked_day_5 + "/>";
					html = html + "<label for='afterpreferreddays-fr'>vr</label>";
					html = html + "<input type='checkbox' name='afterpreferreddays[]' id='afterpreferreddays-sa' value='6' " + checked_day_6 + "/>";
					html = html + "<label for='afterpreferreddays-sa'>za</label>";
					html = html + "<input type='checkbox' name='afterpreferreddays[]' id='afterpreferreddays-su' value='7' " + checked_day_7 + "/>";
					html = html + "<label for='afterpreferreddays-su'>zo</label>";
					html = html + "</fieldset>";
					
					MCOW.Util.setHTML("afteravailset",html);
					//TIMEBOX.Lib.checkAfterAvailSet();
				</script>	
			</form>
		</div>
	</div>
</div>
