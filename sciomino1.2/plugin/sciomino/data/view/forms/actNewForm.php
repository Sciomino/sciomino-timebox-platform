			<form class="puu-create_act">
			<input type="hidden" value="<?php echo $session['time']; ?>" name="go" />
			<fieldset class="puu-find">
				<legend><?php echo language('sciomio_header_act_top'); ?></legend>
				<div class="puu-find_wrap">
						<?php
						if ($XCOW_B['sciomino']['skin-network'] == "yes") {
							#echo "<h2>".language('sciomio_text_act_new_choose_networks')."</h2>";
							echo "<div class='puu-links puu-remove-first'>";
							echo "<div class='puu-chzn_3'>";
							echo "<select name='net' data-placeholder='".language('sciomio_text_act_new_choose_networks')."' class='chzn-select'>";
							echo "<option value='0'>".language('sciomio_text_act_new_choose_networks')."</option>";
							foreach ($session['response']['param']['networkList'] as $list) {
								echo "<option value='".$list['Name']."'>".$list['Name']."</option>";
							}
							echo "</select>";
							echo "</div>";
							echo "</div>";
						}
						?>

					<div class="puu-msg">
						<textarea name="com_description" cols="40" rows="1" maxlength="256"></textarea>
						<p class="puu-char_left"></p>
					</div>
					<div class="puu-duration">
						<select name="com_expiration" >
							<option value="<?php echo language('sciomio_word_act_expiration_value_1'); ?>"><?php echo language('sciomio_word_act_expiration_name_1'); ?></option>
							<option value="<?php echo language('sciomio_word_act_expiration_value_2'); ?>"><?php echo language('sciomio_word_act_expiration_name_2'); ?></option>
							<option value="<?php echo language('sciomio_word_act_expiration_value_3'); ?>"><?php echo language('sciomio_word_act_expiration_name_3'); ?></option>
							<option value="<?php echo language('sciomio_word_act_expiration_value_4'); ?>" SELECTED><?php echo language('sciomio_word_act_expiration_name_4'); ?></option>
							<option value="<?php echo language('sciomio_word_act_expiration_value_5'); ?>"><?php echo language('sciomio_word_act_expiration_name_5'); ?></option>
						</select>
						<?php echo language('sciomio_word_valid'); ?>
					</div>
					<div class="puu-sbt">
						<input type="submit" value="<?php echo language('sciomio_text_act_new_submit'); ?>">
						<p class="puu-bail"><?php echo language('sciomio_word_or'); ?> <a href="dmy"><?php echo language('sciomio_word_reset'); ?></a></p>
					</div>

					<h2><?php echo language('sciomio_text_act_new_show'); ?></h2>
					<p><?php echo language('sciomio_text_act_new_show_remark'); ?></p>
					<div class="puu-links">
						<div class="puu-chzn_1">
							<select name="k[]" data-placeholder="<?php echo language('sciomio_text_act_new_choose_knowledge'); ?>" multiple="multiple" class="chzn-select puu-suggest puu-suggest_knowledge">
							</select>
						</div>
						<div class="puu-chzn_2">
							<select name="h[]" data-placeholder="<?php echo language('sciomio_text_act_new_choose_hobby'); ?>" multiple="multiple" class="chzn-select puu-suggest puu-suggest_hobby">
							</select>
						</div>
					</div>
					
				</div>
			</fieldset>
			</form>

