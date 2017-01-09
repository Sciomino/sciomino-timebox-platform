<div class="metoo-content">
	<div class="header">
		<h3><?php echo language('sciomio_text_ervaring'); ?></h3>
		<h2 class="divider"><?php echo $session['response']['param']['fillTitle']; ?></h2>
	</div>
	<form action="<?php echo $XCOW_B['url'] ?>/snippet/education-new-form" method="post">
        <input type="hidden" value="<?php echo htmlTokens($session['response']['param']['fillTitle']); ?>" name="com_title" />
        <input type="hidden" value="<?php echo htmlTokens($session['response']['param']['fillSubject']); ?>" name="com_subject" />
        <fieldset class="details">
            <dl>
                 <dt><?php echo language('sciomio_text_education_subject'); ?></dt>
                <dd><?php echo $session['response']['param']['fillSubject']; ?></dd>
            </dl>
            <div class="inputset specs">
                <label for="publisher"><?php echo language('sciomio_text_education_publisher'); ?></label>
                <input id="publisher" type="text" class="text autocomplete" data-results="<?php echo $XCOW_B['url'] ?>/snippet/suggest-local?type=education&subtype=publisher" value="<?php echo htmlTokens($session['response']['param']['fillPublisher']); ?>" name="publisher" maxlength="128" />
                <label for="type"><?php echo language('sciomio_text_education_date'); ?></label>
                <input id="type" type="text" class="text" value="" name="date" maxlength="128" />
            </div>
        </fieldset>
		<fieldset class="input-verdict divider">
			<label for="your-verdict"><?php echo language('sciomio_text_education_like'); ?></label>
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
            <label for="toelichting"><?php echo language('sciomio_text_education_description'); ?></label>
            <textarea name="description" id="toelichting" maxlength="256"></textarea>
        </fieldset>
        <fieldset>
            <label for="opl-more-info"><?php echo language('sciomio_text_education_relation-self'); ?></label>
            <input type="text" class="text" value="" name="relation-self" id="opl-more-info" maxlength="256" />
        </fieldset>
        <fieldset class="complete">
			<input class="submit" type="submit" value="<?php echo language('sciomio_text_education_toevoegen'); ?>" />
			<div class="cancelbox">
				<?php echo language('sciomio_word_or'); ?> <a href="#" class="cancel close"><?php echo language('sciomio_word_reset'); ?></a>
			</div>
		</fieldset>
	</form>
</div>

