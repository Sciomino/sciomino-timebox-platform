<form id="user_form" action="javascript:ScioMino.User.actionProfile();" method="post" enctype="multipart/form-data">
<input class='form_input' type='hidden' name='go' value="1">
<input class='form_input' type='hidden' name='photo' value="<?php echo $session['response']['param']['user']['photo']; ?>">
	<fieldset class="img-item highlight">
		<div class="img sub">
			<?php
			if (! isset($session['response']['param']['user']['photo'])) { $session['response']['param']['user']['photo'] = "/ui/gfx/photo.jpg"; }
			else { $session['response']['param']['user']['photo'] = str_replace("/upload/","/upload/96x96_",$session['response']['param']['user']['photo']); }
			echo "<img src='".$XCOW_B['url'].$session['response']['param']['user']['photo']."' width='96' height='96' alt='' />";
			?>
		</div>
		<div class="bd">
			<h3 class="legend"><?php echo $session['response']['param']['displayName']; ?></h3>

			<div class="inputset">
			    <label for="title"><?php echo language('sciomio_text_user_profile_title'); ?></label>
			    <input class="text <?php if (in_array("title", $XCOW_B['sciomino']['personalia-filled'])) {echo "prefill";} ?>" type="text" name="title" id="title" value="<?php echo htmlTokens($session['response']['param']['user']['title']); ?>" maxlength="128" />
			</div>
			<div class="inputset">
			    <label for="firstName"><?php echo language('sciomio_text_user_profile_firstname'); ?></label>
			    <input class="text <?php if (in_array("firstname", $XCOW_B['sciomino']['personalia-filled'])) {echo "prefill";} ?>" type="text" name="firstName" id="firstName" value="<?php echo htmlTokens($session['response']['param']['user']['FirstName']); ?>" maxlength="128" />
			</div>
			<div class="inputset">
			    <label for="lastName"><?php echo language('sciomio_text_user_profile_lastname'); ?></label>
			    <input class="text <?php if (in_array("lastname", $XCOW_B['sciomino']['personalia-filled'])) {echo "prefill";} ?>" type="text" name="lastName" id="lastName" value="<?php echo htmlTokens($session['response']['param']['user']['LastName']); ?>" maxlength="128" />
			</div>
			<div class="inputset textarea">
				<label for="description"><?php echo language('sciomio_text_user_profile_description'); ?></label>
				<textarea name="description" id="description" cols="30" rows="10" maxlength="1024"><?php echo $session['response']['param']['user']['description']; ?></textarea>
			</div>
			<div class="inputset selectgroup">
				<label for="dateofbirth"><?php echo language('sciomio_text_user_profile_dateofbirth'); ?></label>
				<select class="s <?php if (in_array("dateofbirth", $XCOW_B['sciomino']['personalia-filled'])) {echo "prefill";} ?>" name="dateofbirthday">
					<?php 
						echo "<option value=''>".language('sciomio_text_user_profile_dateofbirth_day')."</option>";
						for($i=1;$i<=31;$i++) {
							$selected = "";
							if ($i == $session['response']['param']['user']['dateofbirthday']) {
								$selected = "SELECTED";
							}
							echo "<option value='$i' $selected>$i</option>";
						}
					?>
				</select>
				<select class="m <?php if (in_array("dateofbirth", $XCOW_B['sciomino']['personalia-filled'])) {echo "prefill";} ?>" name="dateofbirthmonth">
					<?php
						echo "<option value=''>".language('sciomio_text_user_profile_dateofbirth_month')."</option>";
						for($i=1;$i<=12;$i++) {
							$selected = "";
							if ($i == $session['response']['param']['user']['dateofbirthmonth']) {
								$selected = "SELECTED";
							}
							echo "<option value='$i' $selected>".language('sciomio_text_user_profile_dateofbirth_month_'.$i)."</option>";
						}
					?>
				</select>
				<select class="m <?php if (in_array("dateofbirth", $XCOW_B['sciomino']['personalia-filled'])) {echo "prefill";} ?>" name="dateofbirthyear">
					<?php
						$year = date('Y');
						echo "<option value=''>".language('sciomio_text_user_profile_dateofbirth_year')."</option>";
						for($i=$year;$i>=$year-110;$i--) {
							$selected = "";
							if ($i == $session['response']['param']['user']['dateofbirthyear']) {
								$selected = "SELECTED";
							}
							echo "<option value='$i' $selected>$i</option>";
						}
					?>
				</select>
			</div>
			<?php
				if (! in_array("calendar", $XCOW_B['sciomino']['personalia-exclude']) ) {
					echo "<div class='inputset'>";
					echo "<label for='dateofbirthshow'></label>";
					$checked = "";
					if ($session['response']['param']['user']['dateofbirthshow'] == 1) {
						$checked = "CHECKED";
					}
					echo "<input class='checkbox' type='checkbox' id='dateofbirthshow' name='dateofbirthshow' value='1' ".$checked." /> ".language('sciomio_text_user_profile_dateofbirth_show');
					echo "</div>";
				}
			?>
			<div class="inputset">
				<label for="gender"><?php echo language('sciomio_text_user_profile_gender'); ?></label>
				<select class="<?php if (in_array("gender", $XCOW_B['sciomino']['personalia-filled'])) {echo "prefill";} ?>" name="gender" id="gender">
					<option value=""><?php echo language('sciomio_text_user_profile_gender_select'); ?></option>
					<option value="M" <?php if ($session['response']['param']['user']['gender'] == "M") { echo "selected"; } ?>><?php echo language('sciomio_text_user_profile_gender_male'); ?></option>
					<option value="V" <?php if ($session['response']['param']['user']['gender'] == "V") { echo "selected"; } ?>><?php echo language('sciomio_text_user_profile_gender_female'); ?></option>
				</select>

			</div>
			<?php
				if (! in_array("photo", $XCOW_B['sciomino']['personalia-exclude']) ) {
					echo "<div class='inputset'>";
					echo "<label for='photo'>".language('sciomio_text_user_profile_photo')."</label>";
					echo "<input class='file' type='file' name='file' size='22' id='photo'/>";
					echo "</div>";
				}
			?>

		</div>
	</fieldset>
	
	<fieldset>
		<h3 class="legend"><?php echo language('sciomio_text_user_profile_work'); ?></h3>
		<?php
		if (! in_array("industry", $XCOW_B['sciomino']['personalia-exclude']) ) {
			if (in_array("industry", $XCOW_B['sciomino']['personalia-filled'])) {$prefill = "prefill";} else { $prefill = ""; } 
			echo "<div class='inputset'>";
			echo "<label for='industry'>".language('sciomio_text_user_profile_work_industry')."</label>";
			echo "<input class='text ".$prefill."' type='text' name='industry' id='industry' value='".htmlTokens($session['response']['param']['organization']['Current']['industry'])."' maxlength='128' />";
			echo "</div>";
		}
		if (! in_array("company", $XCOW_B['sciomino']['personalia-exclude']) ) {
			if (in_array("company", $XCOW_B['sciomino']['personalia-filled'])) {$prefill = "prefill";} else { $prefill = ""; } 
			echo "<div class='inputset'>";
			echo "<label for='company'>".language('sciomio_text_user_profile_work_company')."</label>";
			echo "<input class='text ".$prefill."' type='text' name='company' id='company' value='".htmlTokens($session['response']['param']['organization']['Current']['company'])."' maxlength='128' />";
			echo "</div>";
		}
		if (! in_array("building", $XCOW_B['sciomino']['personalia-exclude']) ) {
			if (in_array("building", $XCOW_B['sciomino']['personalia-filled'])) {$prefill = "prefill";} else { $prefill = ""; } 
			echo "<div class='inputset'>";
			echo "<label for='building'>".language('sciomio_text_user_profile_work_building')."</label>";
			echo "<input class='text ".$prefill."' type='text' name='building' id='building' value='".htmlTokens($session['response']['param']['organization']['Current']['building'])."' maxlength='128' />";
			echo "</div>";
		}
		if (! in_array("room", $XCOW_B['sciomino']['personalia-exclude']) ) {
			if (in_array("room", $XCOW_B['sciomino']['personalia-filled'])) {$prefill = "prefill";} else { $prefill = ""; } 
			echo "<div class='inputset'>";
			echo "<label for='room'>".language('sciomio_text_user_profile_work_room')."</label>";
			echo "<input class='text ".$prefill."' type='text' name='room' id='room' value='".htmlTokens($session['response']['param']['organization']['Current']['room'])."' maxlength='128' />";
			echo "</div>";
		}
		if (! in_array("role", $XCOW_B['sciomino']['personalia-exclude']) ) {
			if (in_array("role", $XCOW_B['sciomino']['personalia-filled'])) {$prefill = "prefill";} else { $prefill = ""; } 
			echo "<div class='inputset'>";
			echo "<label for='role'>".language('sciomio_text_user_profile_work_role')."</label>";
			echo "<input class='text ".$prefill."' type='text' name='role' id='role' value='".htmlTokens($session['response']['param']['organization']['Current']['role'])."' maxlength='128' />";
			echo "</div>";
		}
		if (! in_array("division", $XCOW_B['sciomino']['personalia-exclude']) ) {
			if (in_array("division", $XCOW_B['sciomino']['personalia-filled'])) {$prefill = "prefill";} else { $prefill = ""; } 
			echo "<div class='inputset'>";
			echo "<label for='division'>".language('sciomio_text_user_profile_work_businessunit')."</label>";
			echo "<input class='text ".$prefill."' type='text' name='division' id='division' value='".htmlTokens($session['response']['param']['organization']['Current']['division'])."' maxlength='128' />";
			echo "</div>";
		}
		if (! in_array("section", $XCOW_B['sciomino']['personalia-exclude']) ) {
			if (in_array("section", $XCOW_B['sciomino']['personalia-filled'])) {$prefill = "prefill";} else { $prefill = ""; } 
			echo "<div class='inputset'>";
			echo "<label for='section'>".language('sciomio_text_user_profile_work_section')."</label>";
			echo "<input class='text ".$prefill."' type='text' name='section' id='section' value='".htmlTokens($session['response']['param']['organization']['Current']['section'])."' maxlength='128' />";
			echo "</div>";
		}
		if (! in_array("parttime", $XCOW_B['sciomino']['personalia-exclude']) ) {
			if (in_array("parttime", $XCOW_B['sciomino']['personalia-filled'])) {$prefill = "prefill";} else { $prefill = ""; } 
			echo "<div class='inputset'>";
			echo "<label for='parttime'>".language('sciomio_text_user_profile_work_parttime')."</label>";
			echo "<input class='text ".$prefill."' type='text' name='parttime' id='parttime' value='".htmlTokens($session['response']['param']['organization']['Current']['parttime'])."' maxlength='128' />";
			echo "</div>";
		}
		?>
		<!--
		<div class="inputset">
			<label for="startDate"><?php echo language('sciomio_text_user_profile_work_startdate'); ?></label>
			<input class="text prefill" type="text" name="startDate" id="freestartDatevalue="<?php echo $session['response']['param']['organization']['Current']['startDate']; ?>" />
		</div>
		<div class="inputset">
			<label for="endDate"><?php echo language('sciomio_text_user_profile_work_enddate'); ?></label>
			<input class="text prefill" type="text" name="endDate" id="endDate" value="<?php echo $session['response']['param']['organization']['Current']['endDate']; ?>" />
		</div>
		-->
</fieldset>

	<fieldset>
		<h3 class="legend"><?php echo language('sciomio_text_user_profile_contact_work'); ?></h3>
		<?php
		if (! in_array("email", $XCOW_B['sciomino']['personalia-exclude']) ) {
			if (in_array("email", $XCOW_B['sciomino']['personalia-filled'])) {$prefill = "prefill";} else { $prefill = ""; } 
			echo "<div class='inputset'>";
			echo "<label for='emailWork'>".language('sciomio_text_user_profile_contact_work_email')."</label>";
			echo "<input class='text ".$prefill."' type='email' name='emailWork' id='emailWork' value='".$session['response']['param']['contact']['Work']['email']."' maxlength='128' />";
			echo "</div>";
		}
		if (! in_array("telIntern", $XCOW_B['sciomino']['personalia-exclude']) ) {
			if (in_array("telIntern", $XCOW_B['sciomino']['personalia-filled'])) {$prefill = "prefill";} else { $prefill = ""; } 
			echo "<div class='inputset'>";
			echo "<label for='telInternWork'>".language('sciomio_text_user_profile_contact_work_phoneinternal')."</label>";
			echo "<input class='text ".$prefill."' type='text' name='telInternWork' id='telInternWork' value='".$session['response']['param']['contact']['Work']['telIntern']."' maxlength='128' />";
			echo "</div>";
		}
		if (! in_array("telExtern", $XCOW_B['sciomino']['personalia-exclude']) ) {
			if (in_array("telExtern", $XCOW_B['sciomino']['personalia-filled'])) {$prefill = "prefill";} else { $prefill = ""; } 
			echo "<div class='inputset'>";
			echo "<label for='telExternWork'>".language('sciomio_text_user_profile_contact_work_phoneexternal')."</label>";
			echo "<input class='text ".$prefill."' type='text' name='telExternWork' id='telExternWork' value='".$session['response']['param']['contact']['Work']['telExtern']."' maxlength='128' />";
			echo "</div>";
		}
		if (! in_array("mobile", $XCOW_B['sciomino']['personalia-exclude']) ) {
			if (in_array("mobile", $XCOW_B['sciomino']['personalia-filled'])) {$prefill = "prefill";} else { $prefill = ""; } 
			echo "<div class='inputset'>";
			echo "<label for='telMobileWork'>".language('sciomio_text_user_profile_contact_work_mobile')."</label>";
			echo "<input class='text ".$prefill."' type='text' name='telMobileWork' id='telMobileWork' value='".$session['response']['param']['contact']['Work']['telMobile']."' maxlength='128' />";
			echo "</div>";
		}
		if (! in_array("lync", $XCOW_B['sciomino']['personalia-exclude']) ) {
			if (in_array("lync", $XCOW_B['sciomino']['personalia-filled'])) {$prefill = "prefill";} else { $prefill = ""; } 
			echo "<div class='inputset'>";
			echo "<label for='telLyncWork'>".language('sciomio_text_user_profile_contact_work_lync')."</label>";
			echo "<input class='text ".$prefill."' type='text' name='telLyncWork' id='telLyncWork' value='".$session['response']['param']['contact']['Work']['telLync']."' maxlength='128' />";
			echo "</div>";
		}
		if (! in_array("pager", $XCOW_B['sciomino']['personalia-exclude']) ) {
			if (in_array("pager", $XCOW_B['sciomino']['personalia-filled'])) {$prefill = "prefill";} else { $prefill = ""; } 
			echo "<div class='inputset'>";
			echo "<label for='telPagerWork'>".language('sciomio_text_user_profile_contact_work_pager')."</label>";
			echo "<input class='text ".$prefill."' type='text' name='telPagerWork' id='telPagerWork' value='".$session['response']['param']['contact']['Work']['telPager']."' maxlength='128' />";
			echo "</div>";
		}
		if (! in_array("fax", $XCOW_B['sciomino']['personalia-exclude']) ) {
			if (in_array("fax", $XCOW_B['sciomino']['personalia-filled'])) {$prefill = "prefill";} else { $prefill = ""; } 
			echo "<div class='inputset'>";
			echo "<label for='telFaxWork'>".language('sciomio_text_user_profile_contact_work_fax')."</label>";
			echo "<input class='text ".$prefill."' type='text' name='telFaxWork' id='telFaxWork' value='".$session['response']['param']['contact']['Work']['telFax']."' maxlength='128' />";
			echo "</div>";
		}
		if (! in_array("pac", $XCOW_B['sciomino']['personalia-exclude']) ) {
			if (in_array("pac", $XCOW_B['sciomino']['personalia-filled'])) {$prefill = "prefill";} else { $prefill = ""; } 
			echo "<div class='inputset'>";
			echo "<label for='pac'>".language('sciomio_text_user_profile_contact_work_pac')."</label>";
			echo "<input class='text ".$prefill."' type='text' name='pac' id='pac' value='".$session['response']['param']['contact']['Work']['pac']."' maxlength='128' />";
			echo "</div>";
		}
		if (! in_array("myId", $XCOW_B['sciomino']['personalia-exclude']) ) {
			if (in_array("myId", $XCOW_B['sciomino']['personalia-filled'])) {$prefill = "prefill";} else { $prefill = ""; } 
			echo "<div class='inputset'>";
			echo "<label for='myId'>".language('sciomio_text_user_profile_contact_work_myId')."</label>";
			echo "<input class='text ".$prefill."' type='text' name='myId' id='myId' value='".$session['response']['param']['contact']['Work']['myId']."' maxlength='128' />";
			echo "</div>";
		}
		if (! in_array("assistentId", $XCOW_B['sciomino']['personalia-exclude']) ) {
			if (in_array("assistentId", $XCOW_B['sciomino']['personalia-filled'])) {$prefill = "prefill";} else { $prefill = ""; } 
			echo "<div class='inputset'>";
			echo "<label for='assistentId'>".language('sciomio_text_user_profile_contact_work_assistentId')."</label>";
			echo "<input class='text ".$prefill."' type='text' name='assistentId' id='assistentId' value='".$session['response']['param']['contact']['Work']['assistentId']."' maxlength='128' />";
			echo "</div>";
		}
		if (! in_array("managerId", $XCOW_B['sciomino']['personalia-exclude']) ) {
			if (in_array("managerId", $XCOW_B['sciomino']['personalia-filled'])) {$prefill = "prefill";} else { $prefill = ""; } 
			echo "<div class='inputset'>";
			echo "<label for='managerId'>".language('sciomio_text_user_profile_contact_work_managerId')."</label>";
			echo "<input class='text ".$prefill."' type='text' name='managerId' id='managerId' value='".$session['response']['param']['contact']['Work']['managerId']."' maxlength='128' />";
			echo "</div>";
		}
		?>
	</fieldset>

	<fieldset>
		<h3 class="legend"><?php echo language('sciomio_text_user_profile_address_work'); ?></h3>
		<div class="inputset">
			<label for="addressWork"><?php echo language('sciomio_text_user_profile_address_work_address'); ?></label>
			<input class="text <?php if (in_array("address", $XCOW_B['sciomino']['personalia-filled'])) {echo "prefill";} ?>" type="text" name="addressWork" id="addressWork" value="<?php echo htmlTokens($session['response']['param']['address']['Work']['address']); ?>" maxlength="128" />
		</div>
		<div class="inputset">
			<label for="postalcodeWork"><?php echo language('sciomio_text_user_profile_address_work_postalcode'); ?></label>
			<input class="text <?php if (in_array("postalcode", $XCOW_B['sciomino']['personalia-filled'])) {echo "prefill";} ?>" type="text" name="postalcodeWork" id="postalcodeWork" value="<?php echo htmlTokens($session['response']['param']['address']['Work']['postalcode']); ?>" maxlength="128" />
		</div>
		<div class="inputset">
			<label for="cityWork"><?php echo language('sciomio_text_user_profile_address_work_city'); ?></label>
			<input class="text <?php if (in_array("city", $XCOW_B['sciomino']['personalia-filled'])) {echo "prefill";} ?>" type="text" name="cityWork" id="cityWork" value="<?php echo htmlTokens($session['response']['param']['address']['Work']['city']); ?>" maxlength="128" />
		</div>
		<div class="inputset">
			<label for="countryWork"><?php echo language('sciomio_text_user_profile_address_work_country'); ?></label>
			<select class="<?php if (in_array("country", $XCOW_B['sciomino']['personalia-filled'])) {echo "prefill";} ?>" name="countryWork" id="countryWork">
				<option value="<?php echo language('country_nl_code'); ?>"><?php echo language('sciomio_text_user_profile_address_country_select'); ?></option>
<option value="<?php echo language('country_ad_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ad_code')) { echo "selected"; } ?>><?php echo language('country_ad_name'); ?></option>
<option value="<?php echo language('country_ae_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ae_code')) { echo "selected"; } ?>><?php echo language('country_ae_name'); ?></option>
<option value="<?php echo language('country_af_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_af_code')) { echo "selected"; } ?>><?php echo language('country_af_name'); ?></option>
<option value="<?php echo language('country_ag_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ag_code')) { echo "selected"; } ?>><?php echo language('country_ag_name'); ?></option>
<option value="<?php echo language('country_ai_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ai_code')) { echo "selected"; } ?>><?php echo language('country_ai_name'); ?></option>
<option value="<?php echo language('country_al_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_al_code')) { echo "selected"; } ?>><?php echo language('country_al_name'); ?></option>
<option value="<?php echo language('country_am_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_am_code')) { echo "selected"; } ?>><?php echo language('country_am_name'); ?></option>
<option value="<?php echo language('country_ao_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ao_code')) { echo "selected"; } ?>><?php echo language('country_ao_name'); ?></option>
<option value="<?php echo language('country_aq_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_aq_code')) { echo "selected"; } ?>><?php echo language('country_aq_name'); ?></option>
<option value="<?php echo language('country_ar_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ar_code')) { echo "selected"; } ?>><?php echo language('country_ar_name'); ?></option>
<option value="<?php echo language('country_as_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_as_code')) { echo "selected"; } ?>><?php echo language('country_as_name'); ?></option>
<option value="<?php echo language('country_at_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_at_code')) { echo "selected"; } ?>><?php echo language('country_at_name'); ?></option>
<option value="<?php echo language('country_au_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_au_code')) { echo "selected"; } ?>><?php echo language('country_au_name'); ?></option>
<option value="<?php echo language('country_aw_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_aw_code')) { echo "selected"; } ?>><?php echo language('country_aw_name'); ?></option>
<option value="<?php echo language('country_ax_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ax_code')) { echo "selected"; } ?>><?php echo language('country_ax_name'); ?></option>
<option value="<?php echo language('country_az_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_az_code')) { echo "selected"; } ?>><?php echo language('country_az_name'); ?></option>
<option value="<?php echo language('country_ba_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ba_code')) { echo "selected"; } ?>><?php echo language('country_ba_name'); ?></option>
<option value="<?php echo language('country_bb_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_bb_code')) { echo "selected"; } ?>><?php echo language('country_bb_name'); ?></option>
<option value="<?php echo language('country_bd_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_bd_code')) { echo "selected"; } ?>><?php echo language('country_bd_name'); ?></option>
<option value="<?php echo language('country_be_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_be_code')) { echo "selected"; } ?>><?php echo language('country_be_name'); ?></option>
<option value="<?php echo language('country_bf_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_bf_code')) { echo "selected"; } ?>><?php echo language('country_bf_name'); ?></option>
<option value="<?php echo language('country_bg_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_bg_code')) { echo "selected"; } ?>><?php echo language('country_bg_name'); ?></option>
<option value="<?php echo language('country_bh_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_bh_code')) { echo "selected"; } ?>><?php echo language('country_bh_name'); ?></option>
<option value="<?php echo language('country_bi_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_bi_code')) { echo "selected"; } ?>><?php echo language('country_bi_name'); ?></option>
<option value="<?php echo language('country_bj_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_bj_code')) { echo "selected"; } ?>><?php echo language('country_bj_name'); ?></option>
<option value="<?php echo language('country_bl_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_bl_code')) { echo "selected"; } ?>><?php echo language('country_bl_name'); ?></option>
<option value="<?php echo language('country_bm_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_bm_code')) { echo "selected"; } ?>><?php echo language('country_bm_name'); ?></option>
<option value="<?php echo language('country_bn_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_bn_code')) { echo "selected"; } ?>><?php echo language('country_bn_name'); ?></option>
<option value="<?php echo language('country_bo_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_bo_code')) { echo "selected"; } ?>><?php echo language('country_bo_name'); ?></option>
<option value="<?php echo language('country_bq_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_bq_code')) { echo "selected"; } ?>><?php echo language('country_bq_name'); ?></option>
<option value="<?php echo language('country_br_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_br_code')) { echo "selected"; } ?>><?php echo language('country_br_name'); ?></option>
<option value="<?php echo language('country_bs_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_bs_code')) { echo "selected"; } ?>><?php echo language('country_bs_name'); ?></option>
<option value="<?php echo language('country_bt_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_bt_code')) { echo "selected"; } ?>><?php echo language('country_bt_name'); ?></option>
<option value="<?php echo language('country_bv_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_bv_code')) { echo "selected"; } ?>><?php echo language('country_bv_name'); ?></option>
<option value="<?php echo language('country_bw_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_bw_code')) { echo "selected"; } ?>><?php echo language('country_bw_name'); ?></option>
<option value="<?php echo language('country_by_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_by_code')) { echo "selected"; } ?>><?php echo language('country_by_name'); ?></option>
<option value="<?php echo language('country_bz_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_bz_code')) { echo "selected"; } ?>><?php echo language('country_bz_name'); ?></option>
<option value="<?php echo language('country_ca_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ca_code')) { echo "selected"; } ?>><?php echo language('country_ca_name'); ?></option>
<option value="<?php echo language('country_cc_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_cc_code')) { echo "selected"; } ?>><?php echo language('country_cc_name'); ?></option>
<option value="<?php echo language('country_cd_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_cd_code')) { echo "selected"; } ?>><?php echo language('country_cd_name'); ?></option>
<option value="<?php echo language('country_cf_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_cf_code')) { echo "selected"; } ?>><?php echo language('country_cf_name'); ?></option>
<option value="<?php echo language('country_cg_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_cg_code')) { echo "selected"; } ?>><?php echo language('country_cg_name'); ?></option>
<option value="<?php echo language('country_ch_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ch_code')) { echo "selected"; } ?>><?php echo language('country_ch_name'); ?></option>
<option value="<?php echo language('country_ci_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ci_code')) { echo "selected"; } ?>><?php echo language('country_ci_name'); ?></option>
<option value="<?php echo language('country_ck_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ck_code')) { echo "selected"; } ?>><?php echo language('country_ck_name'); ?></option>
<option value="<?php echo language('country_cl_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_cl_code')) { echo "selected"; } ?>><?php echo language('country_cl_name'); ?></option>
<option value="<?php echo language('country_cm_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_cm_code')) { echo "selected"; } ?>><?php echo language('country_cm_name'); ?></option>
<option value="<?php echo language('country_cn_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_cn_code')) { echo "selected"; } ?>><?php echo language('country_cn_name'); ?></option>
<option value="<?php echo language('country_co_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_co_code')) { echo "selected"; } ?>><?php echo language('country_co_name'); ?></option>
<option value="<?php echo language('country_cr_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_cr_code')) { echo "selected"; } ?>><?php echo language('country_cr_name'); ?></option>
<option value="<?php echo language('country_cu_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_cu_code')) { echo "selected"; } ?>><?php echo language('country_cu_name'); ?></option>
<option value="<?php echo language('country_cv_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_cv_code')) { echo "selected"; } ?>><?php echo language('country_cv_name'); ?></option>
<option value="<?php echo language('country_cw_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_cw_code')) { echo "selected"; } ?>><?php echo language('country_cw_name'); ?></option>
<option value="<?php echo language('country_cx_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_cx_code')) { echo "selected"; } ?>><?php echo language('country_cx_name'); ?></option>
<option value="<?php echo language('country_cy_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_cy_code')) { echo "selected"; } ?>><?php echo language('country_cy_name'); ?></option>
<option value="<?php echo language('country_cz_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_cz_code')) { echo "selected"; } ?>><?php echo language('country_cz_name'); ?></option>
<option value="<?php echo language('country_de_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_de_code')) { echo "selected"; } ?>><?php echo language('country_de_name'); ?></option>
<option value="<?php echo language('country_dj_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_dj_code')) { echo "selected"; } ?>><?php echo language('country_dj_name'); ?></option>
<option value="<?php echo language('country_dk_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_dk_code')) { echo "selected"; } ?>><?php echo language('country_dk_name'); ?></option>
<option value="<?php echo language('country_dm_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_dm_code')) { echo "selected"; } ?>><?php echo language('country_dm_name'); ?></option>
<option value="<?php echo language('country_do_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_do_code')) { echo "selected"; } ?>><?php echo language('country_do_name'); ?></option>
<option value="<?php echo language('country_dz_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_dz_code')) { echo "selected"; } ?>><?php echo language('country_dz_name'); ?></option>
<option value="<?php echo language('country_ec_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ec_code')) { echo "selected"; } ?>><?php echo language('country_ec_name'); ?></option>
<option value="<?php echo language('country_ee_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ee_code')) { echo "selected"; } ?>><?php echo language('country_ee_name'); ?></option>
<option value="<?php echo language('country_eg_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_eg_code')) { echo "selected"; } ?>><?php echo language('country_eg_name'); ?></option>
<option value="<?php echo language('country_eh_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_eh_code')) { echo "selected"; } ?>><?php echo language('country_eh_name'); ?></option>
<option value="<?php echo language('country_er_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_er_code')) { echo "selected"; } ?>><?php echo language('country_er_name'); ?></option>
<option value="<?php echo language('country_es_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_es_code')) { echo "selected"; } ?>><?php echo language('country_es_name'); ?></option>
<option value="<?php echo language('country_et_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_et_code')) { echo "selected"; } ?>><?php echo language('country_et_name'); ?></option>
<option value="<?php echo language('country_fi_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_fi_code')) { echo "selected"; } ?>><?php echo language('country_fi_name'); ?></option>
<option value="<?php echo language('country_fj_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_fj_code')) { echo "selected"; } ?>><?php echo language('country_fj_name'); ?></option>
<option value="<?php echo language('country_fk_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_fk_code')) { echo "selected"; } ?>><?php echo language('country_fk_name'); ?></option>
<option value="<?php echo language('country_fm_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_fm_code')) { echo "selected"; } ?>><?php echo language('country_fm_name'); ?></option>
<option value="<?php echo language('country_fo_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_fo_code')) { echo "selected"; } ?>><?php echo language('country_fo_name'); ?></option>
<option value="<?php echo language('country_fr_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_fr_code')) { echo "selected"; } ?>><?php echo language('country_fr_name'); ?></option>
<option value="<?php echo language('country_ga_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ga_code')) { echo "selected"; } ?>><?php echo language('country_ga_name'); ?></option>
<option value="<?php echo language('country_gb_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_gb_code')) { echo "selected"; } ?>><?php echo language('country_gb_name'); ?></option>
<option value="<?php echo language('country_gd_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_gd_code')) { echo "selected"; } ?>><?php echo language('country_gd_name'); ?></option>
<option value="<?php echo language('country_ge_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ge_code')) { echo "selected"; } ?>><?php echo language('country_ge_name'); ?></option>
<option value="<?php echo language('country_gf_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_gf_code')) { echo "selected"; } ?>><?php echo language('country_gf_name'); ?></option>
<option value="<?php echo language('country_gg_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_gg_code')) { echo "selected"; } ?>><?php echo language('country_gg_name'); ?></option>
<option value="<?php echo language('country_gh_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_gh_code')) { echo "selected"; } ?>><?php echo language('country_gh_name'); ?></option>
<option value="<?php echo language('country_gi_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_gi_code')) { echo "selected"; } ?>><?php echo language('country_gi_name'); ?></option>
<option value="<?php echo language('country_gl_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_gl_code')) { echo "selected"; } ?>><?php echo language('country_gl_name'); ?></option>
<option value="<?php echo language('country_gm_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_gm_code')) { echo "selected"; } ?>><?php echo language('country_gm_name'); ?></option>
<option value="<?php echo language('country_gn_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_gn_code')) { echo "selected"; } ?>><?php echo language('country_gn_name'); ?></option>
<option value="<?php echo language('country_gp_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_gp_code')) { echo "selected"; } ?>><?php echo language('country_gp_name'); ?></option>
<option value="<?php echo language('country_gq_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_gq_code')) { echo "selected"; } ?>><?php echo language('country_gq_name'); ?></option>
<option value="<?php echo language('country_gr_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_gr_code')) { echo "selected"; } ?>><?php echo language('country_gr_name'); ?></option>
<option value="<?php echo language('country_gs_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_gs_code')) { echo "selected"; } ?>><?php echo language('country_gs_name'); ?></option>
<option value="<?php echo language('country_gt_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_gt_code')) { echo "selected"; } ?>><?php echo language('country_gt_name'); ?></option>
<option value="<?php echo language('country_gu_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_gu_code')) { echo "selected"; } ?>><?php echo language('country_gu_name'); ?></option>
<option value="<?php echo language('country_gw_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_gw_code')) { echo "selected"; } ?>><?php echo language('country_gw_name'); ?></option>
<option value="<?php echo language('country_gy_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_gy_code')) { echo "selected"; } ?>><?php echo language('country_gy_name'); ?></option>
<option value="<?php echo language('country_hk_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_hk_code')) { echo "selected"; } ?>><?php echo language('country_hk_name'); ?></option>
<option value="<?php echo language('country_hm_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_hm_code')) { echo "selected"; } ?>><?php echo language('country_hm_name'); ?></option>
<option value="<?php echo language('country_hn_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_hn_code')) { echo "selected"; } ?>><?php echo language('country_hn_name'); ?></option>
<option value="<?php echo language('country_hr_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_hr_code')) { echo "selected"; } ?>><?php echo language('country_hr_name'); ?></option>
<option value="<?php echo language('country_ht_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ht_code')) { echo "selected"; } ?>><?php echo language('country_ht_name'); ?></option>
<option value="<?php echo language('country_hu_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_hu_code')) { echo "selected"; } ?>><?php echo language('country_hu_name'); ?></option>
<option value="<?php echo language('country_id_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_id_code')) { echo "selected"; } ?>><?php echo language('country_id_name'); ?></option>
<option value="<?php echo language('country_ie_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ie_code')) { echo "selected"; } ?>><?php echo language('country_ie_name'); ?></option>
<option value="<?php echo language('country_il_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_il_code')) { echo "selected"; } ?>><?php echo language('country_il_name'); ?></option>
<option value="<?php echo language('country_im_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_im_code')) { echo "selected"; } ?>><?php echo language('country_im_name'); ?></option>
<option value="<?php echo language('country_in_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_in_code')) { echo "selected"; } ?>><?php echo language('country_in_name'); ?></option>
<option value="<?php echo language('country_io_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_io_code')) { echo "selected"; } ?>><?php echo language('country_io_name'); ?></option>
<option value="<?php echo language('country_iq_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_iq_code')) { echo "selected"; } ?>><?php echo language('country_iq_name'); ?></option>
<option value="<?php echo language('country_ir_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ir_code')) { echo "selected"; } ?>><?php echo language('country_ir_name'); ?></option>
<option value="<?php echo language('country_is_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_is_code')) { echo "selected"; } ?>><?php echo language('country_is_name'); ?></option>
<option value="<?php echo language('country_it_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_it_code')) { echo "selected"; } ?>><?php echo language('country_it_name'); ?></option>
<option value="<?php echo language('country_je_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_je_code')) { echo "selected"; } ?>><?php echo language('country_je_name'); ?></option>
<option value="<?php echo language('country_jm_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_jm_code')) { echo "selected"; } ?>><?php echo language('country_jm_name'); ?></option>
<option value="<?php echo language('country_jo_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_jo_code')) { echo "selected"; } ?>><?php echo language('country_jo_name'); ?></option>
<option value="<?php echo language('country_jp_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_jp_code')) { echo "selected"; } ?>><?php echo language('country_jp_name'); ?></option>
<option value="<?php echo language('country_ke_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ke_code')) { echo "selected"; } ?>><?php echo language('country_ke_name'); ?></option>
<option value="<?php echo language('country_kg_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_kg_code')) { echo "selected"; } ?>><?php echo language('country_kg_name'); ?></option>
<option value="<?php echo language('country_kh_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_kh_code')) { echo "selected"; } ?>><?php echo language('country_kh_name'); ?></option>
<option value="<?php echo language('country_ki_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ki_code')) { echo "selected"; } ?>><?php echo language('country_ki_name'); ?></option>
<option value="<?php echo language('country_km_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_km_code')) { echo "selected"; } ?>><?php echo language('country_km_name'); ?></option>
<option value="<?php echo language('country_kn_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_kn_code')) { echo "selected"; } ?>><?php echo language('country_kn_name'); ?></option>
<option value="<?php echo language('country_kp_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_kp_code')) { echo "selected"; } ?>><?php echo language('country_kp_name'); ?></option>
<option value="<?php echo language('country_kr_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_kr_code')) { echo "selected"; } ?>><?php echo language('country_kr_name'); ?></option>
<option value="<?php echo language('country_kw_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_kw_code')) { echo "selected"; } ?>><?php echo language('country_kw_name'); ?></option>
<option value="<?php echo language('country_ky_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ky_code')) { echo "selected"; } ?>><?php echo language('country_ky_name'); ?></option>
<option value="<?php echo language('country_kz_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_kz_code')) { echo "selected"; } ?>><?php echo language('country_kz_name'); ?></option>
<option value="<?php echo language('country_la_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_la_code')) { echo "selected"; } ?>><?php echo language('country_la_name'); ?></option>
<option value="<?php echo language('country_lb_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_lb_code')) { echo "selected"; } ?>><?php echo language('country_lb_name'); ?></option>
<option value="<?php echo language('country_lc_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_lc_code')) { echo "selected"; } ?>><?php echo language('country_lc_name'); ?></option>
<option value="<?php echo language('country_li_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_li_code')) { echo "selected"; } ?>><?php echo language('country_li_name'); ?></option>
<option value="<?php echo language('country_lk_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_lk_code')) { echo "selected"; } ?>><?php echo language('country_lk_name'); ?></option>
<option value="<?php echo language('country_lr_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_lr_code')) { echo "selected"; } ?>><?php echo language('country_lr_name'); ?></option>
<option value="<?php echo language('country_ls_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ls_code')) { echo "selected"; } ?>><?php echo language('country_ls_name'); ?></option>
<option value="<?php echo language('country_lt_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_lt_code')) { echo "selected"; } ?>><?php echo language('country_lt_name'); ?></option>
<option value="<?php echo language('country_lu_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_lu_code')) { echo "selected"; } ?>><?php echo language('country_lu_name'); ?></option>
<option value="<?php echo language('country_lv_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_lv_code')) { echo "selected"; } ?>><?php echo language('country_lv_name'); ?></option>
<option value="<?php echo language('country_ly_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ly_code')) { echo "selected"; } ?>><?php echo language('country_ly_name'); ?></option>
<option value="<?php echo language('country_ma_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ma_code')) { echo "selected"; } ?>><?php echo language('country_ma_name'); ?></option>
<option value="<?php echo language('country_mc_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_mc_code')) { echo "selected"; } ?>><?php echo language('country_mc_name'); ?></option>
<option value="<?php echo language('country_md_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_md_code')) { echo "selected"; } ?>><?php echo language('country_md_name'); ?></option>
<option value="<?php echo language('country_me_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_me_code')) { echo "selected"; } ?>><?php echo language('country_me_name'); ?></option>
<option value="<?php echo language('country_mf_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_mf_code')) { echo "selected"; } ?>><?php echo language('country_mf_name'); ?></option>
<option value="<?php echo language('country_mg_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_mg_code')) { echo "selected"; } ?>><?php echo language('country_mg_name'); ?></option>
<option value="<?php echo language('country_mh_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_mh_code')) { echo "selected"; } ?>><?php echo language('country_mh_name'); ?></option>
<option value="<?php echo language('country_mk_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_mk_code')) { echo "selected"; } ?>><?php echo language('country_mk_name'); ?></option>
<option value="<?php echo language('country_ml_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ml_code')) { echo "selected"; } ?>><?php echo language('country_ml_name'); ?></option>
<option value="<?php echo language('country_mm_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_mm_code')) { echo "selected"; } ?>><?php echo language('country_mm_name'); ?></option>
<option value="<?php echo language('country_mn_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_mn_code')) { echo "selected"; } ?>><?php echo language('country_mn_name'); ?></option>
<option value="<?php echo language('country_mo_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_mo_code')) { echo "selected"; } ?>><?php echo language('country_mo_name'); ?></option>
<option value="<?php echo language('country_mp_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_mp_code')) { echo "selected"; } ?>><?php echo language('country_mp_name'); ?></option>
<option value="<?php echo language('country_mq_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_mq_code')) { echo "selected"; } ?>><?php echo language('country_mq_name'); ?></option>
<option value="<?php echo language('country_mr_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_mr_code')) { echo "selected"; } ?>><?php echo language('country_mr_name'); ?></option>
<option value="<?php echo language('country_ms_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ms_code')) { echo "selected"; } ?>><?php echo language('country_ms_name'); ?></option>
<option value="<?php echo language('country_mt_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_mt_code')) { echo "selected"; } ?>><?php echo language('country_mt_name'); ?></option>
<option value="<?php echo language('country_mu_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_mu_code')) { echo "selected"; } ?>><?php echo language('country_mu_name'); ?></option>
<option value="<?php echo language('country_mv_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_mv_code')) { echo "selected"; } ?>><?php echo language('country_mv_name'); ?></option>
<option value="<?php echo language('country_mw_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_mw_code')) { echo "selected"; } ?>><?php echo language('country_mw_name'); ?></option>
<option value="<?php echo language('country_mx_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_mx_code')) { echo "selected"; } ?>><?php echo language('country_mx_name'); ?></option>
<option value="<?php echo language('country_my_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_my_code')) { echo "selected"; } ?>><?php echo language('country_my_name'); ?></option>
<option value="<?php echo language('country_mz_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_mz_code')) { echo "selected"; } ?>><?php echo language('country_mz_name'); ?></option>
<option value="<?php echo language('country_na_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_na_code')) { echo "selected"; } ?>><?php echo language('country_na_name'); ?></option>
<option value="<?php echo language('country_nc_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_nc_code')) { echo "selected"; } ?>><?php echo language('country_nc_name'); ?></option>
<option value="<?php echo language('country_ne_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ne_code')) { echo "selected"; } ?>><?php echo language('country_ne_name'); ?></option>
<option value="<?php echo language('country_nf_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_nf_code')) { echo "selected"; } ?>><?php echo language('country_nf_name'); ?></option>
<option value="<?php echo language('country_ng_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ng_code')) { echo "selected"; } ?>><?php echo language('country_ng_name'); ?></option>
<option value="<?php echo language('country_ni_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ni_code')) { echo "selected"; } ?>><?php echo language('country_ni_name'); ?></option>
<option value="<?php echo language('country_nl_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_nl_code')) { echo "selected"; } ?>><?php echo language('country_nl_name'); ?></option>
<option value="<?php echo language('country_no_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_no_code')) { echo "selected"; } ?>><?php echo language('country_no_name'); ?></option>
<option value="<?php echo language('country_np_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_np_code')) { echo "selected"; } ?>><?php echo language('country_np_name'); ?></option>
<option value="<?php echo language('country_nr_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_nr_code')) { echo "selected"; } ?>><?php echo language('country_nr_name'); ?></option>
<option value="<?php echo language('country_nu_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_nu_code')) { echo "selected"; } ?>><?php echo language('country_nu_name'); ?></option>
<option value="<?php echo language('country_nz_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_nz_code')) { echo "selected"; } ?>><?php echo language('country_nz_name'); ?></option>
<option value="<?php echo language('country_om_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_om_code')) { echo "selected"; } ?>><?php echo language('country_om_name'); ?></option>
<option value="<?php echo language('country_pa_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_pa_code')) { echo "selected"; } ?>><?php echo language('country_pa_name'); ?></option>
<option value="<?php echo language('country_pe_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_pe_code')) { echo "selected"; } ?>><?php echo language('country_pe_name'); ?></option>
<option value="<?php echo language('country_pf_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_pf_code')) { echo "selected"; } ?>><?php echo language('country_pf_name'); ?></option>
<option value="<?php echo language('country_pg_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_pg_code')) { echo "selected"; } ?>><?php echo language('country_pg_name'); ?></option>
<option value="<?php echo language('country_ph_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ph_code')) { echo "selected"; } ?>><?php echo language('country_ph_name'); ?></option>
<option value="<?php echo language('country_pk_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_pk_code')) { echo "selected"; } ?>><?php echo language('country_pk_name'); ?></option>
<option value="<?php echo language('country_pl_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_pl_code')) { echo "selected"; } ?>><?php echo language('country_pl_name'); ?></option>
<option value="<?php echo language('country_pm_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_pm_code')) { echo "selected"; } ?>><?php echo language('country_pm_name'); ?></option>
<option value="<?php echo language('country_pn_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_pn_code')) { echo "selected"; } ?>><?php echo language('country_pn_name'); ?></option>
<option value="<?php echo language('country_pr_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_pr_code')) { echo "selected"; } ?>><?php echo language('country_pr_name'); ?></option>
<option value="<?php echo language('country_ps_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ps_code')) { echo "selected"; } ?>><?php echo language('country_ps_name'); ?></option>
<option value="<?php echo language('country_pt_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_pt_code')) { echo "selected"; } ?>><?php echo language('country_pt_name'); ?></option>
<option value="<?php echo language('country_pw_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_pw_code')) { echo "selected"; } ?>><?php echo language('country_pw_name'); ?></option>
<option value="<?php echo language('country_py_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_py_code')) { echo "selected"; } ?>><?php echo language('country_py_name'); ?></option>
<option value="<?php echo language('country_qa_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_qa_code')) { echo "selected"; } ?>><?php echo language('country_qa_name'); ?></option>
<option value="<?php echo language('country_re_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_re_code')) { echo "selected"; } ?>><?php echo language('country_re_name'); ?></option>
<option value="<?php echo language('country_ro_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ro_code')) { echo "selected"; } ?>><?php echo language('country_ro_name'); ?></option>
<option value="<?php echo language('country_rs_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_rs_code')) { echo "selected"; } ?>><?php echo language('country_rs_name'); ?></option>
<option value="<?php echo language('country_ru_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ru_code')) { echo "selected"; } ?>><?php echo language('country_ru_name'); ?></option>
<option value="<?php echo language('country_rw_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_rw_code')) { echo "selected"; } ?>><?php echo language('country_rw_name'); ?></option>
<option value="<?php echo language('country_sa_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_sa_code')) { echo "selected"; } ?>><?php echo language('country_sa_name'); ?></option>
<option value="<?php echo language('country_sb_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_sb_code')) { echo "selected"; } ?>><?php echo language('country_sb_name'); ?></option>
<option value="<?php echo language('country_sc_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_sc_code')) { echo "selected"; } ?>><?php echo language('country_sc_name'); ?></option>
<option value="<?php echo language('country_sd_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_sd_code')) { echo "selected"; } ?>><?php echo language('country_sd_name'); ?></option>
<option value="<?php echo language('country_se_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_se_code')) { echo "selected"; } ?>><?php echo language('country_se_name'); ?></option>
<option value="<?php echo language('country_sg_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_sg_code')) { echo "selected"; } ?>><?php echo language('country_sg_name'); ?></option>
<option value="<?php echo language('country_sh_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_sh_code')) { echo "selected"; } ?>><?php echo language('country_sh_name'); ?></option>
<option value="<?php echo language('country_si_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_si_code')) { echo "selected"; } ?>><?php echo language('country_si_name'); ?></option>
<option value="<?php echo language('country_sj_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_sj_code')) { echo "selected"; } ?>><?php echo language('country_sj_name'); ?></option>
<option value="<?php echo language('country_sk_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_sk_code')) { echo "selected"; } ?>><?php echo language('country_sk_name'); ?></option>
<option value="<?php echo language('country_sl_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_sl_code')) { echo "selected"; } ?>><?php echo language('country_sl_name'); ?></option>
<option value="<?php echo language('country_sm_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_sm_code')) { echo "selected"; } ?>><?php echo language('country_sm_name'); ?></option>
<option value="<?php echo language('country_sn_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_sn_code')) { echo "selected"; } ?>><?php echo language('country_sn_name'); ?></option>
<option value="<?php echo language('country_so_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_so_code')) { echo "selected"; } ?>><?php echo language('country_so_name'); ?></option>
<option value="<?php echo language('country_sr_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_sr_code')) { echo "selected"; } ?>><?php echo language('country_sr_name'); ?></option>
<option value="<?php echo language('country_ss_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ss_code')) { echo "selected"; } ?>><?php echo language('country_ss_name'); ?></option>
<option value="<?php echo language('country_st_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_st_code')) { echo "selected"; } ?>><?php echo language('country_st_name'); ?></option>
<option value="<?php echo language('country_sv_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_sv_code')) { echo "selected"; } ?>><?php echo language('country_sv_name'); ?></option>
<option value="<?php echo language('country_sx_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_sx_code')) { echo "selected"; } ?>><?php echo language('country_sx_name'); ?></option>
<option value="<?php echo language('country_sy_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_sy_code')) { echo "selected"; } ?>><?php echo language('country_sy_name'); ?></option>
<option value="<?php echo language('country_sz_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_sz_code')) { echo "selected"; } ?>><?php echo language('country_sz_name'); ?></option>
<option value="<?php echo language('country_tc_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_tc_code')) { echo "selected"; } ?>><?php echo language('country_tc_name'); ?></option>
<option value="<?php echo language('country_td_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_td_code')) { echo "selected"; } ?>><?php echo language('country_td_name'); ?></option>
<option value="<?php echo language('country_tf_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_tf_code')) { echo "selected"; } ?>><?php echo language('country_tf_name'); ?></option>
<option value="<?php echo language('country_tg_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_tg_code')) { echo "selected"; } ?>><?php echo language('country_tg_name'); ?></option>
<option value="<?php echo language('country_th_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_th_code')) { echo "selected"; } ?>><?php echo language('country_th_name'); ?></option>
<option value="<?php echo language('country_tj_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_tj_code')) { echo "selected"; } ?>><?php echo language('country_tj_name'); ?></option>
<option value="<?php echo language('country_tk_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_tk_code')) { echo "selected"; } ?>><?php echo language('country_tk_name'); ?></option>
<option value="<?php echo language('country_tl_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_tl_code')) { echo "selected"; } ?>><?php echo language('country_tl_name'); ?></option>
<option value="<?php echo language('country_tm_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_tm_code')) { echo "selected"; } ?>><?php echo language('country_tm_name'); ?></option>
<option value="<?php echo language('country_tn_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_tn_code')) { echo "selected"; } ?>><?php echo language('country_tn_name'); ?></option>
<option value="<?php echo language('country_to_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_to_code')) { echo "selected"; } ?>><?php echo language('country_to_name'); ?></option>
<option value="<?php echo language('country_tr_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_tr_code')) { echo "selected"; } ?>><?php echo language('country_tr_name'); ?></option>
<option value="<?php echo language('country_tt_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_tt_code')) { echo "selected"; } ?>><?php echo language('country_tt_name'); ?></option>
<option value="<?php echo language('country_tv_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_tv_code')) { echo "selected"; } ?>><?php echo language('country_tv_name'); ?></option>
<option value="<?php echo language('country_tw_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_tw_code')) { echo "selected"; } ?>><?php echo language('country_tw_name'); ?></option>
<option value="<?php echo language('country_tz_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_tz_code')) { echo "selected"; } ?>><?php echo language('country_tz_name'); ?></option>
<option value="<?php echo language('country_ua_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ua_code')) { echo "selected"; } ?>><?php echo language('country_ua_name'); ?></option>
<option value="<?php echo language('country_ug_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ug_code')) { echo "selected"; } ?>><?php echo language('country_ug_name'); ?></option>
<option value="<?php echo language('country_um_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_um_code')) { echo "selected"; } ?>><?php echo language('country_um_name'); ?></option>
<option value="<?php echo language('country_us_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_us_code')) { echo "selected"; } ?>><?php echo language('country_us_name'); ?></option>
<option value="<?php echo language('country_uy_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_uy_code')) { echo "selected"; } ?>><?php echo language('country_uy_name'); ?></option>
<option value="<?php echo language('country_uz_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_uz_code')) { echo "selected"; } ?>><?php echo language('country_uz_name'); ?></option>
<option value="<?php echo language('country_va_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_va_code')) { echo "selected"; } ?>><?php echo language('country_va_name'); ?></option>
<option value="<?php echo language('country_vc_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_vc_code')) { echo "selected"; } ?>><?php echo language('country_vc_name'); ?></option>
<option value="<?php echo language('country_ve_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ve_code')) { echo "selected"; } ?>><?php echo language('country_ve_name'); ?></option>
<option value="<?php echo language('country_vg_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_vg_code')) { echo "selected"; } ?>><?php echo language('country_vg_name'); ?></option>
<option value="<?php echo language('country_vi_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_vi_code')) { echo "selected"; } ?>><?php echo language('country_vi_name'); ?></option>
<option value="<?php echo language('country_vn_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_vn_code')) { echo "selected"; } ?>><?php echo language('country_vn_name'); ?></option>
<option value="<?php echo language('country_vu_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_vu_code')) { echo "selected"; } ?>><?php echo language('country_vu_name'); ?></option>
<option value="<?php echo language('country_wf_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_wf_code')) { echo "selected"; } ?>><?php echo language('country_wf_name'); ?></option>
<option value="<?php echo language('country_ws_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ws_code')) { echo "selected"; } ?>><?php echo language('country_ws_name'); ?></option>
<option value="<?php echo language('country_ye_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_ye_code')) { echo "selected"; } ?>><?php echo language('country_ye_name'); ?></option>
<option value="<?php echo language('country_yt_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_yt_code')) { echo "selected"; } ?>><?php echo language('country_yt_name'); ?></option>
<option value="<?php echo language('country_za_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_za_code')) { echo "selected"; } ?>><?php echo language('country_za_name'); ?></option>
<option value="<?php echo language('country_zm_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_zm_code')) { echo "selected"; } ?>><?php echo language('country_zm_name'); ?></option>
<option value="<?php echo language('country_zw_code'); ?>" <?php if ($session['response']['param']['address']['Work']['country'] == language('country_zw_code')) { echo "selected"; } ?>><?php echo language('country_zw_name'); ?></option>
			</select>
		</div>
	</fieldset>

	<?php
	if ( $XCOW_B['sciomino']['skin-privacy'] == "yes" ) {
		echo "<fieldset>";
		echo "<h3 class='legend'>".language('sciomio_text_user_profile_contact_home')."</h3>";
		echo "<div class='inputset'>";
		echo "<label for='email'>".language('sciomio_text_user_profile_contact_home_email')."</label>";
		echo "<input class='text' type='email' name='email' id='email' value='".$session['response']['param']['contact']['Home']['email'],"' maxlength='128' />";
		echo "</div>";
		echo "<div class='inputset'>";
		echo "<label for='telHome'>".language('sciomio_text_user_profile_contact_home_phone')."</label>";
		echo "<input class='text' type='text' name='telHome' id='telHome' value='".$session['response']['param']['contact']['Home']['telHome']."' maxlength='128' />";
		echo "</div>";
		echo "<div class='inputset'>";
		echo "<label for='telMobile'>".language('sciomio_text_user_profile_contact_home_mobile')."</label>";
		echo "<input class='text' type='text' name='telMobile' id='telMobile' value='".$session['response']['param']['contact']['Home']['telMobile']."' maxlength='128' />";
		echo "</div>";
		echo "</fieldset>";
	}
	?>

	<fieldset>
		<h3 class="legend"><?php echo language('sciomio_text_user_profile_address_home'); ?></h3>
		<?php
		if ( $XCOW_B['sciomino']['skin-privacy'] == "yes" ) {
			echo "<div class='inputset'>";
			echo "<label for='address'>".language('sciomio_text_user_profile_address_home_address')."</label>";
			echo "<input class='text' type='text' name='address' id='address' value='".htmlTokens($session['response']['param']['address']['Home']['address'])."' maxlength='128' />";
			echo "</div>";
			echo "<div class='inputset'>";
			echo "<label for='postalcode'>".language('sciomio_text_user_profile_address_home_postalcode')."</label>";
			echo "<input class='text' type='text' name='postalcode' id='postalcode' value='".htmlTokens($session['response']['param']['address']['Home']['postalcode'])."' maxlength='128' />";
			echo "</div>";
		}
		?>
		<div class="inputset">
			<label for="city"><?php echo language('sciomio_text_user_profile_address_home_city'); ?></label>
			<input class="text autocomplete" data-results="<?php echo $XCOW_B['url'] ?>/snippet/suggest-local?type=hometown" type="text" name="city" id="city" value="<?php echo htmlTokens($session['response']['param']['address']['Home']['city']); ?>" maxlength="128" />
		</div>
		<div class="inputset">
			<label for="country"><?php echo language('sciomio_text_user_profile_address_home_country'); ?></label>
			<select name="country" id="country">
				<option value="<?php echo language('country_nl_code'); ?>"><?php echo language('sciomio_text_user_profile_address_country_select'); ?></option>
<option value="<?php echo language('country_ad_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ad_code')) { echo "selected"; } ?>><?php echo language('country_ad_name'); ?></option>
<option value="<?php echo language('country_ae_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ae_code')) { echo "selected"; } ?>><?php echo language('country_ae_name'); ?></option>
<option value="<?php echo language('country_af_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_af_code')) { echo "selected"; } ?>><?php echo language('country_af_name'); ?></option>
<option value="<?php echo language('country_ag_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ag_code')) { echo "selected"; } ?>><?php echo language('country_ag_name'); ?></option>
<option value="<?php echo language('country_ai_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ai_code')) { echo "selected"; } ?>><?php echo language('country_ai_name'); ?></option>
<option value="<?php echo language('country_al_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_al_code')) { echo "selected"; } ?>><?php echo language('country_al_name'); ?></option>
<option value="<?php echo language('country_am_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_am_code')) { echo "selected"; } ?>><?php echo language('country_am_name'); ?></option>
<option value="<?php echo language('country_ao_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ao_code')) { echo "selected"; } ?>><?php echo language('country_ao_name'); ?></option>
<option value="<?php echo language('country_aq_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_aq_code')) { echo "selected"; } ?>><?php echo language('country_aq_name'); ?></option>
<option value="<?php echo language('country_ar_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ar_code')) { echo "selected"; } ?>><?php echo language('country_ar_name'); ?></option>
<option value="<?php echo language('country_as_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_as_code')) { echo "selected"; } ?>><?php echo language('country_as_name'); ?></option>
<option value="<?php echo language('country_at_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_at_code')) { echo "selected"; } ?>><?php echo language('country_at_name'); ?></option>
<option value="<?php echo language('country_au_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_au_code')) { echo "selected"; } ?>><?php echo language('country_au_name'); ?></option>
<option value="<?php echo language('country_aw_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_aw_code')) { echo "selected"; } ?>><?php echo language('country_aw_name'); ?></option>
<option value="<?php echo language('country_ax_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ax_code')) { echo "selected"; } ?>><?php echo language('country_ax_name'); ?></option>
<option value="<?php echo language('country_az_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_az_code')) { echo "selected"; } ?>><?php echo language('country_az_name'); ?></option>
<option value="<?php echo language('country_ba_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ba_code')) { echo "selected"; } ?>><?php echo language('country_ba_name'); ?></option>
<option value="<?php echo language('country_bb_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_bb_code')) { echo "selected"; } ?>><?php echo language('country_bb_name'); ?></option>
<option value="<?php echo language('country_bd_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_bd_code')) { echo "selected"; } ?>><?php echo language('country_bd_name'); ?></option>
<option value="<?php echo language('country_be_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_be_code')) { echo "selected"; } ?>><?php echo language('country_be_name'); ?></option>
<option value="<?php echo language('country_bf_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_bf_code')) { echo "selected"; } ?>><?php echo language('country_bf_name'); ?></option>
<option value="<?php echo language('country_bg_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_bg_code')) { echo "selected"; } ?>><?php echo language('country_bg_name'); ?></option>
<option value="<?php echo language('country_bh_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_bh_code')) { echo "selected"; } ?>><?php echo language('country_bh_name'); ?></option>
<option value="<?php echo language('country_bi_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_bi_code')) { echo "selected"; } ?>><?php echo language('country_bi_name'); ?></option>
<option value="<?php echo language('country_bj_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_bj_code')) { echo "selected"; } ?>><?php echo language('country_bj_name'); ?></option>
<option value="<?php echo language('country_bl_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_bl_code')) { echo "selected"; } ?>><?php echo language('country_bl_name'); ?></option>
<option value="<?php echo language('country_bm_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_bm_code')) { echo "selected"; } ?>><?php echo language('country_bm_name'); ?></option>
<option value="<?php echo language('country_bn_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_bn_code')) { echo "selected"; } ?>><?php echo language('country_bn_name'); ?></option>
<option value="<?php echo language('country_bo_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_bo_code')) { echo "selected"; } ?>><?php echo language('country_bo_name'); ?></option>
<option value="<?php echo language('country_bq_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_bq_code')) { echo "selected"; } ?>><?php echo language('country_bq_name'); ?></option>
<option value="<?php echo language('country_br_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_br_code')) { echo "selected"; } ?>><?php echo language('country_br_name'); ?></option>
<option value="<?php echo language('country_bs_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_bs_code')) { echo "selected"; } ?>><?php echo language('country_bs_name'); ?></option>
<option value="<?php echo language('country_bt_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_bt_code')) { echo "selected"; } ?>><?php echo language('country_bt_name'); ?></option>
<option value="<?php echo language('country_bv_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_bv_code')) { echo "selected"; } ?>><?php echo language('country_bv_name'); ?></option>
<option value="<?php echo language('country_bw_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_bw_code')) { echo "selected"; } ?>><?php echo language('country_bw_name'); ?></option>
<option value="<?php echo language('country_by_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_by_code')) { echo "selected"; } ?>><?php echo language('country_by_name'); ?></option>
<option value="<?php echo language('country_bz_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_bz_code')) { echo "selected"; } ?>><?php echo language('country_bz_name'); ?></option>
<option value="<?php echo language('country_ca_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ca_code')) { echo "selected"; } ?>><?php echo language('country_ca_name'); ?></option>
<option value="<?php echo language('country_cc_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_cc_code')) { echo "selected"; } ?>><?php echo language('country_cc_name'); ?></option>
<option value="<?php echo language('country_cd_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_cd_code')) { echo "selected"; } ?>><?php echo language('country_cd_name'); ?></option>
<option value="<?php echo language('country_cf_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_cf_code')) { echo "selected"; } ?>><?php echo language('country_cf_name'); ?></option>
<option value="<?php echo language('country_cg_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_cg_code')) { echo "selected"; } ?>><?php echo language('country_cg_name'); ?></option>
<option value="<?php echo language('country_ch_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ch_code')) { echo "selected"; } ?>><?php echo language('country_ch_name'); ?></option>
<option value="<?php echo language('country_ci_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ci_code')) { echo "selected"; } ?>><?php echo language('country_ci_name'); ?></option>
<option value="<?php echo language('country_ck_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ck_code')) { echo "selected"; } ?>><?php echo language('country_ck_name'); ?></option>
<option value="<?php echo language('country_cl_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_cl_code')) { echo "selected"; } ?>><?php echo language('country_cl_name'); ?></option>
<option value="<?php echo language('country_cm_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_cm_code')) { echo "selected"; } ?>><?php echo language('country_cm_name'); ?></option>
<option value="<?php echo language('country_cn_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_cn_code')) { echo "selected"; } ?>><?php echo language('country_cn_name'); ?></option>
<option value="<?php echo language('country_co_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_co_code')) { echo "selected"; } ?>><?php echo language('country_co_name'); ?></option>
<option value="<?php echo language('country_cr_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_cr_code')) { echo "selected"; } ?>><?php echo language('country_cr_name'); ?></option>
<option value="<?php echo language('country_cu_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_cu_code')) { echo "selected"; } ?>><?php echo language('country_cu_name'); ?></option>
<option value="<?php echo language('country_cv_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_cv_code')) { echo "selected"; } ?>><?php echo language('country_cv_name'); ?></option>
<option value="<?php echo language('country_cw_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_cw_code')) { echo "selected"; } ?>><?php echo language('country_cw_name'); ?></option>
<option value="<?php echo language('country_cx_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_cx_code')) { echo "selected"; } ?>><?php echo language('country_cx_name'); ?></option>
<option value="<?php echo language('country_cy_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_cy_code')) { echo "selected"; } ?>><?php echo language('country_cy_name'); ?></option>
<option value="<?php echo language('country_cz_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_cz_code')) { echo "selected"; } ?>><?php echo language('country_cz_name'); ?></option>
<option value="<?php echo language('country_de_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_de_code')) { echo "selected"; } ?>><?php echo language('country_de_name'); ?></option>
<option value="<?php echo language('country_dj_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_dj_code')) { echo "selected"; } ?>><?php echo language('country_dj_name'); ?></option>
<option value="<?php echo language('country_dk_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_dk_code')) { echo "selected"; } ?>><?php echo language('country_dk_name'); ?></option>
<option value="<?php echo language('country_dm_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_dm_code')) { echo "selected"; } ?>><?php echo language('country_dm_name'); ?></option>
<option value="<?php echo language('country_do_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_do_code')) { echo "selected"; } ?>><?php echo language('country_do_name'); ?></option>
<option value="<?php echo language('country_dz_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_dz_code')) { echo "selected"; } ?>><?php echo language('country_dz_name'); ?></option>
<option value="<?php echo language('country_ec_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ec_code')) { echo "selected"; } ?>><?php echo language('country_ec_name'); ?></option>
<option value="<?php echo language('country_ee_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ee_code')) { echo "selected"; } ?>><?php echo language('country_ee_name'); ?></option>
<option value="<?php echo language('country_eg_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_eg_code')) { echo "selected"; } ?>><?php echo language('country_eg_name'); ?></option>
<option value="<?php echo language('country_eh_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_eh_code')) { echo "selected"; } ?>><?php echo language('country_eh_name'); ?></option>
<option value="<?php echo language('country_er_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_er_code')) { echo "selected"; } ?>><?php echo language('country_er_name'); ?></option>
<option value="<?php echo language('country_es_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_es_code')) { echo "selected"; } ?>><?php echo language('country_es_name'); ?></option>
<option value="<?php echo language('country_et_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_et_code')) { echo "selected"; } ?>><?php echo language('country_et_name'); ?></option>
<option value="<?php echo language('country_fi_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_fi_code')) { echo "selected"; } ?>><?php echo language('country_fi_name'); ?></option>
<option value="<?php echo language('country_fj_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_fj_code')) { echo "selected"; } ?>><?php echo language('country_fj_name'); ?></option>
<option value="<?php echo language('country_fk_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_fk_code')) { echo "selected"; } ?>><?php echo language('country_fk_name'); ?></option>
<option value="<?php echo language('country_fm_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_fm_code')) { echo "selected"; } ?>><?php echo language('country_fm_name'); ?></option>
<option value="<?php echo language('country_fo_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_fo_code')) { echo "selected"; } ?>><?php echo language('country_fo_name'); ?></option>
<option value="<?php echo language('country_fr_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_fr_code')) { echo "selected"; } ?>><?php echo language('country_fr_name'); ?></option>
<option value="<?php echo language('country_ga_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ga_code')) { echo "selected"; } ?>><?php echo language('country_ga_name'); ?></option>
<option value="<?php echo language('country_gb_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_gb_code')) { echo "selected"; } ?>><?php echo language('country_gb_name'); ?></option>
<option value="<?php echo language('country_gd_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_gd_code')) { echo "selected"; } ?>><?php echo language('country_gd_name'); ?></option>
<option value="<?php echo language('country_ge_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ge_code')) { echo "selected"; } ?>><?php echo language('country_ge_name'); ?></option>
<option value="<?php echo language('country_gf_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_gf_code')) { echo "selected"; } ?>><?php echo language('country_gf_name'); ?></option>
<option value="<?php echo language('country_gg_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_gg_code')) { echo "selected"; } ?>><?php echo language('country_gg_name'); ?></option>
<option value="<?php echo language('country_gh_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_gh_code')) { echo "selected"; } ?>><?php echo language('country_gh_name'); ?></option>
<option value="<?php echo language('country_gi_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_gi_code')) { echo "selected"; } ?>><?php echo language('country_gi_name'); ?></option>
<option value="<?php echo language('country_gl_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_gl_code')) { echo "selected"; } ?>><?php echo language('country_gl_name'); ?></option>
<option value="<?php echo language('country_gm_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_gm_code')) { echo "selected"; } ?>><?php echo language('country_gm_name'); ?></option>
<option value="<?php echo language('country_gn_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_gn_code')) { echo "selected"; } ?>><?php echo language('country_gn_name'); ?></option>
<option value="<?php echo language('country_gp_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_gp_code')) { echo "selected"; } ?>><?php echo language('country_gp_name'); ?></option>
<option value="<?php echo language('country_gq_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_gq_code')) { echo "selected"; } ?>><?php echo language('country_gq_name'); ?></option>
<option value="<?php echo language('country_gr_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_gr_code')) { echo "selected"; } ?>><?php echo language('country_gr_name'); ?></option>
<option value="<?php echo language('country_gs_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_gs_code')) { echo "selected"; } ?>><?php echo language('country_gs_name'); ?></option>
<option value="<?php echo language('country_gt_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_gt_code')) { echo "selected"; } ?>><?php echo language('country_gt_name'); ?></option>
<option value="<?php echo language('country_gu_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_gu_code')) { echo "selected"; } ?>><?php echo language('country_gu_name'); ?></option>
<option value="<?php echo language('country_gw_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_gw_code')) { echo "selected"; } ?>><?php echo language('country_gw_name'); ?></option>
<option value="<?php echo language('country_gy_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_gy_code')) { echo "selected"; } ?>><?php echo language('country_gy_name'); ?></option>
<option value="<?php echo language('country_hk_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_hk_code')) { echo "selected"; } ?>><?php echo language('country_hk_name'); ?></option>
<option value="<?php echo language('country_hm_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_hm_code')) { echo "selected"; } ?>><?php echo language('country_hm_name'); ?></option>
<option value="<?php echo language('country_hn_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_hn_code')) { echo "selected"; } ?>><?php echo language('country_hn_name'); ?></option>
<option value="<?php echo language('country_hr_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_hr_code')) { echo "selected"; } ?>><?php echo language('country_hr_name'); ?></option>
<option value="<?php echo language('country_ht_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ht_code')) { echo "selected"; } ?>><?php echo language('country_ht_name'); ?></option>
<option value="<?php echo language('country_hu_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_hu_code')) { echo "selected"; } ?>><?php echo language('country_hu_name'); ?></option>
<option value="<?php echo language('country_id_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_id_code')) { echo "selected"; } ?>><?php echo language('country_id_name'); ?></option>
<option value="<?php echo language('country_ie_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ie_code')) { echo "selected"; } ?>><?php echo language('country_ie_name'); ?></option>
<option value="<?php echo language('country_il_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_il_code')) { echo "selected"; } ?>><?php echo language('country_il_name'); ?></option>
<option value="<?php echo language('country_im_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_im_code')) { echo "selected"; } ?>><?php echo language('country_im_name'); ?></option>
<option value="<?php echo language('country_in_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_in_code')) { echo "selected"; } ?>><?php echo language('country_in_name'); ?></option>
<option value="<?php echo language('country_io_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_io_code')) { echo "selected"; } ?>><?php echo language('country_io_name'); ?></option>
<option value="<?php echo language('country_iq_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_iq_code')) { echo "selected"; } ?>><?php echo language('country_iq_name'); ?></option>
<option value="<?php echo language('country_ir_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ir_code')) { echo "selected"; } ?>><?php echo language('country_ir_name'); ?></option>
<option value="<?php echo language('country_is_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_is_code')) { echo "selected"; } ?>><?php echo language('country_is_name'); ?></option>
<option value="<?php echo language('country_it_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_it_code')) { echo "selected"; } ?>><?php echo language('country_it_name'); ?></option>
<option value="<?php echo language('country_je_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_je_code')) { echo "selected"; } ?>><?php echo language('country_je_name'); ?></option>
<option value="<?php echo language('country_jm_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_jm_code')) { echo "selected"; } ?>><?php echo language('country_jm_name'); ?></option>
<option value="<?php echo language('country_jo_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_jo_code')) { echo "selected"; } ?>><?php echo language('country_jo_name'); ?></option>
<option value="<?php echo language('country_jp_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_jp_code')) { echo "selected"; } ?>><?php echo language('country_jp_name'); ?></option>
<option value="<?php echo language('country_ke_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ke_code')) { echo "selected"; } ?>><?php echo language('country_ke_name'); ?></option>
<option value="<?php echo language('country_kg_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_kg_code')) { echo "selected"; } ?>><?php echo language('country_kg_name'); ?></option>
<option value="<?php echo language('country_kh_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_kh_code')) { echo "selected"; } ?>><?php echo language('country_kh_name'); ?></option>
<option value="<?php echo language('country_ki_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ki_code')) { echo "selected"; } ?>><?php echo language('country_ki_name'); ?></option>
<option value="<?php echo language('country_km_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_km_code')) { echo "selected"; } ?>><?php echo language('country_km_name'); ?></option>
<option value="<?php echo language('country_kn_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_kn_code')) { echo "selected"; } ?>><?php echo language('country_kn_name'); ?></option>
<option value="<?php echo language('country_kp_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_kp_code')) { echo "selected"; } ?>><?php echo language('country_kp_name'); ?></option>
<option value="<?php echo language('country_kr_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_kr_code')) { echo "selected"; } ?>><?php echo language('country_kr_name'); ?></option>
<option value="<?php echo language('country_kw_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_kw_code')) { echo "selected"; } ?>><?php echo language('country_kw_name'); ?></option>
<option value="<?php echo language('country_ky_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ky_code')) { echo "selected"; } ?>><?php echo language('country_ky_name'); ?></option>
<option value="<?php echo language('country_kz_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_kz_code')) { echo "selected"; } ?>><?php echo language('country_kz_name'); ?></option>
<option value="<?php echo language('country_la_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_la_code')) { echo "selected"; } ?>><?php echo language('country_la_name'); ?></option>
<option value="<?php echo language('country_lb_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_lb_code')) { echo "selected"; } ?>><?php echo language('country_lb_name'); ?></option>
<option value="<?php echo language('country_lc_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_lc_code')) { echo "selected"; } ?>><?php echo language('country_lc_name'); ?></option>
<option value="<?php echo language('country_li_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_li_code')) { echo "selected"; } ?>><?php echo language('country_li_name'); ?></option>
<option value="<?php echo language('country_lk_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_lk_code')) { echo "selected"; } ?>><?php echo language('country_lk_name'); ?></option>
<option value="<?php echo language('country_lr_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_lr_code')) { echo "selected"; } ?>><?php echo language('country_lr_name'); ?></option>
<option value="<?php echo language('country_ls_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ls_code')) { echo "selected"; } ?>><?php echo language('country_ls_name'); ?></option>
<option value="<?php echo language('country_lt_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_lt_code')) { echo "selected"; } ?>><?php echo language('country_lt_name'); ?></option>
<option value="<?php echo language('country_lu_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_lu_code')) { echo "selected"; } ?>><?php echo language('country_lu_name'); ?></option>
<option value="<?php echo language('country_lv_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_lv_code')) { echo "selected"; } ?>><?php echo language('country_lv_name'); ?></option>
<option value="<?php echo language('country_ly_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ly_code')) { echo "selected"; } ?>><?php echo language('country_ly_name'); ?></option>
<option value="<?php echo language('country_ma_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ma_code')) { echo "selected"; } ?>><?php echo language('country_ma_name'); ?></option>
<option value="<?php echo language('country_mc_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_mc_code')) { echo "selected"; } ?>><?php echo language('country_mc_name'); ?></option>
<option value="<?php echo language('country_md_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_md_code')) { echo "selected"; } ?>><?php echo language('country_md_name'); ?></option>
<option value="<?php echo language('country_me_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_me_code')) { echo "selected"; } ?>><?php echo language('country_me_name'); ?></option>
<option value="<?php echo language('country_mf_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_mf_code')) { echo "selected"; } ?>><?php echo language('country_mf_name'); ?></option>
<option value="<?php echo language('country_mg_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_mg_code')) { echo "selected"; } ?>><?php echo language('country_mg_name'); ?></option>
<option value="<?php echo language('country_mh_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_mh_code')) { echo "selected"; } ?>><?php echo language('country_mh_name'); ?></option>
<option value="<?php echo language('country_mk_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_mk_code')) { echo "selected"; } ?>><?php echo language('country_mk_name'); ?></option>
<option value="<?php echo language('country_ml_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ml_code')) { echo "selected"; } ?>><?php echo language('country_ml_name'); ?></option>
<option value="<?php echo language('country_mm_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_mm_code')) { echo "selected"; } ?>><?php echo language('country_mm_name'); ?></option>
<option value="<?php echo language('country_mn_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_mn_code')) { echo "selected"; } ?>><?php echo language('country_mn_name'); ?></option>
<option value="<?php echo language('country_mo_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_mo_code')) { echo "selected"; } ?>><?php echo language('country_mo_name'); ?></option>
<option value="<?php echo language('country_mp_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_mp_code')) { echo "selected"; } ?>><?php echo language('country_mp_name'); ?></option>
<option value="<?php echo language('country_mq_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_mq_code')) { echo "selected"; } ?>><?php echo language('country_mq_name'); ?></option>
<option value="<?php echo language('country_mr_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_mr_code')) { echo "selected"; } ?>><?php echo language('country_mr_name'); ?></option>
<option value="<?php echo language('country_ms_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ms_code')) { echo "selected"; } ?>><?php echo language('country_ms_name'); ?></option>
<option value="<?php echo language('country_mt_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_mt_code')) { echo "selected"; } ?>><?php echo language('country_mt_name'); ?></option>
<option value="<?php echo language('country_mu_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_mu_code')) { echo "selected"; } ?>><?php echo language('country_mu_name'); ?></option>
<option value="<?php echo language('country_mv_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_mv_code')) { echo "selected"; } ?>><?php echo language('country_mv_name'); ?></option>
<option value="<?php echo language('country_mw_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_mw_code')) { echo "selected"; } ?>><?php echo language('country_mw_name'); ?></option>
<option value="<?php echo language('country_mx_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_mx_code')) { echo "selected"; } ?>><?php echo language('country_mx_name'); ?></option>
<option value="<?php echo language('country_my_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_my_code')) { echo "selected"; } ?>><?php echo language('country_my_name'); ?></option>
<option value="<?php echo language('country_mz_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_mz_code')) { echo "selected"; } ?>><?php echo language('country_mz_name'); ?></option>
<option value="<?php echo language('country_na_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_na_code')) { echo "selected"; } ?>><?php echo language('country_na_name'); ?></option>
<option value="<?php echo language('country_nc_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_nc_code')) { echo "selected"; } ?>><?php echo language('country_nc_name'); ?></option>
<option value="<?php echo language('country_ne_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ne_code')) { echo "selected"; } ?>><?php echo language('country_ne_name'); ?></option>
<option value="<?php echo language('country_nf_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_nf_code')) { echo "selected"; } ?>><?php echo language('country_nf_name'); ?></option>
<option value="<?php echo language('country_ng_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ng_code')) { echo "selected"; } ?>><?php echo language('country_ng_name'); ?></option>
<option value="<?php echo language('country_ni_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ni_code')) { echo "selected"; } ?>><?php echo language('country_ni_name'); ?></option>
<option value="<?php echo language('country_nl_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_nl_code')) { echo "selected"; } ?>><?php echo language('country_nl_name'); ?></option>
<option value="<?php echo language('country_no_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_no_code')) { echo "selected"; } ?>><?php echo language('country_no_name'); ?></option>
<option value="<?php echo language('country_np_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_np_code')) { echo "selected"; } ?>><?php echo language('country_np_name'); ?></option>
<option value="<?php echo language('country_nr_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_nr_code')) { echo "selected"; } ?>><?php echo language('country_nr_name'); ?></option>
<option value="<?php echo language('country_nu_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_nu_code')) { echo "selected"; } ?>><?php echo language('country_nu_name'); ?></option>
<option value="<?php echo language('country_nz_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_nz_code')) { echo "selected"; } ?>><?php echo language('country_nz_name'); ?></option>
<option value="<?php echo language('country_om_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_om_code')) { echo "selected"; } ?>><?php echo language('country_om_name'); ?></option>
<option value="<?php echo language('country_pa_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_pa_code')) { echo "selected"; } ?>><?php echo language('country_pa_name'); ?></option>
<option value="<?php echo language('country_pe_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_pe_code')) { echo "selected"; } ?>><?php echo language('country_pe_name'); ?></option>
<option value="<?php echo language('country_pf_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_pf_code')) { echo "selected"; } ?>><?php echo language('country_pf_name'); ?></option>
<option value="<?php echo language('country_pg_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_pg_code')) { echo "selected"; } ?>><?php echo language('country_pg_name'); ?></option>
<option value="<?php echo language('country_ph_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ph_code')) { echo "selected"; } ?>><?php echo language('country_ph_name'); ?></option>
<option value="<?php echo language('country_pk_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_pk_code')) { echo "selected"; } ?>><?php echo language('country_pk_name'); ?></option>
<option value="<?php echo language('country_pl_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_pl_code')) { echo "selected"; } ?>><?php echo language('country_pl_name'); ?></option>
<option value="<?php echo language('country_pm_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_pm_code')) { echo "selected"; } ?>><?php echo language('country_pm_name'); ?></option>
<option value="<?php echo language('country_pn_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_pn_code')) { echo "selected"; } ?>><?php echo language('country_pn_name'); ?></option>
<option value="<?php echo language('country_pr_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_pr_code')) { echo "selected"; } ?>><?php echo language('country_pr_name'); ?></option>
<option value="<?php echo language('country_ps_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ps_code')) { echo "selected"; } ?>><?php echo language('country_ps_name'); ?></option>
<option value="<?php echo language('country_pt_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_pt_code')) { echo "selected"; } ?>><?php echo language('country_pt_name'); ?></option>
<option value="<?php echo language('country_pw_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_pw_code')) { echo "selected"; } ?>><?php echo language('country_pw_name'); ?></option>
<option value="<?php echo language('country_py_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_py_code')) { echo "selected"; } ?>><?php echo language('country_py_name'); ?></option>
<option value="<?php echo language('country_qa_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_qa_code')) { echo "selected"; } ?>><?php echo language('country_qa_name'); ?></option>
<option value="<?php echo language('country_re_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_re_code')) { echo "selected"; } ?>><?php echo language('country_re_name'); ?></option>
<option value="<?php echo language('country_ro_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ro_code')) { echo "selected"; } ?>><?php echo language('country_ro_name'); ?></option>
<option value="<?php echo language('country_rs_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_rs_code')) { echo "selected"; } ?>><?php echo language('country_rs_name'); ?></option>
<option value="<?php echo language('country_ru_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ru_code')) { echo "selected"; } ?>><?php echo language('country_ru_name'); ?></option>
<option value="<?php echo language('country_rw_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_rw_code')) { echo "selected"; } ?>><?php echo language('country_rw_name'); ?></option>
<option value="<?php echo language('country_sa_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_sa_code')) { echo "selected"; } ?>><?php echo language('country_sa_name'); ?></option>
<option value="<?php echo language('country_sb_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_sb_code')) { echo "selected"; } ?>><?php echo language('country_sb_name'); ?></option>
<option value="<?php echo language('country_sc_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_sc_code')) { echo "selected"; } ?>><?php echo language('country_sc_name'); ?></option>
<option value="<?php echo language('country_sd_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_sd_code')) { echo "selected"; } ?>><?php echo language('country_sd_name'); ?></option>
<option value="<?php echo language('country_se_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_se_code')) { echo "selected"; } ?>><?php echo language('country_se_name'); ?></option>
<option value="<?php echo language('country_sg_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_sg_code')) { echo "selected"; } ?>><?php echo language('country_sg_name'); ?></option>
<option value="<?php echo language('country_sh_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_sh_code')) { echo "selected"; } ?>><?php echo language('country_sh_name'); ?></option>
<option value="<?php echo language('country_si_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_si_code')) { echo "selected"; } ?>><?php echo language('country_si_name'); ?></option>
<option value="<?php echo language('country_sj_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_sj_code')) { echo "selected"; } ?>><?php echo language('country_sj_name'); ?></option>
<option value="<?php echo language('country_sk_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_sk_code')) { echo "selected"; } ?>><?php echo language('country_sk_name'); ?></option>
<option value="<?php echo language('country_sl_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_sl_code')) { echo "selected"; } ?>><?php echo language('country_sl_name'); ?></option>
<option value="<?php echo language('country_sm_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_sm_code')) { echo "selected"; } ?>><?php echo language('country_sm_name'); ?></option>
<option value="<?php echo language('country_sn_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_sn_code')) { echo "selected"; } ?>><?php echo language('country_sn_name'); ?></option>
<option value="<?php echo language('country_so_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_so_code')) { echo "selected"; } ?>><?php echo language('country_so_name'); ?></option>
<option value="<?php echo language('country_sr_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_sr_code')) { echo "selected"; } ?>><?php echo language('country_sr_name'); ?></option>
<option value="<?php echo language('country_ss_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ss_code')) { echo "selected"; } ?>><?php echo language('country_ss_name'); ?></option>
<option value="<?php echo language('country_st_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_st_code')) { echo "selected"; } ?>><?php echo language('country_st_name'); ?></option>
<option value="<?php echo language('country_sv_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_sv_code')) { echo "selected"; } ?>><?php echo language('country_sv_name'); ?></option>
<option value="<?php echo language('country_sx_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_sx_code')) { echo "selected"; } ?>><?php echo language('country_sx_name'); ?></option>
<option value="<?php echo language('country_sy_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_sy_code')) { echo "selected"; } ?>><?php echo language('country_sy_name'); ?></option>
<option value="<?php echo language('country_sz_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_sz_code')) { echo "selected"; } ?>><?php echo language('country_sz_name'); ?></option>
<option value="<?php echo language('country_tc_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_tc_code')) { echo "selected"; } ?>><?php echo language('country_tc_name'); ?></option>
<option value="<?php echo language('country_td_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_td_code')) { echo "selected"; } ?>><?php echo language('country_td_name'); ?></option>
<option value="<?php echo language('country_tf_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_tf_code')) { echo "selected"; } ?>><?php echo language('country_tf_name'); ?></option>
<option value="<?php echo language('country_tg_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_tg_code')) { echo "selected"; } ?>><?php echo language('country_tg_name'); ?></option>
<option value="<?php echo language('country_th_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_th_code')) { echo "selected"; } ?>><?php echo language('country_th_name'); ?></option>
<option value="<?php echo language('country_tj_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_tj_code')) { echo "selected"; } ?>><?php echo language('country_tj_name'); ?></option>
<option value="<?php echo language('country_tk_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_tk_code')) { echo "selected"; } ?>><?php echo language('country_tk_name'); ?></option>
<option value="<?php echo language('country_tl_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_tl_code')) { echo "selected"; } ?>><?php echo language('country_tl_name'); ?></option>
<option value="<?php echo language('country_tm_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_tm_code')) { echo "selected"; } ?>><?php echo language('country_tm_name'); ?></option>
<option value="<?php echo language('country_tn_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_tn_code')) { echo "selected"; } ?>><?php echo language('country_tn_name'); ?></option>
<option value="<?php echo language('country_to_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_to_code')) { echo "selected"; } ?>><?php echo language('country_to_name'); ?></option>
<option value="<?php echo language('country_tr_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_tr_code')) { echo "selected"; } ?>><?php echo language('country_tr_name'); ?></option>
<option value="<?php echo language('country_tt_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_tt_code')) { echo "selected"; } ?>><?php echo language('country_tt_name'); ?></option>
<option value="<?php echo language('country_tv_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_tv_code')) { echo "selected"; } ?>><?php echo language('country_tv_name'); ?></option>
<option value="<?php echo language('country_tw_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_tw_code')) { echo "selected"; } ?>><?php echo language('country_tw_name'); ?></option>
<option value="<?php echo language('country_tz_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_tz_code')) { echo "selected"; } ?>><?php echo language('country_tz_name'); ?></option>
<option value="<?php echo language('country_ua_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ua_code')) { echo "selected"; } ?>><?php echo language('country_ua_name'); ?></option>
<option value="<?php echo language('country_ug_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ug_code')) { echo "selected"; } ?>><?php echo language('country_ug_name'); ?></option>
<option value="<?php echo language('country_um_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_um_code')) { echo "selected"; } ?>><?php echo language('country_um_name'); ?></option>
<option value="<?php echo language('country_us_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_us_code')) { echo "selected"; } ?>><?php echo language('country_us_name'); ?></option>
<option value="<?php echo language('country_uy_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_uy_code')) { echo "selected"; } ?>><?php echo language('country_uy_name'); ?></option>
<option value="<?php echo language('country_uz_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_uz_code')) { echo "selected"; } ?>><?php echo language('country_uz_name'); ?></option>
<option value="<?php echo language('country_va_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_va_code')) { echo "selected"; } ?>><?php echo language('country_va_name'); ?></option>
<option value="<?php echo language('country_vc_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_vc_code')) { echo "selected"; } ?>><?php echo language('country_vc_name'); ?></option>
<option value="<?php echo language('country_ve_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ve_code')) { echo "selected"; } ?>><?php echo language('country_ve_name'); ?></option>
<option value="<?php echo language('country_vg_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_vg_code')) { echo "selected"; } ?>><?php echo language('country_vg_name'); ?></option>
<option value="<?php echo language('country_vi_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_vi_code')) { echo "selected"; } ?>><?php echo language('country_vi_name'); ?></option>
<option value="<?php echo language('country_vn_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_vn_code')) { echo "selected"; } ?>><?php echo language('country_vn_name'); ?></option>
<option value="<?php echo language('country_vu_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_vu_code')) { echo "selected"; } ?>><?php echo language('country_vu_name'); ?></option>
<option value="<?php echo language('country_wf_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_wf_code')) { echo "selected"; } ?>><?php echo language('country_wf_name'); ?></option>
<option value="<?php echo language('country_ws_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ws_code')) { echo "selected"; } ?>><?php echo language('country_ws_name'); ?></option>
<option value="<?php echo language('country_ye_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_ye_code')) { echo "selected"; } ?>><?php echo language('country_ye_name'); ?></option>
<option value="<?php echo language('country_yt_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_yt_code')) { echo "selected"; } ?>><?php echo language('country_yt_name'); ?></option>
<option value="<?php echo language('country_za_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_za_code')) { echo "selected"; } ?>><?php echo language('country_za_name'); ?></option>
<option value="<?php echo language('country_zm_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_zm_code')) { echo "selected"; } ?>><?php echo language('country_zm_name'); ?></option>
<option value="<?php echo language('country_zw_code'); ?>" <?php if ($session['response']['param']['address']['Home']['country'] == language('country_zw_code')) { echo "selected"; } ?>><?php echo language('country_zw_name'); ?></option>
			</select>
		</div>
	</fieldset>

	<fieldset class="final">
		<div class="inputset buttons disabled">
			<input id="SubmitPersonalDetails" class="submit button-saveall" type="submit" value="<?php echo language('sciomio_text_user_profile_toevoegen'); ?>" />
		</div>
	</fieldset>

</form>

