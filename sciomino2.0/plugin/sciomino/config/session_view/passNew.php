<div id="passwordWindow">

	<div class="xcow_paragraph_reverse">
	<form id="pass_form" method="post" onSubmit="javascript:Session.Password.newAction();return false;">
		<input type="hidden" name="redirect" value="<? echo $session['response']['param']['redirect']; ?>" />

		<table class='table_form'>

		<tr><td>
		</td><td>
			<div class="xcow_header xcow_extra_space">
				<h2><?php echo language(session_header_passnew); ?></h2>
				<br/>
			</div>
			<div id="passwordAlertWindow" class="xcow_emphasis xcow_extra_space">
				<?php echo language($session['response']['param']['status']); ?>
			</div>
		</td></tr>

		<tr><td class='table_form_text'>
		</td><td class='table_form_field xcow_extra_space'>
			<span class="form_text_small"><?php echo language(session_text_usermail); ?></span>
			<input class="form_input input_space" type="text" name="mail" size="32" maxlength="128" value="" tabindex="1"/>
		</td></tr>

		<tr><td>
		</td><td class='table_form_button xcow_extra_space'>
			<input name="submit" class="form_button input_button input_button_inverse input_space" type="submit" value="<?php echo language(session_word_pass); ?>" tabindex="2"/>
		</td></tr>

			<tr><td>
			</td><td class='table_form_button xcow_extra_space'>
				<p><a class="form_text_small" href="javascript:Session.Login.load()"><?php echo language(session_word_cancel); ?></a></p>
			</td></tr>

		</table>
	</form>
	</div>

</div>

