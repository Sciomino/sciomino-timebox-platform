<?php
foreach ($session['response']['param']['otherPubList'] as $otherPub) {
	echo"<fieldset class='item divider'>";
	echo "<form action='".$XCOW_B['url']."/snippet/otherPub-edit-form' method='post'>";
    	echo "<input class='form_input' type='hidden' name='otherPubId' value='{$otherPub['Id']}'>";
	echo "<div class='inputset'>";
	echo "<label for='titel-publicatie'>".language('sciomio_text_publication_title')."</label>";
	echo "<input value='".htmlTokens($otherPub['title'])."' type='text' class='text' name='com_title' id='titel-publicatie' maxlength='128' />";
	echo "<div class='interact'>";
	echo "<a href='".$XCOW_B['url']."/snippet/otherPub-delete?otherPubId={$otherPub['Id']}' title='verwijder' class='remove delete-multiple'>x</a>";
	echo "</div>";
	echo "</div>";
	echo "<div class='inputset'>";
	echo "<label for='subtitel-publicatie'>".language('sciomio_text_publication_alternative')."</label>";
	echo "<input value='".htmlTokens($otherPub['alternative'])."' type='text' class='text' name='alternative' id='subtitel-publicatie' maxlength='128' />";
	echo "</div>";
	echo "<div class='inputset'>";
	echo "<label for='description-publicatie'>".language('sciomio_text_publication_description')."</label>";
	echo "<input type='text' class='text' id='description-publicatie' value='".htmlTokens($otherPub['description'])."' name='description' maxlength='256' />";
	echo "</div>";
	echo "<div class='inputset'>";
	echo "<label for='link-publicatie'>".language('sciomio_text_publication_link')."</label>";
	echo "<input value='".htmlTokens($otherPub['relation-self'])."' type='text' class='text' name='relation-self' id='link-publicatie' maxlength='256' />";
	echo "<div class='interact'>";
	echo "<a href='".$XCOW_B['url']."/snippet/otherPub-edit-form' title='opslaan' class='tinybutton save'>".language('sciomio_word_ok')."</a>";
	echo "<div class='cancelbox'>";
	echo language('sciomio_word_ok')."<a href='#' title='Annuleren' class='cancel'>".language('sciomio_word_reset')."</a>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
	echo "</form>";
	echo "</fieldset>";
}
?>

        <fieldset class="item divider">
            <form action="<?php echo $XCOW_B['url'] ?>/snippet/otherPub-new-form" method="post">
                <div class="inputset">
                    <label for="titel-publicatie"><?php echo language('sciomio_text_publication_title'); ?></label>
                    <input value="" type="text" class="text" name="com_title" id="titel-publicatie" maxlength="128" />
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
                    </div>
                </div>
            </form>
        </fieldset>
        <div class="single-add add-container">
            <a class="add" href="<?php echo $XCOW_B['url'] ?>/snippet/otherPub-new-form"><?php echo language('sciomio_text_publication_toevoegen'); ?></a>
        </div>

