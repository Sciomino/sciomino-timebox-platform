<?php
foreach ($session['response']['param']['hobbyList'] as $hobby) {
        echo "<div class='inputset item'>";

        echo "<form action='".$XCOW_B['url']."/snippet/hobby-edit-form' method='post'>";
    	echo "<input class='form_input' type='hidden' name='hobbyId' value='{$hobby['Id']}'>";
        echo "<input value='".htmlTokens($hobby['field'])."' type='text' class='text autocomplete' data-results='".$XCOW_B['url']."/snippet/suggest-local?type=hobby' name='com_field' id='{$hobby['Id']}' maxlength='32' />";
        echo "<div class='interact'>";
        echo "<a href='".$XCOW_B['url']."/snippet/hobby-delete?hobbyId={$hobby['Id']}' title='verwijder' class='remove'>x</a>";
        echo "<a href='".$XCOW_B['url']."/snippet/hobby-edit-form' title='opslaan' class='tinybutton save'>".language('sciomio_word_ok')."</a>";
        echo "<span class='cancelbox'>";
        echo language('sciomio_word_or')." <a href='#' title='Annuleren' class='cancel'>".language('sciomio_word_reset')."</a>";
        echo "</span>";
        echo "</div>";
        echo "</form>";

        echo "</div>";
}

?>

        <div class="inputset item">
		<form action="<?php echo $XCOW_B['url'] ?>/snippet/hobby-new-form" method="post">
		    <input class="text autocomplete" data-results="<?php echo $XCOW_B['url'] ?>/snippet/suggest-local?type=hobby" type="text" name="com_field" id="com_field" maxlength="32" />
		    <div class="interact">
			<a href="<?php echo $XCOW_B['url'] ?>/snippet/hobby-new-form" title="opslaan" class="tinybutton save"><?php echo language('sciomio_word_ok'); ?></a>
		    </div>
		</form>
        </div>

        <div class="add-container">
            <a class="add" href="<?php echo $XCOW_B['url'] ?>/snippet/hobby-new-form"><?php echo language('sciomio_text_hobby_toevoegen'); ?></a>
        </div>

