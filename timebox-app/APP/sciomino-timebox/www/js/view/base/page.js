<header id="header">
	<section>
		<h1> MCOW - MVC (2/3) </h1>
	<section>
</header>
<div id="content">
	<section>
		<p>
			MCOW features a unique MVC pattern. 
			<ul>
			<li>The <b>controller</b> handles each tap or swipe and constructs the session based on the config file. There is configuration for transitions, use of databases and session management.
			<li>The <b>model</b> handles access to the local storage and maintains conenctions to an underlying API. 
			<li>The <b>view</b> updates the index page. There is one index page and NEVER a reload of the whole page. There is no template engine. All data and session information is stored in the DOM. It's easy to use javascript to fill the view with dynamic data
			</ul>
		</p>
		<p>
			<a href="/anotherpage">next</a>
		</p>
	</section>
	<section>
		<div id="MCOW-SCRIPT-TEST"></div>
		<script type="text/javascript">
			html = "<p>";
			for (var i=0;i<10;i++) {
				html = html + "-";
			}
			html = html + "</p>";
			html = html + "<p>The above line is a js loop demo and this parameter 'one' is filled from the DOM: " + MCOW.Session.Response.param["one"] + "</p>";
			MCOW.Util.setHTML("MCOW-SCRIPT-TEST",html);
		</script>
	</section>
</div>
<aside id="sidebar">
	<section>
	</section>		
</aside>
<footer id="footer">
	<section>
		<p>
			&copy; herman
		</p>
	</section>	
</footer>
