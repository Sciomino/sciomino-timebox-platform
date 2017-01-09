<?php
$page = 'inzichten';
?>
<!DOCTYPE html>

<html lang="nl" class="no-js">
<head>
	<meta charset="UTF-8" />

	<title><?php echo language('sciomio_title_insights_location'); ?></title>

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
					if ($session['response']['param']['mode'] == "workplace") {
						echo "<h1><a href='".$XCOW_B['url']."/insights'>".language('sciomio_header_insights_home')."</a> ".language('sciomio_text_insights_location_workplace')."</h1>";
					}
					else {
						echo "<h1><a href='".$XCOW_B['url']."/insights'>".language('sciomio_header_insights_home')."</a> ".language('sciomio_text_insights_location_hometown')."</h1>";
					}
				?>			

				<!--<h1><a href="/insights"><?php echo language('sciomio_header_insights_home'); ?></a> <?php echo language('sciomio_header_insights_location'); ?></h1>-->
				<div class="puu-set puu-geo">
					<?php
						/*
						if ($session['response']['param']['mode'] == "workplace") {
							echo "<h2><a href='/insights/location?mode=hometown'><span class='puu-wrip'>".language('sciomio_text_insights_location_hometown')."</span></a></h2>";
						}
						else {
							echo "<h2><a href='/insights/location?mode=workplace'><span class='puu-wrip'>".language('sciomio_text_insights_location_workplace')."</span></a></h2>";
						}
						*/
					?>			

					<div class="puu-subset puu-sum">

						<?php
						$languageTemplate = array();
						$languageTemplate['count'] = $session['response']['param']['locations']['CityCount'];
						if ($session['response']['param']['mode'] == "workplace") {
							$languageText = 'sciomio_text_insights_workplace';
							if ($languageTemplate['count'] == 1) {
								$languageText = 'sciomio_text_insights_workplace_een';
							}
							echo "<h4>".language_template($languageText, $languageTemplate)."</h4>";
						}
						else {
							$languageText = 'sciomio_text_insights_hometown';
							if ($languageTemplate['count'] == 1) {
								$languageText = 'sciomio_text_insights_hometown_een';
							}
							echo "<h4>".language_template($languageText, $languageTemplate)."</h4>";
						}

						$languageTemplate['count'] = $session['response']['param']['locations']['CountryCount'];
						$languageText = 'sciomio_text_insights_country';
						if ($languageTemplate['count'] == 1) {
							$languageText = 'sciomio_text_insights_country_een';
						}
						echo "<p>".language_template($languageText, $languageTemplate)."</p>";
						?>
						
						<h5><?php echo language('sciomio_text_insights_location_topcity'); ?></h5>
						<ul>
							<li><!--<?php echo language('sciomio_text_insights_location_city'); ?>-->
								<ul>
								<?php
									foreach ($session['response']['param']['locations']['TopCity'] as $key => $val) {
										echo "<li>".$val['label']." (".$val['count'].")</li>";
									}
								?>
								</ul>
							</li>
						</ul>

						<h5><?php echo language('sciomio_text_insights_location_bottomcity'); ?></h5>
						<ul>
							<li><!--<?php echo language('sciomio_text_insights_location_city'); ?>-->
								<ul>
								<?php
									foreach ($session['response']['param']['locations']['BottomCity'] as $key => $val) {
										echo "<li>".$val['label']." (".$val['count'].")</li>";
									}
								?>
								</ul>
							</li>
						</ul>

					</div>
					
					<div id="map" class="puu-subset puu-map"></div>
					<div class="puu-clr"></div>

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
	<?php
		if ($session['response']['param']['mode'] == "workplace") {
			echo "<script type='text/javascript'>var MAP_JSON = new String('".$XCOW_B['url']."/snippet/search-detail?view=mapWorkplaceAll').toString();</script>";
		}
		else {
			echo "<script type='text/javascript'>var MAP_JSON = new String('".$XCOW_B['url']."/snippet/search-detail?view=mapHometownAll').toString();</script>";
		}
	?>
	<?php include 'includes/scripts-map.php'; ?>

	<script type="text/javascript">
		addLoadEvent(function() {Session.View.load();});
	</script>

</body>
</html>

