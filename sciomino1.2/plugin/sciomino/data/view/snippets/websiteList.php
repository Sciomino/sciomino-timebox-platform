<?php
foreach ($session['response']['param']['websiteList'] as $website) {
	echo "<div class='inputset item'>";
	echo "<form action='".$XCOW_B['url']."/snippet/website-edit-form' method='post'>";
    	echo "<input class='form_input' type='hidden' name='com_title' value='".htmlTokens($website['title'])."'>";
    	echo "<input class='form_input' type='hidden' name='websiteId' value='{$website['Id']}'>";
	echo "<label class='icon icon-website' for='website-{$website['Id']}'>".language('sciomio_text_website_website')."</label>";
	echo "<input type='text' id='website-{$website['Id']}' name='com_relation-self' class='text' value='".htmlTokens($website['relation-self'])."' maxlength='256' />";
        echo "<div class='interact'>";
        echo "<a href='".$XCOW_B['url']."/snippet/website-delete?websiteId={$website['Id']}' title='verwijder' class='remove'>x</a>";
        echo "<a href='".$XCOW_B['url']."/snippet/website-edit-form' title='opslaan' class='tinybutton save'>".language('sciomio_word_ok')."</a>";
        echo "<div class='cancelbox'>";
        echo language('sciomio_word_or')." <a href='#' title='Annuleren' class='cancel'>".language('sciomio_word_reset')."</a>";
        echo "</div>";
        echo "</div>";
        echo "</form>";
	echo "</div>";
}
?>
        <div class="inputset item">
            <form action="<?php echo $XCOW_B['url'] ?>/snippet/website-new-form" method="post">
    		<input class='form_input' type='hidden' name='com_title' value='website'>
                <label class="icon icon-website" for="website-0"><?php echo language('sciomio_text_website_website'); ?></label>
                <input class="text" type="text" name="com_relation-self" id="website-0" value="" maxlength="256" />
                <div class="interact">
                    <a href="<?php echo $XCOW_B['url'] ?>/snippet/website-new-form" title="opslaan" class="tinybutton save"><?php echo language('sciomio_word_ok'); ?></a>
               </div>
            </form>
        </div>

        <div class="single-add add-container">
            <a class="add" href="<?php echo $XCOW_B['url'] ?>/snippet/website-new-form"><?php echo language('sciomio_text_website_toevoegen'); ?></a>
        </div>

