<?php
if (count($session['response']['param']['localList']) > 0) {
	#echo "<span class='sectionhead'>".language('sciomio_header_browse_tag_listLarge')."</span>";
	echo "<ul class='linklist index'>";
	echo "<li>";
	echo "<ul>\n";
	foreach ($session['response']['param']['localList'] as $localKey => $localVal) {
		echo "<li><a href='".$XCOW_B['url']."/browse/tag?t=".urlencode($localKey)."'>$localKey  <span class='count'>($localVal)</span></a></li>\n";
	}
	echo "</ul>\n";
	echo "</ul>\n";
}
else {
	echo language('sciomio_text_tag_none');
}

if ($session['response']['param']['thereIsMore']) {
	echo "<a class='more' href='javascript:ScioMino.ListTagNames.loadAlphabet(\"".$session['response']['param']['start']."\",".$session['response']['param']['newLimit'].")'>".language('sciomio_word_more')."</a>";
}

?>

