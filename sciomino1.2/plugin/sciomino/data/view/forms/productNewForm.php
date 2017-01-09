<div class="metoo-content">
	<div class="header">
		<h3><?php echo language('sciomio_text_ervaring'); ?></h3>
		<h2 class="divider"><?php echo language('sciomio_text_product'); ?></h2>
	</div>
	<form action="<?php echo $XCOW_B['url'] ?>/snippet/product-new-form" method="post">
		<?php
		if ($session['response']['param']['missing'] == 1 && $session['response']['param']['go'] == 1) {
			echo "<!--ERROR_MISSING_FIELDS-->\n";
		}
		?>
        <input type="hidden" value="1" name="go" />
        <fieldset class="details">
            <div class="inputset specs <?php if ($session['response']['param']['prevSubject'] == "" && $session['response']['param']['go'] == 1) { echo "error"; } ?>">
                <label for="subjectInput"><?php echo language('sciomio_text_product_subject'); ?></label>
                <input id="subjectInput" type="text" class="text autocomplete" data-results="<?php echo $XCOW_B['url'] ?>/snippet/suggest-local?type=product" value="<?php echo htmlTokens($session['response']['param']['prevSubject']); ?>" name="com_subject" maxlength="128" />
            </div>
            <div class="inputset specs <?php if ($session['response']['param']['prevTitle'] == "" && $session['response']['param']['go'] == 1) { echo "error"; } ?>">
                <label for="titleInput"><?php echo language('sciomio_text_product_title'); ?></label>
                <input id="titleInput" type="text" class="text autocomplete" data-results="<?php echo $XCOW_B['url'] ?>/snippet/suggest-local?type=product&subtype=" data-results-input="subjectInput" value="<?php echo htmlTokens($session['response']['param']['prevTitle']); ?>" name="com_title" maxlength="128" />
            </div>
            <div class="inputset specs <?php if ($session['response']['param']['prevAlternative'] == "" && $session['response']['param']['go'] == 1) { echo "error"; } ?>">
                <label for="type"><?php echo language('sciomio_text_product_alternative'); ?></label>
                <input id="type" type="text" class="text" value="<?php echo htmlTokens($session['response']['param']['prevAlternative']); ?>" name="alternative" maxlength="128" />
            </div>
        </fieldset>
		<fieldset class="radio emph divider">
			<input class="radio" type="radio" name="com_has" id="ownership-current" value="1"/>
			<label for="ownership-current"><?php echo language('sciomio_word_has_1'); ?></label>
			<input class="radio" type="radio" name="com_has" id="ownership-past" value="2"/>
			<label for="ownership-past"><?php echo language('sciomio_word_has_2'); ?></label>
			<!--<input class="radio" type="radio" name="com_has" id="ownership-want" value="3"/>
			<label for="ownership-want"><?php echo language('sciomio_word_has_3'); ?></label>-->
		</fieldset>
		<fieldset class="input-verdict divider">
			<label for="your-verdict"><?php echo language('sciomio_text_product_like'); ?></label>
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
		<fieldset class="review group divider">
			<div class="unit unit1-2">
				<label><?php echo language('sciomio_text_product_plus'); ?></label>
				<ul class="ftw">
					<li class="inputset">
						<input maxlength="64" class="text" type="text" name="positive1" id="" />
					</li>
					<li class="inputset">
						<input maxlength="64" class="text" type="text" name="positive2" id="" />
					</li>
					<li class="inputset">
						<input maxlength="64" class="text" type="text" name="positive3" id="" />
					</li>
				</ul>
			</div>
			<div class="unit unit1-2 last">
				<label><?php echo language('sciomio_text_product_min'); ?></label>
				<ul class="fail">
					<li class="inputset">
						<input maxlength="64" class="text" type="text" name="negative1" id="" />
					</li>
					<li class="inputset">
						<input maxlength="64" class="text" type="text" name="negative2" id="" />
					</li>
					<li class="inputset">
						<input maxlength="64" class="text" type="text" name="negative3" id="" />
					</li>
				</ul>
			</div>
		</fieldset>
        <fieldset class="complete">
			<input class="submit" type="submit" value="<?php echo language('sciomio_text_product_toevoegen'); ?>" />
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
