<div class="section">

<?php
	echo "<h2>".language('sciomio_word_all')." ".language("sciomio_word_filter_".$session['response']['param']['localType'])."</h2>";

	if (count($session['response']['param']['localList']) > 0) {
		echo "<ul class='linklist index'>";
		echo "<li>";
		echo "<ul>\n";
		foreach ($session['response']['param']['localList'] as $localKey => $localVal) {
			echo "<li><a href='".$XCOW_B['url']."/search?p[".$session['response']['param']['localType']."]=".urlencode($localKey)."'>$localKey  <span class='count'>($localVal)</span></a></li>\n";
		}
		echo "</ul>\n";
		echo "</ul>\n";
	}
	else {
		echo "<p>".language('sciomio_word_not_found')."</p>";
	}
?>

</div>
