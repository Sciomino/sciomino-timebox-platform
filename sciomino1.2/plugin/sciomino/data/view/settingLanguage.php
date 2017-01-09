<?php
    $page = 'personen';
    $mijnprofiel = 'language';

?>
<!DOCTYPE html>
<html lang="nl" class="no-js">
<head>
	<meta charset="UTF-8" />

	<title><?php echo language('sciomio_title_setting'); ?></title>

	<?php include("includes/headers.php"); ?>

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

	    <?php if ($session['response']['param']['view'] == "local") { include 'includes/nav-setting-local.php'; } else { include 'includes/nav-setting.php'; } ?>

        </div>
	
	<div id="Content">
			<div style="height:1px;"></div>
		<div class="page">
			<div class="unit unit2-3">

				<div class="section">
				    <div class="hgroup">
					<h2><?php echo language('sciomio_header_setting_language'); ?></h2>
					<h3><?php echo language('sciomio_text_setting_language'); ?></h3>
					<br/>
				    </div>
		
					<div id="settingWindow">
						<?php
							if ($session['response']['language'] != "nl") {
								echo "<a href='/nl/setting/language'><img src='".$XCOW_B['url']."/ui/gfx/ico_nl.png'></a>";
							}
							if ($session['response']['language'] != "en") {
								echo "<a href='/en/setting/language'><img src='".$XCOW_B['url']."/ui/gfx/ico_en.png'></a>";
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
	<script type="text/javascript">
		addLoadEvent(function() {Session.View.load();});
	</script>

</body>
</html>
