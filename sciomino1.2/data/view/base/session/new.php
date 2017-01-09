<div id="registerWindow">

	<div class="xcow_paragraph_reverse">
		<form id="register_form" method="post" onSubmit="javascript:Session.Register.newAction();return false;">
			<input type="hidden" name="go" value="1">
			<table class='table_form'>

			<tr><td>
			</td><td>
				<div class="xcow_header xcow_extra_space">
					<p style="float:right;"><?php echo language(session_text_register_alternative); ?></p>
					<h2><?php echo language(session_header_register); ?></h2>
					<br clear="right"/>
				</div>
				<div id="registerAlertWindow" class="xcow_emphasis xcow_extra_space">
				   	<?php echo language($session['response']['param']['status']); ?>
				</div>
			</td></tr>

			<tr><td class='table_form_text'>
			</td><td class='table_form_field xcow_extra_space'>
				<span class="form_text_small"><?php echo language(session_text_username); ?></span>
				<input class="form_input input_space" type="text" name="user" size="32" maxsize="127" value="<?php echo $session['response']['param']['prevName']; ?>" tabindex="1"/>
			</td></tr>

			<tr><td class='table_form_text'>
			</td><td class='table_form_field xcow_extra_space'>
				<span class="form_text_small"><?php echo language(session_text_userpass); ?></span>
				<input class="form_input input_space" type="password" name="pass" size="32" maxsize="127" tabindex="2"/>
			</td></tr>

			<tr><td>
			</td><td class='table_form_button xcow_extra_space'>
				<input name="submit" class="form_button input_button input_space" type="submit" value="<?php echo language(session_word_register); ?>" tabindex="3"/>
			</td></tr>

			<tr><td>
			</td><td class='table_form_button xcow_extra_space'>
				<p class="form_text_small"><?php echo language('session_text_register_terms'); ?></p>
			</td></tr>

			</table>
		</form>
	</div>
</div>

