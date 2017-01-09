<div id="passwordWindow" style="position:relative;">

	<div class="xcow_paragraph">
		<form id="pass_form" method="post" onSubmit="javascript:Session.Password.updateAction();return false;">
		<!--<form id="pass_form" method="post" action="/session/passUpdate">-->
			<table class='table_form'>

			<tr><td>
			</td><td>
				<div id="passwordAlertWindow" class="xcow_emphasis xcow_extra_space">
				   	<p style="color:red"><?php echo language($session['response']['param']['status']); ?></p>
				</div>
			</td></tr>

			<tr><td class='table_form_text'>
				<label for="oldPass"><?php echo language(session_text_oldpass); ?></label>
			</td><td class='table_form_field xcow_extra_space'>
				<input class='form_input' type="password" name="passOld" size="32" maxlength="128" tabindex="1"/>
			</td></tr>

			<tr><td class='table_form_text'>
				<label for="verifyPass"><?php echo language(session_text_newpass); ?></label>
			</td><td class='table_form_field xcow_extra_space'>
				<input class='form_input' type="password" name="passNew1" size="32" maxlength="128" tabindex="2"/>
			</td></tr>

			<tr><td class='table_form_text'>
				<label for="firstName"><?php echo language(session_text_verifypass); ?></label>
			</td><td class='table_form_field xcow_extra_space'>
				<input class='form_input' type="password" name="passNew2" size="32" maxlength="128" tabindex="3"/>
			</td></tr>

			<tr><td>
			</td><td class='table_form_button xcow_extra_space'>
				<input name="submit" class="form_button" type="submit" value="<?php echo language(session_word_passupdate); ?>" tabindex="4"/>
			</td></tr>

			</table>
		</form>
	</div>

</div>
