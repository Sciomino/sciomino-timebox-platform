	<?php
	foreach ($session['response']['param']['notificationList'] as $list) {
		echo "<div>";

		#echo "<div style='float:left;width:100px;'>";
		#echo "<img height='96px' src=".$XCOW_B['url']."'/upload/networks/".strtolower($list['Name']).".png'>";
		#echo "</div>";

		echo "<div style='float:left;width:50px; padding-top:35px; padding-left:10px'>";
		echo "<form>";
		echo "<input style='-ms-transform: scale(2,2);-moz-transform: scale(2,2);-webkit-transform: scale(2,2);-o-transform: scale(2,2);' onClick='ScioMino.Setting.check(".$session['response']['param']['user'].",".$list['Id'].",\"".$list['Name']."\",event)' type='checkbox' class='checkbox' id='".$list['Name']."' name='".$list['Name']."' ".$list['Checked'].">";
		echo "</form>";
		echo "</div>";
		
		echo "<div style='float:left;width:490px; padding-top:10px; padding-left:10px; padding-right:10px;'>";
		echo "<b>".language("sciomio_text_setting_notification_".$list['Name']."_name")."</b>";
		echo "<br/><p style='padding-top:4px;'>".language("sciomio_text_setting_notification_".$list['Name']."_description")."</p>";
		echo "</div>";
			
		echo "<br clear='left'><hr style='margin-bottom:10px;'/>";
		echo "</div>";
	}
	?>
