<!-- KNOWLEDGE-->
<?php
if (count($session['response']['param']['knowledgeList']) > 0) {
	echo "<ul class='tagcloud'>";
	foreach ($session['response']['param']['knowledgeList'] as $knowledgeKey => $knowledgeVal) {
		$curSize = 'xl';
		if ($knowledgeVal <= $session['response']['param']['maxVal'] - (1 * ($session['response']['param']['interVal']+1))) { $curSize = 'l'; }
		if ($knowledgeVal <= $session['response']['param']['maxVal'] - (2 * ($session['response']['param']['interVal']+1))) { $curSize = 'm'; }
		if ($knowledgeVal <= $session['response']['param']['maxVal'] - (3 * ($session['response']['param']['interVal']+1))) { $curSize = 's'; }
		if ($knowledgeVal <= $session['response']['param']['maxVal'] - (4 * ($session['response']['param']['interVal']+1))) { $curSize = 'xs'; }
		echo "<li><a class='".$curSize."' href='".$XCOW_B['url']."/browse/knowledge?k=".urlencode($knowledgeKey)."'>$knowledgeKey</a> </li>\n";
	}
	echo "</ul>\n";
}
?>

