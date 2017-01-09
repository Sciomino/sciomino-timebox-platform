<!-- KNOWLEDGE-->
<?php
if (count($session['response']['param']['knowledgeList']) > 0) {
	#echo "<span class='sectionhead'>".language('sciomio_header_browse_knowledge_listLarge')."</span>";
	echo "<ul class='linklist index'>";
	echo "<li>";
	#echo "<li><span class='sectionhead'>".$session['response']['param']['start']."</span>";
	echo "<ul>\n";
	foreach ($session['response']['param']['knowledgeList'] as $knowledgeKey => $knowledgeVal) {
		echo "<li><a href='".$XCOW_B['url']."/browse/knowledge?k=".urlencode($knowledgeKey)."'>$knowledgeKey  <span class='count'>($knowledgeVal)</span></a></li>\n";
	}
	#echo "<li><a class='more' href='#'>Meer&hellip;</a></li>";
	echo "</ul>\n";
	echo "</ul>\n";
}
else {
	echo language('sciomio_text_knowledge_none');
}

if ($session['response']['param']['thereIsMore']) {
	echo "<a class='more' href='javascript:ScioMino.ListKnowledgeFields.loadAlphabet(\"".$session['response']['param']['start']."\",".$session['response']['param']['newLimit'].")'>".language('sciomio_word_more')."</a>";
}

?>

