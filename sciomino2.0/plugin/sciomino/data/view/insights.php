<?php
$page = 'inzichten';
?>
<!DOCTYPE html>

<html lang="nl" class="no-js">
<head>
	<meta charset="UTF-8" />

	<title><?php echo language('sciomio_title_insights'); ?></title>

	<?php include("includes/headers.php"); ?>
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

        <div id="Content" class="puu-insight">
			<div style="height:1px;"></div>
		<div class="section">
			<section>
				<?php
				$languageTemplate = array();
				$languageTemplate['count'] = $session['response']['param']['stats']['UserCount'];
				echo "<h1>".language_template('sciomio_header_insights_top', $languageTemplate)."</h1>";
				?>
				<div class="puu-set puu-publications">
					<!--<h2><?php echo language('sciomio_header_insights_publications'); ?></h2>-->
					<table class="puu-channels">
					<tr>
						<th class="puu-channel"><a href="<?php echo $XCOW_B['url'] ?>/insights/social?list=twitter"><img class="puu-lgo" alt="" src="<?php echo $XCOW_B['url'] ?>/ui/insight/ico_twitter.png" width="48" height="48"><?php echo language('sciomio_text_insights_twitter'); ?></a></th>
						<td class="puu-count"><a class="puu-score" href="<?php echo $XCOW_B['url'] ?>/insights/social?list=twitter"><?php echo $session['response']['param']['stats']['UserTwitterCount']; ?> <span class="puu-percent">(<?php echo round(($session['response']['param']['stats']['UserTwitterCount']/$session['response']['param']['stats']['UserCount'])*100,1);?>%)</span> <img class="puu-gauge" style="background-position: 2px <?php echo intval(50 - ($session['response']['param']['stats']['UserTwitterCount']/$session['response']['param']['stats']['UserCount'])*100/2);?>px" alt="" src="<?php echo $XCOW_B['url'] ?>/ui/insight/gauge.gif" width="30" height="50"></a></td>
					</tr>
					<tr>
						<th class="puu-channel"><a href="<?php echo $XCOW_B['url'] ?>/insights/social?list=linkedin"><img class="puu-lgo" alt="" src="<?php echo $XCOW_B['url'] ?>/ui/insight/ico_linkedin.png" width="48" height="48"><?php echo language('sciomio_text_insights_linkedin'); ?></a></th>
						<td class="puu-count"><a class="puu-score" href="<?php echo $XCOW_B['url'] ?>/insights/social?list=linkedin"><?php echo $session['response']['param']['stats']['UserLinkedinCount']; ?> <span class="puu-percent">(<?php echo round(($session['response']['param']['stats']['UserLinkedinCount']/$session['response']['param']['stats']['UserCount'])*100,1);?>%)</span> <img class="puu-gauge" style="background-position: 2px <?php echo intval(50 - ($session['response']['param']['stats']['UserLinkedinCount']/$session['response']['param']['stats']['UserCount'])*100/2);?>px" alt="" src="<?php echo $XCOW_B['url'] ?>/ui/insight/gauge.gif" width="30" height="50"></a></td>
					</tr>
					<tr>
						<th class="puu-channel"><a href="<?php echo $XCOW_B['url'] ?>/insights/social?list=blog"><img class="puu-lgo" alt="" src="<?php echo $XCOW_B['url'] ?>/ui/insight/ico_rss.png" width="48" height="48"><?php echo language('sciomio_text_insights_blog'); ?></a></th>
						<td class="puu-count"><a class="puu-score" href="<?php echo $XCOW_B['url'] ?>/insights/social?list=blog"><?php echo $session['response']['param']['stats']['UserBlogCount']; ?> <span class="puu-percent">(<?php echo round(($session['response']['param']['stats']['UserBlogCount']/$session['response']['param']['stats']['UserCount'])*100,1);?>%)</span> <img class="puu-gauge" style="background-position: 2px <?php echo intval(50 - ($session['response']['param']['stats']['UserBlogCount']/$session['response']['param']['stats']['UserCount'])*100/2);?>px" alt="" src="<?php echo $XCOW_B['url'] ?>/ui/insight/gauge.gif" width="30" height="50"></a></td>
					</tr>
					<tr>
						<th class="puu-channel"><a href="<?php echo $XCOW_B['url'] ?>/insights/social?list=presentation"><img class="puu-lgo" alt="" src="<?php echo $XCOW_B['url'] ?>/ui/insight/ico_slideshare.png" width="48" height="48"><?php echo language('sciomio_text_insights_presentation'); ?></a></th>
						<td class="puu-count"><a class="puu-score" href="<?php echo $XCOW_B['url'] ?>/insights/social?list=presentation"><?php echo $session['response']['param']['stats']['UserPresentationCount']; ?> <span class="puu-percent">(<?php echo round(($session['response']['param']['stats']['UserPresentationCount']/$session['response']['param']['stats']['UserCount'])*100,1);?>%)</span> <img class="puu-gauge" style="background-position: 2px <?php echo intval(50 - ($session['response']['param']['stats']['UserPresentationCount']/$session['response']['param']['stats']['UserCount'])*100/2);?>px" alt="" src="<?php echo $XCOW_B['url'] ?>/ui/insight/gauge.gif" width="30" height="50"></a></td>
					</tr>
					<tr>
						<th class="puu-channel"><a href="<?php echo $XCOW_B['url'] ?>/insights/social?list=website"><img class="puu-lgo" alt="" src="<?php echo $XCOW_B['url'] ?>/ui/insight/ico_websites.png" width="48" height="48"><?php echo language('sciomio_text_insights_website'); ?></a></th>
						<td class="puu-count"><a class="puu-score" href="<?php echo $XCOW_B['url'] ?>/insights/social?list=website"><?php echo $session['response']['param']['stats']['UserWebsiteCount']; ?> <span class="puu-percent">(<?php echo round(($session['response']['param']['stats']['UserWebsiteCount']/$session['response']['param']['stats']['UserCount'])*100,1);?>%)</span> <img class="puu-gauge" style="background-position: 2px <?php echo intval(50 - ($session['response']['param']['stats']['UserWebsiteCount']/$session['response']['param']['stats']['UserCount'])*100/2);?>px" alt="" src="<?php echo $XCOW_B['url'] ?>/ui/insight/gauge.gif" width="30" height="50"></a></td>
					</tr>
					<tr>
						<th class="puu-channel"><a href="<?php echo $XCOW_B['url'] ?>/insights/social?list=publication"><img class="puu-lgo" alt="" src="<?php echo $XCOW_B['url'] ?>/ui/insight/ico_articles.png" width="48" height="48"><?php echo language('sciomio_text_insights_other_publication'); ?></a></th>
						<td class="puu-count"><a class="puu-score" href="<?php echo $XCOW_B['url'] ?>/insights/social?list=publication"><?php echo $session['response']['param']['stats']['UserOtherPubCount']; ?> <span class="puu-percent">(<?php echo round(($session['response']['param']['stats']['UserOtherPubCount']/$session['response']['param']['stats']['UserCount'])*100,1);?>%)</span> <img class="puu-gauge" style="background-position: 2px <?php echo intval(50 - ($session['response']['param']['stats']['UserOtherPubCount']/$session['response']['param']['stats']['UserCount'])*100/2);?>px" alt="" src="<?php echo $XCOW_B['url'] ?>/ui/insight/gauge.gif" width="30" height="50"></a></td>
					</tr>
					</table>

				</div>
				<div class="puu-set puu-presence">
					<!--<h2><?php echo language('sciomio_header_insights_global'); ?></h2>-->

					<div class="puu-subset puu-loc">
						<div class="puu-sum">

							<h3><!--<span class="puu-scope"><?php echo language('sciomio_header_insights_scope'); ?></span>--> <?php echo language('sciomio_header_insights_work'); ?></h3>

							<div class="puu-work">
								<?php
								$languageTemplate = array();
								$languageTemplate['count'] = $session['response']['param']['stats']['WorkplaceCount'];
								$languageText = 'sciomio_text_insights_workplace';
								if ($languageTemplate['count'] == 1) {
									$languageText = 'sciomio_text_insights_workplace_een';
								}

								echo "<h4><a href='".$XCOW_B['url']."/insights/location?mode=workplace'>".language_template($languageText, $languageTemplate)."</a></h4>";

								$languageTemplate['count'] = $session['response']['param']['stats']['WorkplaceCountryCount'];
								$languageText = 'sciomio_text_insights_country';
								if ($languageTemplate['count'] == 1) {
									$languageText = 'sciomio_text_insights_country_een';
								}
								echo "<p>".language_template($languageText, $languageTemplate)."</p>";
								?>
							</div>

							<div class="puu-home">
								<?php
								$languageTemplate = array();
								$languageTemplate['count'] = $session['response']['param']['stats']['HometownCount'];
								$languageText = 'sciomio_text_insights_hometown';
								if ($languageTemplate['count'] == 1) {
									$languageText = 'sciomio_text_insights_hometown_een';
								}
								echo "<h4><a href='".$XCOW_B['url']."/insights/location?mode=hometown'>".language_template($languageText, $languageTemplate)."</a></h4>";

								$languageTemplate['count'] = $session['response']['param']['stats']['HometownCountryCount'];
								$languageText = 'sciomio_text_insights_country';
								if ($languageTemplate['count'] == 1) {
									$languageText = 'sciomio_text_insights_country_een';
								}
								echo "<p>".language_template($languageText, $languageTemplate)."</p>";
								?>
							</div>

						</div>
						<div class="puu-clr"></div>
					</div>

					<div class="puu-subset puu-sex">
						<div class="puu-sum">

							<h3><!--<span class="puu-scope"><?php echo language('sciomio_header_insights_scope'); ?></span>--> <a href="<?php echo $XCOW_B['url'] ?>/insights/date"><?php echo language('sciomio_header_insights_gender'); ?></a></h3>

							<div class="puu-male">
								<h4 class="puu-score"><?php echo $session['response']['param']['stats']['MaleCount']; ?> <span class="puu-percent">(<?php echo round(($session['response']['param']['stats']['MaleCount']/$session['response']['param']['stats']['UserCount'])*100,1);?>%)</span> <img class="puu-gauge" style="background-position: 2px <?php echo intval(50 - ($session['response']['param']['stats']['MaleCount']/$session['response']['param']['stats']['UserCount'])*100/2);?>px" alt="" src="<?php echo $XCOW_B['url'] ?>/ui/insight/gauge.gif" width="30" height="50"></h4>
							</div>

							<div class="puu-female">
								<h4 class="puu-score"><?php echo $session['response']['param']['stats']['FemaleCount']; ?> <span class="puu-percent">(<?php echo round(($session['response']['param']['stats']['FemaleCount']/$session['response']['param']['stats']['UserCount'])*100,1);?>%)</span> <img class="puu-gauge" style="background-position: 2px <?php echo intval(50 - ($session['response']['param']['stats']['FemaleCount']/$session['response']['param']['stats']['UserCount'])*100/2);?>px" alt="" src="<?php echo $XCOW_B['url'] ?>/ui/insight/gauge.gif" width="30" height="50"></h4>
							</div>

							<div>
								<?php
								$languageTemplate['count'] = $session['response']['param']['stats']['UnknownGenderCount'];
								$languageText = 'sciomio_text_insights_people';
								if ($languageTemplate['count'] == 1) {
									$languageText = 'sciomio_text_insights_people_een';
								}
								echo "<p style='font-size:0.7em;'>".language_template($languageText, $languageTemplate)."</p>";
								?>
							</div>
						</div>
						<div class="puu-age puu-compact" height="154">
							<table data-full="160">
								<tr><th>15-24</th><td><?php echo $session['response']['param']['stats']['Male15Count']; ?></td><td><?php echo $session['response']['param']['stats']['Female15Count']; ?></td></tr>
								<tr><th>25-34</th><td><?php echo $session['response']['param']['stats']['Male25Count']; ?></td><td><?php echo $session['response']['param']['stats']['Female25Count']; ?></td></tr>
								<tr><th>35-44</th><td><?php echo $session['response']['param']['stats']['Male35Count']; ?></td><td><?php echo $session['response']['param']['stats']['Female35Count']; ?></td></tr>
								<tr><th>45-54</th><td><?php echo $session['response']['param']['stats']['Male45Count']; ?></td><td><?php echo $session['response']['param']['stats']['Female45Count']; ?></td></tr>
								<tr><th>55+</th><td><?php echo $session['response']['param']['stats']['Male55Count']; ?></td><td><?php echo $session['response']['param']['stats']['Female55Count']; ?></td></tr>
							</table>
						</div>
						<div class="puu-clr"></div>
					</div>

					<div class="puu-subset puu-anno">
						<div class="puu-sum">

							<h3><!--<span class="puu-scope"><?php echo language('sciomio_header_insights_scope'); ?></span>--> <a href="<?php echo $XCOW_B['url'] ?>/insights/date"><?php echo language('sciomio_header_insights_birthday'); ?></a></h3>

							<div class="puu-today">
								<h4><?php echo language('sciomio_word_today'); ?></h4>
								<p><?php echo $session['response']['param']['today']['day'];?> <?php echo language('sciomio_word_month_'.$session['response']['param']['today']['month']);?> <?php echo $session['response']['param']['today']['year'];?></p>
							</div>

							<ul class="puu-nee">
								<?php
								if (count($session['response']['param']['userList']) > 0) {
									foreach ($session['response']['param']['userList'] as $userKey => $userVal) {
										if (! isset($userVal['photo'])) { $userVal['photo'] = "/ui/gfx/photo.jpg"; }
										else { $userVal['photo'] = str_replace("/upload/","/upload/48x48_",$userVal['photo']); }

										$displayOrganization = $userVal['Organization'][get_id_from_multi_array($userVal['Organization'], 'Name', 'Current')]['division'];
										if ($displayOrganization == "") { $displayOrganization = $userVal['Organization'][get_id_from_multi_array($userVal['Organization'], 'Name', 'Current')]['company']; }

										echo "<li><a class='puu-face vcard' href='".$XCOW_B['url']."/view?user=".$userVal['Id']."'><img class='puu-mug photo' alt='' src='".$XCOW_B['url'].$userVal['photo']."' width='48' height='48'> <span class='puu-cap'><span class='fn'>".$userVal['FirstName']." ".$userVal['LastName']."</span> <span class='role'>".$userVal['Organization'][get_id_from_multi_array($userVal['Organization'], 'Name', 'Current')]['role']." - ".$displayOrganization."</span></a></span></li>";
									}
								}
								else {
									echo "<li class='puu-nobody'>".language('sciomio_text_insights_no_birthday')."</li>";
								}
								?>
							</ul>

						</div>

						<table class="puu-months">
							
							<?php 
							$now1=""; if ($session['response']['param']['today']['month'] == 1) { $now1="puu-now"; }
							$now2=""; if ($session['response']['param']['today']['month'] == 2) { $now2="puu-now"; }
							$now3=""; if ($session['response']['param']['today']['month'] == 3) { $now3="puu-now"; }
							$now4=""; if ($session['response']['param']['today']['month'] == 4) { $now4="puu-now"; }
							$now5=""; if ($session['response']['param']['today']['month'] == 5) { $now5="puu-now"; }
							$now6=""; if ($session['response']['param']['today']['month'] == 6) { $now6="puu-now"; }
							$now7=""; if ($session['response']['param']['today']['month'] == 7) { $now7="puu-now"; }
							$now8=""; if ($session['response']['param']['today']['month'] == 8) { $now8="puu-now"; }
							$now9=""; if ($session['response']['param']['today']['month'] == 9) { $now9="puu-now"; }
							$now10=""; if ($session['response']['param']['today']['month'] == 10) { $now10="puu-now"; }
							$now11=""; if ($session['response']['param']['today']['month'] == 11) { $now11="puu-now"; }
							$now12=""; if ($session['response']['param']['today']['month'] == 12) { $now12="puu-now"; }
							?>
							<tr class="odd <?php echo $now1;?>"><th><?php echo language('sciomio_word_month_short_1');?></th><td><span class="puu-bar"><?php echo $session['response']['param']['stats']['UserCountXBirthdayMonth'][get_id_from_multi_array($session['response']['param']['stats']['UserCountXBirthdayMonth'], 'label', 'm-1')]['count']; ?></span></td></tr>
							<tr class="<?php echo $now2;?>"><th><?php echo language('sciomio_word_month_short_2');?></th><td><span class="puu-bar" style="width: 0px;"><?php echo $session['response']['param']['stats']['UserCountXBirthdayMonth'][get_id_from_multi_array($session['response']['param']['stats']['UserCountXBirthdayMonth'], 'label', 'm-2')]['count']; ?></span></td></tr>
							<tr class="odd <?php echo $now3;?>"><th><?php echo language('sciomio_word_month_short_3');?></th><td><span class="puu-bar"><?php echo $session['response']['param']['stats']['UserCountXBirthdayMonth'][get_id_from_multi_array($session['response']['param']['stats']['UserCountXBirthdayMonth'], 'label', 'm-3')]['count']; ?></span></td></tr>
							<tr class="<?php echo $now4;?>"><th><?php echo language('sciomio_word_month_short_4');?></th><td><span class="puu-bar"><?php echo $session['response']['param']['stats']['UserCountXBirthdayMonth'][get_id_from_multi_array($session['response']['param']['stats']['UserCountXBirthdayMonth'], 'label', 'm-4')]['count']; ?></span></td></tr>
							<tr class="odd <?php echo $now5;?>"><th><?php echo language('sciomio_word_month_short_5');?></th><td><span class="puu-bar"><?php echo $session['response']['param']['stats']['UserCountXBirthdayMonth'][get_id_from_multi_array($session['response']['param']['stats']['UserCountXBirthdayMonth'], 'label', 'm-5')]['count']; ?></span></td></tr>
							<tr class="<?php echo $now6;?>"><th><?php echo language('sciomio_word_month_short_6');?></th><td><span class="puu-bar"><?php echo $session['response']['param']['stats']['UserCountXBirthdayMonth'][get_id_from_multi_array($session['response']['param']['stats']['UserCountXBirthdayMonth'], 'label', 'm-6')]['count']; ?></span></td></tr>
							<tr class="odd <?php echo $now7;?>"><th><?php echo language('sciomio_word_month_short_7');?></th><td><span class="puu-bar"><?php echo $session['response']['param']['stats']['UserCountXBirthdayMonth'][get_id_from_multi_array($session['response']['param']['stats']['UserCountXBirthdayMonth'], 'label', 'm-7')]['count']; ?></span></td></tr>
							<tr class="<?php echo $now8;?>"><th><?php echo language('sciomio_word_month_short_8');?></th><td><span class="puu-bar"><?php echo $session['response']['param']['stats']['UserCountXBirthdayMonth'][get_id_from_multi_array($session['response']['param']['stats']['UserCountXBirthdayMonth'], 'label', 'm-8')]['count']; ?></span></td></tr>
							<tr class="odd <?php echo $now9;?>"><th><?php echo language('sciomio_word_month_short_9');?></th><td><span class="puu-bar"><?php echo $session['response']['param']['stats']['UserCountXBirthdayMonth'][get_id_from_multi_array($session['response']['param']['stats']['UserCountXBirthdayMonth'], 'label', 'm-9')]['count']; ?></span></td></tr>
							<tr class="<?php echo $now10;?>"><th><?php echo language('sciomio_word_month_short_10');?></th><td><span class="puu-bar"><?php echo $session['response']['param']['stats']['UserCountXBirthdayMonth'][get_id_from_multi_array($session['response']['param']['stats']['UserCountXBirthdayMonth'], 'label', 'm-10')]['count']; ?></span></td></tr>
							<tr class="odd <?php echo $now11;?>"><th><?php echo language('sciomio_word_month_short_11');?></th><td><span class="puu-bar"><?php echo $session['response']['param']['stats']['UserCountXBirthdayMonth'][get_id_from_multi_array($session['response']['param']['stats']['UserCountXBirthdayMonth'], 'label', 'm-11')]['count']; ?></span></td></tr>
							<tr class="<?php echo $now12;?>"><th><?php echo language('sciomio_word_month_short_12');?></th><td><span class="puu-bar"><?php echo $session['response']['param']['stats']['UserCountXBirthdayMonth'][get_id_from_multi_array($session['response']['param']['stats']['UserCountXBirthdayMonth'], 'label', 'm-12')]['count']; ?></span></td></tr>
						</table>

						<div class="puu-clr"></div>
					</div>

				</div>
				<div class="puu-clr"></div>
			</section>
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
	<?php include 'includes/scripts-insights.php'; ?>
	<script type="text/javascript">
		addLoadEvent(function() {Session.View.load();});
	</script>

</body>
</html>

