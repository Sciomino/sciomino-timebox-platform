<script type="text/javascript" src="/js/utils.js"></script>
<script type="text/javascript" src="/js/XMLexchange.js"></script>
<script type="text/javascript" src="/js/session.js"></script>

<div id="passwordWindow" style="position:relative;">

	<div class="xcow_header">
		<?php echo language(session_header_passupdate); ?>
	</div>

	<div id="passwordAlertWindow" class='xcow_emphasis'>
	   	<?php echo language($session['response']['param']['status']); ?>
	</div>

	<div class="xcow_paragraph">
		<form id="pass_form" method="post" onSubmit="javascript:Session.Password.updateAction();return false;">
			<table class='table_form'>

			<tr><td class='table_form_text'>
				<?php echo language(session_text_oldpass); ?>
			</td><td class='table_form_field'>
				<input class='form_input' type="password" name="passOld" size="32" maxsize="127" />
			</td></tr>

			<tr><td class='table_form_text'>
				<?php echo language(session_text_newpass); ?>
			</td><td class='table_form_field'>
				<input class='form_input' type="password" name="passNew1" size="32" maxsize="127" />
			</td></tr>

			<tr><td class='table_form_text'>
				<?php echo language(session_text_verifypass); ?>
			</td><td class='table_form_field'>
				<input class='form_input' type="password" name="passNew2" size="32" maxsize="127" />
			</td></tr>

			<tr><td class='table_form_button' colspan="2">
				<a class='xcow_link' href='javascript:Session.Password.updateAction()'><?php echo language(session_word_passupdate); ?></a>
			</td></tr>

			</table>
		</form>
	</div>

</div>
