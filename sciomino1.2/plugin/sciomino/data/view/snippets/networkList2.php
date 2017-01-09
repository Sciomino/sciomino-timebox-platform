<ul class="checkbox">
	<?php
	foreach ($session['response']['param']['networkList'] as $list) {
		if ($list['Checked'] == "checked") {
			echo "<li class='item'>";
			echo "<img style='float:right;padding-top:10px;' width='20' src='/upload/networks/".strtolower($list['Name']).".png'><a class='listname' href='".$XCOW_B['url']."/search?tl[public]=".urlencode($list['Name'])."'>".$list['Name']."</a>";
			echo "<br clear='right'/></li>";
		}
	}
	?>

	<hr>

	<li><a style="margin-left:-5px" href="<?php echo $XCOW_B['url'] ?>/setting/networks"><?php echo language('sciomio_text_session_networks_link'); ?></a></li>

</ul>
