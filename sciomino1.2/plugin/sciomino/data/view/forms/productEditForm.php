<div class="metoo-content">
	<div class="header">
		<h3><?php echo language('sciomio_text_ervaring'); ?></h3>
		<h2 class="divider"><?php echo $session['response']['param']['product']['title']; ?></h2>
	</div>
	<form action="<?php echo $XCOW_B['url'] ?>/snippet/product-edit-form" method="post">
	<input class='form_input' type='hidden' name='productId' value="<?php echo $session['response']['param']['productId']; ?>">
        <input type="hidden" value="<?php echo htmlTokens($session['response']['param']['product']['title']); ?>" name="com_title" />
        <input type="hidden" value="<?php echo htmlTokens($session['response']['param']['product']['subject']); ?>" name="com_subject" />
        <fieldset class="details">
            <dl>
                <dt><?php echo language('sciomio_text_product_subject'); ?></dt>
                <dd><?php echo $session['response']['param']['product']['subject']; ?></dd>
            </dl>
            <div class="inputset specs">
                <label for="type"><?php echo language('sciomio_text_product_alternative'); ?></label>
                <input id="type" type="text" class="text" value="<?php echo $session['response']['param']['product']['alternative']; ?>" name="alternative" maxlength="128" />
            </div>
        </fieldset>
		<fieldset class="radio emph divider">
			<input class="radio" type="radio" name="com_has" id="ownership-current" value="1" <?php if ($session['response']['param']['product']['has'] == 1) { echo "checked='checked'"; } ?>/>
			<label for="ownership-current"><?php echo language('sciomio_word_has_1'); ?></label>
			<input class="radio" type="radio" name="com_has" id="ownership-past" value="2" <?php if ($session['response']['param']['product']['has'] == 2) { echo "checked='checked'"; } ?>/>
			<label for="ownership-past"><?php echo language('sciomio_word_has_2'); ?></label>
			<!-- <input class="radio" type="radio" name="com_has" id="ownership-want" value="3" <?php if ($session['response']['param']['product']['has'] == 3) { echo "checked='checked'"; } ?>/>
			<label for="ownership-want"><?php echo language('sciomio_word_has_3'); ?></label>-->
		</fieldset>
		<fieldset class="input-verdict divider">
			<label for="your-verdict"><?php echo language('sciomio_text_product_like'); ?></label>
			<div id="Slider-result" class="slider-result">
				&nbsp;
			</div>
			<div class="sliderbox thumbs-<?php echo (4 - $session['response']['param']['product']['like']) ?>">
				<div id="Slider" class="slider"></div>
				<select name="com_like" id="your-verdict">
					<option value="4" <?php if ($session['response']['param']['product']['like'] == 4) { echo "selected"; } ?>><?php echo language('sciomio_word_like_4'); ?></option>
					<option value="3" <?php if ($session['response']['param']['product']['like'] == 3) { echo "selected"; } ?>><?php echo language('sciomio_word_like_3'); ?></option>
					<option value="2" <?php if ($session['response']['param']['product']['like'] == 2) { echo "selected"; } ?>><?php echo language('sciomio_word_like_2'); ?></option>
					<option value="1" <?php if ($session['response']['param']['product']['like'] == 1) { echo "selected"; } ?>><?php echo language('sciomio_word_like_1'); ?></option>
				</select>
			</div>
		</fieldset>
		<fieldset class="review group divider">
			<div class="unit unit1-2">
				<label><?php echo language('sciomio_text_product_plus'); ?></label>
				<ul class="ftw">
					<li class="inputset">
						<input maxlength="64" class="text" type="text" name="positive1" id="" value="<?php echo htmlTokens($session['response']['param']['product']['positive1']); ?>" />
					</li>
					<li class="inputset">
						<input maxlength="64" class="text" type="text" name="positive2" id="" value="<?php echo htmlTokens($session['response']['param']['product']['positive2']); ?>" />
					</li>
					<li class="inputset">
						<input maxlength="64" class="text" type="text" name="positive3" id="" value="<?php echo htmlTokens($session['response']['param']['product']['positive3']); ?>" />
					</li>
				</ul>
			</div>
			<div class="unit unit1-2 last">
				<label><?php echo language('sciomio_text_product_min'); ?></label>
				<ul class="fail">
					<li class="inputset">
						<input maxlength="64" class="text" type="text" name="negative1" id="" value="<?php echo htmlTokens($session['response']['param']['product']['negative1']); ?>" />
					</li>
					<li class="inputset">
						<input maxlength="64" class="text" type="text" name="negative2" id="" value="<?php echo htmlTokens($session['response']['param']['product']['negative2']); ?>" />
					</li>
					<li class="inputset">
						<input maxlength="64" class="text" type="text" name="negative3" id="" value="<?php echo htmlTokens($session['response']['param']['product']['negative3']); ?>" />
					</li>
				</ul>
			</div>
		</fieldset>
        <fieldset class="complete">
			<input class="submit" type="submit" value="<?php echo language('sciomio_text_product_wijzigen'); ?>" />
			<div class="cancelbox">
				<?php echo language('sciomio_word_or'); ?> <a href="#" class="cancel close"><?php echo language('sciomio_word_reset'); ?></a>
			</div>
		</fieldset>
	</form>
</div>
