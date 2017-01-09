<!-- RESULT -->
<?php
if (count($session['response']['param']['actList']) > 0) {

	if ($session['response']['param']['label'] != "") {
		echo "<h3>".$session['response']['param']['label']."</h3>";
	}

	echo "<ul>";

	foreach ($session['response']['param']['actList'] as $actKey => $actVal) {
		// who is this act from?
		$userRefByActRef = get_id_from_multi_array($session['response']['param']['userList'], 'Reference', $actVal['Reference']);
		$userVal = $session['response']['param']['userList'][$userRefByActRef];

		$actType = "";
		$actTypeString = "";
		if ( ($actVal['Timestamp'] + $actVal['Expiration']) < time() ) {
			$actType = "puu-expired";
			$actTypeString = language('sciomio_text_act_time_expired');
		}
		else {
			$actType = "";
			// inverse future time, because timeDiff only calculates past times...
			$inverseTime = time() - ($actVal['Timestamp'] + $actVal['Expiration'] - time());
			$languageTemplate = array();
			$languageTemplate['time'] = timeDiff($inverseTime);
			$actTypeString = language_template('sciomio_text_act_time_2go', $languageTemplate);
		}
		if ($actVal['Story'] != 0) {
			$actType = "puu-experience";
			$actTypeString = language('sciomio_text_act_time_story');			
		}
		echo "\n<li><a href='".$XCOW_B['url']."/act/view?act=".$actVal['Id']."'>";

		if (! isset($userVal['photo'])) { $userVal['photo'] = "/ui/gfx/photo.jpg"; }
		else { $userVal['photo'] = str_replace("/upload/","/upload/48x48_", $userVal['photo']); }
		echo "<img class='puu-mug' alt='' src='".$XCOW_B['url'].$userVal['photo']."' width='48' height='48' border='0'>\n";

		echo "<div class='puu-blurb'>";

			echo "<h4><span class='fn'>".$userVal['FirstName']." ".$userVal['LastName']."</span></h4>\n";

			if ($actType == "puu-expired") {
				echo "<span class='puu-no_story'>".language('sciomio_text_act_story_no')."</span>\n";
			}
			elseif ($actType == "puu-experience") {
				echo "<span class='puu-story'>".language('sciomio_text_act_story_yes')."</span>\n";
			}
			else {
				echo "<span class='puu-expire'>".$actTypeString."</span>\n";
			}

			# display url's + hashtags
			$description = $actVal['Description'];
			# does not compute in simple list
			# $description = htmlEscapeAndLinkUrls($description);
			# $description = preg_replace('/#(\w+)/','<a href="/act?q=%23$1" class="puu-hashtag">#$1</a>', $description);
			echo "<div class='puu-text'>".$description."</div>";

		echo "</div>";
		echo "</a></li>";

	}
	echo "</ul><br clear='all'/>";

}
else {
	if ($session['response']['param']['mode'] == 'simpleZero') {
		echo "<p>".language('sciomio_word_act_widget_home_geen')."</p>";
	}
}
?>
