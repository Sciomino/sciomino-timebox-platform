<?php
foreach ($session['response']['param']['activityList'] as $activity) {
	if ($activity['Description'] != "") {
	echo "<li class='img-item' style='margin-left:0px;'>";
	
		echo "<a href='/view?user={$activity['UserId']}' class='img'>";
		if (! isset($activity['User']['photo'])) { $activity['User']['photo'] = "/ui/gfx/photo.jpg"; }
		else { $activity['User']['photo'] = str_replace("/upload/","/upload/48x48_",$activity['User']['photo']); }
		echo "<img src='".$activity['User']['photo']."' width='48' height='48' alt='".$activity['User']['firstName']."' />";
		echo "</a>";
		
		echo "<div class='bd'>";
			# default 'motd'
			$activityText = language('sciomio_text_activity_motd').$activity['Description'];
			if ($activity['Title'] == "knowledge") {
				$activityText = language('sciomio_text_activity_knowledge')." <a href='/browse/knowledge?k=".urlencode($activity['Description'])."'>{$activity['Description']}</a>";
			}
			if ($activity['Title'] == "save_user") {
				$activityText = language('sciomio_text_activity_save_user');
			}
			if ($activity['Title'] == "save_user_profile_knowledgefield") {
				$activityText = language('sciomio_text_activity_save_knowledge')." <a href='/browse/knowledge?k=".urlencode($activity['Description'])."'>{$activity['Description']}</a> ";
			}
			if ($activity['Title'] == "save_user_profile_hobbyfield") {
				$activityText = language('sciomio_text_activity_save_hobby')." <a href='/browse/hobby?h=".urlencode($activity['Description'])."'>{$activity['Description']}</a> ";
			}
			if ($activity['Title'] == "save_user_profile_tag") {
				$activityText = language('sciomio_text_activity_save_tag')." <a href='/browse/tag?t=".urlencode($activity['Description'])."'>{$activity['Description']}</a> ";
			}
			
			echo "<span><a href='".$XCOW_B['url']."/view?user={$activity['UserId']}'>{$activity['User']['firstName']} {$activity['User']['lastName']}</a><span class='count'> - ".timeDiff2($activity['Timestamp'])."</span></span>";

			echo "<p>";
			echo "<span>".$activityText."</span>";
			echo "</p>";
		echo "</div>";
		
	echo "</li>";
	}
}

/*
if ($session['response']['param']['thereIsMore']) {
	echo "<a class='more' href='javascript:ScioMino.ActivityList.loadAll(".$session['response']['param']['newLimit'].")'>".language('sciomio_word_more')."</a>";
}
*/

?>


