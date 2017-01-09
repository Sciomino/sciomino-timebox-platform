<?php
foreach ($session['response']['param']['userList'] as $userKey => $userVal) {
	echo "<li>";
	echo "<div class='img-item softbox'>";
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

	$level = 1;
	foreach ($userVal['knowledgefield'] as $knowledge) {
		if (strcasecmp($knowledge['field'], $session['response']['param']['knowledgeField']) == 0) {
			$level = $knowledge['level'];
			break;
		}
	}
	$languageString = "sciomio_word_knowledgefield_".$level;				
	echo "<p><a href='".$XCOW_B['url']."/browse/knowledge?k=".urlencode($session['response']['param']['knowledgeField'])."&level=".$level."'>".language($languageString)."</a></p>";
	echo "</div>";
	echo "</div>";
	echo "</li>\n";
}
# meer...
if ($session['response']['param']['thereIsMore']) {
	echo "<a id='MoreButton' class='button buttonbold' href='".$XCOW_B['url']."/snippet/knowledge-more?k=".urlencode($session['response']['param']['knowledgeField'])."&offset=".$session['response']['param']['searchOffset']."&searchString=".$session['response']['param']['searchString']."'>".language('sciomio_word_moreResults')."</a>";
}

?>

