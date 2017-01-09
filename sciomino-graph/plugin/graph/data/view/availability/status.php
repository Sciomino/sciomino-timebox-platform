<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="user-scalable=no, width=device-width, height=device-height, initial-scale=1, minimum-scale=1, maximum-scale=1, target-densityDpi=device-dpi" />
	
	<title><?php echo language('graph_stats_title_status'); ?></title>
	
	<?php include("includes/headers.php"); ?>

	<script type="text/javascript">
		function reloadPageWithWeek() {
			var e = document.getElementById("weekOffset");
			var weekNr = e.options[e.selectedIndex].value;
			
			window.location.assign("/availability/status?app=<?php echo $session['response']['param']['app']?>&weekOffset=" + weekNr)
		}
		Event.AddOnload(function() {Session.View.load();});
	</script>

	<!-- google jsapi -->
	<script type='text/javascript' src='https://www.google.com/jsapi'></script>

</head>
<body>

	<div class="branded" id="Sciomino">
			<div class="page">
			<!--
			<a class="logo" href="/"><img src="/gfx/graph/logo_sciomino_transp.png" alt="Sciomino"/></a>
			<span id="SciominoForText"><?php echo language('graph_text_main_subtitle'); ?></span>
			-->
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

	<header id="header-top">

		<div style="float:right">
			<img src="/gfx/graph/logo_timebox.png" height="40px">
		</div>
		<div id="logo">
			<hgroup>
				<?php
					$languageTemplate = array();
					$languageTemplate['name'] = ucfirst($session['response']['param']['appName']);
					$appString = language_template('graph_text_main_view_'.$session['response']['param']['appType']['typeName'], $languageTemplate);
					#echo "<h1>".$appString."</h1>";
					echo "<h1>".$languageTemplate['name']."</h1>";
				?>
			</hgroup>
			<p class="fixpad">
				<?php 
					echo language('graph_word_today').": ".date("j ", $session['response']['param']['today']).language('graph_word_month_'.date("n", $session['response']['param']['today']))."<br/>";
					echo language('graph_availability_text_status')." ".date("j ", $session['response']['param']['availabilityDataTimestamp']).language('graph_word_month_'.date("n", $session['response']['param']['availabilityDataTimestamp'])).date(" H:i", $session['response']['param']['availabilityDataTimestamp']);
					echo ", ".$session['response']['param']['userCount']; echo " "; echo language('graph_stats_text_users_small');
					echo " (<a href='/availability/status?app=".$session['response']['param']['app']."&update=1'>update now</a>)" 
				?>
			</p>
		</div>

		<nav id="main-nav-top">
		</nav>

	</header>

	<div id="content-bottom">

		<div class="tabs">
			
		   <div class="tab">
				   
			   <div class="tab-label">
				   <nobr><?php echo language('graph_availability_text_tab1'); ?></nobr>
				</div>
				
			   <div class="tab-content">
					
					<section>
						<script type='text/javascript'>
						  google.load('visualization', '1.0', {'packages':['controls']});
						  google.setOnLoadCallback(drawDashboard2);
						  
						  function drawDashboard2() {
							  
							var data = new google.visualization.DataTable();
							data.addColumn('string', 'naam');
							data.addColumn('number', 'update');
							
							<?php
								echo "data.addColumn('number', 'week ".$session['response']['param']['calendarWeek']."');";
								echo "data.addColumn('string', 'ma ".$session['response']['param']['calendarDay'][1]."');";
								echo "data.addColumn('string', 'di ".$session['response']['param']['calendarDay'][2]."');";
								echo "data.addColumn('string', 'wo ".$session['response']['param']['calendarDay'][3]."');";
								echo "data.addColumn('string', 'do ".$session['response']['param']['calendarDay'][4]."');";
								echo "data.addColumn('string', 'vr ".$session['response']['param']['calendarDay'][5]."');";
								echo "data.addColumn('string', 'za ".$session['response']['param']['calendarDay'][6]."');";
								echo "data.addColumn('string', 'zo ".$session['response']['param']['calendarDay'][7]."');";
								echo "data.addColumn('string', 'e-mailadres');";
								echo "data.addRows([";
								
									$data = json_decode($session['response']['param']['availabilityData']);
																		
									foreach ($data->content->user as $user) {
										// hum, availability info does not exist? should fix this in the api?
										if (! is_array($user->availability)) {
											$user->availability = array();
											$user->availability[0] = (object) array();
											$user->availability[0]->{'status'} = "";
											$user->availability[0]->{'days'} = "";
											$user->availability[0]->{'hours'} = 0;
											$user->availability[0]->{'until'} = "";
											$user->availability[0]->{'future-status'} = "";
											$user->availability[0]->{'future-days'} = "";
											$user->availability[0]->{'future-hours'} = 0;
											$user->availability[0]->{'timestamp'} = 0;
										}
										// show current OR future availability
										// - depending on until setting
										$days = explode(',', $user->availability[0]->days);
										$hours = $user->availability[0]->hours;
										$curStatus = $user->availability[0]->status; 
										if (!($curStatus == "" || $curStatus == "unknown") && $user->availability[0]->until < $session['response']['param']['calendarMonday']) {
											$days = explode(',', $user->availability[0]->{'future-days'});
											$hours = $user->availability[0]->{'future-hours'};
											$curStatus = $user->availability[0]->{'future-status'}; 
										}
										
										echo "[";
										echo "'<nobr>".$user->firstname." ".$user->lastname."</nobr>',";
										if ($user->availability[0]->{'timestamp'} == 0) {
											echo "{v:".$user->availability[0]->{'timestamp'}.",f:'".language('graph_word_unknown')."'},";
										}
										else {
											echo "{v:".$user->availability[0]->{'timestamp'}.",f:'<nobr>".date("j ", $user->availability[0]->{'timestamp'}).language('graph_word_month_'.date("n", $user->availability[0]->{'timestamp'})).date(" H:i", $user->availability[0]->{'timestamp'})."</nobr>'},";
										}
										if ($hours > 0) {
											echo "{v:".$hours.",f:'<nobr>".$hours." ".language('graph_word_hours')."</nobr>',p:{className: 'tableCellTrue'}},";
										}
										else {
											if ($curStatus == "unavailable") {
												echo "{v:0,f:'".language('graph_word_not')."',p:{className: 'tableCellFalse'}},";
											}
											else {
												echo "{v:0,f:'".language('graph_word_unknown')."',p:{className: 'tableCellUnknown'}},";
											}
										}
										for ($i=1;$i<=7;$i++) {
											if (in_array($i, $days)) {
												echo "{v:'v',p:{className: 'tableCellTrueCenter'}},";
											}
											else {
												if ($hours > 0) {
													echo "{v:'-',p:{className: 'tableCellUnknownCenter'}},";
												}
												else {
													if ($curStatus == "unavailable") {
														echo "{v:'x',p:{className: 'tableCellFalseCenter'}},";
													}
													else {
														echo "{v:'-',p:{className: 'tableCellUnknownCenter'}},";
													}												
												}
											}
										}
										echo "'<a href=\"mailto:".$user->id."\">".$user->id."</a>',";
										echo "],";
									}
									
								echo "]);";
							?>

							var dashboard = new google.visualization.Dashboard(document.getElementById('cal_table_dashboard'));
							
							// Create a range filter, passing some options
							var filter = new google.visualization.ControlWrapper({
							  'controlType': 'StringFilter',
							  'containerId': 'cal_table_filter',
							  'options': {
								'filterColumnIndex':0, 'matchType':'any', 'ui':{ 'label':'' }
							  }
							});

							var table = new google.visualization.ChartWrapper({
								'chartType': 'Table',
								'containerId': 'cal_table',
								'options': {
									'showRowNumber':false, 'sort':'enable', 'sortAscending':false, 'sortColumn':1, 'page':'enable', 'pageSize':50, 'allowHtml':true
								}
						    });			

							// hum, cannot directy access page event...
							google.visualization.events.addListener(table, 'ready', onReady);
							function onReady(e) {
								google.visualization.events.addListener(table.getChart(), 'page', pageHandler);
							}
							function pageHandler(e) {
								//alert("go to page: " + e['page']);
								//window.scrollTo(0, 0);
							}
						    				
						    dashboard.bind(filter, table);
						    dashboard.draw(data);
						  }
						</script>

						<div id="cal_table_dashboard">
							<div id="cal_table_export" style="float:right">
								<p><br/>
									<a download="availability.csv" href="#" onclick="return ExcellentExport.csv(this, document.getElementById('cal_table').getElementsByTagName('TABLE')[0]);"><?php echo language('graph_availability_text_table_export'); ?></a>
								</p>
							</div>
						
							<div id="cal_table_header_left" style="float:left; padding:10px;padding-left:0px;">
								<?php echo language('graph_availability_text_search_name'); ?>
								<div id="cal_table_filter">
								</div>
							</div>
							<div id="cal_table_header_rightt" style="float:left; padding:10px;">
								<?php echo language('graph_availability_text_search_week'); ?>
								<div>
									<?php
										echo "<select id='weekOffset' size='1' onchange='javascript:reloadPageWithWeek()'>";
										foreach ($session['response']['param']['calendarNext'] as $next) {
											$selected = "";
											if ($session['response']['param']['calendarWeekoffset'] == $next['offset']) {
												$selected = "selected";
											}
											echo "<option value='".$next['offset']."' ".$selected.">Week ".$next['week'].", ".language('graph_availability_text_show_week')." ".$next['day']."/".$next['month']."</option>";
										}
										echo "</select>";
									?>
								</div>
							</div>
							<br clear="all"/><br/>
							<div id="cal_table">
							</div>

						</div>
					</section>
					
			   </div> 
		   </div>
			
		   <div class="tab">

			   <div class="tab-label">
				   <nobr><?php echo language('graph_availability_text_tab2'); ?></nobr>
				</div>
			   
			   <div class="tab-content">
				   
					<section>
						<script type='text/javascript'>
						  google.load('visualization', '1.0', {'packages':['controls']});
						  google.setOnLoadCallback(drawDashboard);
						  
						  function drawDashboard() {
							  
							var data = new google.visualization.DataTable();
							data.addColumn('string', 'naam');
							data.addColumn('number', 'update');
							data.addColumn('number', 'nu');
							data.addColumn('string', 'ma');
							data.addColumn('string', 'di');
							data.addColumn('string', 'wo');
							data.addColumn('string', 'do');
							data.addColumn('string', 'vr');
							data.addColumn('string', 'za');
							data.addColumn('string', 'zo');
							data.addColumn('number', 't/m');
							data.addColumn('number', 'daarna');
							data.addColumn('string', 'ma');
							data.addColumn('string', 'di');
							data.addColumn('string', 'wo');
							data.addColumn('string', 'do');
							data.addColumn('string', 'vr');
							data.addColumn('string', 'za');
							data.addColumn('string', 'zo');
							data.addColumn('string', 'e-mailadres');
							data.addRows([
								<?php
									$data = json_decode($session['response']['param']['availabilityData']);					
									foreach ($data->content->user as $user) {
										// hum, availability info does not exist? should fix this in the api?
										if (! is_array($user->availability)) {
											$user->availability = array();
											$user->availability[0] = (object) array();
											$user->availability[0]->{'status'} = "";
											$user->availability[0]->{'days'} = "";
											$user->availability[0]->{'hours'} = 0;
											$user->availability[0]->{'until'} = "";
											$user->availability[0]->{'future-status'} = "";
											$user->availability[0]->{'future-days'} = "";
											$user->availability[0]->{'future-hours'} = 0;
											$user->availability[0]->{'timestamp'} = 0;
										}
										// do somthing with day numbers
										$days = explode(',', $user->availability[0]->days);
										$fdays = explode(',', $user->availability[0]->{'future-days'});
										
										echo "[";
										echo "'<nobr>".$user->firstname." ".$user->lastname."</nobr>',";
										if ($user->availability[0]->{'timestamp'} == 0) {
											echo "{v:".$user->availability[0]->{'timestamp'}.",f:'".language('graph_word_unknown')."'},";
										}
										else {
											echo "{v:".$user->availability[0]->{'timestamp'}.",f:'<nobr>".date("j ", $user->availability[0]->{'timestamp'}).language('graph_word_month_'.date("n", $user->availability[0]->{'timestamp'})).date(" H:i", $user->availability[0]->{'timestamp'})."</nobr>'},";
										}
										if ($user->availability[0]->hours > 0) {
											echo "{v:".$user->availability[0]->hours.",f:'<nobr>".$user->availability[0]->hours." ".language('graph_word_hours')."</nobr>',p:{className: 'tableCellTrue'}},";
										}
										else {
											if ($user->availability[0]->status == "unavailable") {
												echo "{v:0,f:'".language('graph_word_not')."',p:{className: 'tableCellFalse'}},";
											}
											else {
												echo "{v:0,f:'".language('graph_word_unknown')."',p:{className: 'tableCellUnknown'}},";
											}											
										}
										for ($i=1;$i<=7;$i++) {
											if (in_array($i, $days)) {
												echo "{v:'v',p:{className: 'tableCellTrueCenter'}},";
											}
											else {
												if ($user->availability[0]->hours > 0) {
													echo "{v:'-',p:{className: 'tableCellUnknownCenter'}},";
												}
												else {
													if ($user->availability[0]->status == "unavailable") {
														echo "{v:'x',p:{className: 'tableCellFalseCenter'}},";
													}
													else {
														echo "{v:'-',p:{className: 'tableCellUnknownCenter'}},";
													}
												}
											}
										}
										if (is_array($user->availability) && !($user->availability[0]->status == "" || $user->availability[0]->status == "unknown")) {
											echo "{v:".($user->availability[0]->until + 0).",f:'<nobr>".date("j ", $user->availability[0]->until+0).language('graph_word_month_'.date("n", $user->availability[0]->until+0))."</nobr>'},";
										}
										else {
											echo "{v:0,f:'".language('graph_word_unknown')."'},";
										}
										if ($user->availability[0]->{'future-hours'} > 0) {
											echo "{v:".$user->availability[0]->{'future-hours'}.",f:'<nobr>".$user->availability[0]->{'future-hours'}." ".language('graph_word_hours')."</nobr>',p:{className: 'tableCellTrue'}},";
										}
										else {
											if ($user->availability[0]->{'future-status'} == "unavailable") {
												echo "{v:0,f:'".language('graph_word_not')."',p:{className: 'tableCellFalse'}},";
											}
											else {
												echo "{v:0,f:'".language('graph_word_unknown')."',p:{className: 'tableCellUnknown'}},";
											}											
										}
										for ($i=1;$i<=7;$i++) {
											if (in_array($i, $fdays)) {
												echo "{v:'v',p:{className: 'tableCellTrueCenter'}},";
											}
											else {
												if ($user->availability[0]->{'future-hours'} > 0) {
													echo "{v:'-',p:{className: 'tableCellUnknownCenter'}},";
												}
												else {
													if ($user->availability[0]->{'future-status'} == "unavailable") {
														echo "{v:'x',p:{className: 'tableCellFalseCenter'}},";
													}
													else {
														echo "{v:'-',p:{className: 'tableCellUnknownCenter'}},";
													}
												}
											}
										}
										echo "'<a href=\"mailto:".$user->id."\">".$user->id."</a>',";
										echo "],";
									}
								?>
							]);

							var dashboard = new google.visualization.Dashboard(document.getElementById('user_table_dashboard'));
							
							// Create a range filter, passing some options
							var filter = new google.visualization.ControlWrapper({
							  'controlType': 'StringFilter',
							  'containerId': 'user_table_filter',
							  'options': {
								'filterColumnIndex':0, 'matchType':'any', 'ui':{ 'label':'' }
							  }
							});

							var table = new google.visualization.ChartWrapper({
								'chartType': 'Table',
								'containerId': 'user_table',
								'options': {
									'showRowNumber':false, 'sort':'enable', 'sortAscending':false, 'sortColumn':1, 'page':'enable', 'pageSize':50, 'allowHtml':true
								}
						    });			

							// hum, cannot directy access page event...
							google.visualization.events.addListener(table, 'ready', onReady);
							function onReady(e) {
								google.visualization.events.addListener(table.getChart(), 'page', pageHandler);
							}
							function pageHandler(e) {
								//alert("go to page: " + e['page']);
								//window.scrollTo(0, 0);
							}
						    				
						    dashboard.bind(filter, table);
						    dashboard.draw(data);
						  }
						</script>

						<div id="user_table_dashboard">
							<div id="user_table_export" style="float:right">
								<p><br/>
									<a download="availability.csv" href="#" onclick="return ExcellentExport.csv(this, document.getElementById('user_table').getElementsByTagName('TABLE')[0]);"><?php echo language('graph_availability_text_table_export'); ?></a>
								</p>
							</div>

							<div id="user_table_header_left" style="float:left; padding:10px;padding-left:0px;">
								<?php echo language('graph_availability_text_search_name'); ?>
								<div id="user_table_filter">
								</div>
							</div>
							<br clear="all"/><br/>
							<div id="user_table">
							</div>
						</div>
					</section>
					
			   </div> 
		   </div>
			
		</div>		
		
	</div>
	
	<aside id="sidebar">

		<section>
		</section>

	</aside>
	
	</div>

</body>
</html>
