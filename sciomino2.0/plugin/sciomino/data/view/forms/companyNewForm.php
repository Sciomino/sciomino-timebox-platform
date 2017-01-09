<div class="metoo-content">
	<div class="header">
		<h3><?php echo language('sciomio_text_ervaring'); ?></h3>
		<h2 class="divider"><?php echo language('sciomio_text_company'); ?></h2>
	</div>
	<form action="<?php echo $XCOW_B['url'] ?>/snippet/company-new-form" method="post">
		<?php
		if ($session['response']['param']['missing'] == 1 && $session['response']['param']['go'] == 1) {
			echo "<!--ERROR_MISSING_FIELDS-->\n";
		}
		?>
        <input type="hidden" value="1" name="go" />
        <fieldset class="details">
            <div class="inputset specs <?php if ($session['response']['param']['prevSubject'] == "" && $session['response']['param']['go'] == 1) { echo "error"; } ?>">
                <label for="subjectInput"><?php echo language('sciomio_text_company_subject'); ?></label>
                <input id="subjectInput" type="text" class="text autocomplete" data-results="<?php echo $XCOW_B['url'] ?>/snippet/suggest-local?type=company" value="<?php echo htmlTokens($session['response']['param']['prevSubject']); ?>" name="com_subject" maxlength="128" />
            </div>
            <div class="inputset specs <?php if ($session['response']['param']['prevTitle'] == "" && $session['response']['param']['go'] == 1) { echo "error"; } ?>">
                <label for="titleInput"><?php echo language('sciomio_text_company_title'); ?></label>

                <input id="titleInput" type="text" class="text autocomplete" data-results="<?php echo $XCOW_B['url'] ?>/snippet/suggest-local?type=company&subtype=" data-results-input="subjectInput" value="<?php echo htmlTokens($session['response']['param']['prevTitle']); ?>" name="com_title" maxlength="128" />
            </div>
            <div class="inputset specs">
                <label for="type"><?php echo language('sciomio_text_company_date'); ?></label>
                <input id="type" type="text" class="text" value="" name="date" maxlength="128" />
            </div>
        </fieldset>
		<fieldset class="input-verdict divider">
			<label for="your-verdict"><?php echo language('sciomio_text_company_like'); ?></label>
			<div id="Slider-result" class="slider-result">
				&nbsp;
			</div>
			<div class="sliderbox thumbs-2">
				<div id="Slider" class="slider"></div>
				<select name="com_like" id="your-verdict">
					<option value="4"><?php echo language('sciomio_word_like_4'); ?></option>
					<option value="3"><?php echo language('sciomio_word_like_3'); ?></option>
					<option value="2"><?php echo language('sciomio_word_like_2'); ?></option>
					<option value="1"><?php echo language('sciomio_word_like_1'); ?></option>
				</select>
			</div>
		</fieldset>
        <fieldset class="divider">
            <label for="toelichting"><?php echo language('sciomio_text_company_description'); ?></label>
            <textarea name="description" id="toelichting" maxlength="256"></textarea>
        </fieldset>
        <fieldset class="complete">
			<input class="submit" type="submit" value="<?php echo language('sciomio_text_company_toevoegen'); ?>" />
			<div class="cancelbox">
				<?php echo language('sciomio_word_or'); ?> <a href="#" class="cancel close"><?php echo language('sciomio_word_reset'); ?></a>
			</div>
		</fieldset>
	</form>
</div>

<?php
if ($session['response']['param']['go']) {
    echo "<script>";
    echo "sc.displayMessage({message : '".language('sciomio_text_ervaring_save_error')."', type : 'error', displayTime : 2000});";
    echo "</script>";
}
