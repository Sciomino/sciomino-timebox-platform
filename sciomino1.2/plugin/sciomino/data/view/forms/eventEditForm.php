<div class="metoo-content highlight">
	<div class="header">
		<h3><?php echo language('sciomio_text_ervaring'); ?></h3>
		<h2 class="divider"><?php echo $session['response']['param']['event']['title']; ?></h2>
	</div>
	<form action="<?php echo $XCOW_B['url'] ?>/snippet/event-edit-form" method="post">
	<input class='form_input' type='hidden' name='eventId' value="<?php echo $session['response']['param']['eventId']; ?>">
        <input type="hidden" value="<?php echo htmlTokens($session['response']['param']['event']['title']); ?>" name="com_title" />
        <input type="hidden" value="<?php echo htmlTokens($session['response']['param']['event']['subject']); ?>" name="com_subject" />
        <fieldset class="details">
            <dl>
                <dt><?php echo language('sciomio_text_event_subject'); ?></dt>
                <dd><?php echo $session['response']['param']['event']['subject']; ?></dd>
            </dl>
            <div class="inputset">
                <label for="publisher"><?php echo language('sciomio_text_event_publisher'); ?></label>
                <input id="publisher" type="text" class="text autocomplete" data-results="<?php echo $XCOW_B['url'] ?>/snippet/suggest-local?type=event&subtype=publisher" value="<?php echo htmlTokens($session['response']['param']['event']['publisher']); ?>" name="publisher" maxlength="128" />
                <label for="ervaringEvenementWanneer"><?php echo language('sciomio_text_event_date'); ?></label>
                <input value="<?php echo htmlTokens($session['response']['param']['event']['date']); ?>" type="text" class="text" name="date" id="ervaringEvenementWanneer" maxlength="128" />
            </div>
        </fieldset>
		<fieldset class="input-verdict divider">
			<label for="your-verdict"><?php echo language('sciomio_text_event_like'); ?></label>
			<div id="Slider-result" class="slider-result">
				&nbsp;
			</div>
			<div class="sliderbox thumbs-<?php echo (4 - $session['response']['param']['event']['like']) ?>">
				<div id="Slider" class="slider"></div>
				<select name="com_like" id="your-verdict">
					<option value="4" <?php if ($session['response']['param']['event']['like'] == 4) { echo "selected"; } ?>><?php echo language('sciomio_word_like_4'); ?></option>
					<option value="3" <?php if ($session['response']['param']['event']['like'] == 3) { echo "selected"; } ?>><?php echo language('sciomio_word_like_3'); ?></option>
					<option value="2" <?php if ($session['response']['param']['event']['like'] == 2) { echo "selected"; } ?>><?php echo language('sciomio_word_like_2'); ?></option>
					<option value="1" <?php if ($session['response']['param']['event']['like'] == 1) { echo "selected"; } ?>><?php echo language('sciomio_word_like_1'); ?></option>
				</select>
			</div>
		</fieldset>
        <fieldset class="divider">
            <div class="inputset">
                <label for="ervaringEvenementToelichting"><?php echo language('sciomio_text_event_description'); ?></label>
                <textarea name="description" id="ervaringEvenementToelichting" maxlength="256"><?php echo $session['response']['param']['event']['description']; ?></textarea>
            </div>
        </fieldset>
        <fieldset>
            <div class="inputset">
                <label for="ervaringEvenementUrl"><?php echo language('sciomio_text_event_relation-self'); ?></label>
                <input type="url" class="text prefix" name="relation-self" id="ervaringEvenementUrl" value="<?php echo htmlTokens($session['response']['param']['event']['relation-self']); ?>" maxlength="256" />
            </div>
        </fieldset>
        <fieldset class="complete">
			<input class="submit" type="submit" value="<?php echo language('sciomio_text_event_wijzigen'); ?>" />
			<div class="cancelbox">
				<?php echo language('sciomio_word_or'); ?> <a href="#" class="cancel close"><?php echo language('sciomio_word_reset'); ?></a>
			</div>
		</fieldset>
	</form>
</div>
