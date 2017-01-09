<form id="act_close_edit_form"  class="puu-result" action="javascript:ScioMino.Act.closeEdit();" method="post" enctype="multipart/form-data">
	<input type="hidden" name="com_act" value="<?php echo $session['response']['param']['parent'] ?>">
	<input type="hidden" name="com_story" value="<?php echo $session['response']['param']['story']['Id'] ?>">
	<input class='form_input' type='hidden' name='photo' value="<?php echo $session['response']['param']['story']['photo']; ?>">
	<div class="puu-content">
		<a href="dmy" class="puu-close"><?php echo language('sciomio_header_act_top'); ?></a>
		<fieldset class="puu-satisfaction">
			<legend><?php echo language('sciomio_text_act_close'); ?></legend>
			<div class="puu-textual">
				<label for="exp"><?php echo language('sciomio_text_act_close_ervaring'); ?></label>
				<div id="Slider-result" class="slider-result">
					&nbsp;
				</div>
				<div class="sliderbox thumbs-<?php echo (4 - $session['response']['param']['story']['like']) ?>">
					<div id="Slider" class="slider"></div>
					<select name="com_like" id="your-verdict">
						<option value="4" <?php if ($session['response']['param']['story']['like'] == 4) { echo "selected"; } ?>><?php echo language('sciomio_word_like_4'); ?></option>
						<option value="3" <?php if ($session['response']['param']['story']['like'] == 3) { echo "selected"; } ?>><?php echo language('sciomio_word_like_3'); ?></option>
						<option value="2" <?php if ($session['response']['param']['story']['like'] == 2) { echo "selected"; } ?>><?php echo language('sciomio_word_like_2'); ?></option>
						<option value="1" <?php if ($session['response']['param']['story']['like'] == 1) { echo "selected"; } ?>><?php echo language('sciomio_word_like_1'); ?></option>
					</select>
				</div>
				<div class="puu-tell">
					<label><?php echo language('sciomio_text_act_close_story'); ?>
					<span class="puu-motivate"><?php echo language('sciomio_text_act_close_story_description'); ?></span></label>
					<textarea name="com_description" maxlength="1024"><?php echo $session['response']['param']['story']['Description']; ?></textarea>
					<p class="puu-char_left"><?php echo language('sciomio_text_act_close_count'); ?><span class="puu-count"></span></p>
				</div>
				<div>
				    <label for="photo"><?php echo language('sciomio_text_act_close_photo_add'); ?></label>
				    <input class="file" type="file" name="file" size="22" id="photo"/>
				</div>

			</div>
			<p class="puu-media">
				<label><?php echo language('sciomio_text_act_close_photo'); ?></label>
				<?php
				if (! isset($session['response']['param']['story']['photo'])) { 
					echo "<img src='".$XCOW_B['url']."/ui/gfx/photo.jpg' alt='Foto's' title='' width='100' height='75'>";
				}
				else {
					echo "<a class='puu-photo' href='act=".$session['response']['param']['story']['Id']."&parent=".$session['response']['param']['parent']."'><img src='".$XCOW_B['url'].$session['response']['param']['story']['photo']."' alt='Foto's' title='' width='100' height='75'></a>";
				}
				?>
				<div class="puu-sbt puu-update">
					<input type="submit" value="<?php echo language('sciomio_text_act_close_submit'); ?>">
					<p class="puu-bail"><?php echo language('sciomio_word_or'); ?> <a href="dmy"><?php echo language('sciomio_word_reset'); ?></a></p>
				</div>
			</p>
		</fieldset>
	</div>
</form>

