<div class="xcow_paragraph_reverse">

	<div class="xcow_header xcow_extra_space">
		<h2><?php echo language(session_header_register); ?></h2>
	</div>
	<div class="xcow_paragraph xcow_extra_space text_space">
	   	<p><?php echo language($session['response']['param']['status']); ?></p>
	</div>

<?php
if ($session['response']['param']['skin'] == "sciomino") {
	echo "<div class='xcow_paragraph xcow_extra_space'>";
	echo "<p><a target='_blank' href='https://www.twitter.com/intent/tweet?url=".urlencode($XCOW_B['this_host'])."&text=".urlencode(language(sciomio_text_login_tweet))."'><img width='32px' src='".urlencode($XCOW_B['this_url'])."/ui/gfx/logo_twitter.png'>tweet this</a></p>";
	echo "</div>";
}
?>
	
</div>

