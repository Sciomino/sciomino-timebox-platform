<?php
$page = 'kennis';
?>
<!DOCTYPE html>

<html lang="nl" class="no-js">
<head>
	<meta charset="UTF-8" />

	<title><?php echo language('sciomio_title_tag'); ?> <?php echo $session['response']['param']['tagName'] ?></title>

	<?php include("includes/headers.php"); ?>
	<?php include("includes/headers-act.php"); ?>
	<?php include("includes/headers-twitterfeed.php"); ?>

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
					<li><a href="<?php echo $XCOW_B['url'] ?>/browse/tag?t=<?php echo urlencode($session['response']['param']['tagName']) ?>"><?php echo $session['response']['param']['tagName'] ?></a></li>
				</ul>

				<?php
				if ($session['response']['param']['userCount'] == 1) {
					echo "<h2 class='joinh1'>".$session['response']['param']['userCount']." ".language('sciomio_header_browse_tag_list_een')."</h2>";
				}
				else {
					echo "<h2 class='joinh1'>".$session['response']['param']['userCount']." ".language('sciomio_header_browse_tag_list')."</h2>";
				}
				?>

			</div>
	

			<div class="group divide div1-2">
				
				<div class="unit unit1-2 ">
					<div class="section">
					   <?php
					   if ($session['response']['param']['showMetoo'] == 0) {
						   echo "<a href='".$XCOW_B['url']."/snippet/tag-new-form-ikook?fill=".urlencode($session['response']['param']['tagName'])."' class='metoo tinybutton joinhd'>".language('sciomio_word_metoo')."</a>";
					   }
					   else {
						   echo "<span class='you-label joinhd'>".language('sciomio_word_youtoo')."</span>";
					   }
					   ?>
					    <h1><?php echo $session['response']['param']['tagName']?></h1>
						<div class="filter highlight">
		
							<ul class="filter-detail">
							</ul>
		
						</div>

						<div>
							<p><a href="<?php echo $XCOW_B['url'] ?>/search?t[<?php echo urlencode($session['response']['param']['tagName']); ?>]" class="more"><?php echo language('sciomio_word_browse_digg'); ?></a><br/><br/></p>
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

								echo "</div>";
								echo "</div>";
								echo "</li>\n";
							}
							# meer...
							if ($session['response']['param']['thereIsMore']) {
								echo "<a id='MoreButton' class='button buttonbold' href='".$XCOW_B['url']."/snippet/tag-more?t=".urlencode($session['response']['param']['tagName'])."&offset=".$session['response']['param']['searchOffset']."&searchString=".$session['response']['param']['searchString']."'>".language('sciomio_word_moreResults')."</a>";
							}
							?>
						</ul>
	
					</div>
				</div>
				
				<div class="unit unit1-2">

					<div id="externalEventWindow">
					</div>

				   	<div class="section">
					   	<div id="actListOpenWindow" class="puu-connect puu-widget">
					    </div>

					   	<div id="actListClosedWindow" class="puu-connect puu-widget">
					    </div>

						<?php
						if ($XCOW_B['sciomino']['show-tweets-on'] == 1) {
							echo "<div id='publicationTwitterListWindow'>";
							echo "<img src='".$XCOW_B['url']."/gfx/ajax-loader-circle.gif'>";
							echo "</div>";
						}
						?>
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
	<?php include 'includes/scripts-twitterfeed.php'; ?>
	<script type="text/javascript">
		addLoadEvent(function() {Session.View.load();});
		addLoadEvent(function() {ScioMino.ActList.loadTagOpen('<?php echo urlencode($session['response']['param']['tagName']) ?>', '<?php echo urlencode(language('sciomio_text_act_widget_open')); ?>');});
		addLoadEvent(function() {ScioMino.ActList.loadTagClosed('<?php echo urlencode($session['response']['param']['tagName']) ?>', '<?php echo urlencode(language('sciomio_text_act_widget_closed')); ?>');});

		<?php
		if ($XCOW_B['sciomino']['show-tweets-on'] == 1) {
			echo "addLoadEvent(function() {ScioMino.Connect.loadEvent('".urlencode($session['response']['param']['tagName'])."');});";
			echo "addLoadEvent(function() {ScioMino.Connect.loadTwitterSearch('".$session['response']['param']['me']."', '".$session['response']['param']['tagName']."');showTwitterFeed('tag', '".$session['response']['param']['tagName']."', 10);});";
		}
		?>
	</script>

</body>
</html>
