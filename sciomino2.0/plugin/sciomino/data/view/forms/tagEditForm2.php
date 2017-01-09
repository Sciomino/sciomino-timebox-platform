<form action="<?php echo $XCOW_B['url'] ?>/snippet/tag-edit-form" method="post">
    <input class='form_input' type='hidden' name='tagId' value="<?php echo $session['response']['param']['tagId']; ?>">

    <input value="<?php echo htmlTokens($session['response']['param']['tagName']); ?>" type="text" class="text autocomplete" data-results="<?php echo $XCOW_B['url'] ?>/snippet/suggest-local?type=tag" name="com_name" id="<?php echo $session['response']['param']['tagId']; ?>" maxlength="32" />
    <div class="interact">
        <a href="<?php echo $XCOW_B['url'] ?>/snippet/tag-delete?tagId=<?php echo $session['response']['param']['tagId']; ?>" title="verwijder" class="remove">x</a>
        <a href="<?php echo $XCOW_B['url'] ?>/snippet/tag-edit-form" title="opslaan" class="tinybutton save"><?php echo language('sciomio_word_ok'); ?></a>
        <div class="cancelbox">
            <?php echo language('sciomio_word_or'); ?> <a href="#" title="annuleren" class="cancel"><?php echo language('sciomio_word_reset'); ?></a>
        </div>
    </div>
</form>
