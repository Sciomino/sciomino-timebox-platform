<div class="section puu-send_act">
    <form action="<?php echo $XCOW_B['url'] ?>/snippet/act-mail-new-form" method="post" enctype="multipart/form-data">
	<input class='form_input' type='hidden' name='act' value="<?php echo $session['response']['param']['act']; ?>">
        <fieldset class="simpleForm">
			<label><?php echo language('sciomio_text_act_mail'); ?></label>
			<select name="address[]" data-placeholder="<?php echo language('sciomio_text_act_mail_search'); ?>" multiple="multiple" class="chzn-select puu-suggest puu-suggest_person">
			</select>
            <label for="message-u"><?php echo language('sciomio_text_mail_message'); ?></label>
            <textarea name="com_message" rows="8" cols="40" id="message-u" maxlength="1024"></textarea>
            <input class="submit" type="submit" name="some_name" value="<?php echo language('sciomio_text_mail_toevoegen'); ?>">
        </fieldset>
    </form>
</div>

