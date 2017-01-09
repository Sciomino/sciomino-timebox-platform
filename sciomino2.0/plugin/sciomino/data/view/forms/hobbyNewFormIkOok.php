<div class="metoo-content highlight">
	<div class="header">
		<h3><?php echo language('sciomio_text_hobby_ikooktitel'); ?></h3>
		<h2 class="divider"><?php echo $session['response']['param']['fill']; ?></h2>
	</div>
	<form action="<?php echo $XCOW_B['url'] ?>/snippet/hobby-new-form-ikook?mode=ikook&fill=<?php echo $session['response']['param']['fill']; ?>" method="post">
	<input class="text" type="hidden" name="com_field" id="com_field" value="<?php echo htmlTokens($session['response']['param']['fill']); ?>" />

        <fieldset class="complete">
			<input class="submit" type="submit" value="<?php echo language('sciomio_text_hobby_toevoegen2'); ?>" />
			<div class="cancelbox">
				<?php echo language('sciomio_word_or'); ?> <a href="#" class="cancel close"><?php echo language('sciomio_word_reset'); ?></a>
			</div>
		</fieldset>
	</form>
</div>

<?php
if ($session['response']['param']['status'] == "Same Same") {
    echo "<script>";
    echo "sc.displayMessage({message : '".language('sciomio_text_hobby_save_error')."', type : 'error', displayTime : 2000});";
    echo "</script>";
}
