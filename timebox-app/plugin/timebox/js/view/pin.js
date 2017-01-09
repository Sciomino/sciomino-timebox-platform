<div id="content">
	<div id="container">
		<div class="register" id="register">
			<h1>Je hebt mail!</h1>
			<p>We hebben een PIN code gestuurd<br />naar dit e-mailadres:</p>
			<span id="PIN-SCRIPT-EMAIL">
			</span>
			<script type="text/javascript">
				var html = "";
				html = html + MCOW.Session.Response.param["email"];
				MCOW.Util.setHTML("PIN-SCRIPT-EMAIL",html);
			</script>	
			<div id="PIN-SCRIPT-CODE">
			</div>
			<script type="text/javascript">
				// new code from parameter
				var code = "";
				var label = "PIN code";
				if (typeof MCOW.Session.Response.param["code"] != "undefined" && MCOW.Session.Response.param["code"] != "") {
					code = MCOW.Session.Response.param["code"];
					label = "";
				}
				var html = "";
				html = html + "<p>&nbsp;</p>";
				html = html + "<form name='pinForm' onsubmit='if (MCOW.Config[\"target\"] == \"phonegap\") { cordova.plugins.Keyboard.close(); }; return false'>";
				html = html + "<input type='tel' name='registerpin' id='registerpin' value='" + code + "' />";
				html = html + "<label for='registerpin'>" + label + "</label>";
				html = html + "<button type='button' id='registerbutton' name='registerbutton'>Log in</button>";
				html = html + "</form>";
				MCOW.Util.setHTML("PIN-SCRIPT-CODE",html);
			</script>	
			<p class="footer">Wanneer je inlogt, ga je akkoord met de <br /> <a href="#" data='http://timebox.nu/legal.html' class='mcow-touchable openexternallink'>voorwaarden en privacyverklaring</a></p>
			<p class="footer"><a href='#' data='/Timebox/home' class='mcow-touchable wrongemailaddresstryagain'>Verkeerd e-mailadres?</a></p>
		</div>
		<div id="loader" class="hidden">
			<div>&nbsp;</div>
			<div>
				<img src="images/ajax-loader.gif" alt="Loading" />
				<p>Valideren PIN code</p>
			</div>
		</div>
		<script type="text/javascript">
			if (typeof MCOW.Session.Response.param["error"] != "undefined" && MCOW.Session.Response.param["error"] != "") {
				$("#register > div > p").addClass("error");
				$("#register > div > p").html(MCOW.Session.Response.param["error"]);
			}
		</script>	
	</div>
</div>
