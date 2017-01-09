<form action="<?php echo $XCOW_B['url'] ?>/snippet/website-edit-form" method="post">
    <input class='form_input' type='hidden' name='com_title' value="<?php echo htmlTokens($session['response']['param']['websiteTitle']); ?>">
    <input class='form_input' type='hidden' name='websiteId' value="<?php echo $session['response']['param']['websiteId']; ?>">
    <label class="icon icon-website" for="website-<?php echo $session['response']['param']['websiteId']; ?>"><?php echo language('sciomio_text_website_'.$session['response']['param']['websiteTitle']); ?></label>
    <input value="<?php echo htmlTokens($session['response']['param']['websiteRelation-self']); ?>" type="text" class="text" name="com_relation-self" id="website-<?php echo $session['response']['param']['websiteId']; ?>" maxlength="256" />
    <div class="interact">
        <a href="<?php echo $XCOW_B['url'] ?>/snippet/website-delete?websiteId=<?php echo $session['response']['param']['websiteId']; ?>" title="verwijder" class="remove">x</a>
        <a href="<?php echo $XCOW_B['url'] ?>/snippet/website-edit-form" title="opslaan" class="tinybutton save"><?php echo language('sciomio_word_ok'); ?></a>
        <div class="cancelbox">
            <?php echo language('sciomio_word_or'); ?> <a href="#" title="annuleren" class="cancel"><?php echo language('sciomio_word_reset'); ?></a>
        </div>
    </div>
</form>
