<form action="<?php echo $XCOW_B['url'] ?>/snippet/otherPub-new-form" method="post">
<div class="inputset">
    <label for="titel-publicatie"><?php echo language('sciomio_text_publication_title'); ?></label>
    <input value="<?php echo htmlTokens($session['response']['param']['fill']); ?>" type="text" class="text" name="com_title" id="titel-publicatie" maxlength="128" />
</div>
<div class="inputset ">
    <label for="subtitel-publicatie"><?php echo language('sciomio_text_publication_alternative'); ?></label>
    <input value="" type="text" class="text" name="alternative" id="subtitel-publicatie" maxlength="128" />
</div>
<div class="inputset">
    <label for="description-publicatie"><?php echo language('sciomio_text_publication_description'); ?></label>
    <input type="text" class="text" id="description-publicatie" value="" name="description" maxlength="256" />
</div>
<div class="inputset">
    <label for="link-publicatie"><?php echo language('sciomio_text_publication_link'); ?></label>
    <input type="text" class="text" id="link-publicatie" value="" name="relation-self" maxlength="256" />
    <div class="interact">
        <a href="<?php echo $XCOW_B['url'] ?>/snippet/otherPub-new-form" title="opslaan" class="tinybutton save"><?php echo language('sciomio_word_ok'); ?></a>
        <div class="cancelbox">
            <?php echo language('sciomio_word_or'); ?> <a href="#" title="Annuleren" class="cancel-new"><?php echo language('sciomio_word_reset'); ?></a>
        </div>
    </div>
</div>
</form>

