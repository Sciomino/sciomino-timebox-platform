<?php

echo "<div class='section puu-details'>";
echo "<section>";
echo "<h2>".language('sciomio_header_act_review')."</h2>";
echo "<div class='puu-content'>";

# reacties
if (count($session['response']['param']['reviewList']) > 0) {

	foreach ($session['response']['param']['reviewList'] as $reviewKey => $reviewVal) {

		// who is this act from?
		$userRefByReviewRef = get_id_from_multi_array($session['response']['param']['userList'], 'Reference', $reviewVal['Reference']);
		$userVal = $session['response']['param']['userList'][$userRefByReviewRef];

		// comment
		if ($reviewVal['Reference'] != $session['response']['param']['userRef']) {
			echo "\n<div><br/>";
			
			if (! isset($userVal['photo'])) { $userVal['photo'] = "/ui/gfx/photo.jpg"; }
			else { $userVal['photo'] = str_replace("/upload/","/upload/32x32_", $userVal['photo']); }

			echo "<a class='puu-mug photo' href='".$XCOW_B['url']."/view?user=".$userVal['Id']."'><img alt='' src='".$XCOW_B['url'].$userVal['photo']."' width='32' height='32' style='float:left; padding-right:5px; padding-top:3px'></a>\n";

			echo "<p class='puu-header'><a class='fn' href='".$XCOW_B['url']."/view?user=".$userVal['Id']."'>".$userVal['FirstName']." ".$userVal['LastName']."</a></p>\n";

			echo "<br clear=left/></div>";
		}
	}
}

echo "</div>";
echo "</section>";
echo "</div>";

?>


