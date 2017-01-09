<div id="passwordWindow" style="position:relative;">

	<div class="xcow_paragraph">
		<!--<form id="pass_form" method="post" onSubmit="javascript:Session.Password.updateAction();return false;">-->
		<form id="pass_form" method="post" action="/snippet/account-delete">
			<input type="hidden" value="<?php echo $session['time']; ?>" name="go" />
			<table class='table_form'>

			</td><td>
				<div class="xcow_header xcow_extra_space">
					<p><?php echo language(sciomio_text_account_delete); ?></p>
				</div>
			</td></tr>

			</td><td class='table_form_button xcow_extra_space'>
				<a href=""><?php echo language(sciomio_word_reset); ?></a>
				<input name="submit" class="form_button" type="submit" value="<?php echo language(sciomio_word_account_delete); ?>" tabindex="4"/>
			</td></tr>

			</table>
		</form>
	</div>

</div>
