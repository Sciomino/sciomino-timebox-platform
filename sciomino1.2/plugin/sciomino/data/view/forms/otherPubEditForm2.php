<form action="<?php echo $XCOW_B['url'] ?>/snippet/otherPub-edit-form" method="post">
    <input class='form_input' type='hidden' name='otherPubId' value="<?php echo $session['response']['param']['otherPubId']; ?>">
    <div class="inputset">
        <label for="titel-publicatie"><?php echo language('sciomio_text_publication_title'); ?></label>
        <input value="<?php echo htmlTokens($session['response']['param']['otherPubTitle']); ?>" type="text" class="text" name="com_title" id="titel-publicatie" maxlength="128" />
        <div class="interact">
            <a href="<?php echo $XCOW_B['url'] ?>/snippet/otherPub-delete?otherPubId=<?php echo $session['response']['param']['otherPubId']; ?>" title="verwijder" class="remove delete-multiple">x</a>
        </div>
    </div>
    <div class="inputset ">
        <label for="subtitel-publicatie"><?php echo language('sciomio_text_publication_alternative'); ?></label>
        <input value="<?php echo htmlTokens($session['response']['param']['otherPubAlternative']); ?>" type="text" class="text" name="alternative" id="subtitel-publicatie" maxlength="128" />
    </div>
    <div class="inputset">
        <label for="description-publicatie"><?php echo language('sciomio_text_publication_description'); ?></label>
        <input type="text" class="text" id="description-publicatie" value="<?php echo htmlTokens($session['response']['param']['otherPubDescription']); ?>" name="description" maxlength="256" />
    </div>
    <div class="inputset ">
        <label for="link-publicatie"><?php echo language('sciomio_text_publication_link'); ?></label>
        <input value="<?php echo htmlTokens($session['response']['param']['otherPubRelation-self']); ?>" type="text" class="text" name="relation-self" id="link-publicatie" maxlength="256" />
        <div class="interact">
            <a href="<?php echo $XCOW_B['url'] ?>/snippet/otherPub-edit-form" title="opslaan" class="tinybutton save"><?php echo language('sciomio_word_ok'); ?></a>
            <div class="cancelbox">
                <?php echo language('sciomio_word_or'); ?> <a href="#" title="Annuleren" class="cancel"><?php echo language('sciomio_word_reset'); ?></a>
            </div>
        </div>
    </div>
</form>

