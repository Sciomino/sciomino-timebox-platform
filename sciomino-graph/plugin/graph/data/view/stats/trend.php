<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="user-scalable=no, width=device-width, height=device-height, initial-scale=1, minimum-scale=1, maximum-scale=1, target-densityDpi=device-dpi" />
	
	<title><?php echo language('graph_stats_title_trend'); ?></title>
	
	<?php include("includes/headers.php"); ?>

	<script type="text/javascript">
		Event.AddOnload(function() {loadNav(<?php echo $session['response']['param']['app']; ?>, Array(<?php echo $session['response']['param']['appType']['year']; ?>, <?php echo $session['response']['param']['today']['year']; ?>, <?php echo $session['response']['param']['year']; ?>, <?php echo $session['response']['param']['appType']['month']; ?>, <?php echo $session['response']['param']['today']['mon']; ?>, <?php echo $session['response']['param']['month']; ?>));});
		Event.AddOnload(function() {Session.View.load();});
	</script>

	<!-- google jsapi -->
	<script type='text/javascript' src='https://www.google.com/jsapi'></script>
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

			<?php
				if ($session['response']['param']['appCount'] > 1) {
					echo "<div style='float:left;'><a href='/' style='color:white;'>".language('graph_text_main_back')."</a></div>";
				}
			?>

			<div class="nav">
				<div id="sessionView">
				</div>
			</div>

		</div>
	</div>

	<div class="page">

	<header id="header">

		<div id="logo">
			<img src="/gfx/graph/logo_sciomino.png" alt="Sciomino" style="height:50px"/>

			<hgroup>
				<?php
					$languageTemplate = array();
					$languageTemplate['name'] = ucfirst($session['response']['param']['appName']);
					$appString = language_template('graph_text_main_view_'.$session['response']['param']['appType']['typeName'], $languageTemplate);
					echo "<h1>".$appString."</h1>";
				?>
			</hgroup>
		</div>

		<nav id="main-nav-top">
		</nav>

	</header>
	
	<div id="content">

		<section>
			<h1><a href="/stats/status?app=<?php echo $session['response']['param']['app']; ?>&year=<?php echo $session['response']['param']['year']; ?>&month=<?php echo $session['response']['param']['month']; ?>"><?php echo language('graph_word_month_'.$session['response']['param']['month']); ?> <?php echo $session['response']['param']['year']; ?></a> &gt; <?php echo language('graph_stats_header_trend'); ?></h1>
		</section>

		<section>
			<div id="next">
				<?php 
				if ($session['response']['param']['nextYear'] != 0) {
					echo "<a href='/stats/trend?app=".$session['response']['param']['app']."&year=".$session['response']['param']['nextYear']."&month=".$session['response']['param']['nextMonth']."&trend=".$session['response']['param']['trend']."'>next month</a> ";
				}
				?>
			</div>
			<div id="prev">
				<?php 
				if ($session['response']['param']['prevYear'] != 0) {
					echo "<a href='/stats/trend?app=".$session['response']['param']['app']."&year=".$session['response']['param']['prevYear']."&month=".$session['response']['param']['prevMonth']."&trend=".$session['response']['param']['trend']."'>previous month</a> ";
				}
				?>
			</div>
			<br clear="both">
		</section>

		<section>
			<h1><?php echo $session['response']['param']['trendGroup']; ?></h1>
		</section>

		<?php
			foreach ($session['response']['param']['trendList'] as $trend) {
				echo "<section>";
				echo "<h2 class='fixpad'>".language('graph_stats_text_trend_'.lcfirst($trend))."</h2>";
				
				echo "<script type='text/javascript'>";
				echo "google.load('visualization', '1', {packages:['corechart']});";
				echo "google.setOnLoadCallback(drawChart);";
					  
				echo "function drawChart() {";
				echo "var data = google.visualization.arrayToDataTable([";
				echo "['Day', '".language('graph_stats_text_trend_legend_'.lcfirst($trend))."'],";
				if (isset($session['response']['param']['statsList'][0][$trend])) {
					echo "['1',".$session['response']['param']['statsList'][0][$trend]."],";
				}
				if (isset($session['response']['param']['statsList'][1][$trend])) {
					echo "['4',".$session['response']['param']['statsList'][1][$trend]."],";
				}
				if (isset($session['response']['param']['statsList'][2][$trend])) {
					echo "['7',".$session['response']['param']['statsList'][2][$trend]."],";
				}
				if (isset($session['response']['param']['statsList'][3][$trend])) {
					echo "['10',".$session['response']['param']['statsList'][3][$trend]."],";
				}
				if (isset($session['response']['param']['statsList'][4][$trend])) {
					echo "['13',".$session['response']['param']['statsList'][4][$trend]."],";
				}
				if (isset($session['response']['param']['statsList'][5][$trend])) {
					echo "['16',".$session['response']['param']['statsList'][5][$trend]."],";
				}
				if (isset($session['response']['param']['statsList'][6][$trend])) {
					echo "['19',".$session['response']['param']['statsList'][6][$trend]."],";
				}
				if (isset($session['response']['param']['statsList'][7][$trend])) {
					echo "['22',".$session['response']['param']['statsList'][7][$trend]."],";
				}
				if (isset($session['response']['param']['statsList'][8][$trend])) {
					echo "['25',".$session['response']['param']['statsList'][8][$trend]."],";
				}
				if (isset($session['response']['param']['statsList'][9][$trend])) {
					echo "['28',".$session['response']['param']['statsList'][9][$trend]."],";
				}
				echo "]);";

				echo "var options = {";
				echo "pointSize: 5,";
				echo "legend: {position: 'none'},";
				echo "hAxis: {title: '".language('graph_word_month_'.$session['response']['param']['month'])."'},";
				#echo "vAxis: {format: '#,#'},";
				echo "};";

				echo "var chart = new google.visualization.AreaChart(document.getElementById('trend_".$trend."_chart'));";
				echo "chart.draw(data, options);";
				echo "}";
				echo "</script>";

				echo "<div id='trend_".$trend."_chart'>";
				echo "</div>";
				echo "</section>\n";
			}
		?>
		
	</div>
	
	<aside id="sidebar">

		<section>
			<h1><?php if ( isset($session['response']['param']['statsList'][0]['UserCount']) ) { echo $session['response']['param']['statsList'][0]['UserCount']; } else { echo 0; } ?> <?php echo language('graph_stats_text_users'); ?></h1>
			<p class="fixpad"><?php echo language('graph_word_on'); ?> <?php echo language('graph_word_month_'.$session['response']['param']['month']); ?> 1, <?php echo $session['response']['param']['year']; ?></p>
		</section>

	</aside>

	<nav id="main-nav-bottom">
	</nav>
	
	</div>
	
</div>

</body>
</html>
