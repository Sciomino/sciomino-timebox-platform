<form action="<?php echo $XCOW_B['url'] ?>/snippet/list-edit-form" method="post">
    <input class='form_input' type='hidden' name='listId' value="<?php echo $session['response']['param']['listId']; ?>">
    <input type="text" class="text" value="<?php echo htmlTokens($session['response']['param']['list']['Name']); ?>" name="com_name" maxlength="32" />
    <span class="interact">
        <a href="<?php echo $XCOW_B['url'] ?>/snippet/list-edit-form" title="opslaan" class="tinybutton save"><?php echo language('sciomio_word_ok'); ?></a>
    </span>
    <span class="interact cancelbox">
        <?php echo language('sciomio_word_or'); ?> <a href="<?php echo $XCOW_B['url'] ?>/snippet/list-edit-form" title="Annuleren" class="cancel"><?php echo language('sciomio_word_reset'); ?></a>
    </span>
</form>

