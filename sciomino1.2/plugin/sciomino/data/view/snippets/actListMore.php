			<!-- RESULT -->
			<?php
			if (count($session['response']['param']['actList']) > 0) {

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
					echo "\n<li class='puu-detail ".$actType."'>";
					echo "<div class='section'>";
					echo "<section>";

						$verdict = "";
						if ($actVal['Like'] == 1) {$verdict = "happy_xl";}
						if ($actVal['Like'] == 2) {$verdict = "happy";}
						if ($actVal['Like'] == 3) {$verdict = "unhappy";}
						if ($actVal['Like'] == 4) {$verdict = "unhappy_xl";}
						echo "<p class='puu-status ".$verdict."'>";
						echo "<span class='puu-lbl'>".$actTypeString."</span>";
						echo "</p>\n";

						echo "<div class='puu-content'>";
							echo "<div class='header'>";
							echo "<header>";
							
							if (! isset($userVal['photo'])) { $userVal['photo'] = "/ui/gfx/photo.jpg"; }
							else { $userVal['photo'] = str_replace("/upload/","/upload/96x96_", $userVal['photo']); }

							echo "<a class='puu-mug photo' href='".$XCOW_B['url']."/view?user=".$userVal['Id']."'><img alt='' src='".$XCOW_B['url'].$userVal['photo']."' width='48' height='48'></a>\n";
							echo "<h1><a class='url' href='".$XCOW_B['url']."/snippet/user-view-card?user=".$userVal['Id']."'><span class='fn'>".$userVal['FirstName']." ".$userVal['LastName']."</span></a></h1>";
							echo "</header>";
							echo "</div>\n";

							# display url's + hashtags
							$description = $actVal['Description'];
							$description = htmlEscapeAndLinkUrls($description);
							$description = preg_replace('/#(\w+)/','<a href="'.$XCOW_B['url'].'/act?q=%23$1" class="puu-hashtag">#$1</a>', $description);
							echo "<div class='puu-blurb'>".$description."</div>\n";

							# foto / video bij verhaal
							echo "<div class='puu-media'>";
								if ($actType == "puu-experience" && $actVal['Photo'] != '') {
									echo "<a class='puu-photo' href='act=".$actVal['Story']."&parent=".$actVal['Id']."'><img src='".$XCOW_B['url'].$actVal['Photo']."' alt='Foto' title='' width='75' height='56'></a>\n";
								}
							echo "</div>\n";

							echo "<div class='footer'>";
							echo "<footer>";

							$timeString = timeDiff2($actVal['Timestamp']);
							echo "<a class='puu-perma' href='".$XCOW_B['url']."/act/view?act=".$actVal['Id']."'>$timeString</a>\n";

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
									$languageTemplate['act'] = $actVal['Id'];
									$actReviewString = language_template('sciomio_text_act_review_likestring_others', $languageTemplate);
								}
							}
							elseif ($actReviewCount > 1) {
								$languageTemplate = array();

								if ($actReviewMe == 1) {
									$languageTemplate['count'] = $actReviewCount - 1;
									$languageTemplate['act'] = $actVal['Id'];
									$actReviewString = language_template('sciomio_text_act_review_likestring_count', $languageTemplate);
								}
								else {
									$languageTemplate['count'] = $actReviewCount;
									$languageTemplate['act'] = $actVal['Id'];
									$actReviewString = language_template('sciomio_text_act_review_likestring_others_count', $languageTemplate);
								}
							}

							$actReactionString = language('sciomio_text_act_react');
							if ($actVal['Reactions'] == 1) {
								$actReactionString = language('sciomio_text_act_react_one');
							}
							elseif ($actVal['Reactions'] > 1) {
								$languageTemplate = array();
								$languageTemplate['count'] = $actVal['Reactions'];
								$actReactionString = language_template('sciomio_text_act_react_more', $languageTemplate);
							}
							echo "<a class='puu-comment' href='".$XCOW_B['url']."/snippet/actReact-list?act=".$actVal['Id']."'>".$actReactionString."</a>\n";

							# mailblock (if viewer has reaction and is not owner of act)
							if (in_array($session['response']['param']['userRef'], $actVal['allRefs']) && $session['response']['param']['userRef'] != $actVal['Reference']) {
								# mailblock link
								$mailBlockString = "<a class='puu-mailblock' href='".$XCOW_B['url']."/snippet/actMailblock-new?act=".$actVal['Id']."'>".language('sciomio_text_act_mailblock_block')."</a>\n";
								# find my mailblock to unlink
								$actMailblockCount = count($actVal['Mailblock']);
								if ( $actMailblockCount > 0 ) {
									if (get_id_from_multi_array($actVal['Mailblock'], 'Reference', $session['response']['param']['userRef']) != 0) {
										$mailBlockString = "<a class='puu-mailblock' href='".$XCOW_B['url']."/snippet/actMailblock-delete?act=".$actVal['Id']."'>".language('sciomio_text_act_mailblock_unblock')."</a>\n";
									}
								}
								echo $mailBlockString; 
							}

							# share
							echo "<a class='puu-share' href='act=".$actVal['Id']."'>".language('sciomio_word_forward')."</a>\n";
							
							# remove act
							if ($actVal['Reference'] == $session['response']['param']['userRef']) {
								echo "<a class='puu-delete' href='".$XCOW_B['url']."/snippet/act-delete?act=".$actVal['Id']."'>Verwijder</a>\n";
							}

							# display review string
							echo "<div class='puu-sum'>";
								if ($actReviewString != "") {
									echo "<p class='puu-likes'>".$actReviewString."</p>\n";
								}
								if ($actType == "puu-expired") {
									echo "<span class='puu-no_story'>".language('sciomio_text_act_story_no')."</span>\n";
								}
								elseif ($actType == "puu-experience") {
									echo "<span class='puu-story'>".language('sciomio_text_act_story_yes')."</span>\n";
								}
							echo "</div>";

							echo "</footer>";
							echo "</div>";
						echo "</div>";

					echo "</section>";
					echo "</div>";
					echo "<div class='puu-clr'></div>";
					echo "</li>";
	
				}

				# meer...
				if ($session['response']['param']['thereIsMore']) {
					echo "<li class='puu-more'><a id='MoreButton' class='button buttonbold' href='".$XCOW_B['url']."/snippet/act-list-more?offset=".$session['response']['param']['searchOffset']."&searchString=".$session['response']['param']['searchString']."'>".language('sciomio_word_moreActs')."</a></li>";
				}

			}
			?>

