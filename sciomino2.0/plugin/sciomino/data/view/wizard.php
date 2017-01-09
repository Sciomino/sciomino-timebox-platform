<!DOCTYPE html>

<html lang="nl" class="no-js">
    <head>
        <meta charset="UTF-8" />

        <title><?php echo language('sciomio_title_wizard'); ?></title>

		<?php include("includes/headers.php"); ?>
        <?php include 'skin/'.$session['response']['param']['skin'].'/css.php'; ?>

		<!-- only on this wizard -->
		<link rel="stylesheet" href="/css/session.css" />

    </head>

    <body>

        <?php include 'skin/'.$session['response']['param']['skin'].'/header.php'; ?>

        <div id="Header">
            <div class="page">
<?php
if ($session['response']['param']['skin'] == "sciomino") {
	echo "<div style='float:left; padding-left:15px;'><a class='logo' href='".$XCOW_B['url']."/'><img height='24px' src='".$XCOW_B['url']."/ui/skin/sciomino/gfx/logo_sciomino_transp.png' alt='Sciomino'/></a></div>";
}
?>
            </div>
        </div>

        <div id="Content">
			<div style="height:1px;"></div>
            <div class="page" style="width:670px">
				<h1><?php echo language(sciomio_header_wizard); ?></h1>
				<div class="section userbox solo highlight" style="overflow:visible;padding:10px;">
					<p><img src="<?php echo $XCOW_B['url']; ?>/ui/skin/sciomino/gfx/wizard_step<?php echo $session['response']['param']['step'] ?>_<?php echo $session['response']['language'] ?>.png" /></p>
					<div class="user-info" style="padding:10px; padding-bottom:70px;">
						<div id="wizardWindow">
							<img src="<?php echo $XCOW_B['url'] ?>/gfx/ajax-loader-circle.gif">
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
			addLoadEvent(function() {ScioMino.Wizard.load(<?php echo $session['response']['param']['step']?>);});
		</script>

	<div id="UploadHidden" style="position:absolute; top:-1000px; left:-1000px;">
		<iframe name="submitFrame" id="submitFrame">
		</iframe>
	</div>

    </body>
</html>
