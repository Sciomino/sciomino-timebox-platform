<form action="<?php echo $XCOW_B['url'] ?>/snippet/website-new-form" method="post">
    <input class='form_input' type='hidden' name='com_title' value='website'>
    <label class="icon icon-website" for="website-0"><?php echo language('sciomio_text_website_website'); ?></label>
    <input value="<?php echo htmlTokens($session['response']['param']['fill']); ?>" type="text" class="text" name="com_relation-self" id="website-0" maxlength="256" />
    <div class="interact">
        <!-- <a href="/snippet/website-delete?websiteId=<?php echo $session['response']['param']['websiteId']; ?>" title="verwijder" class="remove">x</a> -->
        <a href="<?php echo $XCOW_B['url'] ?>/snippet/website-new-form" title="opslaan" class="tinybutton save"><?php echo language('sciomio_word_ok'); ?></a>
        <div class="cancelbox">
            <?php echo language('sciomio_word_or'); ?> <a href="#" title="annuleren" class="cancel-new"><?php echo language('sciomio_word_reset'); ?></a>
        </div>
    </div>
</form>
