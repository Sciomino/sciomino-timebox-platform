<?php
$page = 'personen';
?>
<!DOCTYPE html>

<html lang="nl" class="no-js">
    <head>
        <meta charset="UTF-8" />

        <title><?php echo language('graph_title_login'); ?></title>

		<?php include("includes/headers.php"); ?>

		<!-- only on this login page
		<link rel="stylesheet" href="/css/session.css" /> -->

    </head>

    <body>

		<div class="branded" id="Sciomino">
				<div class="page">
					&nbsp;
				<!-- <a class="logo" href="/"><img src="/gfx/graph/icon_sciomino_transp.png" alt="Sciomino"/></a>
				<span id="SciominoForText"><?php echo language('graph_text_main_subtitle'); ?></span> -->

				</div>
		</div>

        <div id="Header">
            <div class="page">
            </div>
        </div>

        <div id="Content">
            <div class="page">

				<div style="float:right">
					<img src="/gfx/graph/logo_sciomino.png" alt="Sciomino" style="height:50px"/>
					<br/><br/>
					<img src="/gfx/graph/logo_timebox.png" alt="Timebox" style="height:50px"/>
				</div>

                <div class="section">
					<h1><?php echo language('graph_header_login'); ?></h1>
                </div>

                <div class="section">
					<div id="sessionView">
					</div>
                </div>

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
			Event.AddOnload(function() {Session.Login.load();});
		</script>

    </body>
</html>

