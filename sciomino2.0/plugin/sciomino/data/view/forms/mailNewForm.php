<div class="section">
    <form action="<?php echo $XCOW_B['url'] ?>/snippet/mail-new-form" method="post">
	<input class='form_input' type='hidden' name='addressString' value="<?php echo $session['response']['param']['addressString']; ?>">
	<div>
		<?php
		$languageTemplate = array();
		$languageTemplate['count'] = $session['response']['param']['count'];
		echo language_template('sciomio_text_mail', $languageTemplate);
		if ($session['response']['param']['count'] < $session['response']['param']['total']) {
			$languageTemplate = array();
			$languageTemplate['limit'] = $session['response']['param']['limit'];
			$languageTemplate['total'] = $session['response']['param']['total'];
			echo "<br/>".language_template('sciomio_text_mail_max', $languageTemplate);
		}
		?>
	</div>
        <fieldset class="simpleForm">
            <label for="message-u"><?php echo language('sciomio_text_mail_message'); ?></label>
            <textarea name="com_message" rows="8" cols="40" id="com_message" maxlength="1024"></textarea>
            <input class="submit" type="submit" name="some_name" value="<?php echo language('sciomio_text_mail_toevoegen'); ?>">
        </fieldset>
    </form>
</div>
