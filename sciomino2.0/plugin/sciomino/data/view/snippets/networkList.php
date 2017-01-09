	<?php
	foreach ($session['response']['param']['networkList'] as $list) {
		echo "<div>";

		echo "<div style='float:left;width:100px;'>";
		echo "<img height='96px' src=".$XCOW_B['url']."'/upload/networks/".strtolower($list['Name']).".png'>";
		echo "</div>";
		
		echo "<div style='float:left;width:390px; padding-top:10px; padding-left:10px; padding-right:10px;'>";
		echo "<a href='".$XCOW_B['url']."/search?tl[public]=".urlencode($list['Name'])."'>".$list['Name']."</a>";
		echo "<br/><p style='padding-top:4px;'>".$list['Description']."</p>";
		echo "</div>";
		
		echo "<div style='float:left;width:50px; padding-top:35px; padding-left:10px'>";
		echo "<form>";
		echo "<input style='-ms-transform: scale(2,2);-moz-transform: scale(2,2);-webkit-transform: scale(2,2);-o-transform: scale(2,2);' onClick='ScioMino.List.check(".$session['response']['param']['user'].",".$list['Id'].",event)' type='checkbox' class='checkbox' id='".$list['Name']."' name='".$list['Name']."' ".$list['Checked'].">";
		echo "</form>";
		echo "</div>";
		
		echo "<br clear='left'><hr style='margin-bottom:10px;'/>";
		echo "</div>";
	}
	?>
