	<div class="article">
		<?php

		if (! empty($session['response']['param']['twitterAccount']) ) {
			echo "<h3 style='padding-left: 15px;'><a class='icon icon-twitter-xl' href='https://twitter.com/search?q=".urlencode($session['response']['param']['query'])."'>".$session['response']['param']['query']."</a></h3>";

			// get twitter feed from javascript
			echo "<div id='twitter-feed'>";
			echo "<img src='".$XCOW_B['url']."/gfx/ajax-loader-circle.gif'>";
			echo "</div>";

			echo "<p><a target='_blank' href='https://twitter.com/search?q=".urlencode($session['response']['param']['query'])."' class='more'>".language('sciomio_word_moreTweets')."</a></p>";
		}
	
		?>

	</div>
