<form id="socialnetwork_new_form" method="post" onSubmit="javascript:ScioMino.SocialNetworkNew.action();return false;">

	<div class="paragraph">
		Nieuw Sociaal Netwerk
	</div>
	<div class="paragraph_shadow">
	<div class="paragraph_line">
		<table class='table_form'>

		<tr><td class='table_form_text'>
			Netwerk (*): 
		</td><td class='table_form_field'>
			<input class='form_input' type="text" name="com_title" size="32" maxsize="127" value="<?php echo $session['response']['param']['prevTitle']; ?>"/> (twitter, facebook, linkedin, hyves)
		</td></tr>

		<tr><td class='table_form_text'>
			Wat deel jij op dit netwerk? (*):
		</td><td class='table_form_field'>
			<input class='form_input' type="text" name="com_description" size="32" maxsize="127" value="<?php echo $session['response']['param']['prevDescription']; ?>"/>
		</td></tr>

		<tr><td class='table_form_text'>
			Verwijzing naar jouw pagina (*):
		</td><td class='table_form_field'>
			<input class='form_input' type="text" name="com_relation-self" size="32" maxsize="127" value="<?php echo $session['response']['param']['prevRelationSelf']; ?>"/>
		</td></tr>

		</table>
	</div>
	</div>

	<div class="paragraph">
		<a class='xcow_link' href='javascript:ScioMino.SocialNetworkNew.action()' title="opslaan">Opslaan</a>
		(<a class='xcow_link' href='javascript:ScioMino.SocialNetworkNew.action_callback("")' title="annuleren">Annuleren</a>)

		<div id="socialNetworkFormWindowAlert">
			(*) zijn verplichte velden.
		</div>
	</div>

</form>

