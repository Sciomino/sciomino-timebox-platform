<?php
$page = 'inzichten';
?>
<!DOCTYPE html>

<html lang="nl" class="no-js">
<head>
	<meta charset="UTF-8" />

	<title><?php echo language('sciomio_title_insights_social'); ?></title>

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
		<?php
		switch ($session['response']['param']['list']) {
			case "twitter":
				echo "<div class='section puu-channel puu-twitter'>";
				break;
			case "linkedin":
				echo "<div class='section puu-channel puu-linkedin'>";
				break;
			case "blog":
				echo "<div class='section puu-channel puu-rss'>";
				break;
			case "presentation":
				echo "<div class='section puu-channel puu-slideshare'>";
				break;
			case "website":
				echo "<div class='section puu-channel puu-websites'>";
				break;
			case "publication":
				echo "<div class='section puu-channel puu-articles'>";
				break;
			default:
				echo "<div class='section puu-channel'>";
				break;
		}
		?>
			<section>
				<h1><a href="<?php echo $XCOW_B['url'] ?>/insights"><?php echo language('sciomio_header_insights_home'); ?></a> <?php echo language('sciomio_header_insights_social_'.$session['response']['param']['list']); ?></h1>
				<div class="puu-set">
					<?php
					switch ($session['response']['param']['list']) {
						case "twitter":
							$header = language('sciomio_text_insights_twitter');
							$count = $session['response']['param']['stats']['UserTwitterCount'];
							$percent = round(($session['response']['param']['stats']['UserTwitterCount']/$session['response']['param']['stats']['UserCount'])*100,1);
							$gauge = intval(50 - ($session['response']['param']['stats']['UserTwitterCount']/$session['response']['param']['stats']['UserCount'])*100/2);
							$description = language('sciomio_text_insights_twitter_description');
							$description_url = language('sciomio_text_insights_twitter_description_url');
							break;
						case "linkedin":
							$header = language('sciomio_text_insights_linkedin');
							$count = $session['response']['param']['stats']['UserLinkedinCount'];
							$percent = round(($session['response']['param']['stats']['UserLinkedinCount']/$session['response']['param']['stats']['UserCount'])*100,1);
							$gauge = intval(50 - ($session['response']['param']['stats']['UserLinkedinCount']/$session['response']['param']['stats']['UserCount'])*100/2);
							$description = language('sciomio_text_insights_linkedin_description');
							$description_url = language('sciomio_text_insights_linkedin_description_url');
							break;
						case "blog":
							$header = language('sciomio_text_insights_blog');
							$count = $session['response']['param']['stats']['UserBlogCount'];
							$percent = round(($session['response']['param']['stats']['UserBlogCount']/$session['response']['param']['stats']['UserCount'])*100,1);
							$gauge = intval(50 - ($session['response']['param']['stats']['UserBlogCount']/$session['response']['param']['stats']['UserCount'])*100/2);
							$description = language('sciomio_text_insights_blog_description');
							$description_url = language('sciomio_text_insights_blog_description_url');
							break;
						case "presentation":
							$header = language('sciomio_text_insights_presentation');
							$count = $session['response']['param']['stats']['UserPresentationCount'];
							$percent = round(($session['response']['param']['stats']['UserPresentationCount']/$session['response']['param']['stats']['UserCount'])*100,1);
							$gauge = intval(50 - ($session['response']['param']['stats']['UserPresentationCount']/$session['response']['param']['stats']['UserCount'])*100/2);
							$description = language('sciomio_text_insights_presentation_description');
							$description_url = language('sciomio_text_insights_presentation_description_url');
							break;
						case "website":
							$header = language('sciomio_text_insights_website');
							$count = $session['response']['param']['stats']['UserWebsiteCount'];
							$percent = round(($session['response']['param']['stats']['UserWebsiteCount']/$session['response']['param']['stats']['UserCount'])*100,1);
							$gauge = intval(50 - ($session['response']['param']['stats']['UserWebsiteCount']/$session['response']['param']['stats']['UserCount'])*100/2);
							$description = language('sciomio_text_insights_website_description');
							$description_url = language('sciomio_text_insights_website_description_url');
							break;
						case "publication":
							$header = language('sciomio_text_insights_other_publication');
							$count = $session['response']['param']['stats']['UserOtherPubCount'];
							$percent = round(($session['response']['param']['stats']['UserOtherPubCount']/$session['response']['param']['stats']['UserCount'])*100,1);
							$gauge = intval(50 - ($session['response']['param']['stats']['UserOtherPubCount']/$session['response']['param']['stats']['UserCount'])*100/2);
							$description = language('sciomio_text_insights_other_publication_description');
							$description_url = language('sciomio_text_insights_other_publication_description_url');
							break;
					}

					#echo "<h2>".$header."</h2>";

					echo "<div class='puu-subset puu-sum'>";
						echo "<span class='puu-score'>".$count." <span class='puu-percent'>(".$percent."%)</span> <img class='puu-gauge' style='background-position: 2px ".$gauge."px' alt='' src='".$XCOW_B['url']."/ui/insight/gauge.gif' width='30' height='50'></span>";
						echo "<span class='puu-score puu-neg'><img class='puu-gauge' style='background-position: 2px ".(50-$gauge)."px' alt='' src='".$XCOW_B['url']."/ui/insight/gauge.gif' width='30' height='50'> ".($session['response']['param']['stats']['UserCount']-$count)." <span class='puu-percent'>(".(100-$percent)."%)</span></span>";
						echo "<blockquote class='puu-pedia' cite='".$description_url."'>";
							echo $description;
						echo "</blockquote>";
					echo "</div>";
					?>

					<form class="puu-find" onsubmit="return false;">
					<input onkeyup="javascript:ScioMino.InsightsList.loadQuery('<?php echo $session['response']['param']['list']; ?>',10);" class="puu-key placeholder" type="text" name="query" id="insightListBox" value="<?php echo language('sciomio_text_insights_search_list'); ?>" placeholder="<?php echo language('sciomio_text_insights_search_list'); ?>" maxlength="32"/>
					</form>

					<div id="insightsListWindow">
						<img src="<?php echo $XCOW_B['url'] ?>/gfx/ajax-loader-circle.gif">
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
	<?php include 'includes/scripts-linkedin.php'; ?>
	<script type="text/javascript">
		addLoadEvent(function() {Session.View.load();});
		addLoadEvent(function() {ScioMino.InsightsList.load("<?php echo $session['response']['param']['list']; ?>", 10)});
	</script>

</body>
</html>

