<?php
$page = 'personen';
?>
<!DOCTYPE html>

<html lang="nl" class="no-js">
    <head>
        <meta charset="UTF-8" />

        <title><?php echo language('sciomio_title_main'); ?></title>

	<?php include("includes/headers.php"); ?>
 	<?php include("includes/headers-act.php"); ?>

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
			<!--
            <div class="page">
				<div class="section solo highlight searchbox moresolo">
					<h1><?php echo language('sciomio_header_main_search'); ?></h1>
					<form action="<?php echo $XCOW_B['url'] ?>/search" method="get">
						<fieldset>
							<input autocomplete="off" class="text search-xl placeholder" value="<?php echo language('sciomio_text_main_search'); ?>" placeholder="<?php echo language('sciomio_text_main_search'); ?>" type="text" name="q" id="" maxlength="128" />
								<button type="submit" class="submit search-start"><?php echo language('sciomio_word_search'); ?></button>
						</fieldset>
					</form>
					<h4><?php echo language('sciomio_text_main_browse'); ?><a href="<?php echo $XCOW_B['url'] ?>/browse"><?php echo language('sciomio_word_browse'); ?></a></h4>
				</div>
            </div>
            -->
            <div class="page">
                <div class="group divide div1-2">
                    <div class="unit unit1-2">

						<div class="section">
							<h2><?php echo language('sciomio_header_main_you'); ?></h2>
						</div>
						<div id="userPersonalWindow">
							<div class="section">
								<div style="height:200px;"><img src="<?php echo $XCOW_B['url'] ?>/gfx/ajax-loader-circle.gif"></div>
							</div>
						</div>
						<br/>

                        <div class="section">
                            <h2><?php echo language('sciomio_header_main_kennisvelden'); ?></h2>
							<div id="knowledgeListWindow">
								<div style="height:100px;">&nbsp;</div>
							</div>
						</div>
						<br/>

						<div class="section">
							<h2><?php echo language('sciomio_header_main_kennis'); ?></h2>
							<p><?php echo language('sciomio_text_main_kennis'); ?></p>

							<div class="update-section" id="activityListWindow">
								<?php
								if (count($session['response']['param']['activityList2']) == 0) {
									echo "<p><br/>".language('sciomio_text_activity_home_geen')."</p>";
								}
								echo "<ul class='expert-needed'>";
								foreach ($session['response']['param']['activityList2'] as $activity) {
									echo "<li style='padding-left:0px;'>";
									echo "<div class='bd'>";

									if ($activity['UserId'] == $session['response']['param']['meUser']) {
										echo "<a style='float:right;' href='javascript:ScioMino.ActivityDelete.action(".$activity['Id'].");'><img src='".$XCOW_B['url']."/ui/gfx/icon_delete.gif' border='0' /></a>";
									}
									else {
										echo "<a href='".$XCOW_B['url']."/snippet/help-new-form?activity=".$activity['Id']."&knowledge=".urlencode($activity['Description'])."&user=".$activity['UserId']."' class='tinybutton metoo' rel='/snippet/activity-list'>".language('sciomio_word_icanhelp')."</a>";
									}

									echo "<span class='exp-label'>{$activity['Description']}</span>";
									echo "<p>";
									echo language('sciomio_text_activity_search')."<a href='".$XCOW_B['url']."/view?user={$activity['UserId']}'>{$activity['User']['firstName']} {$activity['User']['lastName']}</a>";
									echo "<span class='count'> ".timeDiff2($activity['Timestamp'])."</span>";
									echo "</p>";
									echo "</div>";
									echo "</li>";
								}
								echo "</ul>";

								if ($session['response']['param']['thereIsMore']) {
									echo "<a class='more' href='javascript:ScioMino.ActivityList.load(".$session['response']['param']['newLimit'].")'>".language('sciomio_word_more')."</a>";
								}

								?>

							</div>		
						</div>

                    </div>

                    <div class="unit unit1-2">

						<div class="section">
							<div style="float:right;padding:9px 5px;"><a href="/act?s[relevant]" style="padding:3px 7px;background-image: url('/ui/skin/<?php echo $session['response']['param']['skin']; ?>/act/bg_sbt.png');background-color: rgba(0, 0, 0, 0);border: medium none;color: #FFFFFF;font-size: 12px;font-weight: 800;height: 26px;line-height: 16px;width: 139px;text-decoration: none;"><?php echo language('sciomio_text_act_new_submit'); ?></a></div>
							<h2><?php echo language('sciomio_text_act_widget_home'); ?></h2>
							<p><?php echo language('sciomio_text_act_widget_home_personal'); ?></p>
						</div>
						<div id="actListPersonalWindow" class="puu-connect puu-widget">
								<div style="height:100px;"><img src="<?php echo $XCOW_B['url'] ?>/gfx/ajax-loader-circle.gif"></div>
						</div>

						<div class="section">
							<h2><?php echo language('sciomio_header_main_activity'); ?></h2>

							<div id="activityListAllWindow">
							<?php
							foreach ($session['response']['param']['activityList'] as $activity) {
								if ($activity['Description'] != "") {
								echo "<li class='img-item' style='margin-left:0px;'>";
								
									echo "<a href='/view?user={$activity['UserId']}' class='img'>";
									if (! isset($activity['User']['photo'])) { $activity['User']['photo'] = "/ui/gfx/photo.jpg"; }
									else { $activity['User']['photo'] = str_replace("/upload/","/upload/48x48_",$activity['User']['photo']); }
									echo "<img src='".$activity['User']['photo']."' width='48' height='48' alt='".$activity['User']['firstName']."' />";
									echo "</a>";
									
									echo "<div class='bd'>";
										# default 'motd'
										$activityText = language('sciomio_text_activity_motd').$activity['Description'];
										if ($activity['Title'] == "knowledge") {
											$activityText = language('sciomio_text_activity_knowledge')." <a href='/browse/knowledge?k=".urlencode($activity['Description'])."'>{$activity['Description']}</a>";
										}
										if ($activity['Title'] == "save_user") {
											$activityText = language('sciomio_text_activity_save_user');
										}
										if ($activity['Title'] == "save_user_profile_knowledgefield") {
											$activityText = language('sciomio_text_activity_save_knowledge')." <a href='/browse/knowledge?k=".urlencode($activity['Description'])."'>{$activity['Description']}</a> ";
										}
										if ($activity['Title'] == "save_user_profile_hobbyfield") {
											$activityText = language('sciomio_text_activity_save_hobby')." <a href='/browse/hobby?h=".urlencode($activity['Description'])."'>{$activity['Description']}</a> ";
										}
										if ($activity['Title'] == "save_user_profile_tag") {
											$activityText = language('sciomio_text_activity_save_tag')." <a href='/browse/tag?t=".urlencode($activity['Description'])."'>{$activity['Description']}</a> ";
										}
										
										echo "<span><a href='".$XCOW_B['url']."/view?user={$activity['UserId']}'>{$activity['User']['firstName']} {$activity['User']['lastName']}</a><span class='count'> - ".timeDiff2($activity['Timestamp'])."</span></span>";

										echo "<p>";
										echo "<span>".$activityText."</span>";
										echo "</p>";
									echo "</div>";
									
								echo "</li>";
								}
							}
							?>
							</div>		
						</div>
						<br/>

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
	<script type="text/javascript">
		addLoadEvent(function() {Session.View.load();});
		addLoadEvent(function() {ScioMino.User.personal();});
		addLoadEvent(function() {ScioMino.ListKnowledgeFields.load(30, "cloud");});
		addLoadEvent(function() {ScioMino.ActList.loadPersonal();});
	</script>

    </body>
</html>

