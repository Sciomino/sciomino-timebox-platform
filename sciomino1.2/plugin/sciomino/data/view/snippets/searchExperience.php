<?php
	# title
	if ($session['response']['param']['output'] == "title" || $session['response']['param']['output'] == "all" ) {
		if ($session['response']['param']['type'] == "Product") { $titleDisplay = language('sciomio_word_search_focus_allProducts'); }
		if ($session['response']['param']['type'] == "Company") { $titleDisplay = language('sciomio_word_search_focus_allCompanies'); }
		if ($session['response']['param']['type'] == "Event") { $titleDisplay = language('sciomio_word_search_focus_allEvents'); }
		if ($session['response']['param']['type'] == "Education") { $titleDisplay = language('sciomio_word_search_focus_allEducations'); }

		echo "<ul>";
		echo "<li class='all'><a href='".$XCOW_B['url']."/search?".$session['response']['param']['focus']."&e[".urlencode($session['response']['param']['type'])."][".urlencode($session['response']['param']['experience'])."]=,".urlencode(urlencode($session['response']['param']['prevAlternative'])).",".$session['response']['param']['prevLike'].",".$session['response']['param']['prevHas']."'>{$titleDisplay}</a></li>\n";

		foreach ($session['response']['param']['titleList'] as $titleKey => $titleVal) {
			echo "<li><a href='".$XCOW_B['url']."/search?".$session['response']['param']['focus']."&e[".urlencode($session['response']['param']['type'])."][".urlencode($session['response']['param']['experience'])."]=".urlencode(urlencode($titleKey)).",".urlencode(urlencode($session['response']['param']['prevAlternative'])).",".$session['response']['param']['prevLike'].",".$session['response']['param']['prevHas']."'>$titleKey ($titleVal)</a></li>\n";
		}
		echo "</ul>";
	}

	# alternative
	if ($session['response']['param']['output'] == "alternative" || $session['response']['param']['output'] == "all" ) {
		echo "<ul>";
		echo "<li class='all'><a href='".$XCOW_B['url']."/search?".$session['response']['param']['focus']."&e[".urlencode($session['response']['param']['type'])."][".urlencode($session['response']['param']['experience'])."]=".urlencode(urlencode($session['response']['param']['prevTitle'])).",,".$session['response']['param']['prevLike'].",".$session['response']['param']['prevHas']."'>".language('sciomio_word_search_focus_allProductAlternatives')."</a></li>\n";

		foreach ($session['response']['param']['alternativeList'] as $alternativeKey => $alternativeVal) {
			echo "<li><a href='".$XCOW_B['url']."/search?".$session['response']['param']['focus']."&e[".urlencode($session['response']['param']['type'])."][".urlencode($session['response']['param']['experience'])."]=".urlencode(urlencode($session['response']['param']['prevTitle'])).",".urlencode(urlencode($alternativeKey)).",".$session['response']['param']['prevLike'].",".$session['response']['param']['prevHas']."'>$alternativeKey ($alternativeVal)</a></li>\n";
		}
		echo "</ul>";
	}

	# like
	if ($session['response']['param']['output'] == "like" || $session['response']['param']['output'] == "all" ) {
		echo "<ul>";
		echo "<li class='all'><a href='".$XCOW_B['url']."/search?".$session['response']['param']['focus']."&e[".urlencode($session['response']['param']['type'])."][".urlencode($session['response']['param']['experience'])."]=".urlencode(urlencode($session['response']['param']['prevTitle'])).",".urlencode(urlencode($session['response']['param']['prevAlternative'])).",,".$session['response']['param']['prevHas']."'>".language('sciomio_word_search_focus_allLike')."</a></li>\n";

		foreach ($session['response']['param']['likeList'] as $likeKey => $likeVal) {
			$languageString = "sciomio_word_like_".$likeKey;
			echo "<li><a href='".$XCOW_B['url']."/search?".$session['response']['param']['focus']."&e[".urlencode($session['response']['param']['type'])."][".urlencode($session['response']['param']['experience'])."]=".urlencode(urlencode($session['response']['param']['prevTitle'])).",".urlencode(urlencode($session['response']['param']['prevAlternative'])).",".$likeKey.",".$session['response']['param']['prevHas']."'>".language($languageString)." ($likeVal)</a></li>\n";
		}
		echo "</ul>";
	}

	# has
	if ($session['response']['param']['output'] == "has" || $session['response']['param']['output'] == "all" ) {
		echo "<ul>";
		echo "<li class='all'><a href='".$XCOW_B['url']."/search?".$session['response']['param']['focus']."&e[".urlencode($session['response']['param']['type'])."][".urlencode($session['response']['param']['experience'])."]=".urlencode(urlencode($session['response']['param']['prevTitle'])).",".urlencode(urlencode($session['response']['param']['prevAlternative'])).",".$session['response']['param']['prevLike'].",'>".language('sciomio_word_search_focus_allHas')."</a></li>\n";

		foreach ($session['response']['param']['hasList'] as $hasKey => $hasVal) {
			$languageString = "sciomio_word_has_".$hasKey;
			echo "<li><a href='".$XCOW_B['url']."/search?".$session['response']['param']['focus']."&e[".urlencode($session['response']['param']['type'])."][".urlencode($session['response']['param']['experience'])."]=".urlencode(urlencode($session['response']['param']['prevTitle'])).",".urlencode(urlencode($session['response']['param']['prevAlternative'])).",".$session['response']['param']['prevLike'].",".$hasKey."'>".language($languageString)." ($hasVal)</a></li>\n";
		}
		echo "</ul>";
	}

?>

