<form action="<?php echo $XCOW_B['url'] ?>/snippet/knowledge-new-form" method="post">
    <input class="text autocomplete" data-results="<?php echo $XCOW_B['url'] ?>/snippet/suggest-knowledge" type="text" name="com_field" id="com_field" value="<?php echo htmlTokens($session['response']['param']['fill']); ?>" maxlength="32" />
    <select class="s" name="com_level">
	<option selected value='1'><?php echo language('sciomio_word_knowledgefield_1'); ?></option>
	<option value='2'><?php echo language('sciomio_word_knowledgefield_2'); ?></option>
	<option value='3'><?php echo language('sciomio_word_knowledgefield_3'); ?></option>
    </select>
    <div class="interact">
        <!-- <a href="ajax-html/form-edited-kennisveld.html" title="verwijder" class="remove">x</a> -->
        <a href="<?php echo $XCOW_B['url'] ?>/snippet/knowledge-new-form" title="opslaan" class="tinybutton save"><?php echo language('sciomio_word_ok'); ?></a>
        <div class="cancelbox">
            <?php echo language('sciomio_word_or'); ?> <a href="#" title="annuleren" class="cancel-new"><?php echo language('sciomio_word_reset'); ?></a>
        </div>
    </div>
</form>

<?php
if ($session['response']['param']['status'] == "Same Same") {
    echo "<script>";
    echo "sc.displayMessage({message : '".language('sciomio_text_knowledge_save_error')."', type : 'error', displayTime : 2000});";
    echo "</script>";
}
