<div class="xcow_login" id="loginWindow">

	<form id="login_form" method="post" onSubmit="javascript:Session.Login.newAction();return false;">
		<input type="hidden" name="redirect" value="<? echo $session['response']['param']['redirect']; ?>" />

		<table class='table_form'>

		<tr><td class='table_form_field'>
			<p><?php echo language(session_text_username); ?></p>
			<input id='userInputField' class='form_input_small' type="text" name="user" size="32" maxsize="127" value="" tabindex="1"/>
		</td>
		</tr><tr>
		<td class='table_form_field'>
			<p><?php echo language(session_text_userpass); ?></p>
			<input id='passInputField' class='form_input_small' type="password" name="pass" size="32" maxsize="127" value="" tabindex="2"/>
		</td>
		</tr><tr>
		<td class='table_form_button'>
			<p>&nbsp;</p>
			<input class="form_button" type="submit" value="<?php echo language(session_word_login); ?>" tabindex="3"/>
		</td></tr>

		</table>
	</form>


</div>
