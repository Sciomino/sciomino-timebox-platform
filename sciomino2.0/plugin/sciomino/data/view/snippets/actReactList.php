<?php
if ($session['response']['param']['mode'] != "view") {
	echo "<div class='section puu-details'>";
	echo "<section>";
	echo "<div class='puu-content'>";
}

# reacties
if (count($session['response']['param']['actList']) > 0) {
	foreach ($session['response']['param']['actList'] as $actKey => $actVal) {
		// who is this act from?
		$userRefByActRef = get_id_from_multi_array($session['response']['param']['userList'], 'Reference', $actVal['Reference']);
		$userVal = $session['response']['param']['userList'][$userRefByActRef];

		// comment
		if ($actVal['story'] != 1) {
			echo "<ul class='puu-comments'>";
			echo "\n<li>";

			if (! isset($userVal['photo'])) { $userVal['photo'] = "/ui/gfx/photo.jpg"; }
			else { $userVal['photo'] = str_replace("/upload/","/upload/32x32_", $userVal['photo']); }

			echo "<a class='puu-mug photo' href='".$XCOW_B['url']."/view?user=".$userVal['Id']."'><img alt='' src='".$XCOW_B['url'].$userVal['photo']."' width='32' height='32'></a>\n";

			$timeString = timeDiff2($actVal['Timestamp']);
			echo "<p class='puu-header'><a class='fn' href='".$XCOW_B['url']."/view?user=".$userVal['Id']."'>".$userVal['FirstName']." ".$userVal['LastName']."</a> - ".$timeString."</p>\n";

			$description = $actVal['Description'];
			$description = htmlEscapeAndLinkUrls($description);
			$description = preg_replace('/#(\w+)/','<a href="'.$XCOW_B['url'].'/act?q=%23$1" class="puu-hashtag">#$1</a>', $description);
			echo "<div class='puu-narrative'>".$description."</div>\n";
			echo "<div class='footer'>";

			$actReviewString = "";
			$actReviewMe = 0;
			$actReviewCount = count($actVal['Review']);
			# display review link
			if ( $actReviewCount > 0 ) {
				$reviewIdByRef = get_id_from_multi_array($actVal['Review'], 'Reference', $session['response']['param']['userRef']);
				# found my review
				if ($reviewIdByRef != 0) {
					$actReviewMe = 1;
					echo "<a class='puu-reviewed' href='".$XCOW_B['url']."/snippet/actReview-delete?act=".$actVal['Id']."'>".language('sciomio_text_act_review_dislike')."</a>\n";
				}
				else {
					echo "<a class='puu-review' href='".$XCOW_B['url']."/snippet/actReview-new?act=".$actVal['Id']."'>".language('sciomio_text_act_review_like')."</a>\n";
				}
			}
			else {
				echo "<a class='puu-review' href='".$XCOW_B['url']."/snippet/actReview-new?act=".$actVal['Id']."'>".language('sciomio_text_act_review_like')."</a>\n";
			}
			# get review string
			if ($actReviewCount == 1) {
				if ($actReviewMe == 1) {
					$actReviewString = language('sciomio_text_act_review_likestring');
				}
				else {
					$languageTemplate['act'] = $actVal['Id']."&parent=".$session['response']['param']['act'];
					$actReviewString = language_template('sciomio_text_act_review_likestring_others', $languageTemplate);
				}
			}
			elseif ($actReviewCount > 1) {
				$languageTemplate = array();

				if ($actReviewMe == 1) {
					$languageTemplate['count'] = $actReviewCount - 1;
					$languageTemplate['act'] = $actVal['Id']."&parent=".$session['response']['param']['act'];
					$actReviewString = language_template('sciomio_text_act_review_likestring_count', $languageTemplate);
				}
				else {
					$languageTemplate['count'] = $actReviewCount;
					$languageTemplate['act'] = $actVal['Id']."&parent=".$session['response']['param']['act'];
					$actReviewString = language_template('sciomio_text_act_review_likestring_others_count', $languageTemplate);
				}
			}

			# remove act
			if ($actVal['Reference'] == $session['response']['param']['userRef']) {
				echo "<a class='puu-delete' href='".$XCOW_B['url']."/snippet/act-delete?act=".$actVal['Id']."&parent=".$session['response']['param']['act']."'>Verwijder</a>\n";
			}

			# display review string
			if ($actReviewString != "") {
				echo "<p class='puu-likes'>".$actReviewString."</p>\n";
			}

			echo "</div>";
			echo "</li>";
			echo "</ul>";
		}
		// story
		else {
			echo "\n<div class='puu-narrative'>";
				echo "<span class='puu-story'> ".language('sciomio_text_act_story')."</span>\n";

				$timeString = timeDiff2($actVal['Timestamp']);
				echo "<h2><a class='url' href='".$XCOW_B['url']."/view?user=".$userVal['Id']."'><span class='fn'>".$userVal['FirstName']."</span> - ".$timeString."</a></h2>\n";
				$description = $actVal['Description'];
				$description = htmlEscapeAndLinkUrls($description);
				$description = preg_replace('/#(\w+)/','<a href="'.$XCOW_B['url'].'/act?q=%23$1" class="puu-hashtag">#$1</a>', $description);
				echo "<div><p>".$description."</p></div>\n";
				echo "<div class='footer'>";
					echo "<footer>";

					$actReviewString = "";
					$actReviewMe = 0;
					$actReviewCount = count($actVal['Review']);
					# display review link
					if ( $actReviewCount > 0 ) {
						$reviewIdByRef = get_id_from_multi_array($actVal['Review'], 'Reference', $session['response']['param']['userRef']);
						# found my review
						if ($reviewIdByRef != 0) {
							$actReviewMe = 1;
							echo "<a class='puu-reviewed' href='".$XCOW_B['url']."/snippet/actReview-delete?act=".$actVal['Id']."'>".language('sciomio_text_act_review_dislike')."</a>\n";
						}
						else {
							echo "<a class='puu-review' href='".$XCOW_B['url']."/snippet/actReview-new?act=".$actVal['Id']."'>".language('sciomio_text_act_review_like')."</a>\n";
						}
					}
					else {
						echo "<a class='puu-review' href='".$XCOW_B['url']."/snippet/actReview-new?act=".$actVal['Id']."'>".language('sciomio_text_act_review_like')."</a>\n";
					}
					# get review string
					if ($actReviewCount == 1) {
						if ($actReviewMe == 1) {
							$actReviewString = language('sciomio_text_act_review_likestring');
						}
						else {
							$languageTemplate['act'] = $actVal['Id']."&parent=".$session['response']['param']['act'];
							$actReviewString = language_template('sciomio_text_act_review_likestring_others', $languageTemplate);
						}
					}
					elseif ($actReviewCount > 1) {
						$languageTemplate = array();

						if ($actReviewMe == 1) {
							$languageTemplate['count'] = $actReviewCount - 1;
							$languageTemplate['act'] = $actVal['Id']."&parent=".$session['response']['param']['act'];
							$actReviewString = language_template('sciomio_text_act_review_likestring_count', $languageTemplate);
						}
						else {
							$languageTemplate['count'] = $actReviewCount;
							$languageTemplate['act'] = $actVal['Id']."&parent=".$session['response']['param']['act'];
							$actReviewString = language_template('sciomio_text_act_review_likestring_others_count', $languageTemplate);
						}
					}

					# remove act
					# cannot remove 'story' because the delete function in javascript does not work correctly
					#if ($actVal['Reference'] == $session['response']['param']['userRef']) {
					#	echo "<a class='puu-delete' href='/snippet/act-delete?act=".$actVal['Id']."&parent=".$session['response']['param']['act']."'>Verwijder</a>\n";
					#}

					# display review string
					if ($actReviewString != "") {
						echo "<p class='puu-likes'>".$actReviewString."</p>\n";
					}

					echo "</footer>";
				echo "</div>";
			echo "</div>";
			// add the new comment after this empty comment
			echo "<ul class='puu-comments'>";
			echo "</ul>";
		}
	}
}
else {
	// add the new comment after this empty comment
	echo "<ul class='puu-comments'>";
	echo "</ul>";
}

echo "<form class='puu-write'>";
echo "<input type='hidden' name='com_act' value='".$session['response']['param']['act']."'>";
// rebind growy to the class...
echo "<script>$('.growy').growy()</script>";
echo "<textarea class='growy' rows='1' cols='80' name='com_description' maxlength='1024'>".language('sciomio_text_act_react_new')."</textarea>";
echo "</form>";

if ($session['response']['param']['mode'] != "view") {
	echo "</div>";
	echo "</section>";
	echo "</div>";
}

?>


