<script type="text/javascript" src="/js/utils.js"></script>
<script type="text/javascript" src="/js/XMLexchange.js"></script>
<script type="text/javascript" src="/js/session.js"></script>

<div id="passwordWindow">

	<div class="xcow_header">
		<?php echo language(session_header_passnew); ?>
	</div>

	<div id="passwordAlertWindow" class="xcow_emphasis">
	   	<?php echo language($session['response']['param']['status']); ?>
	</div>

	<div class="xcow_paragraph">
		<form id="pass_form" method="post" onSubmit="javascript:Session.Password.newAction();return false;">
			<table class='table_form'>

			<tr><td class='table_form_text'>
				<?php echo language(session_text_usermail); ?>
			</td><td class='table_form_field'>
				<input class='form_input' type="text" name="mail" size="32" maxsize="256" value="<?php echo $session['response']['param']['prevMail']; ?>"/>
			</td></tr>

			<tr><td class='table_form_button' colspan="2">
				<a class='xcow_link' href='javascript:Session.Password.newAction()'><?php echo language(session_word_pass); ?></a>
			</td></tr>

			</table>
		</form>
	</div>

</div>

