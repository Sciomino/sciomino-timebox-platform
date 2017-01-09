<?php
foreach ($session['response']['param']['shareList'] as $share) {
	echo "<div class='inputset item'>";
	echo "<form action='".$XCOW_B['url']."/snippet/share-edit-form' method='post'>";
    	echo "<input class='form_input' type='hidden' name='com_title' value='".htmlTokens($share['title'])."'>";
    	echo "<input class='form_input' type='hidden' name='shareId' value='{$share['Id']}'>";
	$languageString = "sciomio_text_share_".$share['title'];
	echo "<label class='icon icon-slideshare' for='share-{$share['Id']}'>".language($languageString)."</label>";
	echo "<input type='text' id='share-{$share['Id']}' name='com_relation-self' class='text' value='".htmlTokens($share['relation-self'])."' maxlength='256'/>";
        echo "<div class='interact'>";
        echo "<a href='".$XCOW_B['url']."/snippet/share-delete?shareId={$share['Id']}' title='verwijder' class='remove'>x</a>";
        echo "<a href='".$XCOW_B['url']."/snippet/share-edit-form' title='opslaan' class='tinybutton save'>".language('sciomio_word_ok')."</a>";
        echo "<div class='cancelbox'>";
        echo language('sciomio_word_or')." <a href='#' title='Annuleren' class='cancel'>".language('sciomio_word_reset')."</a>";
        echo "</div>";
        echo "</div>";
        echo "</form>";
	echo "</div>";
}
?>
        <div class="inputset item">
            <form action="<?php echo $XCOW_B['url'] ?>/snippet/share-new-form" method="post">
    		<input class='form_input' type='hidden' name='com_title' value='slideshare'>
                <label class="icon icon-slideshare" for="share-0"><?php echo language('sciomio_text_share_slideshare'); ?></label>
                <input class="text" type="text" name="com_relation-self" id="share-0" value="" maxlength="256" />
                <div class="interact">
                    <a href="<?php echo $XCOW_B['url'] ?>/snippet/share-new-form" title="opslaan" class="tinybutton save"><?php echo language('sciomio_word_ok'); ?></a>
               </div>
            </form>
        </div>
        <div class="inputset add-container">
            <form action="<?php echo $XCOW_B['url'] ?>/snippet/share-new-form" method="post">
                <label><?php echo language('sciomio_text_share_toevoegen'); ?></label>
                <ul class="icon-buttons">
	            <li><a href="<?php echo $XCOW_B['url'] ?>/snippet/share-new-form" title="Slideshare" class="add"><span class="icon icon-slideshare">S</span></a></li>
                </ul>
            </form>
        </div>
