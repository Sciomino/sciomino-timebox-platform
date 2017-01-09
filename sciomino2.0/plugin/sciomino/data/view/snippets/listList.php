<ul class="checkbox">
	<?php
	foreach ($session['response']['param']['listList'] as $list) {
		echo "<li class='inputset item'>";
		echo "<form>";
		echo "<input onClick='ScioMino.List.check(".$session['response']['param']['user'].",".$list['Id'].",event)' type='checkbox' class='checkbox' id='".$list['Name']."' name='".$list['Name']."' ".$list['Checked'].">";
		echo "<label for='".$list['Name']."'>".$list['Name']."</label>";
		echo "</form>";
		echo "</li>";
	}
	?>

	<hr>

	<li class="add-container">
		<a class="add" href="<?php echo $XCOW_B['url'] ?>/snippet/list-new-form?user=<?php echo $session['response']['param']['user']; ?>"><?php echo language('sciomio_text_lijst_toevoegen'); ?></a>
	</li>

</ul>

