<div id="loginWindow">

	<div class="xcow_paragraph_reverse">
	<form id="login_form" method="post" onSubmit="javascript:Session.Login.newAction();return false;">
		<input type="hidden" name="redirect" value="<? echo $session['response']['param']['redirect']; ?>" />

		<table class='table_form'>

		<tr><td>
		</td><td>
			<div class="xcow_header xcow_extra_space">
				<?php
				if ($XCOW_B['sciomino']['skin-register'] == "yes") {
					echo "<p style='float:right;'>".language(session_text_login_alternative)."</p>";
				}
				?>
				<h2><?php echo language(session_header_login); ?></h2>
				<br clear="right"/>
			</div>
			<div id="loginAlertWindow" class="xcow_emphasis xcow_extra_space">
				<?php echo language($session['response']['param']['status']); ?>
			</div>
		</td></tr>

		<tr><td class='table_form_text'>
		</td><td class='table_form_field xcow_extra_space'>
			<span class="form_text_small"><?php echo language(session_text_username); ?></span>
			<input class="form_input input_space" type="text" name="user" size="32" maxlength="128" value="<?php echo $session['response']['param']['prevName']; ?>" tabindex="1"/>
		</td></tr>

		<tr><td class='table_form_text'>
		</td><td class='table_form_field xcow_extra_space'>
			<span class="form_text_small"><?php echo language(session_text_userpass); ?></span>
			<input id='passInputField' class='form_input input_space' type="password" name="pass" size="32" maxlength="128" value="" tabindex="2"/>
		</td></tr>

		<tr><td>
		</td><td class='table_form_button xcow_extra_space'>
			<input name="submit" class="form_button input_button input_button_inverse input_space" type="submit" value="<?php echo language(session_word_login); ?>" tabindex="3"/>
		</td></tr>

			<tr><td>
			</td><td class='table_form_button xcow_extra_space'>
				<p><a class="form_text_small" href="javascript:Session.Password.load()"><?php echo language(session_word_forgetpass); ?></a></p>
			</td></tr>

		</table>
	</form>
	</div>

</div>
