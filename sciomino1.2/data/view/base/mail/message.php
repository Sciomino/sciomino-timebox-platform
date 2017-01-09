<div id="mailWindow">

	<div class="xcow_header">
		<?php echo language(base_header_mail_message); ?>
	</div>

	<div class="xcow_emphasis">
	   	<?php echo language($session['response']['param']['status']); ?>
	</div>

	<div class="xcow_paragraph">
		<form id="mail_form" method="post" enctype="multipart/form-data" action="/mail/message">
   			<input type="hidden" name="flag" value="1" />
			<table class='table_form'>

			<tr><td class='table_form_text'>
				<?php echo language(base_text_mail_username); ?>
			</td><td class='table_form_field'>
				<input class="form_input" type="text" name="sender_name" size="32" maxsize="127" value="<?php echo $session['response']['param']['prevSenderName']; ?>"/>
			</td></tr>

			<tr><td class='table_form_text'>
				<?php echo language(base_text_mail_usermail); ?>
			</td><td class='table_form_field'>
				<input class="form_input" type="text" name="sender_mail" size="32" maxsize="256" value="<?php echo $session['response']['param']['prevSenderMail']; ?>"/>
			</td></tr>


			<tr><td class='table_form_text'>
				<?php echo language(base_text_mail_subject); ?>
			</td><td class='table_form_field'>
				<input class="form_input" type="text" name="subject" size="32" maxsize="256" value="<?php echo $session['response']['param']['prevSubject']; ?>"/>
			</td></tr>

			<tr><td class='table_form_text'>
				<?php echo language(base_text_mail_message); ?>
			</td><td class='table_form_field'>
<textarea class='form_area' name="body" rows="3" cols="72" />
<?php echo $session['response']['param']['prevDescription']; ?>
</textarea>
			</td></tr>

			<tr><td class='table_form_button' colspan="2">
				<input type="submit" value="<?php echo language(base_word_mail_send); ?>" />
			</td></tr>

			</table>
		</form>
	</div>
</div>

