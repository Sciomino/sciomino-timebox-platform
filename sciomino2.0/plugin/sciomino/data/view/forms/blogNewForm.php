<form action="<?php echo $XCOW_B['url'] ?>/snippet/blog-new-form" method="post">
    <input class='form_input' type='hidden' name='com_title' value="<?php echo htmlTokens($session['response']['param']['fillTitle']); ?>">
    <label class="icon icon-<?php echo $session['response']['param']['fillTitle']; ?>" for="blog-0"><?php echo language('sciomio_text_blog_'.$session['response']['param']['fillTitle']); ?></label>
    <input value="<?php echo htmlTokens($session['response']['param']['fill']); ?>" type="text" class="text" name="com_relation-other" id="blog-0" maxlength="256" />
    <div class="interact">
        <!-- <a href="/snippet/blog-delete?blogId=<?php echo $session['response']['param']['blogId']; ?>" title="verwijder" class="remove">x</a> -->
        <a href="<?php echo $XCOW_B['url'] ?>/snippet/blog-new-form" title="opslaan" class="tinybutton save"><?php echo language('sciomio_word_ok'); ?></a>
        <div class="cancelbox">
            <?php echo language('sciomio_word_or'); ?> <a href="#" title="annuleren" class="cancel-new"><?php echo language('sciomio_word_reset'); ?></a>
        </div>
    </div>
</form>

