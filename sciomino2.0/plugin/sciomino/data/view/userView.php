<?php
$page = 'personen';
#if ($session['response']['param']['me'] != 0) {
#	$page = "profiel";
#}
?>
<!DOCTYPE html>

<html lang="nl" class="no-js">
<head>
	<meta charset="UTF-8" />

	<title><?php echo language('sciomio_title_view'); ?> <?php echo $session['response']['param']['user']['FirstName'] ?></title>

	<?php include("includes/headers.php"); ?>
	<?php include("includes/headers-twitterfeed.php"); ?>

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

	<!-- YOU -->
	<?php
		if ($session['response']['param']['me'] != 0) {
			echo "<div class='nav nav-u'><div class='page'>";
			echo "<a href='".$XCOW_B['url']."/user' class='edit'>".language('sciomio_word_view_profile_edit')."</a>";
			echo "</div></div>";
		}
	?>

        </div>

	<div id="Content">
			<div style="height:1px;"></div>
		<div class="page">
			<div class="group divide div3-2">
				<div class="unit unit2-3">

					<div class="section userbox solo highlight" style="overflow:visible;">

						<div class="img-item vcard">
							<?php
							echo "<span class='img'>";
							if (! isset($session['response']['param']['user']['photo'])) { $session['response']['param']['user']['photo'] = "/ui/gfx/photo.jpg"; }
							else { $session['response']['param']['user']['photo'] = str_replace("/upload/","/upload/96x96_",$session['response']['param']['user']['photo']); }
							echo "<a class='modalflex' href='".$XCOW_B['url']."/snippet/user-photo-view?userRef=".$session['response']['param']['user']['Reference']."'><img src='".$XCOW_B['url'].$session['response']['param']['user']['photo']."' width='96' height='96' alt='' /></a> ";
							echo "</span>";
							echo "<div class='bd'>";

							/*
							echo "<div class='controls'>";
							echo "<div class='lists listbutton dropdownAjax dropdown-item'>";
							echo "<a data-readyhref='/snippet/list-list?user=".$session['response']['param']['user']['Id']."' class='control'><span class='icon list'>L</span>".language('sciomio_text_vcard_saveList')."</a>";
							echo "<div class='dropdown interactive-set'></div>";
							echo "</div>";
							echo "</div>";
							*/

							$me = "";
							if ($session['response']['param']['me'] != 0) {
								$me = "<span class='you-label'>".language('sciomio_word_you')."</span>";
							}
							echo "<h1 class='fn n'><span class='given-name'>".$session['response']['param']['user']['FirstName']."</span> <span class='family-name'>".$session['response']['param']['user']['LastName']."</span>".$me."</h1>";
							$displayOrganization = $session['response']['param']['user']['Organization'][get_id_from_multi_array($session['response']['param']['user']['Organization'], 'Name', 'Current')]['division'];
							if ($displayOrganization == "") { $displayOrganization = $session['response']['param']['user']['Organization'][get_id_from_multi_array($session['response']['param']['user']['Organization'], 'Name', 'Current')]['company']; }
							echo "<p class='role'>".$session['response']['param']['user']['Organization'][get_id_from_multi_array($session['response']['param']['user']['Organization'], 'Name', 'Current')]['role']." - ".$displayOrganization."</p>";

							echo "<div class='group'>";
							
							echo "<div class='unit unit1-2 adr'>";
							$displayEmail = $session['response']['param']['user']['Contact'][get_id_from_multi_array($session['response']['param']['user']['Contact'], 'Name', 'Work')]['email'];
							if ($displayEmail == "") { $displayEmail = $session['response']['param']['user']['Contact'][get_id_from_multi_array($session['response']['param']['user']['Contact'], 'Name', 'Home')]['email']; }
							echo "<a class='email' href='mailto:".$displayEmail."'>".$displayEmail."</a>";

							$displayTel = $session['response']['param']['user']['Contact'][get_id_from_multi_array($session['response']['param']['user']['Contact'], 'Name', 'Work')]['telMobile'];
							if ($displayTel == "") { $displayTel = $session['response']['param']['user']['Contact'][get_id_from_multi_array($session['response']['param']['user']['Contact'], 'Name', 'Work')]['telExtern']; }
							if ($displayTel == "") { $displayTel = $session['response']['param']['user']['Contact'][get_id_from_multi_array($session['response']['param']['user']['Contact'], 'Name', 'Home')]['telMobile']; }
							echo "<div class='tel'>".$displayTel."</div>";

							echo "<div class='twitter'>";
							if (isset($session['response']['param']['twitterAccount'])) {
								echo "<a href='".$XCOW_B['url']."/snippet/tweet-new-form?user=".$session['response']['param']['twitterAccount']."' class='modalflex tinyicon tinyicon-twitter userlink'>".$session['response']['param']['twitterAccount']."</a>";
							}
							else {
								echo "&nbsp;";
							}
							echo '</div>';

							echo "<div class='locality'>".$session['response']['param']['user']['Address'][get_id_from_multi_array($session['response']['param']['user']['Address'], 'Name', 'Work')]['city']."</div>";

							echo "</div>";

							echo "<div class='unit unit1-2 last'>";
							if (isset($session['response']['param']['user']['Message'])) {
								$timeString = timeDiff2($session['response']['param']['user']['MessageTimestamp']);
								echo "<p class='time'>".$timeString."</p>";
								echo "<p>".$session['response']['param']['user']['Message']."</p>";
							}
							else {
								echo "&nbsp;";
							}
							echo "</div>";

							echo "</div>";
			
							echo "</div>";
							?>
						</div>

						<div class="user-info">

							<table class="user-data">
								<?php
								$displayRole = $session['response']['param']['user']['Organization'][get_id_from_multi_array($session['response']['param']['user']['Organization'], 'Name', 'Current')]['role'];
								$displayIndustry = $session['response']['param']['user']['Organization'][get_id_from_multi_array($session['response']['param']['user']['Organization'], 'Name', 'Current')]['industry'];
								$displayCompany = $session['response']['param']['user']['Organization'][get_id_from_multi_array($session['response']['param']['user']['Organization'], 'Name', 'Current')]['company'];
								$displayDivision = $session['response']['param']['user']['Organization'][get_id_from_multi_array($session['response']['param']['user']['Organization'], 'Name', 'Current')]['division'];
								$displaySection = $session['response']['param']['user']['Organization'][get_id_from_multi_array($session['response']['param']['user']['Organization'], 'Name', 'Current')]['section'];
								$displayParttime = $session['response']['param']['user']['Organization'][get_id_from_multi_array($session['response']['param']['user']['Organization'], 'Name', 'Current')]['parttime'];
								$displayRoom = $session['response']['param']['user']['Organization'][get_id_from_multi_array($session['response']['param']['user']['Organization'], 'Name', 'Current')]['room'];
								$displayBuilding = $session['response']['param']['user']['Organization'][get_id_from_multi_array($session['response']['param']['user']['Organization'], 'Name', 'Current')]['building'];
								$displayCity = $session['response']['param']['user']['Address'][get_id_from_multi_array($session['response']['param']['user']['Address'], 'Name', 'Work')]['city'];
								?>
								<thead>
									<?php
									if($displayRole != '' || $displayIndustry != '' || $displayCompany != '' || $displayDivision != '' || $displaySection != '' || $displayParttime != '' || $displayRoom != '' || $displayBuilding != '' || $displayCity != '') {
										echo "<tr>";
										echo "<td></td>";
										echo "<th>".language('sciomio_text_user_profile_work')."</th>";
										echo "</tr>";
									}
									?>
								</thead>
								<tbody>
									<?php

									if($displayIndustry != '') {
										echo "<tr>";
										echo "<th>".language('sciomio_text_view_personal_industry')."</th>";
										echo "<td><a href='".$XCOW_B['url']."/search?p[industry]=".urlencode($displayIndustry)."'>".$displayIndustry."</a></td>";
										echo "</tr>";
									}

									if($displayCompany != '') {
										echo "<tr>";
										echo "<th>".language('sciomio_text_view_personal_company')."</th>";
										echo "<td><a href='".$XCOW_B['url']."/search?p[organization]=".urlencode($displayCompany)."'>".$displayCompany."</a></td>";
										echo "</tr>";
									}

									if($displayDivision != '') {
										echo "<tr>";
										echo "<th>".language('sciomio_text_view_personal_businessunit')."</th>";
										echo "<td><a href='".$XCOW_B['url']."/search?p[businessunit]=".urlencode($displayDivision)."'>".$displayDivision."</a></td>";
										echo "</tr>";
									}

									if($displaySection != '') {
										echo "<tr>";
										echo "<th>".language('sciomio_text_view_personal_section')."</th>";
										echo "<td><a href='".$XCOW_B['url']."/search?p[section]=".urlencode($displaySection)."'>".$displaySection."</a></td>";
										echo "</tr>";
									}

									if($displayRole != '') {
										echo "<tr>";
										echo "<th>".language('sciomio_text_view_personal_role')."</th>";
										echo "<td><a href='".$XCOW_B['url']."/search?p[role]=".urlencode($displayRole)."'>".$displayRole."</a></td>";
										echo "</tr>";
									}

									if($displayParttime != '') {
										echo "<tr>";
										echo "<th>".language('sciomio_text_view_personal_parttime')."</th>";
										echo "<td>".$displayParttime."</td>";
										echo "</tr>";
									}

									if($displayRoom != '') {
										echo "<tr>";
										echo "<th>".language('sciomio_text_view_personal_room')."</th>";
										echo "<td>".$displayRoom."</td>";
										echo "</tr>";
									}

									if($displayBuilding != '') {
										echo "<tr>";
										echo "<th>".language('sciomio_text_view_personal_building')."</th>";
										echo "<td>".$displayBuilding."</td>";
										echo "</tr>";
									}

									if($displayCity != '') {
										$countryCode = $session['response']['param']['user']['Address'][get_id_from_multi_array($session['response']['param']['user']['Address'], 'Name', 'Work')]['country'];
										$displayCountry = language("country_".$countryCode."_name");
										echo "<tr>";
										echo "<th>".language('sciomio_text_view_personal_workplace')."</th>";
										echo "<td><a href='".$XCOW_B['url']."/search?p[workplace]=".urlencode($displayCity.", ".$countryCode)."'>".$displayCity.", ".$displayCountry."</a></td>";
										echo "</tr>";
									}

									?>
									<tr><th></th><td></td></tr>
								</tbody>
							</table>
							<?php
							if($displayRole != '' || $displayIndustry != '' || $displayCompany != '' || $displayDivision != '' || $displaySection != '' || $displayParttime != '' || $displayRoom != '' || $displayBuilding != '' || $displayCity != '') {
								echo "<hr class='subtle' />";
							}
							?>
							<table class="user-data">
								<?php
								$displayEmail = $session['response']['param']['user']['Contact'][get_id_from_multi_array($session['response']['param']['user']['Contact'], 'Name', 'Work')]['email'];
								$displayTelExtern = $session['response']['param']['user']['Contact'][get_id_from_multi_array($session['response']['param']['user']['Contact'], 'Name', 'Work')]['telExtern'];
								$displayTelIntern = $session['response']['param']['user']['Contact'][get_id_from_multi_array($session['response']['param']['user']['Contact'], 'Name', 'Work')]['telIntern'];
								$displayTelMobile = $session['response']['param']['user']['Contact'][get_id_from_multi_array($session['response']['param']['user']['Contact'], 'Name', 'Work')]['telMobile'];
								$displayTelLync = $session['response']['param']['user']['Contact'][get_id_from_multi_array($session['response']['param']['user']['Contact'], 'Name', 'Work')]['telLync'];
								$displayTelPager = $session['response']['param']['user']['Contact'][get_id_from_multi_array($session['response']['param']['user']['Contact'], 'Name', 'Work')]['telPager'];
								$displayTelFax = $session['response']['param']['user']['Contact'][get_id_from_multi_array($session['response']['param']['user']['Contact'], 'Name', 'Work')]['telFax'];
								$displayPac = $session['response']['param']['user']['Contact'][get_id_from_multi_array($session['response']['param']['user']['Contact'], 'Name', 'Work')]['pac'];
								$displayMyId = $session['response']['param']['user']['Contact'][get_id_from_multi_array($session['response']['param']['user']['Contact'], 'Name', 'Work')]['myId'];
								$displayAssistentId = $session['response']['param']['user']['Contact'][get_id_from_multi_array($session['response']['param']['user']['Contact'], 'Name', 'Work')]['assistentId'];
								$displayManagerId = $session['response']['param']['user']['Contact'][get_id_from_multi_array($session['response']['param']['user']['Contact'], 'Name', 'Work')]['managerId'];
								?>
								<thead>
									<?php
									if($displayEmail != '' || $displayTelExtern != '' || $displayTelIntern != '' || $displayTelMobile != '' || $displayTelLync != '' || $displayTelPager != '' || $displayTelFax != '' || $displayPac != '' || $displayMyId != '' || $displayAssistentId != '' || $displayManagerId != '') {
										echo "<tr>";
										echo "<td></td>";
										echo "<th>".language('sciomio_text_user_profile_contact_work')."</th>";
										echo "</tr>";
									}
									?>
								</thead>
								<tbody>
									<?php

									if($displayEmail != '') {
										echo "<tr>";
										echo "<th>".language('sciomio_text_view_personal_email')."</th>";
										echo "<td><a href='mailto:".$displayEmail."'>".$displayEmail."</a></td>";
										echo "</tr>";
									}

									if($displayTelExtern != '') {
										echo "<tr>";
										echo "<th>".language('sciomio_text_view_personal_phone')."</th>";
										echo "<td>".$displayTelExtern."</td>";
										echo "</tr>";
									}

									if($displayTelIntern != '') {
										echo "<tr>";
										echo "<th>".language('sciomio_text_view_personal_phone_internal')."</th>";
										echo "<td>".$displayTelIntern."</td>";
										echo "</tr>";
									}

									if($displayTelMobile != '') {
										echo "<tr>";
										echo "<th>".language('sciomio_text_view_personal_mobile')."</th>";
										echo "<td>".$displayTelMobile."</td>";
										echo "</tr>";
									}

									if(! in_array("lync", $XCOW_B['sciomino']['personalia-exclude']) && $displayTelLync != '') {
										echo "<tr>";
										echo "<th>".language('sciomio_text_view_personal_lync')."</th>";
										echo "<td><a href='sip:".$displayTelLync."'>".$displayTelLync."</a></td>";
										echo "</tr>";
									}

									if(! in_array("pager", $XCOW_B['sciomino']['personalia-exclude']) && $displayTelPager != '') {
										echo "<tr>";
										echo "<th>".language('sciomio_text_view_personal_pager')."</th>";
										echo "<td>".$displayTelPager."</td>";
										echo "</tr>";
									}

									if(! in_array("fax", $XCOW_B['sciomino']['personalia-exclude']) && $displayTelFax != '') {
										echo "<tr>";
										echo "<th>".language('sciomio_text_view_personal_fax')."</th>";
										echo "<td>".$displayTelFax."</td>";
										echo "</tr>";
									}

									if(! in_array("pac", $XCOW_B['sciomino']['personalia-exclude']) && $displayPac != '') {
										echo "<tr>";
										echo "<th>".language('sciomio_text_view_personal_pac')."</th>";
										echo "<td>".$displayPac."</td>";
										echo "</tr>";
									}

									if(! in_array("myId", $XCOW_B['sciomino']['personalia-exclude']) && $displayMyId != '') {
										echo "<tr>";
										echo "<th>".language('sciomio_text_view_personal_myId')."</th>";
										echo "<td><a href='".$XCOW_B['url']."/view?name=".urlencode($displayMyId)."'>".$displayMyId."</a></td>";
										echo "</tr>";
									}

									if(! in_array("assistentId", $XCOW_B['sciomino']['personalia-exclude']) && $displayAssistentId != '') {
										echo "<tr>";
										echo "<th>".language('sciomio_text_view_personal_assistentId')."</th>";
										echo "<td><a href='".$XCOW_B['url']."/view?name=".urlencode($displayAssistentId)."'>".$session['response']['param']['assistent']['FirstName']." ".$session['response']['param']['assistent']['LastName']."</a></td>";
										echo "</tr>";
									}

									if(! in_array("managerId", $XCOW_B['sciomino']['personalia-exclude']) && $displayManagerId != '') {
										echo "<tr>";
										echo "<th>".language('sciomio_text_view_personal_managerId')."</th>";
										echo "<td><a href='".$XCOW_B['url']."/view?name=".urlencode($displayManagerId)."'>".$session['response']['param']['manager']['FirstName']." ".$session['response']['param']['manager']['LastName']."</a></td>";
										echo "</tr>";
										// manager list
										if ($session['response']['param']['managerList'] != "") {
											echo "<tr>";
											echo "<th>".language('sciomio_text_view_personal_managerList')."</th>";
											echo "<td><a href='".$XCOW_B['url']."/search?tl[manager]=".urlencode($session['response']['param']['managerList']['Name'])."'>".language('sciomio_text_view_personal_managerList_prefix').$session['response']['param']['managerList']['Name']."</a></td>";
											echo "</tr>";
										}
										// team list
										if ($session['response']['param']['teamList'] != "") {
											echo "<tr>";
											echo "<th>".language('sciomio_text_view_personal_teamList')."</th>";
											echo "<td><a href='".$XCOW_B['url']."/search?tl[manager]=".urlencode($session['response']['param']['teamList']['Name'])."'>".language('sciomio_text_view_personal_teamList_prefix').$session['response']['param']['teamList']['Name']."</a></td>";
											echo "</tr>";
										}
									}

									?>
									<tr><th></th><td></td></tr>
								</tbody>
							</table>
							<?php
							if($displayEmail != '' || $displayTelExtern != '' || $displayTelIntern != '' || $displayTelMobile != '' || $displayTelLync != '' || $displayTelPager != '' || $displayTelFax != '' || $displayPac != '' || $displayMyId != '' || $displayAssistentId != '' || $displayManagerId != '') {
								echo "<hr class='subtle' />";
							}
							?>
							<table class="user-data">
								<?php
								$displayEmail = $session['response']['param']['user']['Contact'][get_id_from_multi_array($session['response']['param']['user']['Contact'], 'Name', 'Home')]['email'];
								$displayTelHome = $session['response']['param']['user']['Contact'][get_id_from_multi_array($session['response']['param']['user']['Contact'], 'Name', 'Home')]['telHome'];
								$displayTelMobile = $session['response']['param']['user']['Contact'][get_id_from_multi_array($session['response']['param']['user']['Contact'], 'Name', 'Home')]['telMobile'];
								?>
								<thead>
									<?php
									if($displayEmail != '' || $displayTelHome != '' || $displayTelMobile != '') {
										echo "<tr>";
										echo "<td></td>";
										echo "<th>".language('sciomio_text_user_profile_contact_home')."</th>";
										echo "</tr>";
									}
									?>
								</thead>
								<tbody>
									<?php

									if($displayEmail != '') {
										echo "<tr>";
										echo "<th>".language('sciomio_text_view_personal_email')."</th>";
										echo "<td><a href='mailto:".$displayEmail."'>".$displayEmail."</a></td>";
										echo "</tr>";
									}

									if($displayTelHome != '') {
										echo "<tr>";
										echo "<th>".language('sciomio_text_view_personal_phone')."</th>";
										echo "<td>".$displayTelHome."</td>";
										echo "</tr>";
									}

									if($displayTelMobile != '') {
										echo "<tr>";
										echo "<th>".language('sciomio_text_view_personal_mobile')."</th>";
										echo "<td>".$displayTelMobile."</td>";
										echo "</tr>";
									}

									?>
									<tr><th></th><td></td></tr>
								</tbody>
							</table>
							<?php
							if($displayEmail != '' || $displayTelHome != '' || $displayTelMobile != '') {
								echo "<hr class='subtle' />";
							}
							?>
							<table class="user-data">
								<?php
								$displayCity = $session['response']['param']['user']['Address'][get_id_from_multi_array($session['response']['param']['user']['Address'], 'Name', 'Home')]['city'];
								?>
								<thead>
									<?php
									if($displayCity != '') {
										echo "<tr>";
										echo "<td></td>";
										echo "<th>".language('sciomio_text_user_profile_address_home')."</th>";
										echo "</tr>";
									}
									?>
								</thead>
								<tbody>
									<?php

									if($displayCity != '') {
										$countryCode = $session['response']['param']['user']['Address'][get_id_from_multi_array($session['response']['param']['user']['Address'], 'Name', 'Home')]['country'];
										$displayCountry = language("country_".$countryCode."_name");
										echo "<tr>";
										echo "<th>".language('sciomio_text_view_personal_hometown')."</th>";
										echo "<td><a href='".$XCOW_B['url']."/search?p[hometown]=".urlencode($displayCity.", ".$countryCode)."'>".$displayCity.", ".$displayCountry."</a></td>";
										echo "</tr>";
									}

									?>
								</tbody>
							</table>

						</div>
					</div>					
				</div>
				<div class="unit unit1-3">

					<div id="userSameWindow">
						<img src="<?php echo $XCOW_B['url'] ?>/gfx/ajax-loader-circle.gif">
					</div>

				</div>
			</div>
			<hr />
			<div class="group divide div3-2">
				<div class="unit unit2-3">
					
					<div class="section">
						<?php
						$languageTemplate = array();
						$languageTemplate['user'] = $session['response']['param']['user']['FirstName'];
						echo "<h2>".language_template('sciomio_header_view_knowledge', $languageTemplate)."</h2>";
								
						echo "<p>".$session['response']['param']['user']['description']."</p><br/>\n";
						echo "<h3>".language('sciomio_header_view_knowledge_sub');
						if ($session['response']['param']['me'] != 0) {
							#TODO: not so nice this styling, should be in css
							echo " <a class='tinybutton delete' style='font-size:0.8em;font-weight:normal;float:right;' href='".$XCOW_B['url']."/user/knowledge'>".language('sciomio_word_edit')."</a>";
						}
						echo "</h3>";

						echo "<ul class='expertise'>";
						if (is_array($session['response']['param']['user']['knowledgefield'])) {
							#TODO: should be nice utility: create a multisort class
							function sortKnowledge($x, $y) {
								if ( $x['field'] == $y['field'] ) { return 0; }
								else if ( $x['field'] < $y['field'] ) { return -1; }
								else { return 1; }
							}
							uasort($session['response']['param']['user']['knowledgefield'], "sortKnowledge");
							foreach ($session['response']['param']['user']['knowledgefield'] as $knowledge) {
								echo "<li>\n";

								echo "<a class='exp-label' href='".$XCOW_B['url']."/browse/knowledge?k=".urlencode($knowledge['field'])."'>".$knowledge['field']."</a>";
								$languageString = "sciomio_word_knowledgefield_".$knowledge['level'];
								echo "<span class='exp-level'>".language($languageString)."</span>";

								if ($session['response']['param']['me'] == 0) {
									echo "<div class='controls'>";
									echo "<div class='buttonbox'>";
									if (get_id_from_multi_array($session['response']['param']['meInfo']['knowledgefield'], 'field', $knowledge['field']) == 0) {
										echo "<a href='".$XCOW_B['url']."/snippet/knowledge-new-form-ikook?fill=".urlencode($knowledge['field'])."' class='tinybutton metoo'>".language('sciomio_word_metoo')."</a>";
									}
									else {
										#echo "<span class='you-label'>".language('sciomio_word_youtoo')."</span>";
									}
									echo "</div>";
									echo "</div>";
								}

								echo "</li>\n";
							}
						}
						echo "</ul>";
						?>

						<?php
						echo "<h3>".language('sciomio_header_view_hobby');
						if ($session['response']['param']['me'] != 0) {
							echo " <a class='tinybutton delete' style='font-size:0.8em;font-weight:normal;float:right;' href='".$XCOW_B['url']."/user/knowledge'>".language('sciomio_word_edit')."</a>";
						}
						echo "</h3>";

						echo "<ul class='expertise'>";
						if (is_array($session['response']['param']['user']['hobbyfield'])) {
							function sortHobby($x, $y) {
								if ( $x['field'] == $y['field'] ) { return 0; }
								else if ( $x['field'] < $y['field'] ) { return -1; }
								else { return 1; }
							}
							uasort($session['response']['param']['user']['hobbyfield'], "sortHobby");
							foreach ($session['response']['param']['user']['hobbyfield'] as $hobby) {
								echo "<li>\n";

								echo "<a class='exp-label' href='".$XCOW_B['url']."/browse/hobby?h=".urlencode($hobby['field'])."'>".$hobby['field']."</a>";

								if ($session['response']['param']['me'] == 0) {
									echo "<div class='controls'>";
									echo "<div class='buttonbox'>";
									if (get_id_from_multi_array($session['response']['param']['meInfo']['hobbyfield'], 'field', $hobby['field']) == 0) {
										echo "<a href='".$XCOW_B['url']."/snippet/hobby-new-form-ikook?fill=".urlencode($hobby['field'])."' class='tinybutton metoo'>".language('sciomio_word_metoo')."</a>";
									}
									else {
										#echo "<span class='you-label'>".language('sciomio_word_youtoo')."</span>";
									}
									echo "</div>";
									echo "</div>";
								}

								echo "</li>\n";
							}
						}
						echo "</ul>";
						?>

						<?php
						echo "<h3>".language('sciomio_header_view_tag');
						if ($session['response']['param']['me'] != 0) {
							echo " <a class='tinybutton delete' style='font-size:0.8em;font-weight:normal;float:right;' href='".$XCOW_B['url']."/user/tag'>".language('sciomio_word_edit')."</a>";
						}
						echo "</h3>";

						echo "<ul class='expertise'>";
						if (is_array($session['response']['param']['user']['tag'])) {
							function sortTags($x, $y) {
								if ( $x['name'] == $y['name'] ) { return 0; }
								else if ( $x['name'] < $y['name'] ) { return -1; }
								else { return 1; }
							}
							uasort($session['response']['param']['user']['tag'], "sortTags");
							foreach ($session['response']['param']['user']['tag'] as $tag) {
								echo "<li>\n";

								echo "<a class='exp-label' href='".$XCOW_B['url']."/browse/tag?t=".urlencode($tag['name'])."'>".$tag['name']."</a>";

								if ($session['response']['param']['me'] == 0) {
									echo "<div class='controls'>";
									echo "<div class='buttonbox'>";
									if (get_id_from_multi_array($session['response']['param']['meInfo']['tag'], 'name', $tag['name']) == 0) {
										echo "<a href='".$XCOW_B['url']."/snippet/tag-new-form-ikook?fill=".urlencode($tag['name'])."' class='tinybutton metoo'>".language('sciomio_word_metoo')."</a>";
									}
									else {
										#echo "<span class='you-label'>".language('sciomio_word_youtoo')."</span>";
									}
									echo "</div>";
									echo "</div>";
								}

								echo "</li>\n";
							}
						}
						echo "</ul>";
						?>

						<?php
						if ($XCOW_B['sciomino']['skin-network'] == "yes") {
							echo "<h3>".language('sciomio_header_view_networks');
							if ($session['response']['param']['me'] != 0) {
								echo " <a class='tinybutton delete' style='font-size:0.8em;font-weight:normal;float:right;' href='".$XCOW_B['url']."/setting/networks'>".language('sciomio_word_edit')."</a>";
							}
							echo "</h3>";

							echo "<ul class='expertise'>";
							$myNetworks = get_list_from_multi_array($session['response']['param']['user']['GroupMember'], 'Type', 'public');
							if (count($myNetworks) != 0) {
								foreach ($myNetworks as $myNet) {
									echo "<li>\n";
									echo "<a class='exp-label' href='".$XCOW_B['url']."/search?tl[public]=".urlencode($session['response']['param']['user']['GroupMember'][$myNet]['Name'])."'>".$session['response']['param']['user']['GroupMember'][$myNet]['Name']."</a>";
									echo "</li>\n";
								}
							}
							echo "</ul>";
						}
						?>

					</div>

				</div>
				<div class="unit unit1-3">

					<div id="publicationLinkedinListWindow">
						<img src="<?php echo $XCOW_B['url'] ?>/gfx/ajax-loader-circle.gif">
					</div>

				</div>
			</div>
			<hr />
			<div class="group divide div3-2">
				<div class="unit unit2-3">

					<div class="section">
						<h2 id="ervaringen"><?php echo language('sciomio_header_view_experience'); ?></h2>
						<div class="section">
							<?php
							echo "<h3>".language('sciomio_header_view_product');
							if ($session['response']['param']['me'] != 0) {
								echo " <a class='tinybutton delete' style='font-size:0.8em;font-weight:normal;float:right;' href='".$XCOW_B['url']."/user/experience'>".language('sciomio_word_edit')."</a>";
							}
							echo "</h3>";

							$oldSubject = "";
							foreach ($session['response']['param']['product'] as $experience) {
								if ($experience['subject'] != $oldSubject) {
									if ($oldSubject != "") {
										echo "</ul></div>";
									}
									$oldSubject = $experience['subject'];
									echo "<div class='section'>";
									echo "<h4><a href='".$XCOW_B['url']."/browse/experience?e[Product]=".urlencode($experience['subject'])."'>{$experience['subject']}</a></h4>";
									echo "<ul class='comps exp-list'>";
								}

								$verdict = "happy";
								if ($experience['like'] == 1) {$verdict = "happy-xl";}
								if ($experience['like'] == 2) {$verdict = "happy";}
								if ($experience['like'] == 3) {$verdict = "unhappy";}
								if ($experience['like'] == 4) {$verdict = "unhappy-xl";}

								echo "<li>\n";

								if ($session['response']['param']['me'] == 0) {
									$meProducts1 = get_list_from_multi_array($session['response']['param']['meInfo']['Experience'], 'subject', $experience['subject']);
									$meProducts2 = get_list_from_multi_array($session['response']['param']['meInfo']['Experience'], 'title', $experience['title']);
									if (count(array_intersect($meProducts1,$meProducts2)) == 0) {
										echo "<a href='".$XCOW_B['url']."/snippet/product-new-form-ikook?fillSubject=".urlencode($experience['subject'])."&fillTitle=".urlencode($experience['title'])."&fillAlternative=".urlencode($experience['alternative'])."' class='tinybutton metoo'>".language('sciomio_word_metoo')."</a>";
									}
									else {
										#echo "<span class='you-label'>".language('sciomio_word_youtoo')."</span>";
									}
								}	
								echo "<dl class='exp-item'>";
								echo "<dt><a class='verdict ".$verdict." exp-link' href='".$XCOW_B['url']."/browse/experience?e[Product]=".urlencode($experience['subject'])."&title=".urlencode($experience['title'])."&alternative=".urlencode($experience['alternative'])."'>".$experience['title']." - ".$experience['alternative']."</a></dt>";
								$languageString = "sciomio_word_has_".$experience['has'];
								echo "<dd>".language($languageString)."</dd>";
								echo "</dl>";

								#echo "<div class='img-item'>";
								#echo "<div class='img sub'>";
								#echo "<img src='/content/images/dummy-product-100x65.jpg' alt='auto A6' />";
								#echo "</div>";
								echo "<div class='review-item'>";
								echo "<table class='review'>";
								echo "<thead><tr><th style='width:50%'>".language('sciomio_text_view_pluspunten')."</th><th style='width:50%'>".language('sciomio_text_view_minpunten')."</th></tr></thead>";
								echo "<tbody><tr><td>";
								echo "<ul class='ftw'>";
								echo "<li>".$experience['positive1']."</li>";
								echo "<li>".$experience['positive2']."</li>";
								echo "<li>".$experience['positive3']."</li>";
								echo "</ul>";
								echo "</td><td>";
								echo "<ul class='fail'>";
								echo "<li>".$experience['negative1']."</li>";
								echo "<li>".$experience['negative2']."</li>";
								echo "<li>".$experience['negative3']."</li>";
								echo "</ul>";
								echo "</td></tr></tbody>";
								echo "</table>";
								echo "</div>";
								#echo "</div>";

								echo "</li>";
							}
							if (count($session['response']['param']['product']) != 0) {
								echo "</ul></div>";
							}
							?>
						</div>
	
						<div class="section">
							<?php
							echo "<h3>".language('sciomio_header_view_company');
							if ($session['response']['param']['me'] != 0) {
								echo " <a class='tinybutton delete' style='font-size:0.8em;font-weight:normal;float:right;' href='".$XCOW_B['url']."/user/experience'>".language('sciomio_word_edit')."</a>";
							}
							echo "</h3>";

							$oldSubject = "";
							foreach ($session['response']['param']['company'] as $experience) {
								if ($experience['subject'] != $oldSubject) {
									if ($oldSubject != "") {
										echo "</ul></div>";
									}
									$oldSubject = $experience['subject'];
									echo "<div class='section'>";
									echo "<h4><a href='".$XCOW_B['url']."/browse/experience?e[Company]=".urlencode($experience['subject'])."'>{$experience['subject']}</a></h4>";
									echo "<ul class='comps exp-list'>";
								}

								$verdict = "happy";
								if ($experience['like'] == 1) {$verdict = "happy-xl";}
								if ($experience['like'] == 2) {$verdict = "happy";}
								if ($experience['like'] == 3) {$verdict = "unhappy";}
								if ($experience['like'] == 4) {$verdict = "unhappy-xl";}

								echo "<li>\n";
								if ($session['response']['param']['me'] == 0) {
									$meCompany1 = get_list_from_multi_array($session['response']['param']['meInfo']['Experience'], 'subject', $experience['subject']);
									$meCompany2 = get_list_from_multi_array($session['response']['param']['meInfo']['Experience'], 'title', $experience['title']);
									if (count(array_intersect($meCompany1,$meCompany2)) == 0) {
										echo "<a href='".$XCOW_B['url']."/snippet/company-new-form-ikook?fillSubject=".urlencode($experience['subject'])."&fillTitle=".urlencode($experience['title'])."' class='tinybutton metoo'>".language('sciomio_word_metoo')."</a>";
									}
									else {
										#echo "<span class='you-label'>".language('sciomio_word_youtoo')."</span>";
									}
								}	

								$displayDate = "";
								if ($experience['date']) {
									$displayDate = "(".$experience['date'].")";
								}
								echo "<a class='verdict ".$verdict." exp-link' href='".$XCOW_B['url']."/browse/experience?e[Company]=".urlencode($experience['subject'])."&title=".urlencode($experience['title'])."'>".$experience['title']."</a> ".$displayDate;

								echo "<p>".$experience['description']."</p>";
								echo "</li>";
							}
							if (count($session['response']['param']['company']) != 0) {
								echo "</ul></div>";
							}
							?>
						</div>
	
						<div class="section">
							<?php
							echo "<h3>".language('sciomio_header_view_event');
							if ($session['response']['param']['me'] != 0) {
								echo " <a class='tinybutton delete' style='font-size:0.8em;font-weight:normal;float:right;' href='".$XCOW_B['url']."/user/experience'>".language('sciomio_word_edit')."</a>";
							}
							echo "</h3>";

							$oldSubject = "";
							foreach ($session['response']['param']['event'] as $experience) {
								if ($experience['subject'] != $oldSubject) {
									if ($oldSubject != "") {
										echo "</ul></div>";
									}
									$oldSubject = $experience['subject'];
									echo "<div class='section'>";
									echo "<h4><a href='".$XCOW_B['url']."/browse/experience?e[Event]=".urlencode($experience['subject'])."'>{$experience['subject']}</a></h4>";
									echo "<ul class='comps exp-list'>";
								}

								$verdict = "happy";
								if ($experience['like'] == 1) {$verdict = "happy-xl";}
								if ($experience['like'] == 2) {$verdict = "happy";}
								if ($experience['like'] == 3) {$verdict = "unhappy";}
								if ($experience['like'] == 4) {$verdict = "unhappy-xl";}

								echo "<li>\n";
								if ($session['response']['param']['me'] == 0) {
									$meEvent1 = get_list_from_multi_array($session['response']['param']['meInfo']['Experience'], 'subject', $experience['subject']);
									$meEvent2 = get_list_from_multi_array($session['response']['param']['meInfo']['Experience'], 'title', $experience['title']);
									if (count(array_intersect($meEvent1,$meEvent2)) == 0) {
										echo "<a href='".$XCOW_B['url']."/snippet/event-new-form-ikook?fillSubject=".urlencode($experience['subject'])."&fillTitle=".urlencode($experience['title'])."&fillPublisher=".urlencode($experience['publisher'])."' class='tinybutton metoo'>".language('sciomio_word_metoo')."</a>";									
									}
									else {
										#echo "<span class='you-label'>".language('sciomio_word_youtoo')."</span>";
									}
								}
								$displayDate = "";
								if ($experience['date']) {
									$displayDate = "(".$experience['date'].")";
								}
								echo "<a class='verdict ".$verdict." exp-link' href='".$XCOW_B['url']."/browse/experience?e[Event]=".urlencode($experience['subject'])."&title=".urlencode($experience['title'])."'>".$experience['title']."</a> ".$displayDate;

								echo "<p>".$experience['description']."</p>";
								if ($experience['relation-self'] != "") {
									echo "<p><a href='".$experience['relation-self']."'>-&gt; website</a></p>";
								}
								echo "</li>";
							}
							if (count($session['response']['param']['event']) != 0) {
								echo "</ul></div>";
							}
							?>
						</div>

						<div class="section">
							<?php
							echo "<h3>".language('sciomio_header_view_education');
							if ($session['response']['param']['me'] != 0) {
								echo " <a class='tinybutton delete' style='font-size:0.8em;font-weight:normal;float:right;' href='".$XCOW_B['url']."/user/experience'>".language('sciomio_word_edit')."</a>";
							}
							echo "</h3>";

							$oldSubject = "";
							foreach ($session['response']['param']['education'] as $experience) {
								if ($experience['subject'] != $oldSubject) {
									if ($oldSubject != "") {
										echo "</ul></div>";
									}
									$oldSubject = $experience['subject'];
									echo "<div class='section'>";
									echo "<h4><a href='".$XCOW_B['url']."/browse/experience?e[Education]=".urlencode($experience['subject'])."'>{$experience['subject']}</a></h4>";
									echo "<ul class='comps exp-list'>";
								}

								$verdict = "happy";
								if ($experience['like'] == 1) {$verdict = "happy-xl";}
								if ($experience['like'] == 2) {$verdict = "happy";}
								if ($experience['like'] == 3) {$verdict = "unhappy";}
								if ($experience['like'] == 4) {$verdict = "unhappy-xl";}

								echo "<li>\n";
								if ($session['response']['param']['me'] == 0) {
									$meEducation1 = get_list_from_multi_array($session['response']['param']['meInfo']['Experience'], 'subject', $experience['subject']);
									$meEducation2 = get_list_from_multi_array($session['response']['param']['meInfo']['Experience'], 'title', $experience['title']);
									if (count(array_intersect($meEducation1,$meEducation2)) == 0) {
										echo "<a href='".$XCOW_B['url']."/snippet/education-new-form-ikook?fillSubject=".urlencode($experience['subject'])."&fillTitle=".urlencode($experience['title'])."&fillPublisher=".urlencode($experience['publisher'])."' class='tinybutton metoo'>".language('sciomio_word_metoo')."</a>";
									}
									else {
										#echo "<span class='you-label'>".language('sciomio_word_youtoo')."</span>";
									}
								}
								$displayDate = "";
								if ($experience['date']) {
									$displayDate = "(".$experience['date'].")";
								}
								echo "<a class='verdict ".$verdict." exp-link' href='".$XCOW_B['url']."/browse/experience?e[Education]=".urlencode($experience['subject'])."&title=".urlencode($experience['title'])."'>".$experience['title']."</a> ".$displayDate;

								echo "<p>".$experience['description']."</p>";
								if ($experience['relation-self'] != "") {
									echo "<p><a href='".$experience['relation-self']."'>-&gt; website</a></p>";
								}
								echo "</li>";
							}
							if (count($session['response']['param']['education']) != 0) {
								echo "</ul></div>";
							}
							?>
						</div>

						<?php 
						if (count($session['response']['param']['product']) == $session['response']['param']['limit'] || count($session['response']['param']['company']) == $session['response']['param']['limit'] || count($session['response']['param']['event']) == $session['response']['param']['limit'] || count($session['response']['param']['education']) == $session['response']['param']['limit']) {
							echo "<a class='more' href='".$XCOW_B['url']."/view?limit=".$session['response']['param']['newLimit']."&user=".$session['response']['param']['view']."#ervaringen'>".language('sciomio_word_more')."</a>";
						}
						?>
					</div>					
					
				</div>
				<div class="unit unit1-3">
					<div class="section">
						<h2><?php echo language('sciomio_header_view_publication'); ?></h2>

						<div id="publicationTwitterListWindow">
							<img src="<?php echo $XCOW_B['url'] ?>/gfx/ajax-loader-circle.gif">
						</div>

						<div id="publicationLinkListWindow">
							<img src="<?php echo $XCOW_B['url'] ?>/gfx/ajax-loader-circle.gif">
						</div>
					</div>
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
	<?php include 'includes/scripts-linkedin.php'; ?>
	<?php include 'includes/scripts-twitterfeed.php'; ?>
	<script type="text/javascript">
		addLoadEvent(function() {Session.View.load();});
		addLoadEvent(function() {ScioMino.User.same("<?php echo $session['response']['param']['view']?>");});
		addLoadEvent(function() {ScioMino.Connect.loadLinkedin("<?php echo $session['response']['param']['view']?>");});
		addLoadEvent(function() {ScioMino.Connect.loadTwitterUser("<?php echo $session['response']['param']['view']?>");showTwitterFeed("user",<?php echo $session['response']['param']['view']?>, 5);});
		addLoadEvent(function() {ScioMino.Connect.loadLink("<?php echo $session['response']['param']['view']?>");});
	</script>

</body>
</html>
