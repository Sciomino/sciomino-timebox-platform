<form action="<?php echo $XCOW_B['url'] ?>/snippet/share-new-form" method="post">
    <input class='form_input' type='hidden' name='com_title' value='slideshare'>
    <label class="icon icon-slideshare" for="slideshare-0"><?php echo language('sciomio_text_share_slideshare'); ?></label>
    <input value="<?php echo htmlTokens($session['response']['param']['fill']); ?>" type="text" class="text" name="com_relation-self" id="slideshare-0" maxlength="256" />
    <div class="interact">
        <!-- <a href="/snippet/share-delete?shareId=<?php echo $session['response']['param']['shareId']; ?>" title="verwijder" class="remove">x</a> -->
        <a href="<?php echo $XCOW_B['url'] ?>/snippet/share-new-form" title="opslaan" class="tinybutton save"><?php echo language('sciomio_word_ok'); ?></a>
        <div class="cancelbox">
            <?php echo language('sciomio_word_or'); ?> <a href="#" title="annuleren" class="cancel-new"><?php echo language('sciomio_word_reset'); ?></a>
        </div>
    </div>
</form>
