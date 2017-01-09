<?php
	$page = 'verbind';
?>
<!DOCTYPE html>

<html lang="nl" class="no-js">
<head>
	<meta charset="UTF-8" />

	<title><?php echo language('sciomio_title_act_view'); ?></title>

	<?php include("includes/headers.php"); ?>
	<?php include("includes/headers-act.php"); ?>

        <?php include 'skin/'.$session['response']['param']['skin'].'/css.php'; ?>

</head>

<body>

        <?php include 'skin/'.$session['response']['param']['skin'].'/header.php'; ?>

        <div id="Header">
            <div class="page">

		<div class="nav">

		    <?php include 'includes/search.php' ?>

                    <?php include 'includes/nav.php'; ?>

		    <div id="sessionView">
		    </div>

		</div>

            </div>
        </div>

        <div id="Content" class="puu-connect vcard">
			<div style="height:1px;"></div>
		<form class="puu-create_act">
		<fieldset class="puu-find">
			<legend><?php echo language('sciomio_header_act_top'); ?></legend>
			<div class="puu-find_wrap">
				<div class="puu-msg">
					<textarea cols="40" rows="2" maxlength="256"><?php echo language('sciomio_text_act_new'); ?></textarea>
				</div>
			</div>
		</fieldset>
		</form>

		<p class="puu-all"><a href="<?php echo $XCOW_B['url'] ?>/act?s[relevant]"><?php echo language('sciomio_text_act_view_back'); ?></a></p>

		<!-- RESULT -->
		<?php
		echo "<ul class='puu-acts puu-act puu-act-only'>";

		$actVal = $session['response']['param']['act'];
		$userVal = $session['response']['param']['user'];

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
		echo "\n<li class='".$actType." puu-detail'>";
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

				# media 
				echo "<div class='puu-media'>";
					# foto / video bij verhaal
					if ($actType == "puu-experience" && $actVal['Photo'] != '') {
						echo "<a class='puu-photo' href='act=".$actVal['Story']."&parent=".$actVal['Id']."'><img src='".$XCOW_B['url'].$actVal['Photo']."' alt='Foto' title='' style='max-width:75px;max-height:56px'></a>\n";
					}

					# kennisvelden & hobbies
					echo "<div class='puu-links'>";
					if (count($actVal['knowledgefield']) > 0) {
						echo "<h3>".language('sciomio_text_act_view_show_knowledge')."</h3>";
						echo "<ul>";
						foreach ($actVal['knowledgefield'] as $kKey => $kVal) {
							echo "<li><a href='".$XCOW_B['url']."/browse/knowledge?k=".urlencode($kVal['field'])."'>".$kVal['field']."</a></li>";
						}
						echo "</ul>";
					}
					if (count($actVal['hobbyfield']) > 0) {
						echo "<h3>".language('sciomio_text_act_view_show_hobby')."</h3>";
						echo "<ul>";
						foreach ($actVal['hobbyfield'] as $hKey => $hVal) {
							echo "<li><a href='".$XCOW_B['url']."/browse/hobby?h=".urlencode($hVal['field'])."'>".$hVal['field']."</a></li>";
						}
						echo "</ul>";
					}
					if ($XCOW_B['sciomino']['skin-network'] == "yes") {
						if (isset($actVal['network'])) {
							echo "<h3>".language('sciomio_text_act_view_show_network')."</h3>";
							echo "<ul>";
							echo "<li><a href='".$XCOW_B['url']."/search?tl[public]=".urlencode($actVal['network'])."'>".$actVal['network']."</a></li>";
							echo "</ul>";
						}
					}

					echo "</div>";

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

				# if story, then click for other reactions, otherwiseshwo reactions immediately
				if ($actVal['Story'] != 0) {
					echo "<a class='puu-comment' href='".$XCOW_B['url']."/snippet/actReact-list?mode=view&act=".$actVal['Id']."'>".$actReactionString."</a>\n";
				}

				# mailblock (if viewer has reaction and is not owner of act)
				if (in_array($session['response']['param']['userRef'], $session['response']['param']['allRefs']) && $session['response']['param']['userRef'] != $actVal['Reference']) {
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
					echo "<a class='puu-delete' href='".$XCOW_B['url']."/snippet/act-delete?act=".$actVal['Id']."'>".language('sciomio_word_delete')."</a>\n";
				}

				# display review string
				echo "<div class='puu-sum'>";
					if ($actReviewString != "") {
						echo "<p class='puu-likes'>".$actReviewString."</p>\n";
					}
					if ($actType == "puu-expired") {
						echo "<span class='puu-no_story'>".language('sciomio_text_act_story_no')."</span>\n";
					}
					/* not in this case ...
					elseif ($actType == "puu-experience") {
						echo "<span class='puu-story'>".language('sciomio_text_act_story_yes')."</span>\n";
					}
					*/
				echo "</div>";

				echo "</footer>";
				echo "</div>";
			echo "</div>";

		echo "</section>";
		echo "</div>";

		# RESULT comments #
		echo "<div class='section puu-details'>";
		echo "<section>";

		# bewerken/toevoegen/beeindigen knop
		if ($session['response']['param']['user']['Reference'] == $session['response']['param']['userRef']) {
			$existsClass = "";
			if ($actType == "puu-experience") {
				$existsClass = "puu-existing";
				$actEditString = language('sciomio_word_act_edit');
			}
			elseif ($actType == "puu-expired") {
				$actEditString = language('sciomio_word_act_add');
			}
			else {
				$actEditString = language('sciomio_word_act_end');
			}
			echo "<a class='puu-edit ".$existsClass."' href='act=".$actVal['Id']."'>".$actEditString."</a>";
		}

		echo "<div class='puu-content'>";

		# Verhaal
		if ($actVal['Story'] != 0) {
			$storyId = $actVal['Story'];
			$storyVal = $session['response']['param']['reactList'][$storyId];

			echo "\n<div class='puu-narrative puu-narrative-only'>";
				echo "<span class='puu-story'> ".language('sciomio_text_act_story')."</span>\n";

				$timeString = timeDiff2($storyVal['Timestamp']);
				echo "<h2><a class='url' href='".$XCOW_B['url']."/view?user=".$userVal['Id']."'><span class='fn'>".$userVal['FirstName']."</span> - ".$timeString."</a></h2>\n";
				$description = $storyVal['Description'];
				$description = htmlEscapeAndLinkUrls($description);
				$description = preg_replace('/#(\w+)/','<a href="'.$XCOW_B['url'].'/act?q=%23$1" class="puu-hashtag">#$1</a>', $description);
				echo "<div><p>".$description."</p></div>\n";
				echo "<div class='footer'>";
					echo "<footer>";

					$actReviewString = "";
					$actReviewMe = 0;
					$actReviewCount = count($storyVal['Review']);
					# display review link
					if ( $actReviewCount > 0 ) {
						$reviewIdByRef = get_id_from_multi_array($storyVal['Review'], 'Reference', $session['response']['param']['userRef']);
						# found my review
						if ($reviewIdByRef != 0) {
							$actReviewMe = 1;
							echo "<a class='puu-reviewed' href='".$XCOW_B['url']."/snippet/actReview-delete?act=".$storyVal['Id']."'>".language('sciomio_text_act_review_dislike')."</a>\n";
						}
						else {
							echo "<a class='puu-review' href='".$XCOW_B['url']."/snippet/actReview-new?act=".$storyVal['Id']."'>".language('sciomio_text_act_review_like')."</a>\n";
						}
					}
					else {
						echo "<a class='puu-review' href='".$XCOW_B['url']."/snippet/actReview-new?act=".$storyVal['Id']."'>".language('sciomio_text_act_review_like')."</a>\n";
					}
					# get review string
					if ($actReviewCount == 1) {
						if ($actReviewMe == 1) {
							$actReviewString = language('sciomio_text_act_review_likestring');
						}
						else {
							$languageTemplate['act'] = $storyVal['Id']."&parent=".$actVal['Id'];
							$actReviewString = language_template('sciomio_text_act_review_likestring_others', $languageTemplate);
						}
					}
					elseif ($actReviewCount > 1) {
						$languageTemplate = array();

						if ($actReviewMe == 1) {
							$languageTemplate['count'] = $actReviewCount - 1;
							$languageTemplate['act'] = $storyVal['Id']."&parent=".$actVal['Id'];
							$actReviewString = language_template('sciomio_text_act_review_likestring_count', $languageTemplate);
						}
						else {
							$languageTemplate['count'] = $actReviewCount;
							$languageTemplate['act'] = $storyVal['Id']."&parent=".$actVal['Id'];
							$actReviewString = language_template('sciomio_text_act_review_likestring_others_count', $languageTemplate);
						}
					}

					# remove act
					if ($storyVal['Reference'] == $session['response']['param']['userRef']) {
						echo "<a class='puu-delete' href='".$XCOW_B['url']."/snippet/act-delete?act=".$storyVal['Id']."&parent=".$actVal['Id']."'>".language('sciomio_word_delete')."</a>\n";
					}

					# display review string
					if ($actReviewString != "") {
						echo "<p class='puu-likes'>".$actReviewString."</p>\n";
					}

					echo "</footer>";
				echo "</div>";
			echo "</div>";
			
			# extra link om alle reacties te tonen
			echo "<p class='puu-show_comments'><a href='".$XCOW_B['url']."/snippet/actReact-list?mode=view&act=".$actVal['Id']."'>".language('sciomio_word_act_more')."</a></p>\n";
			echo "</div>";

		}
		# Reacties
		else {
			echo "<ul class='puu-comments'>";
			if (count($session['response']['param']['reactList']) > 0) {
				foreach ($session['response']['param']['reactList'] as $actKey => $actVal) {
					// who is this act from?
					$userRefByActRef = get_id_from_multi_array($session['response']['param']['userList'], 'Reference', $actVal['Reference']);
					$userVal = $session['response']['param']['userList'][$userRefByActRef];

					if ($actVal['story'] != 1) {
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
								$languageTemplate['act'] = $actVal['Id']."&parent=".$session['response']['param']['act']['Id'];
								$actReviewString = language_template('sciomio_text_act_review_likestring_others', $languageTemplate);
							}
						}
						elseif ($actReviewCount > 1) {
							$languageTemplate = array();

							if ($actReviewMe == 1) {
								$languageTemplate['count'] = $actReviewCount - 1;
								$languageTemplate['act'] = $actVal['Id']."&parent=".$session['response']['param']['act']['Id'];
								$actReviewString = language_template('sciomio_text_act_review_likestring_count', $languageTemplate);
							}
							else {
								$languageTemplate['count'] = $actReviewCount;
								$languageTemplate['act'] = $actVal['Id']."&parent=".$session['response']['param']['act']['Id'];
								$actReviewString = language_template('sciomio_text_act_review_likestring_others_count', $languageTemplate);
							}
						}

						# remove act
						if ($actVal['Reference'] == $session['response']['param']['userRef']) {
							echo "<a class='puu-delete' href='".$XCOW_B['url']."/snippet/act-delete?act=".$actVal['Id']."&parent=".$session['response']['param']['act']['Id']."'>".language('sciomio_word_delete')."</a>\n";
						}

						# display review string
						if ($actReviewString != "") {
							echo "<p class='puu-likes'>".$actReviewString."</p>\n";
						}

						echo "</div>";
						echo "</li>";
					}
				}
			}
			echo "</ul>";

			echo "<form class='puu-write'>";
			echo "<input type='hidden' name='com_act' value='".$session['response']['param']['act']['Id']."'>";
			echo "<textarea class='growy' rows='1' cols='80' name='com_description' maxlength='1024'>".language('sciomio_text_act_react_new')."</textarea>";
			echo "</form>";

		}

		# end content
		echo "</div>";
		echo "</section>";
		# end comments
		echo "</div>";
		echo "<div class='puu-clr'></div>";

		echo "</li>";
		# end act
		echo "</ul>";
		?>

        </div>

        <div id="Footer">
            <div class="page">

                <?php include 'includes/footer.php'; ?>

            </div>
        </div>

 	<div id="sessionPopup" style="display:none">
		<div id="sessionPopupMenu">
		     <a href="javascript:Session.Window.close();"><?php echo language('sciomio_word_close'); ?></a>
		</div>
		<div id="sessionPopupData">
		</div>
	</div>

	<div id="UploadHidden" style="position:absolute; top:-1000px; left:-1000px;">
		<iframe name="submitFrame" id="submitFrame">
		</iframe>
	</div>

	<?php include 'includes/scripts.php'; ?>
	<?php include 'includes/scripts-act.php'; ?>
	<script type="text/javascript">
		addLoadEvent(function() {Session.View.load();});
	</script>

</body>
</html>
