<?php
if (count($session['response']['param']['activityList']) == 0) {
	echo "<p><br/>".language('sciomio_text_activity_home_geen')."</p>";
}
echo "<ul class='expert-needed'>";
foreach ($session['response']['param']['activityList'] as $activity) {
	echo "<li style='padding-left:0px;'>";
	#echo "<li class='img-item'>";
	#echo "<a href='/view?user={$activity['UserId']}' class='img'>";
	#if (! isset($activity['User']['photo'])) { $activity['User']['photo'] = "/ui/gfx/photo.jpg"; }
	#else { $activity['User']['photo'] = str_replace("/upload/","/upload/32x32_",$activity['User']['photo']); }
	#echo "<img src='".$activity['User']['photo']."' width='32' height='32' alt='".$activity['User']['firstName']."' />";
	#echo "</a>";
	echo "<div class='bd'>";

	if ($activity['UserId'] == $session['response']['param']['meUser']) {
		#echo "<a style='float:right;' class='tinybutton delete' href='javascript:ScioMino.ActivityDelete.action(".$activity['Id'].");'>".language('sciomio_word_delete')."</a>";
		echo "<a style='float:right;' href='javascript:ScioMino.ActivityDelete.action(".$activity['Id'].");'><img src='".$XCOW_B['url']."/ui/gfx/icon_delete.gif' border='0' /></a>";
	}
	else {
		#echo "<a href='/snippet/knowledge-new-form-ikook?fill=".urlencode($activity['Description'])."' class='tinybutton metoo'>".language('sciomio_word_ihave')."</a>";

		echo "<a href='".$XCOW_B['url']."/snippet/help-new-form?activity=".$activity['Id']."&knowledge=".urlencode($activity['Description'])."&user=".$activity['UserId']."' class='tinybutton metoo' rel='/snippet/activity-list'>".language('sciomio_word_icanhelp')."</a>";
	}

	#echo "<a class='exp-label' href='/browse/knowledge?k=".urlencode($activity['Description'])."'>{$activity['Description']}</a>";
	echo "<span class='exp-label'>{$activity['Description']}</span>";
	echo "<p>";
	echo language('sciomio_text_activity_search')."<a href='".$XCOW_B['url']."/view?user={$activity['UserId']}'>{$activity['User']['firstName']} {$activity['User']['lastName']}</a>";
	echo "<span class='count'> ".timeDiff2($activity['Timestamp'])."</span>";
	echo "</p>";
	echo "</div>";
	echo "</li>";
}
echo "</ul>";

if ($session['response']['param']['thereIsMore']) {
	echo "<a class='more' href='javascript:ScioMino.ActivityList.load(".$session['response']['param']['newLimit'].")'>".language('sciomio_word_more')."</a>";
}

?>


