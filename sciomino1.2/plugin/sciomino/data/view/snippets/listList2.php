<ul class="checkbox">
	<?php
	foreach ($session['response']['param']['listList'] as $list) {
		echo "<li class='inputset item'>";
		echo "<form>";
		echo "<a class='listname' href='".$XCOW_B['url']."/search?l[".urlencode($list['Name'])."]'>".$list['Name']."</a>";
           	echo "<span class='interact'>";
                echo "<a class='edit' title='wijzig' href='".$XCOW_B['url']."/snippet/list-edit-form?listId=".$list['Id']."'>e</a>";
                echo "<a class='remove' title='verwijder' href='".$XCOW_B['url']."/snippet/list-delete?listId=".$list['Id']."'>x</a>";
            	echo "</span>";
		echo "</form>";
		echo "</li>";
	}
	?>

	<hr>

	<li class="add-container">
		<a class="add" href="<?php echo $XCOW_B['url'] ?>/snippet/list-new-form?mode=view"><?php echo language('sciomio_text_lijst_toevoegen'); ?></a>
	</li>

</ul>

