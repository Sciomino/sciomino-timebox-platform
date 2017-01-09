<div class="article">
	<?php
		echo "<div style='float:left;width:100px;'>";
		echo "<img height='96px' src='".$XCOW_B['url']."/ui/gfx/logo_linkedin.png'>";
		echo "</div>";
		
		echo "<div style='float:left;width:240px; padding-top:10px; padding-left:10px; padding-right:10px;'>";
		echo "<b>LinkedIn</b>";
		echo "<br/><p style='padding-top:4px;'>".language('sciomio_text_wizard_linkedin')."</p>";
		echo "</div>";
		
		echo "<div style='float:left;width:200px; padding-top:10px; padding-left:10px'>";
		if (in_array("linkedin", $session['response']['param']['apps'])) {
			if ($session['response']['param']['linkedinReload']) {
				echo "<p>".language('sciomio_text_connect_linkedin_error')."<a href='".$XCOW_B['url']."/oauth/connect?app=linkedin&action=request'>".language('sciomio_text_connect_linkedin_renew')."</a></p>";
			}
			else {
				echo language('sciomio_text_connect_ok')." <a href='".$XCOW_B['url']."/oauth/connect?app=linkedin&action=invalidate'>".language('sciomio_word_disconnect')."</a>";
				echo "<br/>".$session['response']['param']['linkedinDays'].language('sciomio_text_connect_linkedin_days')."<a href='".$XCOW_B['url']."/oauth/connect?app=linkedin&action=request'>".language('sciomio_text_connect_linkedin_renew')."</a>";
				echo "<br/><br/>".language('sciomio_text_connect_status')." ".$session['response']['param']['linkedin'];
			}
		}
		else {
			echo "<a href='".$XCOW_B['url']."/oauth/connect?app=linkedin&action=request'>".language('sciomio_word_connect')."</a>";
		}
		echo "</div>";
		
		echo "<br clear='left'><hr style='margin-bottom:10px;'/>";
	?>
</div>

<div class="article">
	<?php
		echo "<div style='float:left;width:100px;'>";
		echo "<img height='96px' src='".$XCOW_B['url']."/ui/gfx/logo_twitter.png'>";
		echo "</div>";
		
		echo "<div style='float:left;width:240px; padding-top:10px; padding-left:10px; padding-right:10px;'>";
		echo "<b>Twitter</b>";
		echo "<br/><p style='padding-top:4px;'>".language('sciomio_text_wizard_twitter')."</p>";
		echo "</div>";
		
		echo "<div style='float:left;width:200px; padding-top:10px; padding-left:10px'>";
		if (in_array("twitter", $session['response']['param']['apps'])) {
			echo language('sciomio_text_connect_ok')." <a href='".$XCOW_B['url']."/oauth/connect?app=twitter&action=invalidate'>".language('sciomio_word_disconnect')."</a>";
			echo "<br/><br/>".language('sciomio_text_connect_status')." ".$session['response']['param']['twitter'];
		}
		else {
			echo "<a href='".$XCOW_B['url']."/oauth/connect?app=twitter&action=request'>".language('sciomio_word_connect')."</a>";
		}
		echo "</div>";
		
		echo "<br clear='left'><hr style='margin-bottom:10px;'/>";
	?>
</div>
