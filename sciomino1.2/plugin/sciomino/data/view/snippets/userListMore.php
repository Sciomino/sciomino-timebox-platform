<?php
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

?>

