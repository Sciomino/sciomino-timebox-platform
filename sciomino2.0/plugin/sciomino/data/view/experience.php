<?php
$page = 'kennis';
?>
<!DOCTYPE html>

<html lang="nl" class="no-js">
<head>
	<meta charset="UTF-8" />

	<title><?php echo language('sciomio_title_experience'); ?> <?php echo $session['response']['param']['experience'] ?></title>

	<?php include("includes/headers.php"); ?>

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

	<div id="Content">
			<div style="height:1px;"></div>
		<div class="page">
			<div class="header">
				<ul class="breadcrumbs">
					<li><a href="<?php echo $XCOW_B['url'] ?>/browse"><?php echo language('sciomio_text_browse_breadcrumb'); ?></a></li>
					<?php
					if ($session['response']['param']['experienceTitle'] != '') {
					#if (($session['response']['param']['type'] != 'Product' && $session['response']['param']['experienceTitle'] != '') || ($session['response']['param']['type'] == 'Product' && $session['response']['param']['experienceAlternative'] != '')) {
						echo "<li><a href='".$XCOW_B['url']."/browse/experience?e[".$session['response']['param']['type']."]=".urlencode($session['response']['param']['experience'])."'>".language('sciomio_text_browse_breadcrumb_experience').language('sciomio_word_focus_'.$session['response']['param']['type'])." | ".$session['response']['param']['experience']."</a></li>";
					}
					?>
				</ul>
				<?php
				if (($session['response']['param']['type'] != 'Product' && $session['response']['param']['experienceTitle'] != '') || ($session['response']['param']['type'] == 'Product' && $session['response']['param']['experienceAlternative'] != '')) {

					if ($session['response']['param']['userCount'] == 1) {
						echo "<h2 class='joinh1'>".$session['response']['param']['userCount']." ".language('sciomio_header_browse_experience_list_een')."</h2>";
					}
					else {
						echo "<h2 class='joinh1'>".$session['response']['param']['userCount']." ".language('sciomio_header_browse_experience_list')."</h2>";
					}

				}
				elseif ($session['response']['param']['type'] == 'Product' && $session['response']['param']['experienceTitle'] != '') {
					echo "<h1>".language('sciomio_header_browse_experience_listSub').language('sciomio_word_focus_'.$session['response']['param']['type'])." | ".$session['response']['param']['experienceTitle']."</h1>";
				}
				else {
					echo "<h1>".language('sciomio_header_browse_experience_listSub').language('sciomio_word_focus_'.$session['response']['param']['type'])." | ".$session['response']['param']['experience']."</h1>";
				}
				?>
			</div>
	

			<div class="group divide div1-2">
				
				<div class="unit unit1-2 ">

					<?php
					// browse title + alternative until
					// - title is set 
					// - or alternative is set (by product)
		
					// first - not products - title is set => show users!
					// php include 'components/listed-ervaringen-opleiding-brandveiligheid.php'
					if ($session['response']['param']['type'] != 'Product' && $session['response']['param']['experienceTitle'] != '') {

						echo "<div class='section'>\n";
						if ($session['response']['param']['showMetoo'] == 0) {
							if ($session['response']['param']['type'] == "Company") {
								echo "<a href='".$XCOW_B['url']."/snippet/company-new-form-ikook?fillSubject=".urlencode($session['response']['param']['experience'])."&fillTitle=".urlencode($session['response']['param']['experienceTitle'])."' class='tinybutton metoo joinhd'>".language('sciomio_word_metoo')."</a>";
							}	
							if ($session['response']['param']['type'] == "Event") {
								echo "<a href='".$XCOW_B['url']."/snippet/event-new-form-ikook?fillSubject=".urlencode($session['response']['param']['experience'])."&fillTitle=".urlencode($session['response']['param']['experienceTitle'])."&fillPublisher=".urlencode($session['response']['param']['experiencePublisher'])."' class='tinybutton metoo joinhd'>".language('sciomio_word_metoo')."</a>";
							}	
							if ($session['response']['param']['type'] == "Education") {
								echo "<a href='".$XCOW_B['url']."/snippet/education-new-form-ikook?fillSubject=".urlencode($session['response']['param']['experience'])."&fillTitle=".urlencode($session['response']['param']['experienceTitle'])."&fillPublisher=".urlencode($session['response']['param']['experiencePublisher'])."' class='tinybutton metoo joinhd'>".language('sciomio_word_metoo')."</a>";
							}	
						}
						else {
							echo "<span class='you-label joinhd'>".language('sciomio_word_youtoo')."</span>";
						}
						echo "<h1>".$session['response']['param']['experienceTitle']."</h1>";

						// sort products by 'has'
						echo "<div class='filter highlight'>";

						echo "<table class='barometer'><tbody>";
						foreach ($session['response']['param']['likes'] as $likeKey => $likeVal) {
							$verdict = "happy"; $value = "pos"; $text=language('sciomio_text_stats_like_1');
							if ($likeKey == 1) {$verdict = "happy-xl"; $value = "pos"; $text=language('sciomio_text_stats_like_1');}
							if ($likeKey == 2) {$verdict = "happy"; $value = "pos"; $text=language('sciomio_text_stats_like_2');}
							if ($likeKey == 3) {$verdict = "unhappy"; $value = "neg"; $text=language('sciomio_text_stats_like_3');}
							if ($likeKey == 4) {$verdict = "unhappy-xl"; $value = "neg"; $text=language('sciomio_text_stats_like_4');}
							echo "<tr>";
							echo "<th scope='row'><span class='verdict ".$verdict."'>".$text."</span></th>";
							echo "<td>";
							echo "<div class='scale'>";
							echo "<div class='fill ".$value." fill".round( ($likeVal/$session['response']['param']['userCount']) * 10)."'></div>";
							echo "</div>";
							echo "<span class='count'>(".$likeVal.")</span>";
							echo "</td>";
							echo "</tr>";
						}
						echo "</tbody></table>";

						// no 'has' voor non-products...
						echo "<ul class='filter-detail'>\n";
						echo "</ul>\n";
						echo "</div>\n";

						echo "<div>";
						echo "<p><a href='".$XCOW_B['url']."/search?e[".urlencode($session['response']['param']['type'])."][".urlencode($session['response']['param']['experience'])."]=".urlencode(urlencode($session['response']['param']['experienceTitle'])).",,,' class='more'>".language('sciomio_word_browse_digg')."</a><br/><br/></p>";
						echo "</div>";

						// show user
						echo "<ul class='filtered expandable'>\n";
						foreach ($session['response']['param']['userList'] as $userKey => $userVal) {
							$verdict = "happy";
							if ($session['response']['param']['UserExperienceInfo'][$userVal['Id']]['like'] == 1) {$verdict = "happy-xl";}
							if ($session['response']['param']['UserExperienceInfo'][$userVal['Id']]['like'] == 2) {$verdict = "happy";}
							if ($session['response']['param']['UserExperienceInfo'][$userVal['Id']]['like'] == 3) {$verdict = "unhappy";}
							if ($session['response']['param']['UserExperienceInfo'][$userVal['Id']]['like'] == 4) {$verdict = "unhappy-xl";}

							echo "<li>";

							echo "<div class='img-item box'>";
							echo "<div class='img'>";
							if (! isset($userVal['photo'])) { $userVal['photo'] = "/ui/gfx/photo.jpg"; }
							else { $userVal['photo'] = str_replace("/upload/","/upload/48x48_",$userVal['photo']); }
							echo "<a href='".$XCOW_B['url']."/view?user=".$userVal['Id']."'><img src='".$XCOW_B['url'].$userVal['photo']."' width='48' height='48' alt='' /></a>";
							echo "</div>";
							echo "<div class='bd'>";
							echo $me = "";
							if ($session['response']['param']['me'] == $userVal['Id']) {
								$me = "<span class='you-label'>".language('sciomio_word_you')."</span>";
							}
							echo "<h3><a class='userlink' href='".$XCOW_B['url']."/view?user=".$userVal['Id']."'>".$userVal['FirstName']." ".$userVal['LastName']."</a>".$me."</h3>";
							$displayOrganization = $userVal['Organization'][get_id_from_multi_array($userVal['Organization'], 'Name', 'Current')]['division'];
							if ($displayOrganization == "") { $displayOrganization = $userVal['Organization'][get_id_from_multi_array($userVal['Organization'], 'Name', 'Current')]['company']; }
							echo "<p>".$userVal['Organization'][get_id_from_multi_array($userVal['Organization'], 'Name', 'Current')]['role']." - ".$displayOrganization."</p>";
							echo "</div>";
							echo "</div>";

							echo "<h4 class='verdict ".$verdict." '>".$session['response']['param']['UserExperienceInfo'][$userVal['Id']]['date']." &nbsp;</h4>";
							$displayURL = "";
							if ($session['response']['param']['UserExperienceInfo'][$userVal['Id']]['relation-self'] != "") {
								$displayURL = "<br/><a href='".$session['response']['param']['UserExperienceInfo'][$userVal['Id']]['relation-self']."'>-&gt; website</a></p>";
							}
							echo "<p class='from-user'>".$session['response']['param']['UserExperienceInfo'][$userVal['Id']]['description'].$displayURL."</p>";
							echo "</li>";
						}
						# meer...
						if ($session['response']['param']['thereIsMore']) {
							echo "<a id='MoreButton' class='button buttonbold' href='".$XCOW_B['url']."/snippet/experience-more?e[".$session['response']['param']['type']."]=".urlencode($session['response']['param']['experience'])."&title=".urlencode($session['response']['param']['experienceTitle'])."&offset=".$session['response']['param']['searchOffset']."&searchString=".$session['response']['param']['searchString']."'>".language('sciomio_word_moreResults')."</a>";
						}
						echo "</ul>\n";

						echo "</div>\n";

					}
					// then - the products - alternative is set => show users!
					elseif ($session['response']['param']['type'] == 'Product' && $session['response']['param']['experienceAlternative'] != '') {
						echo "<div class='section'>\n";
						if ($session['response']['param']['showMetoo'] == 0) {
							echo "<a href='".$XCOW_B['url']."/snippet/product-new-form-ikook?fillSubject=".urlencode($session['response']['param']['experience'])."&fillTitle=".urlencode($session['response']['param']['experienceTitle'])."&fillAlternative=".urlencode($session['response']['param']['experienceAlternative'])."' class='tinybutton metoo joinhd'>".language('sciomio_word_metoo')."</a>";
						}
						else {
							echo "<span class='you-label joinhd'>".language('sciomio_word_youtoo')."</span>";
						}
						echo "<h1>".$session['response']['param']['experienceTitle']." ".$session['response']['param']['experienceAlternative']."</h1>";

						// sort products by 'has'
						echo "<div class='filter highlight'>";

						echo "<table class='barometer'><tbody>";
						foreach ($session['response']['param']['likes'] as $likeKey => $likeVal) {
							$verdict = "happy"; $value = "pos"; $text=language('sciomio_text_stats_like_1');;
							if ($likeKey == 1) {$verdict = "happy-xl"; $value = "pos"; $text=language('sciomio_text_stats_like_1');}
							if ($likeKey == 2) {$verdict = "happy"; $value = "pos"; $text=language('sciomio_text_stats_like_2');}
							if ($likeKey == 3) {$verdict = "unhappy"; $value = "neg"; $text=language('sciomio_text_stats_like_3');}
							if ($likeKey == 4) {$verdict = "unhappy-xl"; $value = "neg"; $text=language('sciomio_text_stats_like_4');}
							echo "<tr>";
							echo "<th scope='row'><span class='verdict ".$verdict."'>".$text."</span></th>";
							echo "<td>";
							echo "<div class='scale'>";
							echo "<div class='fill ".$value." fill".round( ($likeVal/$session['response']['param']['userCount']) * 10)."'></div>";
							echo "</div>";
							echo "<span class='count'>(".$likeVal.")</span>";
							echo "</td>";
							echo "</tr>";
						}
						echo "</tbody></table>";

						echo "<ul class='filter-detail'>\n";
						$active = "";
						if ($session['response']['param']['experienceHas'] == '') {
							$active = "class='active'";
						}
						echo "<li><a ".$active." href='".$XCOW_B['url']."/browse/experience?e[".$session['response']['param']['type']."]=".urlencode($session['response']['param']['experience'])."&title=".urlencode($session['response']['param']['experienceTitle'])."&alternative=".urlencode($session['response']['param']['experienceAlternative'])."'>".language('sciomio_word_all')." <span class='count'>(".$session['response']['param']['userCount'].")</span></a></li>\n";
						foreach ($session['response']['param']['experienceDetail'] as $detailKey => $detailVal) {
							if ($detailKey == "has") {
								foreach ($detailVal as $has => $count) {
									$active = "";
									if ($has == $session['response']['param']['experienceHas']) {
										$active = "class='active'";
									}
									$languageString = "sciomio_word_has_".$has;
									echo "<li><a ".$active." href='".$XCOW_B['url']."/browse/experience?e[".$session['response']['param']['type']."]=".urlencode($session['response']['param']['experience'])."&title=".urlencode($session['response']['param']['experienceTitle'])."&alternative=".urlencode($session['response']['param']['experienceAlternative'])."&has=".$has."'>".language($languageString)." <span class='count'>($count)</span></a></li>\n";
								}
							}
						}
						echo "</ul>\n";
						echo "</div>\n";

						echo "<div>\n";
						echo "<p><a href='".$XCOW_B['url']."/search?e[".urlencode($session['response']['param']['type'])."][".urlencode($session['response']['param']['experience'])."]=".urlencode(urlencode($session['response']['param']['experienceTitle'])).",".urlencode(urlencode($session['response']['param']['experienceAlternative'])).",,' class='more'>".language('sciomio_word_browse_digg')."</a><br/><br/></p>";
						echo "</div>\n";

						// show user
						echo "<ul class='filtered expandable'>\n";
						foreach ($session['response']['param']['userList'] as $userKey => $userVal) {

							$verdict = "happy";
							if ($session['response']['param']['UserExperienceInfo'][$userVal['Id']]['like'] == 1) {$verdict = "happy-xl";}
							if ($session['response']['param']['UserExperienceInfo'][$userVal['Id']]['like'] == 2) {$verdict = "happy";}
							if ($session['response']['param']['UserExperienceInfo'][$userVal['Id']]['like'] == 3) {$verdict = "unhappy";}
							if ($session['response']['param']['UserExperienceInfo'][$userVal['Id']]['like'] == 4) {$verdict = "unhappy-xl";}

							echo "<li>";

							echo "<div class='img-item box'>";
							echo "<div class='img'>";
							if (! isset($userVal['photo'])) { $userVal['photo'] = "/ui/gfx/photo.jpg"; }
							else { $userVal['photo'] = str_replace("/upload/","/upload/48x48_",$userVal['photo']); }
							echo "<a href='".$XCOW_B['url']."/view?user=".$userVal['Id']."'><img src='".$XCOW_B['url'].$userVal['photo']."' width='48' height='48' alt='' /></a>";
							echo "</div>";
							echo "<div class='bd'>";
							echo $me = "";
							if ($session['response']['param']['me'] == $userVal['Id']) {
								$me = "<span class='you-label'>".language('sciomio_word_you')."</span>";
							}
							echo "<h3><a class='userlink' href='".$XCOW_B['url']."/view?user=".$userVal['Id']."'>".$userVal['FirstName']." ".$userVal['LastName']."</a>".$me."</h3>";
							$displayOrganization = $userVal['Organization'][get_id_from_multi_array($userVal['Organization'], 'Name', 'Current')]['division'];
							if ($displayOrganization == "") { $displayOrganization = $userVal['Organization'][get_id_from_multi_array($userVal['Organization'], 'Name', 'Current')]['company']; }
							echo "<p>".$userVal['Organization'][get_id_from_multi_array($userVal['Organization'], 'Name', 'Current')]['role']." - ".$displayOrganization."</p>";
							echo "</div>";
							echo "</div>";

							$languageString = "sciomio_word_has_".$session['response']['param']['UserExperienceInfo'][$userVal['Id']]['has'];
							echo "<h4 class='verdict ".$verdict." '>".language($languageString)."</h4>";

							echo "<table class='review from-user' style='width:80%'>";
							echo "<thead><tr><th style='width:50%'>".language('sciomio_text_view_pluspunten')."</th><th style='width:50%'>".language('sciomio_text_view_minpunten')."</th></tr></thead>";
							echo "<tbody><tr><td>";
							echo "<ul class='ftw'>";
							echo "<li>".$session['response']['param']['UserExperienceInfo'][$userVal['Id']]['positive1']."</li>";
							echo "<li>".$session['response']['param']['UserExperienceInfo'][$userVal['Id']]['positive2']."</li>";
							echo "<li>".$session['response']['param']['UserExperienceInfo'][$userVal['Id']]['positive3']."</li>";
							echo "</ul>";
							echo "</td><td>";
							echo "<ul class='fail'>";
							echo "<li>".$session['response']['param']['UserExperienceInfo'][$userVal['Id']]['negative1']."</li>";
							echo "<li>".$session['response']['param']['UserExperienceInfo'][$userVal['Id']]['negative2']."</li>";
							echo "<li>".$session['response']['param']['UserExperienceInfo'][$userVal['Id']]['negative3']."</li>";
							echo "</ul>";
							echo "</td></tr></tbody>";
							echo "</table>";

							echo "</li>";
						}
						# meer...
						if ($session['response']['param']['thereIsMore']) {
							echo "<a id='MoreButton' class='button buttonbold' href='".$XCOW_B['url']."/snippet/experience-more?e[".$session['response']['param']['type']."]=".urlencode($session['response']['param']['experience'])."&title=".urlencode($session['response']['param']['experienceTitle'])."&alternative=".urlencode($session['response']['param']['experienceAlternative'])."&offset=".$session['response']['param']['searchOffset']."&searchString=".$session['response']['param']['searchString']."'>".language('sciomio_word_moreResults')."</a>";
						}
						echo "</ul>\n";
	
						echo "</div>\n";
					}
					else {
						// title = '' => laat lijst titels zien
						if ($session['response']['param']['experienceTitle'] == '') {
							echo "<div class='section softdivide solo'>\n";
							if ($session['response']['param']['type'] == 'Product') {
								echo "<h2>".language('sciomio_text_browse_choose_product')."</h2>";
							}
							else {
								echo "<h2>".language('sciomio_text_browse_choose_other')."</h2>";
							}
							echo "<ul class='linklist index'>";
							echo "<li>";
							#echo "<div class='sectionhead single highlight'>Merk: <a href='#'>Audi</a></div>";
							echo "<ul>";
							foreach ($session['response']['param']['experienceDetail'] as $detailKey => $detailVal) {
								if ($detailKey == "title") {
									foreach ($detailVal as $title => $count) {
										echo "<li><a href='".$XCOW_B['url']."/browse/experience?e[".urlencode($session['response']['param']['type'])."]=".urlencode($session['response']['param']['experience'])."&title=".urlencode($title)."'>$title ($count)</a></li>\n";
									}
								}
							}
							echo "</ul>\n";
							echo "</li>\n";
							echo "</ul>\n";
							echo "</div>\n";
						}
						// product:alternative = '' => laat lijst alternatieven zien
						else {
							if ($session['response']['param']['type'] == 'Product' && $session['response']['param']['experienceAlternative'] == '') {
								echo "<div class='section softdivide solo'>\n";
								echo "<h2>".language('sciomio_text_browse_choose_product_alternative')."</h2>";
								echo "<ul class='linklist index'>";
								echo "<li>";
								echo "<div class='sectionhead single highlight'>".$session['response']['param']['experienceTitle']."<a href='".$XCOW_B['url']."/browse/experience?e[".urlencode($session['response']['param']['type'])."]=".urlencode($session['response']['param']['experience'])."&title=".urlencode($title)."'>".language('sciomio_text_browse_label_product')."</a></div>";
								echo "<ul>";
								foreach ($session['response']['param']['experienceDetail'] as $detailKey => $detailVal) {
									if ($detailKey == "alternative") {
										foreach ($detailVal as $alternative => $count) {
											echo "<li><a href='".$XCOW_B['url']."/browse/experience?e[".urlencode($session['response']['param']['type'])."]=".urlencode($session['response']['param']['experience'])."&title=".urlencode($session['response']['param']['experienceTitle'])."&alternative=".urlencode($alternative)."'>$alternative ($count)</a></li>\n";
										}
									}
								}
								echo "</ul>\n";
								echo "</li>\n";
								echo "</ul>\n";
								echo "</div>\n";
							}
						}
					}
					?>
		
				</div>
				
				<div class="unit unit1-2">
						
					<?php
					if (($session['response']['param']['type'] != 'Product' && $session['response']['param']['experienceTitle'] != '') || ($session['response']['param']['type'] == 'Product' && $session['response']['param']['experienceAlternative'] != '')) {
						/*
						echo "<div class='section'>";
						echo "<div class='article'>";
						echo "";
						echo "</div>";
						echo "</div>";
						*/
					}
					else {
						echo "<div class='section softdivide'>";
						if ($session['response']['param']['bestWorst']) {
							echo "<h2>".language('sciomio_text_browse_best_worst')."</h2>";
							echo "<ul>";
							#best
							echo "<li class='img-item'>";
							echo "<div class='hd'>";
							$countDisplay = language('sciomio_text_browse_experience_word');
							if ($session['response']['param']['bestCount'] == 1) {
								$countDisplay = language('sciomio_text_browse_experience_word_een');
							}
							echo "<h3><a href='".$XCOW_B['url']."/browse/experience?e[".urlencode($session['response']['param']['type'])."]=".urlencode($session['response']['param']['bestSubject'])."&title=".urlencode($session['response']['param']['bestTitle'])."'>".$session['response']['param']['bestTitle']."</a> <span class='count'>".$session['response']['param']['bestCount']." ".$countDisplay."</span></h3>";
							echo "</div>";
							echo "<div class='img'>";
							#echo "<a href='#'><img src='/content/images/dummy-audi130x90.jpg' width='130' height='90' alt='Dummy Audi130x90' /></a>
							echo "</div>";
							echo "<div class='bd'>";
							echo "<table class='barometer'><tbody>";
							foreach ($session['response']['param']['bestList']['like'] as $likeKey => $likeVal) {
								$verdict = "happy"; $value = "pos"; $text=language('sciomio_text_stats_like_1');;
								if ($likeKey == 1) {$verdict = "happy-xl"; $value = "pos"; $text=language('sciomio_text_stats_like_1');}
								if ($likeKey == 2) {$verdict = "happy"; $value = "pos"; $text=language('sciomio_text_stats_like_2');}
								if ($likeKey == 3) {$verdict = "unhappy"; $value = "neg"; $text=language('sciomio_text_stats_like_3');}
								if ($likeKey == 4) {$verdict = "unhappy-xl"; $value = "neg"; $text=language('sciomio_text_stats_like_4');}
								echo "<tr>";
								echo "<th scope='row'><span class='verdict ".$verdict."'>".$text."</span></th>";
								echo "<td>";
								echo "<div class='scale'>";
								echo "<div class='fill ".$value." fill".round( ($likeVal/$session['response']['param']['bestCount']) * 10)."'></div>";
								echo "</div>";
								echo "<span class='count'>(".$likeVal.")</span>";
								echo "</td>";
								echo "</tr>";
							}
							echo "</tbody></table>";
							echo "</div>";
							echo "</li>";
							# worst
							echo "<li class='img-item'>";
							echo "<div class='hd'>";
							$countDisplay = language('sciomio_text_browse_experience_word');
							if ($session['response']['param']['worstCount'] == 1) {
								$countDisplay = language('sciomio_text_browse_experience_word_een');
							}
							echo "<h3><a href='".$XCOW_B['url']."/browse/experience?e[".urlencode($session['response']['param']['type'])."]=".urlencode($session['response']['param']['worstSubject'])."&title=".urlencode($session['response']['param']['worstTitle'])."'>".$session['response']['param']['worstTitle']."</a> <span class='count'>".$session['response']['param']['worstCount']." ".$countDisplay."</span></h3>";
							echo "</div>";
							echo "<div class='img'>";
							#echo "<a href='#'><img src='/content/images/dummy-audi130x90.jpg' width='130' height='90' alt='Dummy Audi130x90' /></a>
							echo "</div>";
							echo "<div class='bd'>";
							echo "<table class='barometer'><tbody>";
							foreach ($session['response']['param']['worstList']['like'] as $likeKey => $likeVal) {
								$verdict = "happy"; $value = "pos"; $text=language('sciomio_text_stats_like_1');;
								if ($likeKey == 1) {$verdict = "happy-xl"; $value = "pos"; $text=language('sciomio_text_stats_like_1');}
								if ($likeKey == 2) {$verdict = "happy"; $value = "pos"; $text=language('sciomio_text_stats_like_2');}
								if ($likeKey == 3) {$verdict = "unhappy"; $value = "neg"; $text=language('sciomio_text_stats_like_3');}
								if ($likeKey == 4) {$verdict = "unhappy-xl"; $value = "neg"; $text=language('sciomio_text_stats_like_4');}
								echo "<tr>";
								echo "<th scope='row'><span class='verdict ".$verdict."'>".$text."</span></th>";
								echo "<td>";
								echo "<div class='scale'>";
								echo "<div class='fill ".$value." fill".round( ($likeVal/$session['response']['param']['worstCount']) * 10)."'></div>";
								echo "</div>";
								echo "<span class='count'>(".$likeVal.")</span>";
								echo "</td>";
								echo "</tr>";
							}
							echo "</tbody></table>";
							echo "</div>";
							echo "</li>";
							echo "</ul>";
						}
						echo "</div>";
					}
					?>
				</div>
				
			</div>

		</div>
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
	<script type="text/javascript">
		addLoadEvent(function() {Session.View.load();});
	</script>

</body>
</html>
