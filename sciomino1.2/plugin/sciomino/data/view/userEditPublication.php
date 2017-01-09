<?php
    $page = 'personen';
    $mijnprofiel = 'publicaties';

?>
<!DOCTYPE html>
<html lang="nl" class="no-js">
<head>
	<meta charset="UTF-8" />

	<title><?php echo language('sciomio_title_user'); ?></title>

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

	    <?php include 'includes/nav-user.php'; ?>

        </div>
	
	<div id="Content">
			<div style="height:1px;"></div>
		<div class="page">
			<div class="unit unit2-3">

				<div class="section form" id="Form-profile">
				    <div class="hgroup">
					<h2><?php echo language('sciomio_header_user_publication'); ?></h2>
					<h3><?php echo language('sciomio_text_user_publication'); ?></h3>
				    </div>

				    <fieldset class="divider interactive-set"> 
					<div class="fieldset-info highlight">
					    <p><?php echo language('sciomio_text_user_publication_connect'); ?> <a href="<?php echo $XCOW_B['url'] ?>/setting/connect"><?php echo language('sciomio_word_user_publication_connect'); ?></a></p>
					</div>
					<h3 class="legend"><?php echo language('sciomio_header_user_publication_blog'); ?></h3>

					<div id="blogListWindow">
						<img src="<?php echo $XCOW_B['url'] ?>/gfx/ajax-loader-circle.gif">
					</div>

				    </fieldset>

				    <fieldset class="divider interactive-set">
					<h3 class="legend"><?php echo language('sciomio_header_user_publication_share'); ?></h3>

					<div id="shareListWindow">
						<img src="<?php echo $XCOW_B['url'] ?>/gfx/ajax-loader-circle.gif">
					</div>

				    </fieldset>

				    <fieldset class="divider interactive-set">
					<h3 class="legend"><?php echo language('sciomio_header_user_publication_website'); ?></h3>

					<div id="websiteListWindow">
						<img src="<?php echo $XCOW_B['url'] ?>/gfx/ajax-loader-circle.gif">
					</div>

				    </fieldset>

				    <fieldset class="interactive-set">
					<h3 class="legend"><?php echo language('sciomio_header_user_publication_otherPub'); ?></h3>

					<div id="otherPubListWindow">
						<img src="<?php echo $XCOW_B['url'] ?>/gfx/ajax-loader-circle.gif">
					</div>

				    </fieldset>

				    <fieldset class="final">
					<div class="inputset buttons disabled">
					    <input class="submit button-saveall" type="submit" value="<?php echo language('sciomio_text_publication_all_toevoegen'); ?>" />
					    <div class="cancelbox">
						<?php echo language('sciomio_word_or'); ?> <a class="resetall" href="<?php echo $XCOW_B['url'] ?>/user/publication"><?php echo language('sciomio_word_resetAll'); ?></a>
					    </div>
					</div>
				    </fieldset>

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
		addLoadEvent(function() {ScioMino.BlogList.load();});
		addLoadEvent(function() {ScioMino.ShareList.load();});
		addLoadEvent(function() {ScioMino.WebsiteList.load();});
		addLoadEvent(function() {ScioMino.OtherPubList.load();});
	</script>

</body>
</html>
