<form action="<?php echo $XCOW_B['url'] ?>/snippet/tag-new-form" method="post">
	<input value="<?php echo htmlTokens($session['response']['param']['fill']); ?>" type="text" class="text autocomplete" data-results="<?php echo $XCOW_B['url'] ?>/snippet/suggest-local?type=tag" name="com_name" id="com_name" maxlength="32" />
	<div class="interact">
		<!--<a href="/ajax-html/form-edited-tag.html" title="verwijder" class="remove">x</a>-->
		<a href="<?php echo $XCOW_B['url'] ?>/snippet/tag-new-form" title="opslaan" class="tinybutton save"><?php echo language('sciomio_word_ok'); ?></a>
        <span class="cancelbox">
            <?php echo language('sciomio_word_or'); ?> <a href="#" title="annuleren" class="cancel-new"><?php echo language('sciomio_word_reset'); ?></a>
        </span>
        
	</div>
</form>

<?php
if ($session['response']['param']['status'] == "Same Same") {
    echo "<script>";
    echo "sc.displayMessage({message : '".language('sciomio_text_tag_save_error')."', type : 'error', displayTime : 2000});";
    echo "</script>";
}
