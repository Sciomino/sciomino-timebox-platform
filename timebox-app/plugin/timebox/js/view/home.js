<script type="text/javascript">
var html = "";
html = html + "<h2>";
html = html + "Jouw beschikbaarheid";
html = html + "</h2>";
MCOW.Util.setHTML("topbar",html);
document.getElementById("topbar").classList.add("dashboardHeader");
</script>
<div id="content">
	<div id="container">
		<div class="dashboard">

			<div id="HOME-SCRIPT-CURRENT" class="currently">
			</div>
			<script type="text/javascript">
				var html = "";
				html = html + "<img id='timebox-data-background' src='images/bg.jpg' alt='' />";
				html = html + "<div class='bg'>&nbsp;</div>";
				html = html + "<div class='header'>";
				//html = html + "<a href='#' data='http://gettimeboxnow.com/about' class='mcow-touchable info-icon'>&nbsp;</a>";
				html = html + "<img id='timebox-data-avatar' src='images/noavatar.png' alt='No avatar' class='mcow-touchable opencamera' />";
				html = html + "</div>";
				html = html + "<span class='mcow-touchable openinternalpage' data='/Timebox/availability'>";
				html = html + "<h2>nu</h2>";
				var currentStatus = MCOW.Session.Response.param["current"]["status"];
				if (currentStatus == "available") { currentStatus = "beschikbaar"; }
				if (currentStatus == "unavailable") { currentStatus = "niet beschikbaar"; }
				if (currentStatus == "unknown") { currentStatus = "onbekend (stel in)"; }
				html = html + "<span>" + currentStatus + "</span>";
				if (MCOW.Session.Response.param["current"]["status"] == "available") {
					html = html + "<p>" + MCOW.Session.Response.param["current"]["hours"] + " uur &middot; " + TIMEBOX.Lib.getDaysString(MCOW.Session.Response.param["current"]["days"]);
					html = html + "</p>";
				}
				html = html + "</span>";
				MCOW.Util.setHTML("HOME-SCRIPT-CURRENT",html);
				// set class & image
				document.getElementById('HOME-SCRIPT-CURRENT').classList.add(MCOW.Session.Response.param["current"]["status"]);
				if (typeof MCOW.Session.Response.param["photoStream"] != "undefined" && MCOW.Session.Response.param["photoStream"] != "") {
					document.getElementById('timebox-data-avatar').setAttribute( 'src', 'data:image/jpeg;base64, ' + MCOW.Session.Response.param["photoStream"]);
				}
				if (typeof MCOW.Session.Response.param["background"] != "undefined" && MCOW.Session.Response.param["background"] != "") {
					document.getElementById('timebox-data-background').setAttribute( 'src', 'data:image/jpeg;base64, ' + MCOW.Session.Response.param["background"]);
				}
			</script>	

			<div id="HOME-SCRIPT-AFTER" class="after mcow-touchable openinternalpage" data="/Timebox/availability">
			</div>
			<script type="text/javascript">
				var html = "";

				var date = "deze periode";
				if (typeof MCOW.Session.Response.param["current"]["until"] != "undefined" && MCOW.Session.Response.param["current"]["until"] != "") {
					date = TIMEBOX.Lib.getDate(MCOW.Session.Response.param["current"]["until"]);
				}
				html = html + "<h2>na " + date + "</h2>";
				var futureStatus = MCOW.Session.Response.param["future"]["status"];
				if (futureStatus == "available") { futureStatus = "beschikbaar"; }
				if (futureStatus == "unavailable") { futureStatus = "niet beschikbaar"; }
				if (futureStatus == "unknown") { futureStatus = "onbekend (stel in)"; }
				html = html + "<span>" + futureStatus + "</span>";
				if (MCOW.Session.Response.param["future"]["status"] == "available") {
					html = html + "<p>" + MCOW.Session.Response.param["future"]["hours"] + " uur &middot; " + TIMEBOX.Lib.getDaysString(MCOW.Session.Response.param["future"]["days"]);
					html = html + "</p>";
				}
				MCOW.Util.setHTML("HOME-SCRIPT-AFTER",html);
				// set class & date
				document.getElementById('HOME-SCRIPT-AFTER').classList.add(MCOW.Session.Response.param["future"]["status"]);
			</script>	

			<div id="HOME-SCRIPT-SHARING" class="sharing mcow-touchable openinternalpage" data="/Timebox/share">
			</div>
			<script type="text/javascript">
				var html = "";
				html = html + "<h2>wordt real-time gedeeld met </h2>";
				html = html + "<span>";
				if (MCOW.Session.Response.param['networkCount'] == 0) {
					html = html + "0 bedrijven (kies)";
				}
				else if (MCOW.Session.Response.param['networkCount'] == 1) {
					html = html + MCOW.Session.Response.param['networkCount'];
					html = html + " bedrijf";
				}
				else {
					html = html + MCOW.Session.Response.param['networkCount'];
					html = html + " bedrijven";
				}
				html = html + "</span>";
				html = html + "<a href='#' class='mcow-touchable socialshare'>zelf delen...</a>";
				MCOW.Util.setHTML("HOME-SCRIPT-SHARING",html);
			</script>	

		</div>
	</div>
</div>
<!-- overlay -->
<script type="text/javascript">
var html = "";
		
html = html + "<div id='socialshare' class='hidden'>";
html = html + "<div>&nbsp;</div>";
html = html + "<div>";
html = html + "<h2>Delen via</h2>";
html = html + "<ol>";
html = html + "<li><a href='#' class='twitter'><span>&nbsp;</span>Twitter</a></li>";
html = html + "<li><a href='#' class='facebook'><span>&nbsp;</span>Facebook</a></li>";
html = html + "<li><a href='#' class='other'><span>&nbsp;</span>Andere app</a></li>";
html = html + "<li><a href='#' class='cancel'>Cancel</a></li>";
html = html + "</ol>";
html = html + "</div>";
html = html + "</div>";

html = html + "<div id='photoselection' class='hidden'>";
html = html + "<div>&nbsp;</div>";
html = html + "<div>";
html = html + "<h2>Foto kiezen</h2>";
html = html + "<ol>";
html = html + "<li><a href='#' class='camera'><span>&nbsp;</span>Nieuwe profielfoto</a></li>";
html = html + "<li><a href='#' class='album'><span>&nbsp;</span>Profielfoto uit album</a></li>";
html = html + "<li><a href='#' class='background'><span>&nbsp;</span>Achtergrond uit album</a></li>";
html = html + "<li><a href='#' class='cancel'>Cancel</a></li>";
html = html + "</ol>";
html = html + "</div>";
html = html + "</div>";

MCOW.Util.setHTML("overlay",html);
</script>
