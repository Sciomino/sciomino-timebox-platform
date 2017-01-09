<?php
$page = 'personen';
?>
<!DOCTYPE html>

<html lang="nl" class="no-js">
    <head>
        <meta charset="UTF-8" />

        <title><?php echo language('sciomio_title_error'); ?></title>

		<?php include("includes/headers.php"); ?>

        <?php include 'skin/'.$session['response']['param']['skin'].'/css.php'; ?>

    </head>

    <body>

        <?php include 'skin/'.$session['response']['param']['skin'].'/header.php'; ?>

        <div id="Header">
            <div class="page">
           </div>
        </div>

        <div id="Content">
			<div style="height:1px;"></div>
            <div class="page">

				<div class="section">
					<h1><?php echo language('sciomio_header_error'); ?></h1>
					<h4><?php echo language('sciomio_header_error_401'); ?></h4>
					<br/>
					<img src="<?php echo $XCOW_B['url'] ?>/ui/gfx/error-sciomino.jpg" alt="Error Sciomino" width="990" height="476"/>
					<p><a target="blank'" href="http://polomski.art.pl/?lang=en">Jakub Polomski</a></p>
				</div>

            </div>
        </div>

        <div id="Footer">
            <div class="page">

                <?php include 'includes/footer.php'; ?>

            </div>
        </div>

        <?php include 'includes/scripts.php'; ?>

    </body>
</html>

