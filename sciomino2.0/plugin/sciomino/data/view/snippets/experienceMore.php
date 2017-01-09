<?php
if ($session['response']['param']['type'] != 'Product') {
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
		echo "<p class='from-user'>".$session['response']['param']['UserExperienceInfo'][$userVal['Id']]['description']."</p>";

		echo "</li>";
	}
	# meer...
	if ($session['response']['param']['thereIsMore']) {
		echo "<a id='MoreButton' class='button buttonbold' href='".$XCOW_B['url']."/snippet/experience-more?e[".$session['response']['param']['type']."]=".urlencode($session['response']['param']['experience'])."&title=".urlencode($session['response']['param']['experienceTitle'])."&offset=".$session['response']['param']['searchOffset']."&searchString=".$session['response']['param']['searchString']."'>".language('sciomio_word_moreResults')."</a>";
	}
}
else {
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

		echo "<table class='review-item from-user'>";
		echo "<thead><tr><th>".language('sciomio_text_view_pluspunten')."</th><th>".language('sciomio_text_view_minpunten')."</th></tr></thead>";
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
}

