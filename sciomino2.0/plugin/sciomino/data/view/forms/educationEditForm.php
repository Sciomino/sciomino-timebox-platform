<div class="metoo-content highlight">
	<div class="header">
		<h3><?php echo language('sciomio_text_ervaring'); ?></h3>
		<h2 class="divider"><?php echo $session['response']['param']['education']['title']; ?></h2>
	</div>
	<form action="<?php echo $XCOW_B['url'] ?>/snippet/education-edit-form" method="post">
	<input class='form_input' type='hidden' name='educationId' value="<?php echo $session['response']['param']['educationId']; ?>">
        <input type="hidden" value="<?php echo htmlTokens($session['response']['param']['education']['title']); ?>" name="com_title" />
        <input type="hidden" value="<?php echo htmlTokens($session['response']['param']['education']['subject']); ?>" name="com_subject" />
        <fieldset class="details">
            <dl>
                <dt><?php echo language('sciomio_text_education_subject'); ?></dt>
                <dd><?php echo $session['response']['param']['education']['subject']; ?></dd>
            </dl>
            <div class="inputset">
                <label for="publisher"><?php echo language('sciomio_text_education_publisher'); ?></label>
                <input id="publisher" type="text" class="text autocomplete" data-results="<?php echo $XCOW_B['url'] ?>/snippet/suggest-local?type=education&subtype=publisher" value="<?php echo htmlTokens($session['response']['param']['education']['publisher']); ?>" name="publisher" maxlength="128" />
                <label for="ervaringOpleidingWanneer"><?php echo language('sciomio_text_education_date'); ?></label>
                <input value="<?php echo htmlTokens($session['response']['param']['education']['date']); ?>" type="text" class="text" name="date" id="ervaringOpleidingWanneer" maxlength="128" />
            </div>
        </fieldset>
		<fieldset class="input-verdict divider">
			<label for="your-verdict"><?php echo language('sciomio_text_education_like'); ?></label>
			<div id="Slider-result" class="slider-result">
				&nbsp;
			</div>
			<div class="sliderbox thumbs-<?php echo (4 - $session['response']['param']['education']['like']) ?>">
				<div id="Slider" class="slider"></div>
				<select name="com_like" id="your-verdict">
					<option value="4" <?php if ($session['response']['param']['education']['like'] == 4) { echo "selected"; } ?>><?php echo language('sciomio_word_like_4'); ?></option>
					<option value="3" <?php if ($session['response']['param']['education']['like'] == 3) { echo "selected"; } ?>><?php echo language('sciomio_word_like_3'); ?></option>
					<option value="2" <?php if ($session['response']['param']['education']['like'] == 2) { echo "selected"; } ?>><?php echo language('sciomio_word_like_2'); ?></option>
					<option value="1" <?php if ($session['response']['param']['education']['like'] == 1) { echo "selected"; } ?>><?php echo language('sciomio_word_like_1'); ?></option>
				</select>
			</div>
		</fieldset>
        <fieldset class="divider">
            <div class="inputset">
                <label for="ervaringOpleidingToelichting"><?php echo language('sciomio_text_education_description'); ?></label>
                <textarea name="description" id="ervaringOpleidingToelichting" maxlength="256"><?php echo $session['response']['param']['education']['description']; ?></textarea>
            </div>
        </fieldset>
        <fieldset>
            <div class="inputset">
                <label for="ervaringOpleidingUrl"><?php echo language('sciomio_text_education_relation-self'); ?></label>
                <input type="url" class="text prefix" name="relation-self" id="ervaringOpleidingUrl" value="<?php echo htmlTokens($session['response']['param']['education']['relation-self']); ?>" maxlength="256" />
            </div>
        </fieldset>
        <fieldset class="complete">
			<input class="submit" type="submit" value="<?php echo language('sciomio_text_education_wijzigen'); ?>" />
			<div class="cancelbox">
				<?php echo language('sciomio_word_or'); ?> <a href="#" class="cancel close"><?php echo language('sciomio_word_reset'); ?></a>
			</div>
		</fieldset>
	</form>
</div>

