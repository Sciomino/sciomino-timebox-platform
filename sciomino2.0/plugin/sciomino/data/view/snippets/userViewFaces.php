<div style="margin:0px 15px;">
<table>
<?php
	if (count($session['response']['param']['birthDayList']) > 0) {
		echo "<tr><td>".language('sciomio_text_user_faces_birthday')."</td><td>"; 

		foreach ($session['response']['param']['birthDayList'] as $birthDay) {

			echo "<div style='float:left; padding: 2px; text-align:center'>";
			if (! isset($birthDay['photo'])) { $birthDay['photo'] = "/ui/gfx/photo.jpg"; }
			else { $birthDay['photo'] = str_replace("/upload/","/upload/48x48_",$birthDay['photo']); }
			echo "<a href='".$XCOW_B['url']."/view?user=".$birthDay['Id']."'><img src='".$XCOW_B['url'].$birthDay['photo']."' width='48' height='48' alt='' /></a> ";
			echo "<br/>".$birthDay['dateofbirthday']."<br/>".language('sciomio_word_month_short_'.$birthDay['dateofbirthmonth'])."</div>";

		}
		echo "</td></tr>";
	}
?>
<?php
	if (count($session['response']['param']['newList']) > 0) {
		echo "<tr><td>".language('sciomio_text_user_faces_new')."</td><td>"; 

		foreach ($session['response']['param']['newList'] as $new) {

			echo "<div style='float:left; padding: 2px; text-align:center' onmouseover='javascript:document.getElementById(\"NewFacesName\").innerHTML=\"".$new['FirstName']." ".$new['LastName']."\"' onmouseout='javascript:document.getElementById(\"NewFacesName\").innerHTML=\"&nbsp;\"'>";
			if (! isset($new['photo'])) { $new['photo'] = "/ui/gfx/photo.jpg"; }
			else { $new['photo'] = str_replace("/upload/","/upload/48x48_",$new['photo']); }
			echo "<a href='".$XCOW_B['url']."/view?user=".$new['Id']."'><img src='".$XCOW_B['url'].$new['photo']."' width='48' height='48' alt='' /></a> ";
			echo "</div>";

		}
		echo "</td></tr>";
		echo "<tr><td></td><td colspan='".$session['response']['param']['limit']."'>"; 
		echo "<div id='NewFacesName'>&nbsp;</div>";
		echo "</td></tr>";
	}
?>
</table>
</div>
