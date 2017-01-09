<?php

if (count($session['response']['param']['userList']) > 0) {
	echo "<table class='puu-board'>";
	echo "<thead>";
	echo "<tr>";
	echo "<th>Naam</th>";
	if ($session['response']['param']['list'] == "twitter") {
		echo "<th>Account</th>";
		echo "<th>Tweets</th>";
		echo "<th>Following</th>";
		echo "<th>Followers</th>";
	}
	if ($session['response']['param']['list'] == "linkedin") {
		echo "<th>Account</th>";
	}
	if ($session['response']['param']['list'] == "blog") {
		echo "<th>Blog</th>";
	}
	if ($session['response']['param']['list'] == "presentation") {
		echo "<th>Account</th>";
	}
	if ($session['response']['param']['list'] == "website") {
		echo "<th>Website</th>";
	}
	if ($session['response']['param']['list'] == "publication") {
		echo "<th>Titel</th>";
	}
	echo "</tr>";
	echo "</thead>";

	foreach ($session['response']['param']['userList'] as $userKey => $userVal) {

		if (! isset($userVal['photo'])) { $userVal['photo'] = "/ui/gfx/photo.jpg"; }
		else { $userVal['photo'] = str_replace("/upload/","/upload/48x48_",$userVal['photo']); }

		echo "<tbody>";
		echo "<tr>";
		$displayOrganization = $userVal['Organization'][get_id_from_multi_array($userVal['Organization'], 'Name', 'Current')]['division'];
		if ($displayOrganization == "") { $displayOrganization = $userVal['Organization'][get_id_from_multi_array($userVal['Organization'], 'Name', 'Current')]['company']; }
		echo "<td><a class='puu-face vcard' href='".$XCOW_B['url']."/view?user=".$userVal['Id']."'><img class='puu-mug photo' alt='' src='".$XCOW_B['url'].$userVal['photo']."' width='48' height='48'> <span class='puu-cap'><span class='fn'>".$userVal['FirstName']." ".$userVal['LastName']."</span> <span class='role'>".$userVal['Organization'][get_id_from_multi_array($userVal['Organization'], 'Name', 'Current')]['role']." - ".$displayOrganization."</span></span></a></td>";

		if ($session['response']['param']['list'] == "twitter") {
			$SocialNetworkList = get_list_from_multi_array($userVal['Publication'], 'Name', 'SocialNetwork');
			echo "<td>";
			foreach ($SocialNetworkList as $SocialNetworkId) {
				if ($userVal['Publication'][$SocialNetworkId]['title'] == "twitter") {
					$twitterAccount = $userVal['Publication'][$SocialNetworkId]['relation-self'];
					echo "<a target='_blank' href='https://twitter.com/#!/".$twitterAccount."'>".$twitterAccount."</a><br/>";
				}
			}
			echo "</td>";
			echo "<td>";
			foreach ($SocialNetworkList as $SocialNetworkId) {
				if ($userVal['Publication'][$SocialNetworkId]['title'] == "twitter") {
					echo $userVal['Publication'][$SocialNetworkId]['count-statuses'];
				}
			}
			echo "</td>";
			echo "<td>";
			foreach ($SocialNetworkList as $SocialNetworkId) {
				if ($userVal['Publication'][$SocialNetworkId]['title'] == "twitter") {
					echo $userVal['Publication'][$SocialNetworkId]['count-friends'];
				}
			}
			echo "</td>";
			echo "<td>";
			foreach ($SocialNetworkList as $SocialNetworkId) {
				if ($userVal['Publication'][$SocialNetworkId]['title'] == "twitter") {
					echo $userVal['Publication'][$SocialNetworkId]['count-followers'];
				}
			}
			echo "</td>";
		}
		if ($session['response']['param']['list'] == "linkedin") {
			$SocialNetworkList = get_list_from_multi_array($userVal['Publication'], 'Name', 'SocialNetwork');
			echo "<td>";
			foreach ($SocialNetworkList as $SocialNetworkId) {
				if ($userVal['Publication'][$SocialNetworkId]['title'] == "linkedin") {
					$linkedinUrl = $userVal['Publication'][$SocialNetworkId]['relation-self'];
					if ($linkedinUrl != '') {
						echo "<script type='IN/MemberProfile' data-id='".$linkedinUrl."' data-format='click' data-text='".$userVal['FirstName']."' data-related='false' data-width='300'></script>";
					}
					
				}
			}
			echo "</td>";
		}
		if ($session['response']['param']['list'] == "blog") {
			$blogUrlList = get_list_from_multi_array($userVal['Publication'], 'Name', 'Blog');
			echo "<td>";
			foreach ($blogUrlList as $blogUrlId) {
				$blogUrl = $userVal['Publication'][$blogUrlId]['relation-other'];
				echo "<a target='_blank' href='".$blogUrl."'>".$blogUrl."</a><br/>";
			}
			echo "</td>";
		}
		if ($session['response']['param']['list'] == "presentation") {
			$slideshareList = get_list_from_multi_array($userVal['Publication'], 'Name', 'Share');
			echo "<td>";
			foreach ($slideshareList as $slideshareId) {
				$slideshareAccount = $userVal['Publication'][$slideshareId]['relation-self'];
				echo "<a target='_blank' href='http://www.slideshare.net/".$slideshareAccount."'>".$slideshareAccount."</a><br/>";
			}
			echo "</td>";
		}
		if ($session['response']['param']['list'] == "website") {
			$websiteList = get_list_from_multi_array($userVal['Publication'], 'Name', 'Website');
			echo "<td>";
			foreach ($websiteList as $websiteId) {
				$website = $userVal['Publication'][$websiteId]['relation-self'];
				echo "<a target='_blank' href='".$website."'>".$website."</a><br/>";
			}
			echo "</td>";
		}
		if ($session['response']['param']['list'] == "publication") {
			$publicationList = get_list_from_multi_array($userVal['Publication'], 'Name', 'Other');
			echo "<td>";
			foreach ($publicationList as $publicationId) {
				$pubTitle = $userVal['Publication'][$publicationId]['title'];
				$pubUrl = $userVal['Publication'][$publicationId]['relation-self'];
				echo "<a target='_blank' href='".$pubUrl."'>".$pubTitle."</a><br/>";
			}
			echo "</td>";
		}
		echo "</tr>";
		echo "</tbody>";

	}

	echo "</table>";

}
else {
	echo "<table class='puu-board'>";
	echo "<thead>";
	echo "<tr>";
	echo "<td>Geen resultaten gevonden</td>";
	echo "</tr>";
	echo "</thead>";
	echo "</table>";

}

if ($session['response']['param']['thereIsMore']) {
	echo "<a class='more' href='javascript:ScioMino.InsightsList.loadAlphabet(\"".$session['response']['param']['list']."\",\"".$session['response']['param']['start']."\",".$session['response']['param']['newLimit'].")'>".language('sciomio_word_more')."</a>";
}

?>


