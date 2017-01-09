<?php
$page = 'inzichten';
?>
<!DOCTYPE html>

<html lang="nl" class="no-js">
<head>
	<meta charset="UTF-8" />

	<title><?php echo language('sciomio_title_insights_date'); ?></title>

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
		<div class="section puu-coworkers">
			<section>
				<h1><a href="<?php echo $XCOW_B['url'] ?>/insights"><?php echo language('sciomio_header_insights_home'); ?></a> <?php echo language('sciomio_header_insights_date'); ?></h1>
				<div class="puu-set">
					<h2><?php echo language('sciomio_header_insights_age'); ?></h2>

					<div class="puu-subset puu-sex">
						<div class="puu-sum">

							<h3><span class="puu-scope"><?php echo language('sciomio_header_insights_you'); ?></span> <?php echo $session['response']['param']['stats']['UserCount']; ?> <?php echo language('sciomio_header_insights_coworkers'); ?></h3>

							<div class="puu-male">
								<h4 class="puu-score"><?php echo $session['response']['param']['stats']['MaleCount']; ?> <span class="puu-percent">(<?php echo round(($session['response']['param']['stats']['MaleCount']/$session['response']['param']['stats']['UserCount'])*100,1);?>%)</span> <img class="puu-gauge" style="background-position: 2px <?php echo intval(50 - ($session['response']['param']['stats']['MaleCount']/$session['response']['param']['stats']['UserCount'])*100/2);?>px" alt="" src="<?php echo $XCOW_B['url'] ?>/ui/insight/gauge.gif" width="30" height="50"></h4>
							</div>

							<div class="puu-female">
								<h4 class="puu-score"><?php echo $session['response']['param']['stats']['FemaleCount']; ?> <span class="puu-percent">(<?php echo round(($session['response']['param']['stats']['FemaleCount']/$session['response']['param']['stats']['UserCount'])*100,1);?>%)</span> <img class="puu-gauge" style="background-position: 2px <?php echo intval(50 - ($session['response']['param']['stats']['FemaleCount']/$session['response']['param']['stats']['UserCount'])*100/2);?>px" alt="" src="<?php echo $XCOW_B['url'] ?>/ui/insight/gauge.gif" width="30" height="50"></h4>
							</div>

							<div>
								<p style='font-size:0.7em;'>Van <?php echo $session['response']['param']['stats']['UnknownGenderCount']; ?> personen zijn deze gegevens onbekend.</p>
							</div>
						</div>
						<div class="puu-age">
							<table data-full="119">
								<tr><th>15-24</th><td><?php echo $session['response']['param']['stats']['Male15Count']; ?></td><td><?php echo $session['response']['param']['stats']['Female15Count']; ?></td></tr>
								<tr><th>25-34</th><td><?php echo $session['response']['param']['stats']['Male25Count']; ?></td><td><?php echo $session['response']['param']['stats']['Female25Count']; ?></td></tr>
								<tr><th>35-44</th><td><?php echo $session['response']['param']['stats']['Male35Count']; ?></td><td><?php echo $session['response']['param']['stats']['Female35Count']; ?></td></tr>
								<tr><th>45-54</th><td><?php echo $session['response']['param']['stats']['Male45Count']; ?></td><td><?php echo $session['response']['param']['stats']['Female45Count']; ?></td></tr>
								<tr><th>55+</th><td><?php echo $session['response']['param']['stats']['Male55Count']; ?></td><td><?php echo $session['response']['param']['stats']['Female55Count']; ?></td></tr>
							</table>
						</div>

						<div class="puu-clr"></div>

					</div>
				</div>

				<div class="puu-set puu-events">

					<h2><?php echo language('sciomio_header_insights_birthday'); ?></h2>

					<div class="puu-subset puu-today">

						<h3><?php echo language('sciomio_word_today'); ?> <span class="puu-day"><?php echo $session['response']['param']['today']['day'];?> <?php echo language('sciomio_word_month_'.$session['response']['param']['today']['month']);?> <?php echo $session['response']['param']['today']['year'];?></span></h3>

						<ul class="puu-nee">
							<?php
							if (count($session['response']['param']['userList']) > 0) {
								foreach ($session['response']['param']['userList'] as $userKey => $userVal) {
									if (! isset($userVal['photo'])) { $userVal['photo'] = "/ui/gfx/photo.jpg"; }
									else { $userVal['photo'] = str_replace("/upload/","/upload/48x48_",$userVal['photo']); }

									$displayOrganization = $userVal['Organization'][get_id_from_multi_array($userVal['Organization'], 'Name', 'Current')]['division'];
									if ($displayOrganization == "") { $displayOrganization = $userVal['Organization'][get_id_from_multi_array($userVal['Organization'], 'Name', 'Current')]['company']; }
									echo "<li><a class='puu-face vcard' href='".$XCOW_B['url']."/view?user=".$userVal['Id']."'><img class='puu-mug photo' alt='' src='".$XCOW_B['url'].$userVal['photo']."' width='48' height='48'> <span class='puu-cap'><span class='fn'>".$userVal['FirstName']." ".$userVal['LastName']."</span> <span class='role'>".$userVal['Organization'][get_id_from_multi_array($userVal['Organization'], 'Name', 'Current')]['role']." - ".$displayOrganization."</span></span></a></li>";
								}
							}
							else {
								echo "<li class='puu-nobody'>".language('sciomio_text_insights_no_birthday')."</li>";
							}
							?>
						</ul>

					</div>

					<div class="puu-subset puu-tomorrow">

						<h3><span class="puu-change-value"><?php echo language('sciomio_word_tomorrow'); ?></span> <a class="puu-change" href="dmy"><?php echo language('sciomio_word_insights_other_day'); ?></a></h3>

						<div id="insightsBirthdayWindow">
						</div>

					</div>

					<div class="puu-subset puu-cal">

						<h3><?php echo language('sciomio_header_insights_calendar'); ?></h3>

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

					</div>

				</div>
			</section>
		</div>
		<br clear="all">
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
		addLoadEvent(function() {ScioMino.InsightsBirthday.load('tomorrow');});
	</script>

</body>
</html>

