<div class="metoo-content highlight">
	<div class="header">
		<h3><?php echo language('sciomio_text_ervaring'); ?></h3>
		<h2 class="divider"><?php echo $session['response']['param']['company']['title']; ?></h2>
	</div>
	<form action="<?php echo $XCOW_B['url'] ?>/snippet/company-edit-form" method="post">
	<input class='form_input' type='hidden' name='companyId' value="<?php echo $session['response']['param']['companyId']; ?>">
        <input type="hidden" value="<?php echo htmlTokens($session['response']['param']['company']['title']); ?>" name="com_title" />
        <input type="hidden" value="<?php echo htmlTokens($session['response']['param']['company']['subject']); ?>" name="com_subject" />
        <fieldset class="details">
            <dl>
                <dt><?php echo language('sciomio_text_company_subject'); ?></dt>
                <dd><?php echo $session['response']['param']['company']['subject']; ?></dd>
            </dl>
            <div class="inputset">
                <label for="ervaringBedrijfWanneer"><?php echo language('sciomio_text_company_date'); ?></label>
                <input value="<?php echo htmlTokens($session['response']['param']['company']['date']); ?>" type="text" class="text" name="date" id="ervaringBedrijfWanneer" maxlength="128" />
            </div>
        </fieldset>
		<fieldset class="input-verdict divider">
			<label for="your-verdict"><?php echo language('sciomio_text_company_like'); ?></label>
			<div id="Slider-result" class="slider-result">
				&nbsp;
			</div>
			<div class="sliderbox thumbs-<?php echo (4 - $session['response']['param']['company']['like']) ?>">
				<div id="Slider" class="slider"></div>
				<select name="com_like" id="your-verdict">
					<option value="4" <?php if ($session['response']['param']['company']['like'] == 4) { echo "selected"; } ?>><?php echo language('sciomio_word_like_4'); ?></option>
					<option value="3" <?php if ($session['response']['param']['company']['like'] == 3) { echo "selected"; } ?>><?php echo language('sciomio_word_like_3'); ?></option>
					<option value="2" <?php if ($session['response']['param']['company']['like'] == 2) { echo "selected"; } ?>><?php echo language('sciomio_word_like_2'); ?></option>
					<option value="1" <?php if ($session['response']['param']['company']['like'] == 1) { echo "selected"; } ?>><?php echo language('sciomio_word_like_1'); ?></option>
				</select>
			</div>
		</fieldset>
        <fieldset class="divider">
            <div class="inputset">
                <label for="ervaringEvenementToelichting"><?php echo language('sciomio_text_company_description'); ?></label>
                <textarea name="description" id="ervaringEvenementToelichting" maxlength="256"><?php echo $session['response']['param']['company']['description']; ?></textarea>
            </div>
        </fieldset>
        <fieldset class="complete">
			<input class="submit" type="submit" value="<?php echo language('sciomio_text_company_wijzigen'); ?>" />
			<div class="cancelbox">
				<?php echo language('sciomio_word_or'); ?> <a href="#" class="cancel close"><?php echo language('sciomio_word_reset'); ?></a>
			</div>
		</fieldset>
	</form>
</div>

