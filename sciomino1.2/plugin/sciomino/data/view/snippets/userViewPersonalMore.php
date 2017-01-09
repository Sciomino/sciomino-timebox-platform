<div class="section">

<?php
if ($session['response']['param']['listType'] == "k1") {
	$typeList = $session['response']['param']['knowledgeList1'];
	$typeListEntry = "field";
	$typeName = "knowledge";
	$typeUrlPrefix = "k";
	$typeUrlSuffix = "=1";
	$typeHeading = "(".language('sciomio_word_knowledgefield_1').")";
}
if ($session['response']['param']['listType'] == "k2") {
	$typeList = $session['response']['param']['knowledgeList2'];
	$typeListEntry = "field";
	$typeName = "knowledge";
	$typeUrlPrefix = "k";
	$typeUrlSuffix = "=2";
	$typeHeading = "(".language('sciomio_word_knowledgefield_2').")";
}
if ($session['response']['param']['listType'] == "k3") {
	$typeList = $session['response']['param']['knowledgeList3'];
	$typeListEntry = "field";
	$typeName = "knowledge";
	$typeUrlPrefix = "k";
	$typeUrlSuffix = "=3";
	$typeHeading = "(".language('sciomio_word_knowledgefield_3').")";
}
if ($session['response']['param']['listType'] == "h") {
	$typeList = $session['response']['param']['hobbyList'];
	$typeListEntry = "field";
	$typeName = "hobby";
	$typeUrlPrefix = "h";
	$typeUrlSuffix = "";
	$typeHeading = "";
}
if ($session['response']['param']['listType'] == "t") {
	$typeList = $session['response']['param']['tagList'];
	$typeListEntry = "name";
	$typeName = "tag";
	$typeUrlPrefix = "t";
	$typeUrlSuffix = "";
	$typeHeading = "";
}
if ($session['response']['param']['listType'] == "tl") {
	$typeList = $session['response']['param']['networkList'];
	$typeListEntry = "Name";
	$typeName = "network";
	$typeUrlPrefix = "tl";
	$typeUrlSuffix = "public";
	$typeHeading = "";
}

echo "<h2>".language('sciomio_text_user_personal_others')." ".language("sciomio_word_filter_".$typeName)." ".$typeHeading."</h2>";

if (count($typeList) > 0) {
	echo "<ul class='linklist index'>";
	echo "<li>";
	echo "<ul>\n";
	foreach ($typeList as $type) {
		if ($session['response']['param']['listType'] == "tl") {
			echo "<li><a href='".$XCOW_B['url']."/search?".$typeUrlPrefix."[".$typeUrlSuffix."]=".urlencode($type[$typeListEntry])."'>".$type[$typeListEntry]."</a></li>\n";
		}
		else {
			echo "<li><a href='".$XCOW_B['url']."/search?".$typeUrlPrefix."[".urlencode($type[$typeListEntry])."]".$typeUrlSuffix."'>".$type[$typeListEntry]."</a></li>\n";
		}
	}
	echo "</ul>\n";
	echo "</ul>\n";
}
else {
	echo "<p>".language('sciomio_word_not_found')."</p>";
}

?>

</div>
