<?php
	$page = 'personen';
    	$view = 'listview';
?>
<!DOCTYPE html>

<html lang="nl" class="no-js">
<head>
	<meta charset="UTF-8" />

	<title><?php echo language('sciomio_title_search'); ?></title>

	<?php include("includes/headers.php"); ?>
	<!-- needed to display map standalone -->
	<?php include("includes/headers-maps.php"); ?>

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
			<!-- FOCUS -->
			<div class="section focusbar">
				<div class="hgroup focusheader">
					<h2><?php echo language('sciomio_header_search_focus'); ?></h2>
					<a class="tooltip" title="Word bewaard op je account pagina's" href="javascript:ScioMino.FocusNew.action('<?php echo $session['response']['param']['query']['focus']?>')"><?php echo language('sciomio_word_search_focus_save'); ?></a>
					<div id='focusWindow'>
					</div>
				</div>

				<ul class="focuscurrent">

				<!-- NAME-->
				<?php
				if ($session['response']['param']['query']['name'] != '') {

					$nameEncode = urlencode($session['response']['param']['query']['name']);
					$nameEncodeEscape = str_replace('+', '\+', $queryEncode);
					$nameFocus = "\&n=".$nameEncodeEscape;
		
					$focusStripped = preg_replace("/^(.*)$nameFocus([^\&]*)(.*)$/", "$1$3", $session['response']['param']['query']['focus']);

					echo "<li class='focusgroup'><dl>";
					echo "<dt>".language('sciomio_word_focus_name')."<a href='".$XCOW_B['url']."/search?".$focusStripped."' class='delete'>x</a></dt>";

					echo "<dd>";
					echo "<span class='field' href='#'>".$session['response']['param']['query']['name']."</span>";

					/*
					if (count($session['response']['param']['suggestList']) > 0) {
						echo "<div style='margin:5px;padding:2px;border: 1px solid grey;'>";
						echo "<div>Bedoelde je misschien:</div>\n";
						foreach ($session['response']['param']['suggestList'] as $suggest) {
							if ($suggest['context'] == "name") {
								echo "<b>".$suggest['context']."</b>: <a href='/web/list?n=".$suggest['word']."'>".$suggest['word']."</a><br/>\n";
							}
						}
						echo "</div>\n";
					}
					*/

					echo "</dd>";
					echo "</dl></li>";
				}
				?>

				<!-- QUERY-->
				<?php
				if ($session['response']['param']['query']['words'] != '') {

					$queryEncode = urlencode($session['response']['param']['query']['words']);
					$queryEncodeEscape = str_replace('+', '\+', $queryEncode);
					$queryFocus = "\&q=".$queryEncodeEscape;
		
					$focusStripped = preg_replace("/^(.*)$queryFocus([^\&]*)(.*)$/", "$1$3", $session['response']['param']['query']['focus']);

					echo "<li class='focusgroup'><dl>";
					echo "<dt>".language('sciomio_word_focus_query')."<a href='".$XCOW_B['url']."/search?".$focusStripped."' class='delete'>x</a></dt>";

					echo "<dd>";
					echo "<span class='field' href='#'>".$session['response']['param']['query']['words']."</span>";

					if (count($session['response']['param']['suggestList']) > 0) {
						echo "<div class='suggestion'>";
						echo "<p>".language('sciomio_text_search_suggest')."</p>\n";
						echo "<ul>";
						$count = 1;
						foreach ($session['response']['param']['suggestList'] as $suggest) {
							if ($suggest['context'] == "name") {
								echo "<li><strong>".language('sciomio_word_focus_name')."</strong>: <a href='".$XCOW_B['url']."/search?n=".urlencode($suggest['word'])."'>".$suggest['word']."</a></li>\n";							}
							elseif ($suggest['context'] == "knowledge") {
								echo "<li><strong>".language('sciomio_word_focus_knowledge')."</strong>: <a href='".$XCOW_B['url']."/search?k[".urlencode($suggest['word'])."]'>".$suggest['word']."</a></li>\n";
							}
							elseif ($suggest['context'] == "hobby") {
								echo "<li><strong>".language('sciomio_word_focus_hobby')."</strong>: <a href='".$XCOW_B['url']."/search?h[".urlencode($suggest['word'])."]'>".$suggest['word']."</a></li>\n";
							}
							elseif ($suggest['context'] == "tag") {
								echo "<li><strong>".language('sciomio_word_focus_tag')."</strong>: <a href='".$XCOW_B['url']."/search?t[".urlencode($suggest['word'])."]'>".$suggest['word']."</a></li>\n";
							}
							elseif ($suggest['context'] == "Product" || $suggest['context'] == "Company" || $suggest['context'] == "Event" || $suggest['context'] == "Education") {
								$languageString = "sciomio_word_focus_".$suggest['context'];
								echo "<li><strong>".language($languageString)."</strong>: <a href='".$XCOW_B['url']."/search?e[".urlencode($suggest['context'])."][".urlencode($suggest['word'])."]'>".$suggest['word']."</a></li>\n";
							}
							else {
								$languageString = "sciomio_word_focus_".$suggest['context'];
								echo "<li><strong>".language($languageString)."</strong>: <a href='".$XCOW_B['url']."/search?p[".urlencode($suggest['context'])."]=".urlencode($suggest['word'])."'>".$suggest['word']."</a></li>\n";
							}
							if ($count > 3) {
								break;
							}
							$count++;
						}
						echo "</ul>\n";
						echo "</div>\n";
					}

					echo "</dd>";
					echo "</dl></li>";
				}
				?>

				<!-- KNOWLEDGE-->
				<?php
				if (count($session['response']['param']['query']['knowledge']) > 0) {

					foreach ($session['response']['param']['query']['knowledge'] as $knowledgeKey => $knowledgeVal) {

						$knowledgeKeyEncode = urlencode($knowledgeKey);
						$knowledgeKeyEscape = str_replace('+', '\+', $knowledgeKeyEncode);
						$knowledgeFocus = "\&k\[".$knowledgeKeyEscape."\]";
						$knowledgeFocusReplace = "&k[".$knowledgeKeyEncode."]";
			
						$focusStripped = preg_replace("/^(.*)$knowledgeFocus([^\&]*)(.*)$/", "$1$3", $session['response']['param']['query']['focus']);

						echo "<li class='focusgroup'><dl>";
						echo "<dt>".language('sciomio_word_focus_knowledge')."<a href='".$XCOW_B['url']."/search?".$focusStripped."' class='delete'>x</a></dt>";

						echo "<dd>";
						echo "<a class='field' href='".$XCOW_B['url']."/browse/knowledge?k=".urlencode($knowledgeKey)."'>".$knowledgeKey."</a>";

						echo "<div class='dropdown-item dropdownAjax additional'>";
						if ($knowledgeVal == '') {
							echo "<a href='".$XCOW_B['url']."/snippet/search-knowledge?".$session['response']['param']['query']['focus']."&object=".urlencode($knowledgeKey)."' class='control'><span class='value'>".language('sciomio_word_search_focus_allKnowledge')."</span> <span class='edit'>wijzig</span></a>";
						}
						else {
							$focusCleared = preg_replace("/^(.*)$knowledgeFocus([^\&]*)(.*)$/", "$1$knowledgeFocusReplace$3", $session['response']['param']['query']['focus']);
							$languageString = "sciomio_word_knowledgefield_".$knowledgeVal;
							echo "<a href='".$XCOW_B['url']."/snippet/search-knowledge?".$focusCleared."&object=".urlencode($knowledgeKey)."' class='control'><span class='value'>".language($languageString)."</span> <span class='edit'>wijzig</span></a>";

						}
						echo "<div class='dropdown'></div>";
						echo "</div>";

						echo "</dd>";
						echo "</dl></li>";
					}

				}
				?>

				<!-- Hobby -->
				<?php
				if (count($session['response']['param']['query']['hobby']) > 0) {

					foreach ($session['response']['param']['query']['hobby'] as $hobbyKey => $dummy) {
						$hobbyKeyEncode = urlencode($hobbyKey);
						$hobbyKeyEscape = str_replace('+', '\+', $hobbyKeyEncode);
						$hobbyFocus = "\&h\[".$hobbyKeyEscape."\]";
			
						$focusStripped = preg_replace("/^(.*)$hobbyFocus([^\&]*)(.*)$/", "$1$3", $session['response']['param']['query']['focus']);

						echo "<li class='focusgroup'><dl>";
						echo "<dt>".language('sciomio_word_focus_hobby')."<a href='".$XCOW_B['url']."/search?".$focusStripped."' class='delete'>x</a></dt>";

						echo "<dd>";
						echo "<a class='field' href='".$XCOW_B['url']."/browse/hobby?h=".urlencode($hobbyKey)."'>".$hobbyKey."</a>";
						echo "</dd>";
						echo "</dl></li>";
					}

				}
				?>

				<!-- EXPERIENCE-->
				<?php
				if (count($session['response']['param']['query']['experience']) > 0) {

					foreach ($session['response']['param']['query']['experience'] as $experienceKey => $experienceVal) {
						foreach ($experienceVal as $experienceSubKey => $experienceSubVal) {
							if ($experienceSubVal != '') {
								list($title, $alternative, $like, $has) = explode(',', $experienceSubVal);
							}
							else {
								$title = '';
								$alternative = '';
								$like = '';
								$has = '';
							}

							$experienceKeyEncode = urlencode($experienceKey);
							$experienceKeyEscape = str_replace('+', '\+', $experienceKeyEncode);
							$experienceSubKeyEncode = urlencode($experienceSubKey);
							$experienceSubKeyEscape = str_replace('+', '\+', $experienceSubKeyEncode);
							$experienceFocus = "\&e\[$experienceKeyEscape\]\[$experienceSubKeyEscape\]";
							$experienceFocusReplace = "&e[$experienceKeyEncode][$experienceSubKeyEncode]";

							$focusStripped = preg_replace("/^(.*)$experienceFocus([^\&]*)(.*)$/", "$1$3", $session['response']['param']['query']['focus']);

							echo "<li class='focusgroup'><dl>";
							$languageString = "sciomio_word_focus_".$experienceKey;
							echo "<dt>".language($languageString)."<a href='".$XCOW_B['url']."/search?".$focusStripped."' class='delete'>x</a></dt>";

							echo "<dd>";
							echo "<a class='field' href='".$XCOW_B['url']."/browse/experience?e[".urlencode($experienceKey)."]=".urlencode($experienceSubKey)."'>".$experienceSubKey."</a>";

							# has (alleen producten)
							if ($experienceKey == "Product") {
								echo "<div class='dropdown-item dropdownAjax additional'>";
								if ($has == '') {
									echo "<a href='".$XCOW_B['url']."/snippet/search-experience?".$session['response']['param']['query']['focus']."&type=".urlencode($experienceKey)."&object=".urlencode($experienceSubKey)."&output=has' class='control'><span class='value'>".language('sciomio_word_search_focus_allHas')."</span> <span class='edit'>wijzig</span></a>";
								}
								else {
									$focusCleared = preg_replace("/^(.*)$experienceFocus([^\&]*)(.*)$/", "$1$experienceFocusReplace=$title,$alternative,$like,$3", $session['response']['param']['query']['focus']);
									$languageString = "sciomio_word_has_".$has;
									echo "<a href='".$XCOW_B['url']."/snippet/search-experience?".$focusCleared."&type=".urlencode($experienceKey)."&object=".urlencode($experienceSubKey)."&output=has' class='control'><span class='value'>".language($languageString)."</span> <span class='edit'>wijzig</span></a>";
								}
								echo "<div class='dropdown'></div>";
								echo "</div>";
							}

							# title
							echo "<div class='dropdown-item dropdownAjax additional'>";
							if ($title == '') {
								if ($experienceKey == "Product") { $titleDisplay = language('sciomio_word_search_focus_allProducts'); }
								if ($experienceKey == "Company") { $titleDisplay = language('sciomio_word_search_focus_allCompanies'); }
								if ($experienceKey == "Event") { $titleDisplay = language('sciomio_word_search_focus_allEvents'); }
								if ($experienceKey == "Education") { $titleDisplay = language('sciomio_word_search_focus_allEducations'); }
								echo "<a href='".$XCOW_B['url']."/snippet/search-experience?".$session['response']['param']['query']['focus']."&type=".urlencode($experienceKey)."&object=".urlencode($experienceSubKey)."&output=title' class='control'><span class='value'>".$titleDisplay."</span> <span class='edit'>wijzig</span></a>";
							}
							else {
								$focusCleared = preg_replace("/^(.*)$experienceFocus([^\&]*)(.*)$/", "$1$experienceFocusReplace=,$alternative,$like,$has$3", $session['response']['param']['query']['focus']);
								echo "<a href='".$XCOW_B['url']."/snippet/search-experience?".$focusCleared."&type=".urlencode($experienceKey)."&object=".urlencode($experienceSubKey)."&output=title' class='control'><span class='value'>".urldecode($title)."</span> <span class='edit'>wijzig</span></a>";
							}
							echo "<div class='dropdown'></div>";
							echo "</div>";

							# alternative (alleen producten, title moet al gekozen zijn)
							if ($experienceKey == "Product" && $title != "") {
								echo "<div class='dropdown-item dropdownAjax additional'>";
								if ($alternative == '') {
									echo "<a href='".$XCOW_B['url']."/snippet/search-experience?".$session['response']['param']['query']['focus']."&type=".urlencode($experienceKey)."&object=".urlencode($experienceSubKey)."&output=alternative' class='control'><span class='value'>".language('sciomio_word_search_focus_allProductAlternatives')."</span> <span class='edit'>wijzig</span></a>";
								}
								else {
									$focusCleared = preg_replace("/^(.*)$experienceFocus([^\&]*)(.*)$/", "$1$experienceFocusReplace=$title,,$like,$has$3", $session['response']['param']['query']['focus']);
									echo "<a href='".$XCOW_B['url']."/snippet/search-experience?".$focusCleared."&type=".urlencode($experienceKey)."&object=".urlencode($experienceSubKey)."&output=alternative' class='control'><span class='value'>".urldecode($alternative)."</span> <span class='edit'>wijzig</span></a>";
								}
								echo "<div class='dropdown'></div>";
								echo "</div>";
							}

							# like
							echo "<div class='dropdown-item dropdownAjax additional'>";
							if ($like == '') {
								echo "<a href='".$XCOW_B['url']."/snippet/search-experience?".$session['response']['param']['query']['focus']."&type=".urlencode($experienceKey)."&object=".urlencode($experienceSubKey)."&output=like' class='control'><span class='value'>".language('sciomio_word_search_focus_allLike')."</span> <span class='edit'>wijzig</span></a>";
							}
							else {
								$focusCleared = preg_replace("/^(.*)$experienceFocus([^\&]*)(.*)$/", "$1$experienceFocusReplace=$title,$alternative,,$has$3", $session['response']['param']['query']['focus']);
								$languageString = "sciomio_word_like_".$like;
								echo "<a href='".$XCOW_B['url']."/snippet/search-experience?".$focusCleared."&type=".urlencode($experienceKey)."&object=".urlencode($experienceSubKey)."&output=like' class='control'><span class='value'>".language($languageString)."</span> <span class='edit'>wijzig</span></a>";
							}
							echo "<div class='dropdown'></div>";
							echo "</div>";

							echo "</dd>";
							echo "</dl></li>";

						}
					}

				}
				?>

				<!-- Tag -->
				<?php
				if (count($session['response']['param']['query']['tag']) > 0) {

					foreach ($session['response']['param']['query']['tag'] as $tagKey => $dummy) {
						$tagKeyEncode = urlencode($tagKey);
						$tagKeyEscape = str_replace('+', '\+', $tagKeyEncode);
						$tagFocus = "\&t\[".$tagKeyEscape."\]";
				
						$focusStripped = preg_replace("/^(.*)$tagFocus([^\&]*)(.*)$/", "$1$3", $session['response']['param']['query']['focus']);

						echo "<li class='focusgroup'><dl>";
						echo "<dt>".language('sciomio_word_focus_tag')."<a href='".$XCOW_B['url']."/search?".$focusStripped."' class='delete'>x</a></dt>";

						echo "<dd>";
						echo "<a class='field' href='".$XCOW_B['url']."/browse/tag?t=".urlencode($tagKey)."'>".$tagKey."</a>";
						echo "</dd>";
						echo "</dl></li>";
					}

				}
				?>

				<!-- Personal -->
				<?php
				if (count($session['response']['param']['query']['personal']) > 0) {

					foreach ($session['response']['param']['query']['personal'] as $personalKey => $personalVal) {
						$personalKeyEncode = urlencode($personalKey);
						$personalKeyEscape = str_replace('+', '\+', $personalKeyEncode);
						$personalFocus = "\&p\[".$personalKeyEscape."\]";
				
						$focusStripped = preg_replace("/^(.*)$personalFocus([^\&]*)(.*)$/", "$1$3", $session['response']['param']['query']['focus']);

						echo "<li class='focusgroup'><dl>";
						echo "<dt>".language('sciomio_word_focus_'.$personalKey)."<a href='".$XCOW_B['url']."/search?".$focusStripped."' class='delete'>x</a></dt>";

						echo "<dd>";
						echo "<span class='field' href='#'>".$personalVal."</span>";
						echo "</dd>";
						echo "</dl></li>";
					}

				}
				?>

				<!-- Types Lijst -->
				<?php
				if (count($session['response']['param']['query']['typeList']) > 0) {

					foreach ($session['response']['param']['query']['typeList'] as $typeListKey => $typeListVal) {
						$typeListKeyEncode = urlencode($typeListKey);
						$typeListKeyEscape = str_replace('+', '\+', $typeListKeyEncode);
						$typeListFocus = "\&tl\[".$typeListKeyEscape."\]";
				
						$focusStripped = preg_replace("/^(.*)$typeListFocus([^\&]*)(.*)$/", "$1$3", $session['response']['param']['query']['focus']);

						echo "<li class='focusgroup'><dl>";
						echo "<dt>".language('sciomio_word_focus_'.$typeListKey.'List')."<a href='".$XCOW_B['url']."/search?".$focusStripped."' class='delete'>x</a></dt>";

						echo "<dd>";
						echo "<span class='field' href='#'>".$typeListVal."</span>";
						echo "</dd>";
						echo "</dl></li>";
					}

				}
				?>

				<!-- Lijst -->
				<?php
				if (count($session['response']['param']['query']['list']) > 0) {

					foreach ($session['response']['param']['query']['list'] as $listKey => $dummy) {
						$listKeyEncode = urlencode($listKey);
						$listKeyEscape = str_replace('+', '\+', $listKeyEncode);
						$listFocus = "\&l\[".$listKeyEscape."\]";
				
						$focusStripped = preg_replace("/^(.*)$listFocus([^\&]*)(.*)$/", "$1$3", $session['response']['param']['query']['focus']);

						echo "<li class='focusgroup'><dl>";
						echo "<dt>".language('sciomio_word_focus_list')."<a href='".$XCOW_B['url']."/search?".$focusStripped."' class='delete'>x</a></dt>";

						echo "<dd>";
						echo "<span class='field' href='#'>".$listKey."</span>";
						echo "</dd>";
						echo "</dl></li>";
					}

				}
				?>

			<!-- end FOCUS -->
			</div>

		</div>
		
		<div class="page">

			<!-- FILTER -->
			<div class="unit unit1-3alt">
				<?php
				if ($session['response']['param']['userCount'] == 1) {
					echo "<h1>".$session['response']['param']['userCount']." <span class='sub'>".language('sciomio_header_search_filter_een')."</span></h1>";
				}
				else {
					$plus="";
					if ($session['response']['param']['userCount'] >= $XCOW_B['sciomino']['user-api-max']) { $plus="+"; }
					echo "<h1>".$session['response']['param']['userCount'].$plus." <span class='sub'>".language('sciomio_header_search_filter')."</span></h1>";
				}
				?>

				<?php
				# show filtergroup if there are results
				if ($session['response']['param']['userCount'] > 0) {
					echo "<div class='section filtergroup'>\n";
				}
				?>

				<!-- KNOWLEDGE -->
				<?php
				if (count($session['response']['param']['knowledgeList']) > 0 || count($session['response']['param']['hobbyList']) > 0 || count($session['response']['param']['tagList']) > 0) {

					echo "<h2>".language('sciomio_header_search_filter_knowledge')."</h2>";
					echo "<ul class='togglelist'>";

					# knowledge
					if (count($session['response']['param']['knowledgeList']) > 0) {
						echo "<li class='open'><span class='sectionhead'>".language('sciomio_word_filter_knowledge')."</span>";
	
						echo "<ul>";
						foreach ($session['response']['param']['knowledgeList'] as $knowledgeKey => $knowledgeVal) {
							echo "<li><a href='".$XCOW_B['url']."/search?".$session['response']['param']['query']['focus']."&k[".urlencode($knowledgeKey)."]'>$knowledgeKey</a> <span class='count'>($knowledgeVal)</span></li>";
						}
						if (count($session['response']['param']['knowledgeList']) > 4) {
					    		echo "<li><a class='modal' href='".$XCOW_B['url']."/snippet/search-detail?detail=knowledge&".$session['response']['param']['query']['focus']."'>Meer&hellip;</a></li>";
						}
						echo "</ul>";
						echo "</li>";
					}

					# hobbies
					if (count($session['response']['param']['hobbyList']) > 0) {
						echo "<li class='open'><span class='sectionhead'>".language('sciomio_word_filter_hobby')."</span>";
	
						echo "<ul>";
						foreach ($session['response']['param']['hobbyList'] as $hobbyKey => $hobbyVal) {
							echo "<li><a href='".$XCOW_B['url']."/search?".$session['response']['param']['query']['focus']."&h[".urlencode($hobbyKey)."]'>$hobbyKey</a> <span class='count'>($hobbyVal)</span></li>";
						}
						if (count($session['response']['param']['hobbyList']) > 4) {
						    	echo "<li><a class='modal' href='".$XCOW_B['url']."/snippet/search-detail?detail=hobby&".$session['response']['param']['query']['focus']."'>Meer&hellip;</a></li>";
						}
						echo "</ul>";
						echo "</li>";
					}

					# tag
					if (count($session['response']['param']['tagList']) > 0) {
						echo "<li class='open'><span class='sectionhead'>".language('sciomio_word_filter_tag')."</span>";
	
						echo "<ul>";
						foreach ($session['response']['param']['tagList'] as $tagKey => $tagVal) {
							echo "<li><a href='".$XCOW_B['url']."/search?".$session['response']['param']['query']['focus']."&t[".urlencode($tagKey)."]'>$tagKey</a> <span class='count'>($tagVal)</span></li>";
						}
						if (count($session['response']['param']['tagList']) > 4) {
					    		echo "<li><a class='modal' href='".$XCOW_B['url']."/snippet/search-detail?detail=tag&".$session['response']['param']['query']['focus']."'>Meer&hellip;</a></li>";
						}
						echo "</ul>";
						echo "</li>";
					}

					echo "</ul>";
				}
				?>

				<!-- EXPERIENCE-->
				<?php
				if (count($session['response']['param']['companyList']) > 0 || count($session['response']['param']['eventList']) > 0 || count($session['response']['param']['educationList']) > 0 || count($session['response']['param']['productList']) > 0) {

					echo "<h2>".language('sciomio_header_search_filter_experience')."</h2>";
					echo "<ul class='togglelist'>";

					# product
					if (count($session['response']['param']['productList']) > 0) {
						echo "<li class='open'><span class='sectionhead'>".language('sciomio_word_filter_product')."</span>";

						echo "<ul>";
						foreach ($session['response']['param']['productList'] as $productKey => $productVal) {
							echo "<li><a href='".$XCOW_B['url']."/search?".$session['response']['param']['query']['focus']."&e[Product][".urlencode($productKey)."]'>$productKey</a> <span class='count'>($productVal)</span></li>";
						}
						if (count($session['response']['param']['productList']) > 4) {
					    		echo "<li><a class='modal' href='".$XCOW_B['url']."/snippet/search-detail?detail=product&".$session['response']['param']['query']['focus']."'>Meer&hellip;</a></li>";
						}
						echo "</ul>";
						echo "</li>";
					}

					# company
					if (count($session['response']['param']['companyList']) > 0) {
						echo "<li class='open'><span class='sectionhead'>".language('sciomio_word_filter_company')."</span>";

						echo "<ul>";
						foreach ($session['response']['param']['companyList'] as $companyKey => $companyVal) {
							echo "<li><a href='".$XCOW_B['url']."/search?".$session['response']['param']['query']['focus']."&e[Company][".urlencode($companyKey)."]'>$companyKey</a> <span class='count'>($companyVal)</span></a></li>";
						}
						if (count($session['response']['param']['companyList']) > 4) {
					    		echo "<li><a class='modal' href='".$XCOW_B['url']."/snippet/search-detail?detail=company&".$session['response']['param']['query']['focus']."'>Meer&hellip;</a></li>";
						}
						echo "</ul>";
						echo "</li>";
					}

					# event
					if (count($session['response']['param']['eventList']) > 0) {
						echo "<li class='open'><span class='sectionhead'>".language('sciomio_word_filter_event')."</span>";

						echo "<ul>";
						foreach ($session['response']['param']['eventList'] as $eventKey => $eventVal) {
							echo "<li><a href='".$XCOW_B['url']."/search?".$session['response']['param']['query']['focus']."&e[Event][".urlencode($eventKey)."]'>$eventKey</a> <span class='count'>($eventVal)</span></a></li>";
						}
						if (count($session['response']['param']['eventList']) > 4) {
					    		echo "<li><a class='modal' href='".$XCOW_B['url']."/snippet/search-detail?detail=event&".$session['response']['param']['query']['focus']."'>Meer&hellip;</a></li>";
						}
						echo "</ul>";
						echo "</li>";
					}

					# education
					if (count($session['response']['param']['educationList']) > 0) {
						echo "<li class='open'><span class='sectionhead'>".language('sciomio_word_filter_education')."</span>";

						echo "<ul>";
						foreach ($session['response']['param']['educationList'] as $educationKey => $educationVal) {
							echo "<li><a href='".$XCOW_B['url']."/search?".$session['response']['param']['query']['focus']."&e[Education][".urlencode($educationKey)."]'>$educationKey</a> <span class='count'>($educationVal)</span></li>";
						}
						if (count($session['response']['param']['educationList']) > 4) {
					    		echo "<li><a class='modal' href='".$XCOW_B['url']."/snippet/search-detail?detail=education&".$session['response']['param']['query']['focus']."'>Meer&hellip;</a></li>";
						}
						echo "</ul>";
						echo "</li>";
					}

					echo "</ul>";
				}
				?>

				<!-- Personal -->
				<?php
				if (count($session['response']['param']['roleList']) > 0 || count($session['response']['param']['industryList']) > 0 || count($session['response']['param']['organizationList']) > 0 || count($session['response']['param']['businessunitList']) > 0 || count($session['response']['param']['sectionList']) > 0 || count($session['response']['param']['listList']) > 0 || count($session['response']['param']['managerListList']) > 0 || count($session['response']['param']['publicListList']) > 0) {

					echo "<h2>".language('sciomio_header_search_filter_personal')."</h2>";
					echo "<ul class='togglelist'>";

					# industry
					if (count($session['response']['param']['industryList']) > 0) {
						echo "<li class='open'><span class='sectionhead'>".language('sciomio_word_filter_industry')."</span>";
	
						echo "<ul>";
						foreach ($session['response']['param']['industryList'] as $industryKey => $industryVal) {
							echo "<li><a href='".$XCOW_B['url']."/search?".$session['response']['param']['query']['focus']."&p[industry]=".urlencode($industryKey)."'>$industryKey</a> <span class='count'>($industryVal)</span></li>";
						}
						if (count($session['response']['param']['industryList']) > 4) {
					    		echo "<li><a class='modal' href='".$XCOW_B['url']."/snippet/search-detail?detail=industry&".$session['response']['param']['query']['focus']."'>Meer&hellip;</a></li>";
						}
						echo "</ul>";
						echo "</li>";
					}

					# organization
					if (count($session['response']['param']['organizationList']) > 0) {
						echo "<li class='open'><span class='sectionhead'>".language('sciomio_word_filter_organization')."</span>";
	
						echo "<ul>";
						foreach ($session['response']['param']['organizationList'] as $organizationKey => $organizationVal) {
							echo "<li><a href='".$XCOW_B['url']."/search?".$session['response']['param']['query']['focus']."&p[organization]=".urlencode($organizationKey)."'>$organizationKey</a> <span class='count'>($organizationVal)</span></li>";
						}
						if (count($session['response']['param']['organizationList']) > 4) {
					    		echo "<li><a class='modal' href='".$XCOW_B['url']."/snippet/search-detail?detail=organization&".$session['response']['param']['query']['focus']."'>Meer&hellip;</a></li>";
						}
						echo "</ul>";
						echo "</li>";
					}

					# businessunit
					if (count($session['response']['param']['businessunitList']) > 0) {
						echo "<li class='open'><span class='sectionhead'>".language('sciomio_word_filter_businessunit')."</span>";
	
						echo "<ul>";
						foreach ($session['response']['param']['businessunitList'] as $businessunitKey => $businessunitVal) {
							echo "<li><a href='".$XCOW_B['url']."/search?".$session['response']['param']['query']['focus']."&p[businessunit]=".urlencode($businessunitKey)."'>$businessunitKey</a> <span class='count'>($businessunitVal)</span></li>";
						}
						if (count($session['response']['param']['businessunitList']) > 4) {
					    		echo "<li><a class='modal' href='".$XCOW_B['url']."/snippet/search-detail?detail=businessunit&".$session['response']['param']['query']['focus']."'>Meer&hellip;</a></li>";
						}
						echo "</ul>";
						echo "</li>";
					}

					# section
					if (count($session['response']['param']['sectionList']) > 0) {
						echo "<li class='open'><span class='sectionhead'>".language('sciomio_word_filter_section')."</span>";
	
						echo "<ul>";
						foreach ($session['response']['param']['sectionList'] as $sectionKey => $sectionVal) {
							echo "<li><a href='".$XCOW_B['url']."/search?".$session['response']['param']['query']['focus']."&p[section]=".urlencode($sectionKey)."'>$sectionKey</a> <span class='count'>($sectionVal)</span></li>";
						}
						if (count($session['response']['param']['sectionList']) > 4) {
					    		echo "<li><a class='modal' href='".$XCOW_B['url']."/snippet/search-detail?detail=section&".$session['response']['param']['query']['focus']."'>Meer&hellip;</a></li>";
						}
						echo "</ul>";
						echo "</li>";
					}

					# role
					if (count($session['response']['param']['roleList']) > 0) {
						echo "<li class='open'><span class='sectionhead'>".language('sciomio_word_filter_role')."</span>";
	
						echo "<ul>";
						foreach ($session['response']['param']['roleList'] as $roleKey => $roleVal) {
							echo "<li><a href='".$XCOW_B['url']."/search?".$session['response']['param']['query']['focus']."&p[role]=".urlencode($roleKey)."'>$roleKey</a> <span class='count'>($roleVal)</span></li>";
						}
						if (count($session['response']['param']['roleList']) > 4) {
					    		echo "<li><a class='modal' href='".$XCOW_B['url']."/snippet/search-detail?detail=role&".$session['response']['param']['query']['focus']."'>Meer&hellip;</a></li>";
						}
						echo "</ul>";
						echo "</li>";
					}

					# lijsten
					if (count($session['response']['param']['managerListList']) > 0) {
						echo "<li class='open'><span class='sectionhead'>".language('sciomio_word_filter_managerList')."</span>";
	
						echo "<ul>";
						foreach ($session['response']['param']['managerListList'] as $listKey => $listVal) {
							echo "<li><a href='".$XCOW_B['url']."/search?".$session['response']['param']['query']['focus']."&tl[manager]=".urlencode($listKey)."'>$listKey</a> <span class='count'>($listVal)</span></li>";
						}
						if (count($session['response']['param']['managerListList']) > 4) {
					    		echo "<li><a class='modal' href='".$XCOW_B['url']."/snippet/search-detail?detail=managerList&".$session['response']['param']['query']['focus']."'>Meer&hellip;</a></li>";
						}
						echo "</ul>";
						echo "</li>";
					}

					if (count($session['response']['param']['listList']) > 0) {
						echo "<li class='open'><span class='sectionhead'>".language('sciomio_word_filter_list')."</span>";
	
						echo "<ul>";
						foreach ($session['response']['param']['listList'] as $listKey => $listVal) {
							echo "<li><a href='".$XCOW_B['url']."/search?".$session['response']['param']['query']['focus']."&l[".urlencode($listKey)."]'>$listKey</a> <span class='count'>($listVal)</span></li>";
						}
						if (count($session['response']['param']['listList']) > 4) {
					    		echo "<li><a class='modal' href='".$XCOW_B['url']."/snippet/search-detail?detail=list&".$session['response']['param']['query']['focus']."'>Meer&hellip;</a></li>";
						}
						echo "</ul>";
						echo "</li>";
					}

					if (count($session['response']['param']['publicListList']) > 0) {
						echo "<li class='open'><span class='sectionhead'>".language('sciomio_word_filter_publicList')."</span>";
	
						echo "<ul>";
						foreach ($session['response']['param']['publicListList'] as $listKey => $listVal) {
							echo "<li><a href='".$XCOW_B['url']."/search?".$session['response']['param']['query']['focus']."&tl[public]=".urlencode($listKey)."'>$listKey</a> <span class='count'>($listVal)</span></li>";
						}
						if (count($session['response']['param']['publicListList']) > 4) {
					    		echo "<li><a class='modal' href='".$XCOW_B['url']."/snippet/search-detail?detail=publicList&".$session['response']['param']['query']['focus']."'>Meer&hellip;</a></li>";
						}
						echo "</ul>";
						echo "</li>";
					}

					echo "</ul>";
				}
				?>

				<!-- Werken en wonen -->
				<?php
				if (count($session['response']['param']['workplaceList']) > 0 || count($session['response']['param']['hometownList']) > 0) {

					echo "<h2>".language('sciomio_header_search_filter_location')."</h2>";
					echo "<ul class='togglelist'>";

					# workplace
					if (count($session['response']['param']['workplaceList']) > 0) {
						echo "<li class='open'><span class='sectionhead'>".language('sciomio_word_filter_workplace')." <a class='modal showmap show' href='".$XCOW_B['url']."/snippet/user-list-map?detail=workplace&".$session['response']['param']['query']['focus']."'>".language('sciomio_word_search_filter_map')."</a> </span>";
	
						echo "<ul>";
						foreach ($session['response']['param']['workplaceList'] as $workplaceKey => $workplaceVal) {
							echo "<li><a href='".$XCOW_B['url']."/search?".$session['response']['param']['query']['focus']."&p[workplace]=".urlencode($workplaceKey)."'>$workplaceKey</a> <span class='count'>($workplaceVal)</span></li>";
						}
						if (count($session['response']['param']['workplaceList']) > 4) {
					    		echo "<li><a class='modal showmap show' href='".$XCOW_B['url']."/snippet/user-list-map?detail=workplace&view=list&".$session['response']['param']['query']['focus']."'>Meer&hellip;</a></li>";
						}
						echo "</ul>";
						echo "</li>";
					}

					# hometown
					if (count($session['response']['param']['hometownList']) > 0) {
						echo "<li class='open'><span class='sectionhead'>".language('sciomio_word_filter_hometown')." <a class='modal showmap show' href='".$XCOW_B['url']."/snippet/user-list-map?detail=hometown&".$session['response']['param']['query']['focus']."'>".language('sciomio_word_search_filter_map')."</a> </span>";
	
						echo "<ul>";
						foreach ($session['response']['param']['hometownList'] as $hometownKey => $hometownVal) {
							echo "<li><a href='".$XCOW_B['url']."/search?".$session['response']['param']['query']['focus']."&p[hometown]=".urlencode($hometownKey)."'>$hometownKey</a> <span class='count'>($hometownVal)</span></li>";
						}
						if (count($session['response']['param']['hometownList']) > 4) {
					    		echo "<li><a class='modal showmap show' href='".$XCOW_B['url']."/snippet/user-list-map?detail=hometown&view=list&".$session['response']['param']['query']['focus']."'>Meer&hellip;</a></li>";
						}
						echo "</ul>";
						echo "</li>";
					}

					echo "</ul>";
				}
				?>

				<?php
				# show filtergroup if there are results
				if ($session['response']['param']['userCount'] > 0) {
					echo "</div>\n";
				}
				?>

			<!-- END FILTER -->
			</div>

			<div class="unit unit2-3alt">
				<div class="section main">

					<!-- RESULT -->
					<?php
					if (count($session['response']['param']['userList']) > 0) {

						echo "<div id='Searchresults' class='connect-checkboxes'>";

						echo "<div class='views'>";
						echo "<span class='sendcontrols'>";
						echo "<a href='".$XCOW_B['url']."/snippet/mail-new-form' class='button sendmessage'>".language('sciomio_word_message')."</a>";
						echo "<input class='checkbox checkall' type='checkbox' name='checkall2' id='checkall2' />";
						echo "</span>";
						echo "</div>";

						echo "<ul id='SearchList' class='filtered'>";

						foreach ($session['response']['param']['userList'] as $userKey => $userVal) {
							echo "<li class='img-item vcard'>";

								echo "<div class='img'>";
								if (! isset($userVal['photo'])) { $userVal['photo'] = "/ui/gfx/photo.jpg"; }
								else { $userVal['photo'] = str_replace("/upload/","/upload/96x96_",$userVal['photo']); }
								echo "<a href='".$XCOW_B['url']."/view?user=".$userVal['Id']."'><img class='photo' src='".$XCOW_B['url'].$userVal['photo']."' width='96' height='96' alt='' /></a>";
								echo "</div>";

								echo "<div class='bd'>";
			
									echo "<div class='controls'>";
									echo "<div class='lists listbutton dropdownAjax dropdown-item'>";
									echo "<a href='".$XCOW_B['url']."/snippet/list-list?user=".$userVal['Id']."' class='control'><span class='icon list'>L</span>".language('sciomio_text_vcard_saveList')."</a>";
									echo "<div class='dropdown interactive-set'></div>";
									echo "</div>";
									echo "<input class='message checkbox' type='checkbox' name='address[]' id='' value='".$userVal['Id']."' />";
									echo "</div>";
			
									echo $me = "";
									if ($session['response']['param']['user'] == $userVal['Id']) {
										$me = "<span class='you-label'>".language('sciomio_word_you')."</span>";
									}
									echo "<h3 class='fn n'><a class='userlink' href='".$XCOW_B['url']."/view?user=".$userVal['Id']."'><span class='given-name'>".$userVal['FirstName']."</span> <span class='family-name'>".$userVal['LastName']."</span></a>".$me."</h3>";
									$displayOrganization = $userVal['Organization'][get_id_from_multi_array($userVal['Organization'], 'Name', 'Current')]['division'];
									if ($displayOrganization == "") { $displayOrganization = $userVal['Organization'][get_id_from_multi_array($userVal['Organization'], 'Name', 'Current')]['company']; }
									echo "<p class='role'>".$userVal['Organization'][get_id_from_multi_array($userVal['Organization'], 'Name', 'Current')]['role']." - ".$displayOrganization."</p>";

									echo "<div class='group'>";

										echo "<div class='unit unit1-2 adr'>";
										$displayEmail = $userVal['Contact'][get_id_from_multi_array($userVal['Contact'], 'Name', 'Work')]['email'];
										if ($displayEmail == "") { $displayEmail = $userVal['Contact'][get_id_from_multi_array($userVal['Contact'], 'Name', 'Home')]['email']; }
										echo "<a class='email' href='mailto:".$displayEmail."'>".$displayEmail."</a>";

										$displayTel = $userVal['Contact'][get_id_from_multi_array($userVal['Contact'], 'Name', 'Work')]['telMobile'];
										if ($displayTel == "") { $displayTel = $userVal['Contact'][get_id_from_multi_array($userVal['Contact'], 'Name', 'Work')]['telExtern']; }
										if ($displayTel == "") { $displayTel = $userVal['Contact'][get_id_from_multi_array($userVal['Contact'], 'Name', 'Home')]['telMobile']; }
										echo "<div class='tel'>".$displayTel."</div>";

										echo "<div class='twitter'>";
										if (isset($session['response']['param']['twitterAccountList'][$userVal['Id']])) {
											echo "<a href='".$XCOW_B['url']."/snippet/tweet-new-form?user=".$session['response']['param']['twitterAccountList'][$userVal['Id']]."' class='modalflex tinyicon tinyicon-twitter userlink'>".$session['response']['param']['twitterAccountList'][$userVal['Id']]."</a>";
										}
										else {
											echo "&nbsp;";
										}
										echo '</div>';

										echo "<div class='locality'>".$userVal['Address'][get_id_from_multi_array($userVal['Address'], 'Name', 'Work')]['city']."</div>";
										echo "</div>";

										echo "<div class='unit unit1-2 last'>";
										if (isset($userVal['Message'])) {
											$timeString = timeDiff2($userVal['MessageTimestamp']);
											echo "<p class='time'>".$timeString."</p>";
											echo "<p>".$userVal['Message']."</p>";
										}
										else {
											echo "&nbsp;";
										}
										echo "</div>";

									echo "</div>";

								echo "</div>";

							echo "</li>";
						}

						# meer...
						if ($session['response']['param']['thereIsMore']) {
							echo "<a id='MoreButton' class='button buttonbold' href='".$XCOW_B['url']."/snippet/user-list-more?offset=".$session['response']['param']['searchOffset']."&searchString=".$session['response']['param']['searchString']."'>".language('sciomio_word_moreResults')."</a>";
						}
						echo "</ul>";

						echo "<span class='sendcontrols up'>";
						echo "<a href='".$XCOW_B['url']."/snippet/mail-new-form' class='button sendmessage'>".language('sciomio_word_message')."</a>";
						echo "<input class='checkbox checkall' type='checkbox' name='checkall2' id='checkall2' />";
						echo "</span>";
	
						echo "</div>";
					}
					else {
						if ($session['response']['param']['query']['words'] != '') {

							$languageTemplate = array();
							$languageTemplate['knowledge'] = $session['response']['param']['query']['words'];
							echo "<div>";
							echo "<h2>".language('sciomio_header_search_noResult')."</h2>";

							echo "<li style='padding-left:0px;'>";
							echo "<p>".language('sciomio_text_search_noResult_1_label')."</p>";
							echo "<h4><a href='javascript:ScioMino.ActivityNew.action(\"knowledge\",\"".$session['response']['param']['query']['words']."\");'>".language_template('sciomio_text_search_noResult_1_action', $languageTemplate)."</a></h4>";
							echo "<div id='activityNewWindow'></div>\n";
							echo "</li>";

							echo "<li style='padding-left:0px;'>";
							echo "<p>".language('sciomio_text_search_noResult_2_label')."</p>";
							echo "<h4><a href='".$XCOW_B['url']."/user/knowledge'>".language_template('sciomio_text_search_noResult_2_action', $languageTemplate)."</a></h4>";
							echo "</li>";

							echo "<li style='padding-left:0px;'>";
							echo "<p>".language('sciomio_text_search_noResult_3_label')."</p>";
							echo "</li>";

							echo "<li style='padding-left:0px;'>";
							echo "<p>".language('sciomio_text_search_noResult_4_label')."</p>";
							echo "<h4><a href='".$XCOW_B['url']."/act'>".language('sciomio_text_search_noResult_4_action')."</a></h4>";
							echo "</li>";
							echo "</div>";

						}
						elseif ($session['response']['param']['query']['name'] != '') {

							$languageTemplate = array();
							$languageTemplate['name'] = $session['response']['param']['query']['name'];
							echo "<div>";
							echo "<h2>".language('sciomio_header_search_noResult')."</h2>";

							echo "<li style='padding-left:0px;'>";
							echo "<p>".language('sciomio_text_search_noResult_3_label')."</p>";
							echo "</li>";

							echo "<li style='padding-left:0px;'>";
							echo "<p>".language('sciomio_text_search_noResult_5_label')."</p>";
							echo "<h4><a href='".$XCOW_B['url']."/search?q=".urlencode($session['response']['param']['query']['name'])."'>".language_template('sciomio_text_search_noResult_5_action', $languageTemplate)."</a></h4>";
							echo "</li>";

							echo "<li style='padding-left:0px;'>";
							echo "<p>".language('sciomio_text_search_noResult_4_label')."</p>";
							echo "<h4><a href='".$XCOW_B['url']."/act'>".language('sciomio_text_search_noResult_4_action')."</a></h4>";
							echo "</li>";
							echo "</div>";

						}
						else {

							echo "<div>";
							echo "<h2>".language('sciomio_header_search_noResult')."</h2>";

							echo "<li style='padding-left:0px;'>";
							echo "<p>".language('sciomio_text_search_noResult_3_label')."</p>";
							echo "</li>";
							echo "</div>";

						}
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
	<!-- prepare to load map -->
	<?php include 'includes/scripts-map.php'; ?>

 	<script type="text/javascript">
		addLoadEvent(function() {Session.View.load();});
	</script>

</body>
</html>
