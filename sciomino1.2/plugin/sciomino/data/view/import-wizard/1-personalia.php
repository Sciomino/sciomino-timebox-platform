<div id="wizardWindow" style="text-align:left;width:600px">
	<h2><?php echo language(sciomio_header_wizard_1); ?></h2>

	<div>
		<form id="wizard_form" method="post" onSubmit="javascript:ScioMino.Wizard.action(1);return false;">
			<input type="hidden" name="go" value="1">
			<table class='table_form' style="width:400px">

			<tr><td class='table_form_text'>
				<label for="firstName"><?php echo language('sciomio_text_user_profile_firstname'); ?></label>
			</td><td class='table_form_field'>
				<div class="inputset">
				<input class="form_input" type="text" name="firstName" size="32" maxlength="128" value="<?php echo $session['response']['param']['prevFirstName']; ?>"/>
				</div>
			</td></tr>

			<tr><td class='table_form_text'>
				<label for="lastName"><?php echo language('sciomio_text_user_profile_lastname'); ?></label>
			</td><td class='table_form_field'>
				<div class="inputset">
				<input class="form_input" type="text" name="lastName" size="32" maxlength="128" value="<?php echo $session['response']['param']['prevLastName']; ?>"/>
				</div>
			</td></tr>

			<tr><td class='table_form_text'>
				<label for="dateofbirth"><?php echo language('sciomio_text_user_profile_dateofbirth'); ?></label>
			</td><td class='table_form_field'>
				<div class="inputset selectgroup">
				<select class="s" name="dateofbirthday">
					<?php 
						echo "<option value=''>".language('sciomio_text_user_profile_dateofbirth_day')."</option>";
						for($i=1;$i<=31;$i++) {
							$selected = "";
							if ($i == $session['response']['param']['prevDateofbirthday']) {
								$selected = "SELECTED";
							}
							echo "<option value='$i' $selected>$i</option>";
						}
					?>
				</select>
				<select class="m" name="dateofbirthmonth">
					<?php
						echo "<option value=''>".language('sciomio_text_user_profile_dateofbirth_month')."</option>";
						for($i=1;$i<=12;$i++) {
							$selected = "";
							if ($i == $session['response']['param']['prevDateofbirthmonth']) {
								$selected = "SELECTED";
							}
							echo "<option value='$i' $selected>".language('sciomio_text_user_profile_dateofbirth_month_'.$i)."</option>";
						}
					?>
				</select>
				<select class="m" name="dateofbirthyear">
					<?php
						$year = date('Y') - 16;
						echo "<option value=''>".language('sciomio_text_user_profile_dateofbirth_year')."</option>";
						for($i=$year;$i>=$year-110;$i--) {
							$selected = "";
							if ($i == $session['response']['param']['prevDateofbirthyear']) {
								$selected = "SELECTED";
							}
							echo "<option value='$i' $selected>$i</option>";
						}
					?>
				</select>
				</div>
			</td></tr>

			<tr><td class='table_form_text'>
				<label for="gender"><?php echo language('sciomio_text_user_profile_gender'); ?></label>
			</td><td class='table_form_field'>
				<div class="inputset">
				<select name="gender" id="gender">
					<option value=""><?php echo language('sciomio_text_user_profile_gender_select'); ?></option>
					<option value="M" <?php if ($session['response']['param']['prevGender'] == "M") { echo "selected"; } ?>><?php echo language('sciomio_text_user_profile_gender_male'); ?></option>
					<option value="V" <?php if ($session['response']['param']['prevGender'] == "V") { echo "selected"; } ?>><?php echo language('sciomio_text_user_profile_gender_female'); ?></option>
				</select>
				</div>
			</td></tr>

			<tr><td>
			</td><td class='table_form_button'>
				<div class="inputset" style="width:270px;">
					<?php echo language('sciomio_text_wizard_personalia'); ?>
					<br/><br/>
					<input style="float:right;width:100px;" name="submit" class="form_button input_button input_space" type="submit" value="<?php echo language(sciomio_word_next); ?>" />
					<div id="wizardAlertWindow">
						<?php echo language($session['response']['param']['status']); ?>
					</div>
				</div>
			</td></tr>

			</table>
		</form>
	</div>
</div>

