<form action="<?php echo $XCOW_B['url'] ?>/snippet/knowledge-edit-form" method="post">
    <input class='form_input' type='hidden' name='knowledgeId' value="<?php echo $session['response']['param']['knowledgeId']; ?>">
    <input class="text autocomplete" data-results="<?php echo $XCOW_B['url'] ?>/snippet/suggest-knowledge" type="text" name="com_field" value="<?php echo htmlTokens($session['response']['param']['knowledgeField']); ?>" id="<?php echo $session['response']['param']['knowledgeId']; ?>" maxlength="32" />
    <select class="s" name="com_level">
	<option <?php if ($session['response']['param']['knowledgeLevel'] == 1) { echo 'selected'; } ?>  value='1'><?php echo language('sciomio_word_knowledgefield_1'); ?></option>
	<option <?php if ($session['response']['param']['knowledgeLevel'] == 2) { echo 'selected'; } ?> value='2'><?php echo language('sciomio_word_knowledgefield_2'); ?></option>
	<option <?php if ($session['response']['param']['knowledgeLevel'] == 3) { echo 'selected'; } ?> value='3'><?php echo language('sciomio_word_knowledgefield_3'); ?></option>
    </select>
    <div class="interact">
        <a href="<?php echo $XCOW_B['url'] ?>/snippet/knowledge-delete?knowledgeId=<?php echo $session['response']['param']['knowledgeId']; ?>" title="verwijder" class="remove">x</a>
        <a href="<?php echo $XCOW_B['url'] ?>/snippet/knowledge-edit-form" title="opslaan" class="tinybutton save"><?php echo language('sciomio_word_ok'); ?></a>
        <div class="cancelbox">
            <?php echo language('sciomio_word_or'); ?> <a href="#" title="annuleren" class="cancel"><?php echo language('sciomio_word_reset'); ?></a>
        </div>
    </div>
</form>
