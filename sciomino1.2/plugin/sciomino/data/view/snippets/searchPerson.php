<?php
echo "<table>\n";
foreach ($session['response']['param']['userList'] as $userKey => $userVal) {
	if (! isset($userVal['photo'])) { $userVal['photo'] = "/ui/gfx/photo.jpg"; }
	else { $userVal['photo'] = str_replace("/upload/","/upload/32x32_",$userVal['photo']); }
	echo "<tr><td><img height='32' width='32' src='".$XCOW_B['url'].$userVal['photo']."'></td>\n";
	echo "<td><a href='".$XCOW_B['url']."/view?user=".$userVal['Id']."'><b>".$userVal['FirstName']." ".$userVal['LastName']."</b></a></td></tr>\n";
}
echo "</table>\n";
?>

