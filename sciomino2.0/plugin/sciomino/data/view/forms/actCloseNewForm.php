<form id="act_close_new_form" class="puu-result" action="javascript:ScioMino.Act.closeNew();" method="post" enctype="multipart/form-data">
	<input type="hidden" name="com_act" value="<?php echo $session['response']['param']['parent'] ?>">
	<div class="puu-content">
		<a href="dmy" class="puu-close"><?php echo language('sciomio_header_act_top'); ?></a>
		<fieldset class="puu-satisfaction">
			<legend><?php echo language('sciomio_text_act_close'); ?></legend>
			<div class="puu-textual">
				<label for="exp"><?php echo language('sciomio_text_act_close_ervaring'); ?></label>
				<div id="Slider-result" class="slider-result">
					&nbsp;
				</div>
				<div class="sliderbox thumbs-2">
					<div id="Slider" class="slider"></div>
					<select name="com_like" id="your-verdict">
						<option value="4"><?php echo language('sciomio_word_like_4'); ?></option>
						<option value="3"><?php echo language('sciomio_word_like_3'); ?></option>
						<option value="2"><?php echo language('sciomio_word_like_2'); ?></option>
						<option value="1"><?php echo language('sciomio_word_like_1'); ?></option>
					</select>
				</div>
				<div class="puu-tell">
					<label><?php echo language('sciomio_text_act_close_story'); ?>
					<span class="puu-motivate"><?php echo language('sciomio_text_act_close_story_description'); ?></span></label>
					<textarea name="com_description" maxlength="1024"></textarea>
					<p class="puu-char_left"><?php echo language('sciomio_text_act_close_count'); ?><span class="puu-count"></span></p>
				</div>
				<div>
				    <label for="photo"><?php echo language('sciomio_text_act_close_photo_add'); ?></label>
				    <input class="file" type="file" name="file" size="22" id="photo"/>
				</div>

			</div>
			<p class="puu-media">
				<label><?php echo language('sciomio_text_act_close_photo'); ?></label>
				<img src="<?php echo $XCOW_B['url'] ?>/ui/gfx/photo.jpg" alt="Foto's" title="" width="100" height="75">
				<!--
				<label>Afbeelding &amp; Video</label>
				<a class="puu-photo" href="dmy"><img src="content/images/dmy_photo.jpg" alt="Foto's" title="" width="100" height="75"></a>
				<span class="puu-crud"><a href="dmy">Wijzigen</a> <a href="dmy">Delete</a></span>
				<a class="puu-video" href="dmy"><img src="content/images/dmy_video.jpg" alt="Video's" title="" width="100" height="75"></a>
				<span class="puu-crud"><a href="dmy">Wijzigen</a> <a href="dmy">Delete</a></span>
				-->
				<div class="puu-sbt">
					<input type="submit" value="<?php echo language('sciomio_text_act_close_submit'); ?>">
					<p class="puu-bail"><?php echo language('sciomio_word_or'); ?> <a href="dmy"><?php echo language('sciomio_word_reset'); ?></a></p>
				</div>
			</p>
		</fieldset>
	</div>
</form>

