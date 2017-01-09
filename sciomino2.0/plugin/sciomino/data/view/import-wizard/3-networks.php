<div id="wizardWindow" style="text-align:left;width:600px">

<h2><?php echo language(sciomio_header_wizard_3); ?></h2>
<p>&nbsp;</p>

<div class="article">
	<?php
	foreach ($session['response']['param']['networkList'] as $list) {
		echo "<div>";

		echo "<div style='float:left;width:100px;'>";
		echo "<img height='96px' src='".$XCOW_B['url']."/upload/networks/".strtolower($list['Name']).".png'>";
		echo "</div>";
		
		echo "<div style='float:left;width:390px; padding-top:10px; padding-left:10px; padding-right:10px;'>";
		echo "<b>".$list['Name']."</b>";
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
</div>

<div style="width:100%;valign:center;">
	<a style="width:100px;float:right;text-decoration:none;padding-top:10px;" class="form_button input_button input_space" href="/wizard?step=4"><?php echo language(sciomio_word_next); ?></a>
</div>

</div>

