<script type="text/javascript" src="/js/utils.js"></script>
<script type="text/javascript" src="/js/XMLexchange.js"></script>
<script type="text/javascript" src="/js/session.js"></script>

<div id="loginWindow">

	<div class="xcow_header">
		<?php echo language(session_header_login); ?>
	</div>

	<div id="loginAlertWindow" class="xcow_emphasis">
		<?php echo language($session['response']['param']['status']); ?>
	</div>

	<div class="xcow_paragraph">
		<form id="login_form" method="post" onSubmit="javascript:Session.Login.newAction();return false;">
			<input type="hidden" name="redirect" value="<? echo $session['response']['param']['redirect']; ?>" />

			<table class='table_form'>

			<tr><td class='table_form_text'>
				<?php echo language(session_text_username); ?>
			</td><td class='table_form_field'>
				<input class='form_input' type="text" name="user" size="32" maxsize="127" value="<?php echo $session['response']['param']['prevName']; ?>"/>
			</td></tr>

			<tr><td class='table_form_text'>
				<?php echo language(session_text_userpass); ?>
			</td><td class='table_form_field'>
				<input class='form_input' type="password" name="pass" size="32" maxsize="127" />
			</td></tr>

			<tr><td class='table_form_button' colspan="2">
				<a class='xcow_link' href="javascript:Session.Password.load()"><?php echo language(session_word_forgetpass); ?></a> | 
				<a class='xcow_link' href='javascript:Session.Login.newAction()'><?php echo language(session_word_login); ?></a>
			</td></tr>

			</table>
		</form>
	</div>

</div>
