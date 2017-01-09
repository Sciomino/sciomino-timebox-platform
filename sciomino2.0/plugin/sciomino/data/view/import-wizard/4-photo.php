<div id="wizardWindow" style="text-align:left;width:600px">

	<h2><?php echo language(sciomio_header_wizard_4); ?></h2>

	<div>
		<form id="photo_form" action="javascript:ScioMino.Wizard.actionPhoto(4);" method="post" enctype="multipart/form-data">
			
			<input class='form_input' type='hidden' name='go' value="1">
			<input class='form_input' type='hidden' name='photo' value="<?php echo $session['response']['param']['user']['photo']; ?>">

			<table class='table_form'>

			<tr><td rowspan="2" class='table_form_text'>
				<?php
					if (! isset($session['response']['param']['user']['photo'])) { $session['response']['param']['user']['photo'] = "/ui/gfx/photo.jpg"; }
					else { $session['response']['param']['user']['photo'] = str_replace("/upload/","/upload/96x96_",$session['response']['param']['user']['photo']); }
					echo "<img src='".$XCOW_B['url'].$session['response']['param']['user']['photo']."' width='96' height='96' alt='' />";
				?>
			</td><td class='table_form_field'>
				<div class="inputset">
				<?php echo language('sciomio_text_wizard_photo'); ?>
				<br/>
				<input class='file' type='file' name='file' size='22' id='photo'/>
				</div>
			</td></tr>

			<tr><td class='table_form_button'>
				<div class="inputset">
					<input name="bnsubmit" class="form_button" type="submit" value="<?php echo language(sciomio_word_photo_upload); ?>" />
					<div id="wizardAlertWindow">
						<?php echo language($session['response']['param']['status']); ?>
					</div>
				</div>
			</td></tr>

			</table>
		</form>
	</div>
	

<div style="width:100%;valign:center;">
	<a title="Happy digging!" style="width:200px;float:right;text-decoration:none;padding-top:10px;" class="form_button input_button input_space" href="/"><?php echo language(sciomio_word_finish); ?></a>
</div>

</div>

