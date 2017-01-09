<?php

	echo "<ul>";
	echo "<li class='all'><a href='".$XCOW_B['url']."/search?".$session['response']['param']['focus']."&k[".urlencode($session['response']['param']['knowledge'])."]'>".language('sciomio_word_search_focus_allKnowledge')."</a></li>\n";

foreach ($session['response']['param']['levelList'] as $levelKey => $levelVal) {

	#<li><a href="#">*** Expert (3)</a></li>
	#<li><a class="current" href="#">** Medior (10)</a></li>
	$languageString = "sciomio_word_knowledgefield_".$levelKey;
	echo "<li><a href='".$XCOW_B['url']."/search?".$session['response']['param']['focus']."&k[".urlencode($session['response']['param']['knowledge'])."]=".$levelKey."'>".language($languageString)." ($levelVal)</a></li>\n";

}

	echo "</ul>";

?>

