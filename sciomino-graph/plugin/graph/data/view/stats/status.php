<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="user-scalable=no, width=device-width, height=device-height, initial-scale=1, minimum-scale=1, maximum-scale=1, target-densityDpi=device-dpi" />
	
	<title><?php echo language('graph_stats_title_status'); ?></title>
	
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
			<h1><?php echo language('graph_word_month_'.$session['response']['param']['month']); ?> <?php echo $session['response']['param']['year']; ?> <?php echo language('graph_stats_header_status'); ?></h1>
		</section>

		<section>
			<div id="next">
				<?php 
				if ($session['response']['param']['nextYear'] != 0) {
					echo "<a href='/stats/status?app=".$session['response']['param']['app']."&year=".$session['response']['param']['nextYear']."&month=".$session['response']['param']['nextMonth']."'>next month</a> ";
				}
				?>
			</div>
			<div id="prev">
				<?php 
				if ($session['response']['param']['prevYear'] != 0) {
					echo "<a href='/stats/status?app=".$session['response']['param']['app']."&year=".$session['response']['param']['prevYear']."&month=".$session['response']['param']['prevMonth']."'>previous month</a> ";
				}
				?>
			</div>
			<br clear="both">
		</section>
		
		<section>
			<p class="fixpad"><?php echo language('graph_stats_text_status'); ?></p>
		</section>

		<section>
			<h1><?php echo language('graph_stats_header_status_people'); ?></h1>
			<script type='text/javascript'>
			  google.load('visualization', '1', {packages:['table']});
			  google.setOnLoadCallback(drawTable);
			  
			  function drawTable() {
				var data = new google.visualization.DataTable();
				data.addColumn('string', '');
				data.addColumn('number', '1');
				data.addColumn('number', '8');
				data.addColumn('number', '15');
				data.addColumn('number', '22');
				data.addColumn('number', '29');
				data.addRows([
				  ['<?php echo language('graph_stats_text_status_userCount'); ?>', 
					<?php echo $session['response']['param']['statsList'][0]['UserCount']; ?>,
					<?php echo $session['response']['param']['statsList'][1]['UserCount']; ?>,
					<?php echo $session['response']['param']['statsList'][2]['UserCount']; ?>,
					<?php echo $session['response']['param']['statsList'][3]['UserCount']; ?>,
					<?php echo $session['response']['param']['statsList'][4]['UserCount']; ?>,
				  ],
				  ['<?php echo language('graph_stats_text_status_userKnowledgeCount'); ?>', 
					<?php echo $session['response']['param']['statsList'][0]['UserKnowledgeCount']; ?>,
					<?php echo $session['response']['param']['statsList'][1]['UserKnowledgeCount']; ?>,
					<?php echo $session['response']['param']['statsList'][2]['UserKnowledgeCount']; ?>,
					<?php echo $session['response']['param']['statsList'][3]['UserKnowledgeCount']; ?>,
					<?php echo $session['response']['param']['statsList'][4]['UserKnowledgeCount']; ?>,
				  ],
				  ['<?php echo language('graph_stats_text_status_userHobbyCount'); ?>', 
					<?php echo $session['response']['param']['statsList'][0]['UserHobbyCount']; ?>,
					<?php echo $session['response']['param']['statsList'][1]['UserHobbyCount']; ?>,
					<?php echo $session['response']['param']['statsList'][2]['UserHobbyCount']; ?>,
					<?php echo $session['response']['param']['statsList'][3]['UserHobbyCount']; ?>,
					<?php echo $session['response']['param']['statsList'][4]['UserHobbyCount']; ?>,
				  ],
				  ['<?php echo language('graph_stats_text_status_userTagCount'); ?>', 
					<?php echo $session['response']['param']['statsList'][0]['UserTagCount']; ?>,
					<?php echo $session['response']['param']['statsList'][1]['UserTagCount']; ?>,
					<?php echo $session['response']['param']['statsList'][2]['UserTagCount']; ?>,
					<?php echo $session['response']['param']['statsList'][3]['UserTagCount']; ?>,
					<?php echo $session['response']['param']['statsList'][4]['UserTagCount']; ?>,
				  ],
				  ['<?php echo language('graph_stats_text_status_userExperienceCount'); ?>', 
					<?php echo $session['response']['param']['statsList'][0]['UserExperienceCount']; ?>,
					<?php echo $session['response']['param']['statsList'][1]['UserExperienceCount']; ?>,
					<?php echo $session['response']['param']['statsList'][2]['UserExperienceCount']; ?>,
					<?php echo $session['response']['param']['statsList'][3]['UserExperienceCount']; ?>,
					<?php echo $session['response']['param']['statsList'][4]['UserExperienceCount']; ?>,
				  ],
				  ['<?php echo language('graph_stats_text_status_userPublicationCount'); ?>', 
					<?php echo $session['response']['param']['statsList'][0]['UserPublicationCount']; ?>,
					<?php echo $session['response']['param']['statsList'][1]['UserPublicationCount']; ?>,
					<?php echo $session['response']['param']['statsList'][2]['UserPublicationCount']; ?>,
					<?php echo $session['response']['param']['statsList'][3]['UserPublicationCount']; ?>,
					<?php echo $session['response']['param']['statsList'][4]['UserPublicationCount']; ?>,
				  ],
				]);
				
				var table = new google.visualization.Table(document.getElementById('user_table'));
				google.visualization.events.addListener(table, 'select', selectHandler);

				function selectHandler(e) {
					var selection = table.getSelection();
					   
					var row = 0;
					for (var i = 0; i < selection.length; i++) {
						  var item = selection[i];
						  
						  if (item.row != null) {
							  //row = data.getFormattedValue(item.row,0);
							  row = item.row;
						  }
					}

				    var goList = ['UserCount','UserKnowledgeCount','UserHobbyCount','UserTagCount','UserExperienceCount','UserPublicationCount'];
						  
					//alert('You selected ' + goList[row]);
					window.location.href = "/stats/trend?app=" + <?php echo $session['response']['param']['app']; ?> + "&year=" + <?php echo $session['response']['param']['year']; ?> + "&month=" + <?php echo $session['response']['param']['month']; ?> + "&trend=" + goList[row];
				}

				table.draw(data, {showRowNumber: true});
			  }
			</script>

			<div id="user_table">
			</div>
		</section>

		<section>
			<h1><?php echo language('graph_stats_header_status_knowledge'); ?></h1>
			<script type='text/javascript'>
			  google.load('visualization', '1', {packages:['table']});
			  google.setOnLoadCallback(drawTable);
			  function drawTable() {
				var data = new google.visualization.DataTable();
				data.addColumn('string', '');
				data.addColumn('number', '1');
				data.addColumn('number', '8');
				data.addColumn('number', '15');
				data.addColumn('number', '22');
				data.addColumn('number', '29');
				data.addRows([
				  ['<?php echo language('graph_stats_text_status_knowledgeCount'); ?>', 
					<?php echo $session['response']['param']['statsList'][0]['KnowledgeCount']; ?>,
					<?php echo $session['response']['param']['statsList'][1]['KnowledgeCount']; ?>,
					<?php echo $session['response']['param']['statsList'][2]['KnowledgeCount']; ?>,
					<?php echo $session['response']['param']['statsList'][3]['KnowledgeCount']; ?>,
					<?php echo $session['response']['param']['statsList'][4]['KnowledgeCount']; ?>,
				  ],
				  ['<?php echo language('graph_stats_text_status_hobbyCount'); ?>', 
					<?php echo $session['response']['param']['statsList'][0]['HobbyCount']; ?>,
					<?php echo $session['response']['param']['statsList'][1]['HobbyCount']; ?>,
					<?php echo $session['response']['param']['statsList'][2]['HobbyCount']; ?>,
					<?php echo $session['response']['param']['statsList'][3]['HobbyCount']; ?>,
					<?php echo $session['response']['param']['statsList'][4]['HobbyCount']; ?>,
				  ],
				  ['<?php echo language('graph_stats_text_status_tagCount'); ?>', 
					<?php echo $session['response']['param']['statsList'][0]['TagCount']; ?>,
					<?php echo $session['response']['param']['statsList'][1]['TagCount']; ?>,
					<?php echo $session['response']['param']['statsList'][2]['TagCount']; ?>,
					<?php echo $session['response']['param']['statsList'][3]['TagCount']; ?>,
					<?php echo $session['response']['param']['statsList'][4]['TagCount']; ?>,
				  ],
				]);

				var table = new google.visualization.Table(document.getElementById('knowledge_table'));
				google.visualization.events.addListener(table, 'select', selectHandler);

				function selectHandler(e) {
					var selection = table.getSelection();
					   
					var row = 0;
					for (var i = 0; i < selection.length; i++) {
						  var item = selection[i];
						  
						  if (item.row != null) {
							  //row = data.getFormattedValue(item.row,0);
							  row = item.row;
						  }
					}

				    var goList = ['KnowledgeCount','HobbyCount','TagCount'];
						  
					//alert('You selected ' + goList[row]);
					window.location.href = "/stats/trend?app=" + <?php echo $session['response']['param']['app']; ?> + "&year=" + <?php echo $session['response']['param']['year']; ?> + "&month=" + <?php echo $session['response']['param']['month']; ?> + "&trend=" + goList[row];
				}

				table.draw(data, {showRowNumber: true});
			  }
			</script>

			<div id="knowledge_table">
			</div>
		</section>

		<section>
			<h1><?php echo language('graph_stats_header_status_experience'); ?></h1>
			<script type='text/javascript'>
			  google.load('visualization', '1', {packages:['table']});
			  google.setOnLoadCallback(drawTable);
			  function drawTable() {
				var data = new google.visualization.DataTable();
				data.addColumn('string', '');
				data.addColumn('number', '1');
				data.addColumn('number', '8');
				data.addColumn('number', '15');
				data.addColumn('number', '22');
				data.addColumn('number', '29');
				data.addRows([
				  ['<?php echo language('graph_stats_text_status_productCount'); ?>', 
					<?php echo $session['response']['param']['statsList'][0]['ProductCount']; ?>,
					<?php echo $session['response']['param']['statsList'][1]['ProductCount']; ?>,
					<?php echo $session['response']['param']['statsList'][2]['ProductCount']; ?>,
					<?php echo $session['response']['param']['statsList'][3]['ProductCount']; ?>,
					<?php echo $session['response']['param']['statsList'][4]['ProductCount']; ?>,
				  ],
				  ['<?php echo language('graph_stats_text_status_companyCount'); ?>', 
					<?php echo $session['response']['param']['statsList'][0]['CompanyCount']; ?>,
					<?php echo $session['response']['param']['statsList'][1]['CompanyCount']; ?>,
					<?php echo $session['response']['param']['statsList'][2]['CompanyCount']; ?>,
					<?php echo $session['response']['param']['statsList'][3]['CompanyCount']; ?>,
					<?php echo $session['response']['param']['statsList'][4]['CompanyCount']; ?>,
				  ],
				  ['<?php echo language('graph_stats_text_status_educationCount'); ?>', 
					<?php echo $session['response']['param']['statsList'][0]['EducationCount']; ?>,
					<?php echo $session['response']['param']['statsList'][1]['EducationCount']; ?>,
					<?php echo $session['response']['param']['statsList'][2]['EducationCount']; ?>,
					<?php echo $session['response']['param']['statsList'][3]['EducationCount']; ?>,
					<?php echo $session['response']['param']['statsList'][4]['EducationCount']; ?>,
				  ],
				  ['<?php echo language('graph_stats_text_status_eventCount'); ?>', 
					<?php echo $session['response']['param']['statsList'][0]['EventCount']; ?>,
					<?php echo $session['response']['param']['statsList'][1]['EventCount']; ?>,
					<?php echo $session['response']['param']['statsList'][2]['EventCount']; ?>,
					<?php echo $session['response']['param']['statsList'][3]['EventCount']; ?>,
					<?php echo $session['response']['param']['statsList'][4]['EventCount']; ?>,
				  ],
				]);

				var table = new google.visualization.Table(document.getElementById('experience_table'));
				google.visualization.events.addListener(table, 'select', selectHandler);

				function selectHandler(e) {
					var selection = table.getSelection();
					   
					var row = 0;
					for (var i = 0; i < selection.length; i++) {
						  var item = selection[i];
						  
						  if (item.row != null) {
							  //row = data.getFormattedValue(item.row,0);
							  row = item.row;
						  }
					}

				    var goList = ['ProductCount','CompanyCount','EducationCount','EventCount'];
						  
					//alert('You selected ' + goList[row]);
					window.location.href = "/stats/trend?app=" + <?php echo $session['response']['param']['app']; ?> + "&year=" + <?php echo $session['response']['param']['year']; ?> + "&month=" + <?php echo $session['response']['param']['month']; ?> + "&trend=" + goList[row];
				}

				table.draw(data, {showRowNumber: true});
			  }
			</script>

			<div id="experience_table">
			</div>
		</section>

		<section>
			<h1><?php echo language('graph_stats_header_status_publication'); ?></h1>
			<script type='text/javascript'>
			  google.load('visualization', '1', {packages:['table']});
			  google.setOnLoadCallback(drawTable);
			  function drawTable() {
				var data = new google.visualization.DataTable();
				data.addColumn('string', '');
				data.addColumn('number', '1');
				data.addColumn('number', '8');
				data.addColumn('number', '15');
				data.addColumn('number', '22');
				data.addColumn('number', '29');
				data.addRows([
				  ['<?php echo language('graph_stats_text_status_userTwitterCount'); ?>', 
					<?php echo $session['response']['param']['statsList'][0]['UserTwitterCount']; ?>,
					<?php echo $session['response']['param']['statsList'][1]['UserTwitterCount']; ?>,
					<?php echo $session['response']['param']['statsList'][2]['UserTwitterCount']; ?>,
					<?php echo $session['response']['param']['statsList'][3]['UserTwitterCount']; ?>,
					<?php echo $session['response']['param']['statsList'][4]['UserTwitterCount']; ?>,
				  ],
				  ['<?php echo language('graph_stats_text_status_userLinkedinCount'); ?>', 
					<?php echo $session['response']['param']['statsList'][0]['UserLinkedinCount']; ?>,
					<?php echo $session['response']['param']['statsList'][1]['UserLinkedinCount']; ?>,
					<?php echo $session['response']['param']['statsList'][2]['UserLinkedinCount']; ?>,
					<?php echo $session['response']['param']['statsList'][3]['UserLinkedinCount']; ?>,
					<?php echo $session['response']['param']['statsList'][4]['UserLinkedinCount']; ?>,
				  ],
				  ['<?php echo language('graph_stats_text_status_blogCount'); ?>', 
					<?php echo $session['response']['param']['statsList'][0]['BlogCount']; ?>,
					<?php echo $session['response']['param']['statsList'][1]['BlogCount']; ?>,
					<?php echo $session['response']['param']['statsList'][2]['BlogCount']; ?>,
					<?php echo $session['response']['param']['statsList'][3]['BlogCount']; ?>,
					<?php echo $session['response']['param']['statsList'][4]['BlogCount']; ?>,
				  ],
				  ['<?php echo language('graph_stats_text_status_presentationCount'); ?>', 
					<?php echo $session['response']['param']['statsList'][0]['PresentationCount']; ?>,
					<?php echo $session['response']['param']['statsList'][1]['PresentationCount']; ?>,
					<?php echo $session['response']['param']['statsList'][2]['PresentationCount']; ?>,
					<?php echo $session['response']['param']['statsList'][3]['PresentationCount']; ?>,
					<?php echo $session['response']['param']['statsList'][4]['PresentationCount']; ?>,
				  ],
				  ['<?php echo language('graph_stats_text_status_websiteCount'); ?>', 
					<?php echo $session['response']['param']['statsList'][0]['WebsiteCount']; ?>,
					<?php echo $session['response']['param']['statsList'][1]['WebsiteCount']; ?>,
					<?php echo $session['response']['param']['statsList'][2]['WebsiteCount']; ?>,
					<?php echo $session['response']['param']['statsList'][3]['WebsiteCount']; ?>,
					<?php echo $session['response']['param']['statsList'][4]['WebsiteCount']; ?>,
				  ],
				  ['<?php echo language('graph_stats_text_status_otherPubCount'); ?>', 
					<?php echo $session['response']['param']['statsList'][0]['OtherPubCount']; ?>,
					<?php echo $session['response']['param']['statsList'][1]['OtherPubCount']; ?>,
					<?php echo $session['response']['param']['statsList'][2]['OtherPubCount']; ?>,
					<?php echo $session['response']['param']['statsList'][3]['OtherPubCount']; ?>,
					<?php echo $session['response']['param']['statsList'][4]['OtherPubCount']; ?>,
				  ],
				]);

				var table = new google.visualization.Table(document.getElementById('publication_table'));
				google.visualization.events.addListener(table, 'select', selectHandler);

				function selectHandler(e) {
					var selection = table.getSelection();
					   
					var row = 0;
					for (var i = 0; i < selection.length; i++) {
						  var item = selection[i];
						  
						  if (item.row != null) {
							  //row = data.getFormattedValue(item.row,0);
							  row = item.row;
						  }
					}

				    var goList = ['UserTwitterCount','UserLinkedinCount','BlogCount','PresentationCount','WebsiteCount','OtherPubCount'];
						  
					//alert('You selected ' + goList[row]);
					window.location.href = "/stats/trend?app=" + <?php echo $session['response']['param']['app']; ?> + "&year=" + <?php echo $session['response']['param']['year']; ?> + "&month=" + <?php echo $session['response']['param']['month']; ?> + "&trend=" + goList[row];
				}

				table.draw(data, {showRowNumber: true});
			  }
			</script>

			<div id="publication_table">
			</div>
		</section>

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

	
</body>
</html>
