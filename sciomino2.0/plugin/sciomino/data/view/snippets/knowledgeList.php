
<?php
foreach ($session['response']['param']['knowledgeList'] as $knowledge) {
	echo "<div class='inputset item'>";

	echo "<form action='".$XCOW_B['url']."/snippet/knowledge-edit-form' method='post'>";
    echo "<input class='form_input' type='hidden' name='knowledgeId' value='{$knowledge['Id']}'>";
	echo "<input class='text autocomplete' data-results='".$XCOW_B['url']."/snippet/suggest-knowledge' type='text' name='com_field' id='{$knowledge['Id']}' value='".htmlTokens($knowledge['field'])."' maxlength='32' />";
	echo "<select class='s' name='com_level' id='kennisveld-interaction-design'>";
	if ($knowledge['level'] == 1) { $select1 = 'selected'; } else { $select1 = ""; }
	if ($knowledge['level'] == 2) { $select2 = 'selected'; } else { $select2 = ""; }
	if ($knowledge['level'] == 3) { $select3 = 'selected'; } else { $select3 = ""; }
	echo "<option ".$select1." value='1'>".language('sciomio_word_knowledgefield_1')."</option>";
	echo "<option ".$select2." value='2'>".language('sciomio_word_knowledgefield_2')."</option>";
	echo "<option ".$select3." value='3'>".language('sciomio_word_knowledgefield_3')."</option>";
	echo "</select>";
	echo "<div class='interact'>";
	echo "<a href='".$XCOW_B['url']."/snippet/knowledge-delete?knowledgeId={$knowledge['Id']}' title='verwijder' class='remove'>x</a>";
	echo "<a href='".$XCOW_B['url']."/snippet/knowledge-edit-form' title='opslaan' class='tinybutton save'>".language('sciomio_word_ok')."</a>";
	echo "<div class='cancelbox'>";
	echo language('sciomio_word_or')." <a href='#' title='annuleren' class='cancel'>".language('sciomio_word_reset')."</a>";
	echo "</div>";
	echo "</div>";
	echo "</form>";

	echo "</div>";
}
?>
        <div class="inputset item">
		<form action="<?php echo $XCOW_B['url'] ?>/snippet/knowledge-new-form" method="post">
		    <input class="text autocomplete" data-results="<?php echo $XCOW_B['url'] ?>/snippet/suggest-knowledge" type="text" name="com_field" id="com_field" maxlength="32" />
		    <select class="s" name="com_level">
			<option selected value='1'><?php echo language('sciomio_word_knowledgefield_1'); ?></option>
			<option value='2'><?php echo language('sciomio_word_knowledgefield_2'); ?></option>
			<option value='3'><?php echo language('sciomio_word_knowledgefield_3'); ?></option>
		    </select>
		    <div class="interact">
			<a href="<?php echo $XCOW_B['url'] ?>/snippet/knowledge-new-form" title="opslaan" class="tinybutton save"><?php echo language('sciomio_word_ok'); ?></a>
		    </div>
		</form>
        </div>

        <div class="add-container">
            <a href="<?php echo $XCOW_B['url'] ?>/snippet/knowledge-new-form" class="add"><?php echo language('sciomio_text_knowledge_toevoegen'); ?></a>
        </div>

