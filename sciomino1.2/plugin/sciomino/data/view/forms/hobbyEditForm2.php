<form action="<?php echo $XCOW_B['url'] ?>/snippet/hobby-edit-form" method="post">
    <input class='form_input' type='hidden' name='hobbyId' value="<?php echo $session['response']['param']['hobbyId']; ?>">

    <input value="<?php echo htmlTokens($session['response']['param']['hobbyField']); ?>" type="text" class="text autocomplete" data-results="<?php echo $XCOW_B['url'] ?>/snippet/suggest-local?type=hobby" name="com_field" id="<?php echo $session['response']['param']['hobbyId']; ?>" maxlength="32" />
    <div class="interact">
        <a href="<?php echo $XCOW_B['url'] ?>/snippet/hobby-delete?hobbyId=<?php echo $session['response']['param']['hobbyId']; ?>" title="verwijder" class="remove">x</a>
        <a href="<?php echo $XCOW_B['url'] ?>/snippet/hobby-edit-form" title="opslaan" class="tinybutton save"><?php echo language('sciomio_word_ok'); ?></a>
        <div class="cancelbox">
            <?php echo language('sciomio_word_or'); ?> <a href="#" title="annuleren" class="cancel"><?php echo language('sciomio_word_reset'); ?></a>
        </div>
    </div>
</form>
