	<div class="article">
		<?php

		if (! empty($session['response']['param']['twitterAccount']) ) {
			echo "<h3><a class='icon icon-twitter-xl' href='https://twitter.com/".$session['response']['param']['twitterAccount']."'>".$session['response']['param']['twitterAccount']."</a></h3>";

			if ($session['response']['param']['myTwitterInfo']['displayFollow']) {

				echo "<div><p>".language('sciomio_text_twitter_following_'.$session['response']['param']['myTwitterInfo']['following']);
				echo "<br/>".language('sciomio_text_twitter_followby_'.$session['response']['param']['myTwitterInfo']['followedby'])."</p></div>";
			}

			// get twitter feed from javascript
			echo "<div id='twitter-feed'>";
			echo "<img src='".$XCOW_B['url']."/gfx/ajax-loader-circle.gif'>";
			echo "</div>";
		
			echo "<p><a target='_blank' href='https://twitter.com/".$session['response']['param']['twitterAccount']."' class='more'>".language('sciomio_word_moreTweets')."</a></p>";
		}
	
		?>

	</div>
