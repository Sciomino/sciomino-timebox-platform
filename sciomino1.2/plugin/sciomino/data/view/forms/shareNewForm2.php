<form action="<?php echo $XCOW_B['url'] ?>/snippet/share-edit-form" method="post">
    <input class='form_input' type='hidden' name='com_title' value="<?php echo htmlTokens($session['response']['param']['shareTitle']); ?>">
    <input class='form_input' type='hidden' name='shareId' value="<?php echo $session['response']['param']['shareId']; ?>">
    <label class="icon icon-slideshare" for="slideshare-<?php echo $session['response']['param']['shareId']; ?>"><?php echo language('sciomio_text_share_'.$session['response']['param']['shareTitle']); ?></label>
    <input value="<?php echo htmlTokens($session['response']['param']['shareRelation-self']); ?>" type="text" class="text" name="com_relation-self" id="slideshare-<?php echo $session['response']['param']['shareId']; ?>" maxlength="256" />
    <div class="interact">
        <a href="<?php echo $XCOW_B['url'] ?>/snippet/share-delete?shareId=<?php echo $session['response']['param']['shareId']; ?>" title="verwijder" class="remove">x</a>
        <a href="<?php echo $XCOW_B['url'] ?>/snippet/share-edit-form" title="opslaan" class="tinybutton save"><?php echo language('sciomio_word_ok'); ?></a>
        <div class="cancelbox">
            <?php echo language('sciomio_word_or'); ?> <a href="#" title="annuleren" class="cancel"><?php echo language('sciomio_word_reset'); ?></a>
        </div>
    </div>
</form>
