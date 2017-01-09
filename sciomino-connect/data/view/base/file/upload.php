<div id="uploadWindow">

	<div class="xcow_header">
		<?php echo language(base_header_file_upload); ?>
	</div>

	<div class="xcow_emphasis">
	   	<?php echo language($session['response']['param']['status']); ?>
		<?php
			$file = $session['response']['param']['theFile'];
			if ($file != "") {
			  echo "<br/><a href='$file'>$file<a/>\n";
			}
		?>
	</div>

	<div class="xcow_paragraph">
		<form id="upload_form" method="post" enctype="multipart/form-data" action="/file/upload">
			<input type="hidden" name="MAX_FILE_SIZE" value=" <?php echo $session['response']['param']['max_upload']; ?> " />
  			<input type="hidden" name="flag" value="1" />
			<table class='table_form'>

			<tr><td class='table_form_text'>
				<?php echo language(base_text_file_file); ?>
			</td><td class='table_form_field'>
				<input class="form_input" type="file" name="file" size="32"/>
			</td></tr>


			<tr><td class='table_form_button' colspan="2">
				<input type="submit" value="<?php echo language(base_word_file_upload); ?>" />
			</td></tr>

			</table>
		</form>
	</div>
</div>

