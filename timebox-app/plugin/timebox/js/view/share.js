<script type="text/javascript">
var html = "";
html = html + "<h2>";
html = html + "<a href='#' data='/Timebox/home?MCOW-transition=out' class='backbtn mcow-touchable openinternalpage'>&lt;</a>";
html = html + "Bedrijven kiezen";
html = html + "</h2>";
MCOW.Util.setHTML("topbar",html);
document.getElementById("topbar").classList.add("sharingsettingsHeader");
</script>
<div id="content">
	<div id="container">
		<div class="sharingsettings">
			<!--<div id="companiessection" class="mcow-touchable leavesharepagebyswipe" data="/Timebox/home?MCOW-transition=out">-->
			<div id="companiessection">
				<p>Deel mijn beschikbaarheid met:</p>				
				
				<ol id="SHARE-SCRIPT-NETWORKS">
				</ol>
				<script type="text/javascript">
					var html = "";
					for (var i=0;i<MCOW.Session.Response.param["networks"].length;i++) {
						if (MCOW.Session.Response.param["networks"][i]["type"] == "availability-customer") {
							var checked1 = "";
							var checked2 = "checked";
							if (MCOW.Session.Response.param["networks"][i]["share"] == 1) {
								checked1 = "checked";
								checked2 = "";
							}
							var j = i+1;
							html = html + "<li>";
							html = html + "<div><img id='network-data-image-1-" + i + "' src='images/nonetwork.png' /><h3>" + MCOW.Session.Response.param["networks"][i]["name"] + "</h3></div>";
							html = html + "<fieldset data-role='controlgroup' data-type='horizontal'>";
							html = html + "<input type='radio' name='sharecompany-"+j+"' id='sharecompany-"+j+"-1' value='1' " + checked1 + "/>";
							html = html + "<label class='mcow-touchable sharecompany' data=" + MCOW.Session.Response.param["networks"][i]["id"] + " selection='1' for='sharecompany-"+j+"-1'>Yes</label>";
							html = html + "<input type='radio' name='sharecompany-"+j+"' id='sharecompany-"+j+"-0' value='0' " + checked2 + "/>";
							html = html + "<label class='mcow-touchable sharecompany' data=" + MCOW.Session.Response.param["networks"][i]["id"] + " selection='0' for='sharecompany-"+j+"-0'>No</label>";
							html = html + "</fieldset>";
							html = html + "</li>";
						}
					}
					MCOW.Util.setHTML("SHARE-SCRIPT-NETWORKS",html);
					for (var i=0;i<MCOW.Session.Response.param["networks"].length;i++) {
						if (MCOW.Session.Response.param["networks"][i]["type"] == "availability-customer") {
							if (typeof MCOW.Session.Response.param["networks"][i]["photoStream"] != "undefined" && MCOW.Session.Response.param["networks"][i]["photoStream"] != "") {
								document.getElementById('network-data-image-1-'+i).setAttribute( 'src', 'data:image/png;base64, ' + MCOW.Session.Response.param["networks"][i]["photoStream"]);
							}
						}
					}		
				</script>	

				<p id="SHARE-SCRIPT-EMAIL" class="footer footerborderr"></p>
				<script type="text/javascript">
					var html = "";
					html = html + "Zorg dat je met het e-mailadres dat je voor TimeBox gebruikt (" + MCOW.Session.Response.param["email"] + ") bekend bent bij de geselecteerde bedrijven.";
					MCOW.Util.setHTML("SHARE-SCRIPT-EMAIL",html);
				</script>

				<p class="footer">We benaderen elke dag nieuwe bedrijven om deel te nemen. Ken je een bedrijf dat ook gebruik zou moeten maken van TimeBox, of heb je andere feedback? <a href="#" class="shareMail">Laat het ons weten!</a></p>
				
				<p><a href="#" data='http://timebox.nu/legal.html' class='mcow-touchable openexternallink'>Voorwaarden en privacyverklaring</a></p>
			</div>
		</div>
	</div>
</div>
