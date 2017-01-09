<?php
foreach ($session['response']['param']['focusList'] as $focus) {
	echo "<div class='article focus'>";
	echo "<h3>".language('sciomio_header_user_focus_item')."</h3>";
	echo "<dl>";

	$focusItems = explode ('&', $focus['Value']);
	foreach ($focusItems as $item) {
		if (preg_match ("/^n=(.*)$/", $item, $matches)) {
			echo "<dt>".language('sciomio_word_focus_name')."</dt>";
			echo "<dd>".urldecode($matches[1])." </dd>";
		}
		if (preg_match ("/^q=(.*)$/", $item, $matches)) {
			echo "<dt>".language('sciomio_word_focus_query')."</dt>";
			echo "<dd>".urldecode($matches[1])." </dd>";
		}
		if (preg_match ("/^k\[(.*)\](.*)$/", $item, $matches)) {
			echo "<dt>".language('sciomio_word_focus_knowledge')."</dt>";
			echo "<dd>".urldecode($matches[1])." </dd>";
		}
		if (preg_match ("/^h\[(.*)\](.*)$/", $item, $matches)) {
			echo "<dt>".language('sciomio_word_focus_hobby')."</dt>";
			echo "<dd>".urldecode($matches[1])." </dd>";
		}
		if (preg_match ("/^e\[(.*)\]\[(.*)\](.*)$/", $item, $matches)) {
			$languageString = "sciomio_word_focus_".$matches[1];
			echo "<dt>".language($languageString)."</dt>";
			echo "<dd>".urldecode($matches[2])." </dd>";
		}
		if (preg_match ("/^t\[(.*)\](.*)$/", $item, $matches)) {
			echo "<dt>".language('sciomio_word_focus_tag')."</dt>";
			echo "<dd>".urldecode($matches[1])." </dd>";
		}
		if (preg_match ("/^p\[(.*)\]=(.*)$/", $item, $matches)) {
			$languageString = "sciomio_word_focus_".$matches[1];
			echo "<dt>".language($languageString)."</dt>";
			echo "<dd>".urldecode($matches[2])." </dd>";
		}
		if (preg_match ("/^l\[(.*)\](.*)$/", $item, $matches)) {
			echo "<dt>".language('sciomio_word_focus_list')."</dt>";
			echo "<dd>".urldecode($matches[1])." </dd>";
		}
		if (preg_match ("/^tl\[(.*)\]=(.*)$/", $item, $matches)) {
			$languageString = "sciomio_word_focus_".$matches[1]."List";
			echo "<dt>".language($languageString)."</dt>";
			echo "<dd>".urldecode($matches[2])." </dd>";
		}
	}

	echo "</dl>";
	echo "<a href='".$XCOW_B['url']."/search?".$focus['Value']."'>".language('sciomio_word_user_focus_view')."</a>";
	echo " | <a href='javascript:ScioMino.FocusDelete.action({$focus['Id']});'>".language('sciomio_word_user_focus_delete')."</a>";
	echo "</div>";
}

?>

