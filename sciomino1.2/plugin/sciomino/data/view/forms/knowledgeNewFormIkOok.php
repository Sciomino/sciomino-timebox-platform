<div class="metoo-content highlight" <?php if ($session['response']['param']['mode'] == "modal") { echo "style='padding:10px;'";} ?> >
	<div class="header">
		<h3><?php echo language('sciomio_text_knowledge_ikooktitel'); ?></h3>
		<h2 class="divider"><?php echo $session['response']['param']['fill']; ?></h2>
	</div>
	<form action="<?php echo $XCOW_B['url'] ?>/snippet/knowledge-new-form-ikook?mode=ikook&fill=<?php echo $session['response']['param']['fill']; ?>" method="post">
	<input class="text" type="hidden" name="com_field" id="com_field" value="<?php echo htmlTokens($session['response']['param']['fill']); ?>" />

        <fieldset class="divider">
            <div class="">
                <p></p>
            </div>
            <div class="selectbox" id="zomaareenbox">
            	<label><?php echo language('sciomio_text_knowledge_level'); ?></label>
		    <select class="s" name="com_level">
			<option selected value='1'><?php echo language('sciomio_word_knowledgefield_1'); ?></option>
			<option value='2'><?php echo language('sciomio_word_knowledgefield_2'); ?></option>
			<option value='3'><?php echo language('sciomio_word_knowledgefield_3'); ?></option>
		    </select>
             </div>
        </fieldset>

        <fieldset class="complete">
			<input class="submit" type="submit" value="<?php echo language('sciomio_text_knowledge_toevoegen2'); ?>" />
			<?php
			if ($session['response']['param']['mode'] != "modal") {
				echo "<div class='cancelbox'>";
				echo language('sciomio_word_or')."<a href='#' class='cancel close'>".language('sciomio_word_reset')."</a>";
				echo "</div>";
			}
			?>
		</fieldset>
	</form>
</div>

<?php
if ($session['response']['param']['status'] == "Same Same") {
    echo "<script>";
    echo "sc.displayMessage({message : '".language('sciomio_text_knowledge_save_error')."', type : 'error', displayTime : 2000});";
    echo "</script>";
}
