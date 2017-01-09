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

		<h1 style="padding-top:44px;"><?php echo language('sciomio_header_login_title'); ?></h1>
		<p class="puu-sum" style="margin-bottom:20px"><?php echo language('sciomio_header_login_title_tagline'); ?></p>

		<div style="padding-top:80px; padding-bottom:88px; background: url('/ui/skin/<?php echo $session['response']['param']['skin'] ?>/gfx/login/bg_home.png') no-repeat 50% 0px">

			<div class="puu-shots">
				<!-- en:78580762/nl:78580761-->
				<!--?title=0&amp;byline=0&amp;portrait=0-->
				<?php $videoId="78054851"; if ($session['response']['language'] == "nl") { $videoId="76502478"; } ?>
				<iframe name="videoFrame" src="//player.vimeo.com/video/<?php echo $videoId;?>" width="632" height="355" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
				<br/>
				<div style="background-color:#333333;opacity:0.7;filter:alpha(opacity=70);color:#ffffff;font-size:0.8em;padding:0 10px;text-align:right;float:right;">
					animation by <a style="color:white" target="_blank" href="http://www.pitchparrot.com">Pitch Parrot</a>
				</div>
			</div>

			<div class="puu-pitch" style="background-color: #2e3639; height:340px;">
			    <div id="sessionRegisterView">
			    </div>
			</div>

		</div>

		<h1><?php echo language('sciomio_header_login_work'); ?></h1>
		<p class="puu-sum"><?php echo language('sciomio_header_login_tagline'); ?> <a href="http://business.sciomino.com/" class="puu-fup"><?php echo language('sciomio_text_login_tagline_link'); ?></a></p>

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
	//addLoadEvent(function() {Session.Register.load();});
	Session.Login.load();
</script>

</body>
</html>
