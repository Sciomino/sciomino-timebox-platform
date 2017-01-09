<div class="section">
<?php
	if (! $session['response']['param']['twitterOk']) {
		echo "<p>".language('sciomio_text_twitter_connect')."</p>";
	}
	else {
		echo "<form action='".$XCOW_B['url']."/snippet/tweet-new-form' method='post'>";
		echo "<input type='hidden' name='user' value='".$session['response']['param']['user']."'>";
		echo "<div>";
		echo language('sciomio_text_twitter')." ".$session['response']['param']['user'];
		echo "</div>";
		echo "<fieldset class='simpleForm'>";
		echo "<label for='message-u'>".language('sciomio_text_twitter_message')."</label>";
		echo "<textarea name='com_tweet' rows='5' cols='40' id='message-u' maxlength='140'>".$session['response']['param']['user']."</textarea>";
		echo "<input class='submit' type='submit' name='some_name' value='".language('sciomio_text_twitter_toevoegen')."'>";
		echo "</fieldset>";
		echo "</form>";
	}
?>
</div>
