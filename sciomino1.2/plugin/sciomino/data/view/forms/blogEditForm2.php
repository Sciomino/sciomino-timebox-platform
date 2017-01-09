<form action="<?php echo $XCOW_B['url'] ?>/snippet/blog-edit-form" method="post">
    <input class='form_input' type='hidden' name='com_title' value="<?php echo htmlTokens($session['response']['param']['blogTitle']); ?>">
    <input class='form_input' type='hidden' name='blogId' value="<?php echo $session['response']['param']['blogId']; ?>">
    <label class="icon icon-<?php echo $session['response']['param']['blogTitle']; ?>" for="blog-<?php echo $session['response']['param']['blogId']; ?>"><?php echo language('sciomio_text_blog_'.$session['response']['param']['blogTitle']); ?></label>
    <input value="<?php echo htmlTokens($session['response']['param']['blogRelation-other']); ?>" type="text" class="text" name="com_relation-other" id="blog-<?php echo $session['response']['param']['blogId']; ?>" maxlength="256" />

    <div class="interact">
        <a href="<?php echo $XCOW_B['url'] ?>/snippet/blog-delete?blogId=<?php echo $session['response']['param']['blogId']; ?>" title="verwijder" class="remove">x</a>
        <a href="<?php echo $XCOW_B['url'] ?>/snippet/blog-edit-form" title="opslaan" class="tinybutton save"><?php echo language('sciomio_word_ok'); ?></a>
        <div class="cancelbox">
            <?php echo language('sciomio_word_or'); ?> <a href="#" title="annuleren" class="cancel"><?php echo language('sciomio_word_reset'); ?></a>
        </div>
    </div>
</form>
