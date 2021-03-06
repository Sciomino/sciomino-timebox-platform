<?php
$page = 'kennis';
?>
<!DOCTYPE html>

<html lang="nl" class="no-js">
<head>
	<meta charset="UTF-8" />

	<title><?php echo language('sciomio_title_knowledge'); ?> <?php echo $session['response']['param']['knowledgeField'] ?></title>

	<?php include("includes/headers.php"); ?>
	<?php include("includes/headers-act.php"); ?>
	<?php include("includes/headers-insights.php"); ?>

        <?php include 'skin/'.$session['response']['param']['skin'].'/css.php'; ?>

</head>

<body>

        <?php include 'skin/'.$session['response']['param']['skin'].'/header.php'; ?>

        <div id="Header">
            <div class="page">

		<div class="nav">

		    <?php include 'includes/search.php' ?>

                    <?php include 'includes/nav.php'; ?>

		    <div id="sessionView">
		    </div>

		</div>

            </div>

        </div>

	<div id="Content">
			<div style="height:1px;"></div>
		<div class="page">
			<div class="header">
				<ul class="breadcrumbs">
					<li><a href="<?php echo $XCOW_B['url'] ?>/browse"><?php echo language('sciomio_text_browse_breadcrumb'); ?></a></li>
					<li><a href="<?php echo $XCOW_B['url'] ?>/browse/knowledge?k=<?php echo urlencode($session['response']['param']['knowledgeField']) ?>"><?php echo $session['response']['param']['knowledgeField'] ?></a></li>
				</ul>

				<?php
				if ($session['response']['param']['userCount'] == 1) {
					echo "<h2 class='joinh1'>".$session['response']['param']['userCount']." ".language('sciomio_header_browse_knowledge_list_een')."</h2>";
				}
				else {
					echo "<h2 class='joinh1'>".$session['response']['param']['userCount']." ".language('sciomio_header_browse_knowledge_list')."</h2>";
				}
				?>

			</div>
	

			<div class="group divide div1-2">
				
				<div class="unit unit1-2 ">
					<div class="section">
					   <?php
					   if ($session['response']['param']['showMetoo'] == 0) {
						   echo "<a href='".$XCOW_B['url']."/snippet/knowledge-new-form-ikook?fill=".urlencode($session['response']['param']['knowledgeField'])."' class='metoo tinybutton joinhd'>".language('sciomio_word_metoo')."</a>";
					   }
					   else {
						   echo "<span class='you-label joinhd'>".language('sciomio_word_youtoo')."</span>";
					   }
					   ?>
					    <h1><?php echo $session['response']['param']['knowledgeField']?></h1>
						<div class="filter highlight puu-insight">

							<div class="puu-age puu-compact puu-small" style="width:200px; height:80px;">
								<table data-full="78">
									<tr><th>15-24</th><td><?php echo $session['response']['param']['stats']['MF15Count']; ?></td></tr>
									<tr><th>25-34</th><td><?php echo $session['response']['param']['stats']['MF25Count']; ?></td></tr>
									<tr><th>35-44</th><td><?php echo $session['response']['param']['stats']['MF35Count']; ?></td></tr>
									<tr><th>45-54</th><td><?php echo $session['response']['param']['stats']['MF45Count']; ?></td></tr>
									<tr><th>55+</th><td><?php echo $session['response']['param']['stats']['MF55Count']; ?></td></tr>
								</table>
							</div>
		
							<ul class="filter-detail">
								<?php
								$active = "";
								if ($session['response']['param']['knowledgeLevel'] == '') {
									$active = "class='active'";
								}
								echo "<li><a ".$active." href='".$XCOW_B['url']."/browse/knowledge?k=".urlencode($session['response']['param']['knowledgeField'])."'>".language('sciomio_word_all')." <span class='count'>(".$session['response']['param']['userCount'].")</span></a></li>\n";
								foreach ($session['response']['param']['knowledgeDetail'] as $detailKey => $detailVal) {
									if ($detailKey == "level") {
										foreach ($detailVal as $level => $count) {
											$active = "";
											if ($level == $session['response']['param']['knowledgeLevel']) {
												$active = "class='active'";
											}
											$languageString = "sciomio_word_knowledgefield_".$level;
											echo "<li><a ".$active." href='".$XCOW_B['url']."/browse/knowledge?k=".urlencode($session['response']['param']['knowledgeField'])."&level=".$level."'>".language($languageString)." <span class='count'>(".$count.")</span></a></li>\n";
										}
									}
								}
								?>
							</ul>
						</div>
		
						<div>
							<p><a href="<?php echo $XCOW_B['url'] ?>/search?k[<?php echo urlencode($session['response']['param']['knowledgeField']); ?>]" class="more"><?php echo language('sciomio_word_browse_digg'); ?></a><br/><br/></p>
						</div>
	
						<ul class="filtered expandable">
							
							<?php
							foreach ($session['response']['param']['userList'] as $userKey => $userVal) {
								echo "<li>";
								echo "<div class='img-item softbox'>";
								echo "<div class='img'>";
								if (! isset($userVal['photo'])) { $userVal['photo'] = "/ui/gfx/photo.jpg"; }
								else { $userVal['photo'] = str_replace("/upload/","/upload/48x48_",$userVal['photo']); }
								echo "<a href='".$XCOW_B['url']."/view?user=".$userVal['Id']."'><img src='".$XCOW_B['url'].$userVal['photo']."' width='48' height='48' alt='' /></a>";
								echo "</div>";
								echo "<div class='bd'>";
								echo $me = "";
								if ($session['response']['param']['me'] == $userVal['Id']) {
									$me = "<span class='you-label'>".language('sciomio_word_you')."</span>";
								}
								echo "<h3><a class='userlink' href='".$XCOW_B['url']."/view?user=".$userVal['Id']."'>".$userVal['FirstName']." ".$userVal['LastName']."</a>".$me."</h3>";
								$displayOrganization = $userVal['Organization'][get_id_from_multi_array($userVal['Organization'], 'Name', 'Current')]['division'];
								if ($displayOrganization == "") { $displayOrganization = $userVal['Organization'][get_id_from_multi_array($userVal['Organization'], 'Name', 'Current')]['company']; }
								echo "<p>".$userVal['Organization'][get_id_from_multi_array($userVal['Organization'], 'Name', 'Current')]['role']." - ".$displayOrganization."</p>";

								$level = 1;
								foreach ($userVal['knowledgefield'] as $knowledge) {
									if (strcasecmp($knowledge['field'], $session['response']['param']['knowledgeField']) == 0) {
										$level = $knowledge['level'];
										break;
									}
								}
								$languageString = "sciomio_word_knowledgefield_".$level;				
								echo "<p><a href='".$XCOW_B['url']."/browse/knowledge?k=".urlencode($session['response']['param']['knowledgeField'])."&level=".$level."'>".language($languageString)."</a></p>";
								echo "</div>";
								echo "</div>";
								echo "</li>\n";
							}
							# meer...
							if ($session['response']['param']['thereIsMore']) {
								echo "<a id='MoreButton' class='button buttonbold' href='".$XCOW_B['url']."/snippet/knowledge-more?k=".urlencode($session['response']['param']['knowledgeField'])."&offset=".$session['response']['param']['searchOffset']."&searchString=".$session['response']['param']['searchString']."'>".language('sciomio_word_moreResults')."</a>";
							}
							?>
						</ul>

					</div>
				</div>
				
				<div class="unit unit1-2">

					<div id="externalWikipediaWindow">
						<img src="<?php echo $XCOW_B['url'] ?>/gfx/ajax-loader-circle.gif">
					</div>

				   	<div class="section">
					   	<div id="actListOpenWindow" class="puu-connect puu-widget">
					    	</div>

					   	<div id="actListClosedWindow" class="puu-connect puu-widget">
					    	</div>
				    	</div>
					</div>
				
			</div>

		</div>
	</div>

        <div id="Footer">
            <div class="page">

                <?php include 'includes/footer.php'; ?>

            </div>
        </div>

 	<div id="sessionPopup" style="display:none">
		<div id="sessionPopupMenu">
		     <a href="javascript:Session.Window.close();"><?php echo language('sciomio_word_close'); ?></a>
		</div>
		<div id="sessionPopupData">
		</div>
	</div>

	<?php include 'includes/scripts.php'; ?>
	<?php include 'includes/scripts-act.php'; ?>
	<?php include 'includes/scripts-insights.php'; ?>
	<script type="text/javascript">
		addLoadEvent(function() {Session.View.load();});
		addLoadEvent(function() {ScioMino.Connect.loadWikipedia("<?php echo urlencode($session['response']['param']['knowledgeField']) ?>");});
		addLoadEvent(function() {ScioMino.ActList.loadKnowledgeOpen('<?php echo urlencode($session['response']['param']['knowledgeField']) ?>', '<?php echo urlencode(language('sciomio_text_act_widget_open')); ?>');});
		addLoadEvent(function() {ScioMino.ActList.loadKnowledgeClosed('<?php echo urlencode($session['response']['param']['knowledgeField']) ?>', '<?php echo urlencode(language('sciomio_text_act_widget_closed')); ?>');});
	</script>

</body>
</html>
