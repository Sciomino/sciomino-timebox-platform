<div class="metoo-content">
	<div class="header">
		<h3><?php echo language('sciomio_text_ervaring'); ?></h3>
		<h2 class="divider"><?php echo $session['response']['param']['fillTitle']; ?></h2>
	</div>
	<form action="<?php echo $XCOW_B['url'] ?>/snippet/product-new-form" method="post">
        <input type="hidden" value="<?php echo htmlTokens($session['response']['param']['fillTitle']); ?>" name="com_title" />
        <input type="hidden" value="<?php echo htmlTokens($session['response']['param']['fillSubject']); ?>" name="com_subject" />
        <fieldset class="details">
            <dl>
                <dt><?php echo language('sciomio_text_product_subject'); ?></dt>
                <dd><?php echo $session['response']['param']['fillSubject']; ?></dd>
            </dl>
            <div class="inputset specs">
                <label for="type"><?php echo language('sciomio_text_product_alternative'); ?></label>
                <input id="type" type="text" class="text" value="<?php echo htmlTokens($session['response']['param']['fillAlternative']); ?>" name="alternative" maxlength="128" />
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
