<ul class="puu-nee">
	<?php
	if (count($session['response']['param']['userList']) > 0) {
		foreach ($session['response']['param']['userList'] as $userKey => $userVal) {
			if (! isset($userVal['photo'])) { $userVal['photo'] = "/ui/gfx/photo.jpg"; }
			else { $userVal['photo'] = str_replace("/upload/","/upload/48x48_",$userVal['photo']); }

			$displayOrganization = $userVal['Organization'][get_id_from_multi_array($userVal['Organization'], 'Name', 'Current')]['division'];
			if ($displayOrganization == "") { $displayOrganization = $userVal['Organization'][get_id_from_multi_array($userVal['Organization'], 'Name', 'Current')]['company']; }
			echo "<li><a class='puu-face vcard' href='".$XCOW_B['url']."/view?user=".$userVal['Id']."'><img class='puu-mug photo' alt='' src='".$XCOW_B['url'].$userVal['photo']."' width='48' height='48'> <span class='puu-cap'><span class='fn'>".$userVal['FirstName']." ".$userVal['LastName']."</span> <span class='role'>".$userVal['Organization'][get_id_from_multi_array($userVal['Organization'], 'Name', 'Current')]['role']." - ".$displayOrganization."</span></span></a></li>";
		}
	}
	else {
		echo "<li class='puu-nobody'>".language('sciomio_text_insights_no_birthday')."</li>";
	}
	?>
</ul>

