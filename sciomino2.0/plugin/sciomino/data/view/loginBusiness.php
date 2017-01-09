<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="<?php echo $session['response']['language']; ?>">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta name="author" content="Sciomino"> 
	<title><?php echo language('sciomio_title_login'); ?></title>

	<!-- only on this login page -->
	<link rel="shortcut icon" href="<?php echo $XCOW_B['url'] ?>/ui/skin/<?php echo $session['response']['param']['skin'] ?>/gfx/favicon.ico" />
	<?php include("includes/headers-login.php"); ?>
	<link rel="stylesheet" href="/css/session.css" />

</head>

<body style="background-color:#e7e7e7;margin:0px;">
<div class="puu-main" style="padding-top:1px;background-color:white;border-left: 1px solid #c8c8c8;border-right: 1px solid #c8c8c8">
	
    <?php include 'skin/'.$session['response']['param']['skin'].'/header-login.php'; ?>

	<div class="puu-cnt puu-home">

		<div style="padding-top:80px; padding-bottom:88px; background: url('/ui/skin/<?php echo $session['response']['param']['skin'] ?>/gfx/bg_home.png') no-repeat 50% 0px">

			<div class="puu-pitch" style="background-color: #2e3639; height:340px;width:326px;margin-left:auto;margin-right:auto;">
			    <div id="sessionRegisterView">
			    </div>
			</div>

		</div>

	</div>

</div>

<?php include("includes/footer-login.php"); ?>

<div id="sessionPopup" style="display:none">
	<div id="sessionPopupMenu">
	     <a href="javascript:Session.Window.close();"><?php echo language('sciomio_word_close'); ?></a>
	</div>
	<div id="sessionPopupData">
	</div>
</div>

<?php include 'includes/scripts.php'; ?>
<script type="text/javascript">
	addLoadEvent(function() {Session.Login.load();});
</script>

</body>
</html>
