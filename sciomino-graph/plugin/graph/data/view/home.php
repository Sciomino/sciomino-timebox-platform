<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="user-scalable=no, width=device-width, height=device-height, initial-scale=1, minimum-scale=1, maximum-scale=1, target-densityDpi=device-dpi" />
	
	<title><?php echo language('graph_title_main'); ?></title>
	
	<?php include("includes/headers.php"); ?>

	<script type="text/javascript">
		//Event.AddOnload(function() {loadNav("home",(2013));});
		Event.AddOnload(function() {Session.View.load();});
	</script>
</head>

<body>

	<div class="branded" id="Sciomino">
			<div class="page">

				<!-- <a class="logo" href="/"><img src="/gfx/graph/icon_sciomino_transp.png" alt="Sciomino"/></a>
				<span id="SciominoForText"><?php echo language('graph_text_main_subtitle'); ?></span> -->

			</div>
	</div>

	<div id="Header">
		<div class="page">

			<div class="nav">
				<div id="sessionView">
				</div>
			</div>

		</div>
	</div>

	<div class="page">

		<header id="header">

			<div id="logo">
			</div>

		</header>
		
		<div id="content">

			<section>
				<h1><?php echo count($session['response']['param']['appList']); echo " "; echo language('graph_header_main_view'); ?></h1>
				<ul class="index linklist fixpad">
					<?php
						foreach ($session['response']['param']['appList'] as $app) {
							$languageTemplate = array();
							$languageTemplate['name'] = ucfirst($app['name']);
							$appString = language_template('graph_text_main_view_'.$app['typeName'], $languageTemplate);
							if ($app['network'] != "") {
								$appString .= ", network: ".$app['network'];
							}
							# hum, not each apptype should have a status, fix when we get there :-)
							echo "<li><a href='/".$app['typeName']."/status?app=".$app['id']."'>".$appString."</a></li>";
						}
					?>
				</ul>		
			</section>

			<section>
			</section>

		</div>
		
		<aside id="sidebar">

			<section>
			</section>

		</aside>

	</div>
	
</body>
</html>
