<?php
if ($session['response']['param']['externalContent'] == 1) {
	echo "<div class='section'>";
	echo "<ul>";
	echo "<li class='article'>";
	echo "<div class='img-item'>";
	echo "<div class='img'><img src='".$session['response']['param']['externalContentImage']."' width='30 alt='Event Logo' /></div>";
	echo "<div class='bd'>";
	echo "<h3>".$session['response']['param']['externalContentTitle']."</h3>";
	echo "<p>".$session['response']['param']['externalContentDescription']."</p>";
	echo "<a target='_blank' href='".$session['response']['param']['externalContentLink']."' class='more'>".language('sciomio_word_more')."</a>";
	echo "</div>";
	echo "</div>";
	echo "</li>";
	echo "</ul>";
	echo "</div>";
}
?>
