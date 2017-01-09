<div id="content">
	<div id="container">
		<h1 class="logo"><span>Time</span>Box</h1>
		<h2 class="logo">by Sciomino</h2>
		<div id="walkthroughcontainer" class="walkthrough">
			<span>&nbsp;</span>
			<div class="slideone selected"><p>Hou je beschikbaarheid<br />up to date</p></div>
			<div class="slidetwo"><p>Wij delen het real-time<br/>met bedrijven die jij selecteert</p></div>
			<div class="slidethree"><p>Deel het op social media<br />voor extra aandacht</p></div>
			<ol>
				<li><a href="#" class="slideone selected">Slide 1</a></li>
				<li><a href="#" class="slidetwo">Slide 2</a></li>
				<li><a href="#" class="slidethree">Slide 3</a></li>
			</ol>
			<div>
				<form name="emailForm" onsubmit="if (MCOW.Config['target'] == 'phonegap') { cordova.plugins.Keyboard.close(); }; return false">
				<label for="walkthroughemail">e-mailadres</label>
				<input type="email" name="walkthroughemail" class="error" id="walkthroughemail" value="" />
				<button type="button" id="walkthroughbutton" name="walkthroughbutton">Go!</button>
				</form>
			</div>
		</div>
		<div id="loader" class="hidden">
			<div>&nbsp;</div>
			<div>
				<img src="images/ajax-loader.gif" alt="Loading" />
				<p>Valideren e-mailadres</p>
			</div>
		</div>
		<script type="text/javascript">
			if (typeof MCOW.Session.Response.param["error"] != "undefined" && MCOW.Session.Response.param["error"] != "") {
				$("#walkthroughcontainer > div > p").addClass("error");
				$("#walkthroughcontainer > div > p").html(MCOW.Session.Response.param["error"]);
			}
		</script>
	</div>
</div>
