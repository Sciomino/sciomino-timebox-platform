<?php
	$page = 'verbind';
?>
<!DOCTYPE html>

<html lang="nl" class="no-js">
<head>
	<meta charset="UTF-8" />

	<!-- stay uptodate with new content
	<meta http-equiv="refresh" content="15" />
	-->

	<title><?php echo language('sciomio_title_act'); ?></title>

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

			<!-- FILTER -->
			<form class="puu-filter_acts">
			<fieldset class="puu-filter">
				<p class="puu-search">
					<?php
					if ($session['response']['param']['actCount'] == 1) {
						echo "<label for='zoekwoord'>".$session['response']['param']['actCount']." ".language('sciomio_header_act_filter_een')."</label>";
					}
					else {
						$plus="";
						if ($session['response']['param']['actCount'] >= $XCOW_B['sciomino']['answers-api-max']) { $plus="+"; }
						echo "<label for='zoekwoord'>".$session['response']['param']['actCount'].$plus." ".language('sciomio_header_act_filter')."</label>";
					}
					if ($session['response']['param']['query']['words'] != '') {
						echo "<input name='q' id='zoekwoord' value='".htmlTokens($session['response']['param']['query']['words'])."' maxlength='128'>";
					}
					else {
						echo "<input name='q' id='zoekwoord' value='".language('sciomio_text_act_search')."' maxlength='128'>";
					}
					?>
				</p>
				<?php
					# status
					//if (count($session['response']['param']['statusListList']) > 0) {
						$focusStripped = preg_replace("/^(.*)\&s([^\&]*)(.*)$/", "$1$3", $session['response']['param']['query']['focus']);
						echo "<p>";
						echo "<label for='status'>".language('sciomio_word_filter_statusList')."</label>";
						echo "<select id='status' class='chzn-select puu-chzn_short'>";

						if (count($session['response']['param']['actList']) > 0) {
							echo "<option value='".$XCOW_B['url']."/act?".$focusStripped."&s[all]'>".language('sciomio_text_act_filter_status')."</option>";
							foreach ($session['response']['param']['statusListList'] as $statusListKey => $statusListVal) {
								$SELECTED = "";
								if ($statusListKey == key($session['response']['param']['query']['statusList'])) {
									$SELECTED = "SELECTED";
								}
								echo "<option value='".$XCOW_B['url']."/act?".$focusStripped."&s[".urlencode($statusListKey)."]' ".$SELECTED.">".language('sciomio_word_filter_act_status_'.$statusListKey)."</option>";
							}
						}
						else {
							# if 0 results, set the value to the input (if available)
							if (count($session['response']['param']['query']['statusList']) > 0) {
								echo "<option value='".$XCOW_B['url']."/act?".$focusStripped."&s[".urlencode(key($session['response']['param']['query']['statusList']))."]' SELECTED>".language('sciomio_word_filter_act_status_'.key($session['response']['param']['query']['statusList']))."</option>";
							}
							else {
								echo "<option value='".$XCOW_B['url']."/act?".$focusStripped."' SELECTED>".language('sciomio_text_act_filter_status')."</option>";
							}
						}

						echo "</select>";
						echo "</p>";
					//}
				?>
				<?php
					# my
					//if (count($session['response']['param']['myList']) > 0) {
						$focusStripped = preg_replace("/^(.*)\&m([^\&]*)(.*)$/", "$1$3", $session['response']['param']['query']['focus']);
						echo "<p>";
						echo "<label for='lijst'>".language('sciomio_word_filter_my')."</label>";
						echo "<select id='lijst' class='chzn-select puu-chzn_short'>";

						if (count($session['response']['param']['actList']) > 0) {
							echo "<option value='".$XCOW_B['url']."/act?".$focusStripped."'>".language('sciomio_text_act_filter_my')."</option>";
							foreach ($session['response']['param']['myList'] as $myKey => $myVal) {
								$SELECTED = "";
								if ($myKey == key($session['response']['param']['query']['my'])) {
									$SELECTED = "SELECTED";
								}
								echo "<option value='".$XCOW_B['url']."/act?".$focusStripped."&m[".urlencode($myKey)."]' ".$SELECTED.">".language('sciomio_word_filter_act_my_'.$myKey)."</option>";
							}
						}
						else {
							# if 0 results, set the value to the input (if available)
							if (count($session['response']['param']['query']['my']) > 0) {
								echo "<option value='".$XCOW_B['url']."/act?".$focusStripped."&m[".urlencode(key($session['response']['param']['query']['my']))."]' SELECTED>".language('sciomio_word_filter_act_my_'.key($session['response']['param']['query']['my']))."</option>";
							}
							else {
								echo "<option value='".$XCOW_B['url']."/act?".$focusStripped."' SELECTED>".language('sciomio_text_act_filter_my')."</option>";
							}
						}

						echo "</select>";
						echo "</p>";
					//}
				?>
				<?php
					# businessunit
					/* disabled since version 1.2n
					$focusStripped = preg_replace("/^(.*)\&p\[businessunit\]([^\&]*)(.*)$/", "$1$3", $session['response']['param']['query']['focus']);

					if (count($session['response']['param']['businessunitList']) > 0) {
						echo "<p>";
						echo "<label for='businessunit'>".language('sciomio_word_filter_businessunit')."</label>";

						//echo "<select id='businessunit' data-placeholder='Selecteer…' class='chzn-select-deselect //puu-suggest puu-suggest_businessunit'>";
						//echo "<option value=''></option>";
						//echo "</select>";
						
						echo "<select id='businessunit' data-placeholder='".language('sciomio_word_select')."' class='chzn-select-deselect'>";
						echo "<option value='".$XCOW_B['url']."/act?".$focusStripped."'></option>";
						foreach ($session['response']['param']['businessunitList'] as $businessunitKey => $businessunitVal) {
							$SELECTED = "";
							if ($businessunitKey == $session['response']['param']['query']['personal']['businessunit']) {
								$SELECTED = "SELECTED";
							}
							echo "<option value='".$XCOW_B['url']."/act?".$focusStripped."&p[businessunit]=".urlencode($businessunitKey)."' ".$SELECTED.">".$businessunitKey."</option>";
						}
						echo "</select>";
						echo "</p>";
					}
					else {
						echo "<p>";
						echo "<label for='businessunit'>".language('sciomio_word_filter_businessunit')."</label>";
						echo "<select id='businessunit' class='chzn-select puu-chzn_short'>";
						if (isset($session['response']['param']['query']['personal']['businessunit'])) {
							echo "<option value='".$XCOW_B['url']."/act?".$focusStripped."&p[businessunit]=".urlencode($session['response']['param']['query']['personal']['businessunit'])."' SELECTED>".$session['response']['param']['query']['personal']['businessunit']."</option>";
						}
						else {
							echo "<option value='".$XCOW_B['url']."/act?".$session['response']['param']['query']['focus']."'>".language('sciomio_text_act_filter_businessunit')."</option>";
						}
						echo "</select>";
						echo "</p>";
					}
					*/
				?>
				<?php
					# workplace
					/* disabled since version 1.2n
					$focusStripped = preg_replace("/^(.*)\&p\[workplace\]([^\&]*)(.*)$/", "$1$3", $session['response']['param']['query']['focus']);
					if (count($session['response']['param']['workplaceList']) > 0) {
						echo "<p>";
						echo "<label for='workplace'>".language('sciomio_word_filter_workplace')."</label>";

						//echo "<select id='workplace' data-placeholder='Selecteer…' class='chzn-select-deselect //puu-suggest puu-suggest_workplace'>";
						//echo "<option value=''></option>";
						//echo "</select>";

						echo "<select id='workplace' data-placeholder='".language('sciomio_word_select')."' class='chzn-select-deselect'>";
						echo "<option value='".$XCOW_B['url']."/act?".$focusStripped."'></option>";
						foreach ($session['response']['param']['workplaceList'] as $workplaceKey => $workplaceVal) {
							$SELECTED = "";
							if ($workplaceKey == $session['response']['param']['query']['personal']['workplace']) {
								$SELECTED = "SELECTED";
							}
							echo "<option value='".$XCOW_B['url']."/act?".$focusStripped."&p[workplace]=".urlencode($workplaceKey)."' ".$SELECTED.">".$workplaceKey."</option>";
						}
						echo "</select>";
						echo "</p>";
					}
					else {
						echo "<p>";
						echo "<label for='workplace'>".language('sciomio_word_filter_workplace')."</label>";
						echo "<select id='workplace' class='chzn-select puu-chzn_short'>";
						if (isset($session['response']['param']['query']['personal']['workplace'])) {
							echo "<option value='".$XCOW_B['url']."/act?".$focusStripped."&p[workplace]=".urlencode($session['response']['param']['query']['personal']['workplace'])."' SELECTED>".$session['response']['param']['query']['personal']['workplace']."</option>";
						}
						else {
							echo "<option value='".$XCOW_B['url']."/act?".$session['response']['param']['query']['focus']."'>".language('sciomio_text_act_filter_workplace')."</option>";
						}
						echo "</select>";
						echo "</p>";
					}
					*/
				?>
				<?php
					# knowledge
					$focusStripped = preg_replace("/^(.*)\&k([^\&]*)(.*)$/", "$1$3", $session['response']['param']['query']['focus']);
					if (count($session['response']['param']['knowledgeList']) > 0) {
						echo "<p>";
						echo "<label for='kennisveld'>".language('sciomio_word_filter_knowledge')."</label>";
						/*
						echo "<select id='kennisveld' data-placeholder='Selecteer…' class='chzn-select-deselect puu-suggest puu-suggest_knowledge'>";
						echo "<option value=''></option>";
						echo "</select>";
						*/
						echo "<select id='kennisveld' class='chzn-select puu-chzn_short'>";
						echo "<option value='".$XCOW_B['url']."/act?".$focusStripped."'>".language('sciomio_text_act_filter_knowledge')."</option>";
						foreach ($session['response']['param']['knowledgeList'] as $knowledgeKey => $knowledgeVal) {
							$SELECTED = "";
							if ($knowledgeKey == key($session['response']['param']['query']['knowledge'])) {
								$SELECTED = "SELECTED";
							}
							echo "<option value='".$XCOW_B['url']."/act?".$focusStripped."&k[".urlencode($knowledgeKey)."]' ".$SELECTED.">".$knowledgeKey."</option>";
						}
						echo "</select>";
						echo "</p>";
					}
					else {
						echo "<p>";
						echo "<label for='kennisveld'>".language('sciomio_word_filter_knowledge')."</label>";
						echo "<select id='kennisveld' class='chzn-select puu-chzn_short'>";
						if (count($session['response']['param']['query']['knowledge']) > 0) {
							echo "<option value='".$XCOW_B['url']."/act?".$focusStripped."&k[".urlencode(key($session['response']['param']['query']['knowledge']))."]' SELECTED>".key($session['response']['param']['query']['knowledge'])."</option>";
						}
						else {
							echo "<option value='".$XCOW_B['url']."/act?".$session['response']['param']['query']['focus']."'>".language('sciomio_text_act_filter_knowledge')."</option>";
						}
						echo "</select>";
						echo "</p>";
					}
				?>
				<?php
					# hobbies
					$focusStripped = preg_replace("/^(.*)\&h([^\&]*)(.*)$/", "$1$3", $session['response']['param']['query']['focus']);
					if (count($session['response']['param']['hobbyList']) > 0) {
						echo "<p>";
						echo "<label for='hobby'>".language('sciomio_word_filter_hobby')."</label>";
						echo "<select id='hobby' class='chzn-select puu-chzn_short'>";
						echo "<option value='".$XCOW_B['url']."/act?".$focusStripped."'>".language('sciomio_text_act_filter_hobby')."</option>";
						foreach ($session['response']['param']['hobbyList'] as $hobbyKey => $hobbyVal) {
							$SELECTED = "";
							if ($hobbyKey == key($session['response']['param']['query']['hobby'])) {
								$SELECTED = "SELECTED";
							}
							echo "<option value='".$XCOW_B['url']."/act?".$focusStripped."&h[".urlencode($hobbyKey)."]' ".$SELECTED.">".$hobbyKey."</option>";
						}
						echo "</select>";
						echo "</p>";
					}
					else {
						echo "<p>";
						echo "<label for='hobby'>".language('sciomio_word_filter_hobby')."</label>";
						echo "<select id='hobby' class='chzn-select puu-chzn_short'>";
						if (count($session['response']['param']['query']['hobby']) > 0) {
							echo "<option value='".$XCOW_B['url']."/act?".$focusStripped."&h[".urlencode(key($session['response']['param']['query']['hobby']))."]' SELECTED>".key($session['response']['param']['query']['hobby'])."</option>";
						}
						else {
							echo "<option value='".$XCOW_B['url']."/act?".$session['response']['param']['query']['focus']."'>".language('sciomio_text_act_filter_hobby')."</option>";
						}
						echo "</select>";
						echo "</p>";
					}
				?>

				<?php
					# networks
					if ($XCOW_B['sciomino']['skin-network'] == "yes") {
						$focusStripped = preg_replace("/^(.*)\&net([^\&]*)(.*)$/", "$1$3", $session['response']['param']['query']['focus']);
						if (count($session['response']['param']['networkList']) > 0) {
							echo "<p>";
							echo "<label for='network'>".language('sciomio_word_filter_network')."</label>";
							echo "<select id='network' class='chzn-select puu-chzn_short'>";
							echo "<option value='".$XCOW_B['url']."/act?".$focusStripped."'>".language('sciomio_text_act_filter_network')."</option>";
							foreach ($session['response']['param']['networkList'] as $networkKey => $networkVal) {
								$SELECTED = "";
								if ($networkKey == key($session['response']['param']['query']['network'])) {
									$SELECTED = "SELECTED";
								}
								echo "<option value='".$XCOW_B['url']."/act?".$focusStripped."&net[".urlencode($networkKey)."]' ".$SELECTED.">".$networkKey."</option>";
							}
							echo "</select>";
							echo "</p>";
						}
						else {
							echo "<p>";
							echo "<label for='network'>".language('sciomio_word_filter_network')."</label>";
							echo "<select id='network' class='chzn-select puu-chzn_short'>";
							if (count($session['response']['param']['query']['network']) > 0) {
								echo "<option value='".$XCOW_B['url']."/act?".$focusStripped."&net[".urlencode(key($session['response']['param']['query']['network']))."]' SELECTED>".key($session['response']['param']['query']['network'])."</option>";
							}
							else {
								echo "<option value='".$XCOW_B['url']."/act?".$session['response']['param']['query']['focus']."'>".language('sciomio_text_act_filter_network')."</option>";
							}
							echo "</select>";
							echo "</p>";
						}
					}
				?>

			</fieldset>
			</form>

			<!-- RESULT -->
			<?php
			if (count($session['response']['param']['actList']) > 0) {

				echo "<ul class='puu-acts'>";

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
									echo "<a class='puu-photo' href='act=".$actVal['Story']."&parent=".$actVal['Id']."'><img src='".$XCOW_B['url'].$actVal['Photo']."' alt='Foto' title='' style='max-width:75px;max-height:56px'></a>\n";
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

				echo "</ul>";

			}
			else {
				echo "<ul class='puu-acts'>";
				echo "<li><a href='javascript:history.go(-1)'>".language('sciomio_text_act_filter_back')."</a>";
				echo "</ul>";
			}
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

	<?php include 'includes/scripts.php'; ?>
	<?php include 'includes/scripts-act.php'; ?>
	<script type="text/javascript">
		addLoadEvent(function() {Session.View.load();});
	</script>

</body>
</html>
