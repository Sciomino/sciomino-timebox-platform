<div class="metoo-content highlight">
	<div class="header">
		<h3><?php echo $session['response']['param']['userName']; ?><?php echo language('sciomio_text_icanhelp'); ?></h3>
		<h2 class="divider"><?php echo $session['response']['param']['knowledge']; ?></h2>
	</div>
	<form action="<?php echo $XCOW_B['url'] ?>/snippet/help-new-form" method="post">
	<?php
	if ($session['response']['param']['missing'] == 1 && $session['response']['param']['go'] == 1) {
		echo "<!--ERROR_MISSING_FIELDS-->\n";
	}
	?>
   <input type="hidden" value="1" name="go" />
	<input class='form_input' type='hidden' name='activity' value="<?php echo $session['response']['param']['activity']; ?>">
	<input class='form_input' type='hidden' name='user' value="<?php echo $session['response']['param']['user']; ?>">
	<input class='form_input' type='hidden' name='knowledge' value="<?php echo htmlTokens($session['response']['param']['knowledge']); ?>">
        <fieldset class="divider">
            <div class="inputset  <?php if ($session['response']['param']['prevMessage'] == "" && $session['response']['param']['go'] == 1) { echo "error"; } ?>">
                <label for="ervaringEventToelichting"><?php echo language('sciomio_text_icanhelp_mail'); ?> <?php echo $session['response']['param']['userName']; ?></label>
                <textarea name="com_message" id="ervaringEventToelichting" maxlength="1024"></textarea>
            </div>
        </fieldset>
        <fieldset class="divider">
            <div class="inputset checkbox">
                <input class="checkbox" data-toggleBox="ToggleSelect" type="checkbox" id="Metoo" name="add-new" />
                <label for="Metoo"><?php echo language('sciomio_text_icanhelp_knowledge'); ?></label>
            </div>
            <div class="inputset hidden selectbox" id="ToggleSelect">
                <label><?php echo language('sciomio_text_icanhelp_level'); ?></label>
                <select name="level" id="nivo">
			<option selected value='1'><?php echo language('sciomio_word_knowledgefield_1'); ?></option>
			<option value='2'><?php echo language('sciomio_word_knowledgefield_2'); ?></option>
			<option value='3'><?php echo language('sciomio_word_knowledgefield_3'); ?></option>
                </select>
            </div>
        </fieldset>
        <fieldset class="complete">
			<input class="submit" type="submit" value="<?php echo language('sciomio_text_icanhelp_toevoegen'); ?>" />
			<div class="cancelbox">
				<?php echo language('sciomio_word_or'); ?> <a href="#" class="cancel close"><?php echo language('sciomio_word_reset'); ?></a>
			</div>
		</fieldset>
	</form>
</div>

<?php
if ($session['response']['param']['go']) {
    echo "<script>";
    echo "sc.displayMessage({message : '".language('sciomio_text_icanhelp_save_error')."', type : 'error', displayTime : 2000});";
    echo "</script>";
}
?>
