<form id="socialnetwork_edit_form" method="post" onSubmit="javascript:ScioMino.SocialNetworkEdit.action();return false;">
	<input class='form_input' type='hidden' name='networkId' value="<?php echo $session['response']['param']['networkId']; ?>">

	<div class="paragraph">
		Bewerk Sociaal Netwerk
	</div>
	<div class="paragraph_shadow">
	<div class="paragraph_line">
		<table class='table_form'>

		<tr><td class='table_form_text'>
			Netwerk (*): 
		</td><td class='table_form_field'>
			<input class='form_input' type="text" name="com_title" size="32" maxsize="127" value="<?php echo $session['response']['param']['network']['title']; ?>"/> (twitter, facebook, linkedin, hyves)
		</td></tr>

		<tr><td class='table_form_text'>
			Wat deel jij op dit netwerk? (*):
		</td><td class='table_form_field'>
			<input class='form_input' type="text" name="com_description" size="32" maxsize="127" value="<?php echo $session['response']['param']['network']['description']; ?>"/>
		</td></tr>

		<tr><td class='table_form_text'>
			Verwijzing naar jouw pagina (*):
		</td><td class='table_form_field'>
			<input class='form_input' type="text" name="com_relation-self" size="32" maxsize="127" value="<?php echo $session['response']['param']['network']['relation-self']; ?>"/>
		</td></tr>

		</table>
	</div>
	</div>

	<div class="paragraph">
		<a class='xcow_link' href='javascript:ScioMino.SocialNetworkEdit.action()' title="opslaan">Opslaan</a>
		(<a class='xcow_link' href='javascript:ScioMino.SocialNetworkEdit.action_callback("")' title="annuleren">Annuleren</a>)

		<div id="socialNetworkFormWindowAlert">
			(*) zijn verplichte velden.
		</div>
	</div>

</form>

