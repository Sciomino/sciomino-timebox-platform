<script type="text/javascript" src="/js/utils.js"></script>
<script type="text/javascript" src="/js/XMLexchange.js"></script>
<script type="text/javascript" src="/js/session.js"></script>

<div id="registerWindow">

	<div class="xcow_header">
		<?php echo language(session_header_register); ?>
	</div>

	<div id="registerAlertWindow" class="xcow_emphasis">
	   	<?php echo language($session['response']['param']['status']); ?>
	</div>

	<div class="xcow_paragraph">
		<form id="register_form" method="post" onSubmit="javascript:Session.Register.newAction();return false;">
			<table class='table_form'>

			<tr><td class='table_form_text'>
				<?php echo language(session_text_username); ?>
			</td><td class='table_form_field'>
				<input class="form_input" type="text" name="user" size="32" maxsize="127" value="<?php echo $session['response']['param']['prevName']; ?>"/>
				<br/>
				<?php echo language(session_text_userdescription); ?>
			</td></tr>

			<tr><td class='table_form_text'>
				<?php echo language(session_text_userpass); ?>
			</td><td class='table_form_field'>
				<input class="form_input" type="password" name="pass" size="32" maxsize="127" />
			</td></tr>

			<tr><td class='table_form_text'>
				<?php echo language(session_text_usermail); ?>
			</td><td class='table_form_field'>
				<input class="form_input" type="text" name="mail" size="32" maxsize="256" value="<?php echo $session['response']['param']['prevMail']; ?>"/>
			</td></tr>

			<tr><td class='table_form_button' colspan="2">
				<a class='xcow_link' href='javascript:Session.Register.newAction()'><?php echo language(session_word_register); ?></a>
			</td></tr>

			</table>
		</form>
	</div>
</div>
