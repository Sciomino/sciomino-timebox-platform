<html>
<head>

	<meta http-equiv="content-type" content="text/html; charset=utf-8"> 

	<title><?php echo language(base_title_example); ?></title>

	<?php include("includes/headers.php"); ?>

	<script type="text/javascript">
		addLoadEvent(Session.View.load);
	</script>

</head>

<body>

<div class="page">
<div class="box">

<div class="header">

	<div class="logo">
		<a href="/"><img id="logo" src="/gfx/xcow_logo.png"></a>
	</div>

	<div class="user">
        	<div id="sessionView">
		</div>
	</div>

</div>

<div class="content">

	<div class="c_left3">
		<div class="paragraph_shadow">
		<div class="paragraph_line">
			<?php echo language(base_text_hello_world); ?>
		</div>
		</div>

		<div class="paragraph_shadow">
		<div class="paragraph_line">
			<?php echo language(base_text_choose_language); ?>
			<a href="/nl/web/mypage"><?php echo language(base_word_nederlands); ?></a>
			<a href="/en/web/mypage"><?php echo language(base_word_english); ?></a>
		</div>
		</div>

	</div>

	<div class="c_right3">
		<div class="paragraph">
		</div>
	</div>

</div>

<div class="footer">

	<div class="about-left">
		<?php include("includes/footer.php"); ?>
	</div>

	<div class="about-right">
		<a href="/"><?php echo language(base_word_home); ?></a>
		| <a href="javascript:enableDisplay('sessionPopup');Session.Register.load()"><?php echo language(base_word_register); ?></a>
	</div>

</div>

<!-- end box -->
</div>

<div id="sessionPopup" style="display:none">
	<div id="sessionPopupMenu">
	     <a href="javascript:Session.Window.close();"><?php echo language(base_word_close); ?></a>
	</div>
	<div id="sessionPopupData">
	</div>
</div>

<!-- end page -->
</div>

</body>
</html>
