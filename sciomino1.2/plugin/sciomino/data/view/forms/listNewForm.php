<form action="<?php echo $XCOW_B['url'] ?>/snippet/list-new-form" method="post">
    <input type="hidden" value="<?php echo $session['response']['param']['user']; ?>" name="user" />
    <input type="text" class="text" value="nieuw" name="com_name" maxlength="32" />
    <span class="interact">
        <a href="<?php echo $XCOW_B['url'] ?>/snippet/list-new-form" title="opslaan" class="tinybutton save"><?php echo language('sciomio_word_ok'); ?></a>
    </span>
    <span class="interact cancelbox">
        <?php echo language('sciomio_word_or'); ?> <a href="<?php echo $XCOW_B['url'] ?>/snippet/list-new-form" title="Annuleren" class="cancel-new"><?php echo language('sciomio_word_reset'); ?></a>
    </span>
</form>
