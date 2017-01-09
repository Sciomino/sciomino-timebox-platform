<div class="section userbox solo highlight" style="overflow:visible;">
	<div class="img-item vcard">
		<?php
		echo "<span class='img'>";
		if (! isset($session['response']['param']['user']['photo'])) { $session['response']['param']['user']['photo'] = "/ui/gfx/photo.jpg"; }
		else { $session['response']['param']['user']['photo'] = str_replace("/upload/","/upload/96x96_",$session['response']['param']['user']['photo']); }
		#echo "<a class='modalflex' href='".$XCOW_B['url']."/snippet/user-photo-view?userRef=".$session['response']['param']['user']['Reference']."'><img src='".$XCOW_B['url'].$session['response']['param']['user']['photo']."' width='96' height='96' alt='' /></a> ";
		echo "<a class='url' href='".$XCOW_B['url']."/view?user=".$session['response']['param']['user']['Id']."'><img src='".$XCOW_B['url'].$session['response']['param']['user']['photo']."' width='96' height='96' alt='' /></a> ";
		echo "</span>";
		?>
		
		<div class='bd'>
			<div class='group' style="padding:0px;">
				<table class="user-data">
					<?php
					$displayRole = $session['response']['param']['user']['Organization'][get_id_from_multi_array($session['response']['param']['user']['Organization'], 'Name', 'Current')]['role'];
					$displayIndustry = $session['response']['param']['user']['Organization'][get_id_from_multi_array($session['response']['param']['user']['Organization'], 'Name', 'Current')]['industry'];
					$displayCompany = $session['response']['param']['user']['Organization'][get_id_from_multi_array($session['response']['param']['user']['Organization'], 'Name', 'Current')]['company'];
					$displayDivision = $session['response']['param']['user']['Organization'][get_id_from_multi_array($session['response']['param']['user']['Organization'], 'Name', 'Current')]['division'];
					$displaySection = $session['response']['param']['user']['Organization'][get_id_from_multi_array($session['response']['param']['user']['Organization'], 'Name', 'Current')]['section'];
					$displayCity = $session['response']['param']['user']['Address'][get_id_from_multi_array($session['response']['param']['user']['Address'], 'Name', 'Work')]['city'];
					$displayCityHome = $session['response']['param']['user']['Address'][get_id_from_multi_array($session['response']['param']['user']['Address'], 'Name', 'Home')]['city'];
					?>
					<thead style="padding-left:15px;">
					</thead>
					<tbody>
						<?php

						if($displayIndustry != '') {
							echo "<tr>";
							echo "<td>".language('sciomio_text_view_personal_industry')."</td>";
							echo "<td><a href='".$XCOW_B['url']."/search?p[industry]=".urlencode($displayIndustry)."'>".$displayIndustry."</a></td>";
							echo "</tr>";
						}

						if($displayCompany != '') {
							echo "<tr>";
							echo "<td>".language('sciomio_text_view_personal_company')."</td>";
							echo "<td><a href='".$XCOW_B['url']."/search?p[organization]=".urlencode($displayCompany)."'>".$displayCompany."</a></td>";
							echo "</tr>";
						}

						if($displayDivision != '') {
							echo "<tr>";
							echo "<td>".language('sciomio_text_view_personal_businessunit')."</td>";
							echo "<td><a href='".$XCOW_B['url']."/search?p[businessunit]=".urlencode($displayDivision)."'>".$displayDivision."</a></td>";
							echo "</tr>";
						}

						if($displaySection != '') {
							echo "<tr>";
							echo "<td>".language('sciomio_text_view_personal_section')."</td>";
							echo "<td><a href='".$XCOW_B['url']."/search?p[section]=".urlencode($displaySection)."'>".$displaySection."</a></td>";
							echo "</tr>";
						}

						if($displayRole != '') {
							echo "<tr>";
							echo "<td>".language('sciomio_text_view_personal_role')."</td>";
							echo "<td><a href='".$XCOW_B['url']."/search?p[role]=".urlencode($displayRole)."'>".$displayRole."</a></td>";
							echo "</tr>";
						}

						if($displayCity != '') {
							$countryCode = $session['response']['param']['user']['Address'][get_id_from_multi_array($session['response']['param']['user']['Address'], 'Name', 'Work')]['country'];
							$displayCountry = language("country_".$countryCode."_name");
							echo "<tr>";
							echo "<td>".language('sciomio_text_view_personal_workplace')."</td>";
							echo "<td><a href='".$XCOW_B['url']."/search?p[workplace]=".urlencode($displayCity.", ".$countryCode)."'>".$displayCity.", ".$displayCountry."</a></td>";
							echo "</tr>";
						}

						if($displayCityHome != '') {
							$countryCodeHome = $session['response']['param']['user']['Address'][get_id_from_multi_array($session['response']['param']['user']['Address'], 'Name', 'Home')]['country'];
							$displayCountryHome = language("country_".$countryCodeHome."_name");
							echo "<tr>";
							echo "<td>".language('sciomio_text_view_personal_hometown')."</td>";
							echo "<td><a href='".$XCOW_B['url']."/search?p[hometown]=".urlencode($displayCityHome.", ".$countryCodeHome)."'>".$displayCityHome.", ".$displayCountryHome."</a></td>";
							echo "</tr>";
						}

						?>
					</tbody>
				</table>
			</div>
		</div>
		
	<!-- end card -->
	</div>

	<div class="section" style="padding:0px 15px;">					
		<p> 
			<?php 
			echo language('sciomio_text_user_personal_all');
			if (! in_array("industry", $XCOW_B['sciomino']['personalia-exclude']) ) {
				echo "<a class='modal' href='".$XCOW_B['url']."/snippet/list-personal-fields?type=industry'>".language('sciomio_word_filter_industry')."</a>, ";
			}
			if (! in_array("company", $XCOW_B['sciomino']['personalia-exclude']) ) {
				echo "<a class='modal' href='".$XCOW_B['url']."/snippet/list-personal-fields?type=organization'>".language('sciomio_word_filter_organization')."</a>, ";
			}
			if (! in_array("division", $XCOW_B['sciomino']['personalia-exclude']) ) {
				echo "<a class='modal' href='".$XCOW_B['url']."/snippet/list-personal-fields?type=businessunit'>".language('sciomio_word_filter_businessunit')."</a>, ";
			}
			if (! in_array("section", $XCOW_B['sciomino']['personalia-exclude']) ) {
				echo "<a class='modal' href='".$XCOW_B['url']."/snippet/list-personal-fields?type=section'>".language('sciomio_word_filter_section')."</a>, ";
			}
			if (! in_array("role", $XCOW_B['sciomino']['personalia-exclude']) ) {
				echo "<a class='modal' href='".$XCOW_B['url']."/snippet/list-personal-fields?type=role'>".language('sciomio_word_filter_role')."</a>, ";
			}
			echo "<a class='modal' href='".$XCOW_B['url']."/snippet/list-personal-fields?type=workplace'>".language('sciomio_word_filter_workplace')."</a>, ";
			echo "<a class='modal' href='".$XCOW_B['url']."/snippet/list-personal-fields?type=hometown'>".language('sciomio_word_filter_hometown')."</a>";
			?>
		</p>
	</div>


	<div class="user-info" style="padding:10px 15px;">
		<div class='group' style="margin-top:0px;margin-bottom:0px;">
			<div class='unit unit1-2 adr' style="margin-top:0px;margin-bottom:0px;">
				<?php
				if (count($session['response']['param']['knowledgeList1Slice']) > 0) {
					echo "<h5>".language('sciomio_text_user_personal_knowledgefield_1')."</h5>";
					foreach ($session['response']['param']['knowledgeList1Slice'] as $knowledge) {
						echo "<div style='padding-bottom:2px;'><a class='' href='".$XCOW_B['url']."/search?k[".urlencode($knowledge['field'])."]=1'>".$knowledge['field']."</a></div>";
					}
					if (count($session['response']['param']['knowledgeList1']) > $session['response']['param']['limit']) {
						echo "<div style='padding-bottom:2px;'><a class='modal' href='".$XCOW_B['url']."/snippet/user-view-personal-more?type=k1'>Meer&hellip;</a></div>";
					}
				}
				if (count($session['response']['param']['knowledgeList2Slice']) > 0) {
					echo "<h5>".language('sciomio_text_user_personal_knowledgefield_2')."</h5>";
					foreach ($session['response']['param']['knowledgeList2Slice'] as $knowledge) {
						echo "<div style='padding-bottom:2px;'><a class='' href='".$XCOW_B['url']."/search?k[".urlencode($knowledge['field'])."]=2'>".$knowledge['field']."</a></div>";
					}
					if (count($session['response']['param']['knowledgeList2']) > $session['response']['param']['limit']) {
						echo "<div style='padding-bottom:2px;'><a class='modal' href='".$XCOW_B['url']."/snippet/user-view-personal-more?type=k2'>Meer&hellip;</a></div>";
					}
				}
				if (count($session['response']['param']['knowledgeList3Slice']) > 0) {
					echo "<h5>".language('sciomio_text_user_personal_knowledgefield_3')."</h5>";
					foreach ($session['response']['param']['knowledgeList3Slice'] as $knowledge) {
						echo "<div style='padding-bottom:2px;'><a class='' href='".$XCOW_B['url']."/search?k[".urlencode($knowledge['field'])."]=3'>".$knowledge['field']."</a></div>";
					}
					if (count($session['response']['param']['knowledgeList3']) > $session['response']['param']['limit']) {
						echo "<div style='padding-bottom:2px;'><a class='modal' href='".$XCOW_B['url']."/snippet/user-view-personal-more?type=k3'>Meer&hellip;</a></div>";
					}
				}
				?>
			</div>

			<div class='unit unit1-2 last' style="margin-top:0px;margin-bottom:0px;">
				<?php
				if (count($session['response']['param']['hobbyListSlice']) > 0) {
					echo "<h5>".language('sciomio_text_user_personal_hobby')."</h5>";
					foreach ($session['response']['param']['hobbyListSlice'] as $hobby) {
						echo "<div style='padding-bottom:2px;'><a class='' href='".$XCOW_B['url']."/search?h[".urlencode($hobby['field'])."]'>".$hobby['field']."</a></div>";
					}
					if (count($session['response']['param']['hobbyList']) > $session['response']['param']['limit']) {
						echo "<div style='padding-bottom:2px;'><a class='modal' href='".$XCOW_B['url']."/snippet/user-view-personal-more?type=h'>Meer&hellip;</a></div>";
					}
				}

				if (count($session['response']['param']['tagListSlice']) > 0) {
					echo "<h5>".language('sciomio_text_user_personal_tag')."</h5>";
					foreach ($session['response']['param']['tagListSlice'] as $tag) {
						echo "<div style='padding-bottom:2px;'><a class='' href='".$XCOW_B['url']."/search?t[".urlencode($tag['name'])."]'>".$tag['name']."</a></div>";
					}
					if (count($session['response']['param']['tagList']) > $session['response']['param']['limit']) {
						echo "<div style='padding-bottom:2px;'><a class='modal' href='".$XCOW_B['url']."/snippet/user-view-personal-more?type=t'>Meer&hellip;</a></div>";
					}
				}

				if (count($session['response']['param']['networkListSlice']) > 0) {
					echo "<h5>".language('sciomio_text_user_personal_network')."</h5>";
					foreach ($session['response']['param']['networkListSlice'] as $network) {
						echo "<div style='padding-bottom:2px;'><a class='' href='".$XCOW_B['url']."/search?tl[public]=".urlencode($network['Name'])."'>".$network['Name']."</a></div>";
					}
					if (count($session['response']['param']['networkList']) > $session['response']['param']['limit']) {
						echo "<div style='padding-bottom:2px;'><a class='modal' href='".$XCOW_B['url']."/snippet/user-view-personal-more?type=tl'>Meer&hellip;</a></div>";
					}
				}
				?>
			</div>
		</div>

	<!-- end info -->
	</div>
	
</div>

<div style="margin:0px 15px;">
<table>
<?php
	if (count($session['response']['param']['birthDayList']) > 0) {
		echo "<tr><td><b>".language('sciomio_text_user_faces_birthday')."</b></td><td>"; 

		foreach ($session['response']['param']['birthDayList'] as $birthDay) {

			echo "<div style='float:left; padding: 3px; text-align:center'>";
			if (! isset($birthDay['photo'])) { $birthDay['photo'] = "/ui/gfx/photo.jpg"; }
			else { $birthDay['photo'] = str_replace("/upload/","/upload/48x48_",$birthDay['photo']); }
			echo "<a href='".$XCOW_B['url']."/view?user=".$birthDay['Id']."'><img src='".$XCOW_B['url'].$birthDay['photo']."' width='48' height='48' alt='' /></a>";
			echo "<br/>".$birthDay['dateofbirthday']."<br/>".language('sciomio_word_month_short_'.$birthDay['dateofbirthmonth'])."</div>";

		}
		echo "</td></tr>";
	}
?>
<?php
	if (count($session['response']['param']['newList']) > 0) {
		echo "<tr><td><b>".language('sciomio_text_user_faces_new')."</b></td><td>"; 

		$divId = 1;
		foreach ($session['response']['param']['newList'] as $new) {
			echo "<div style='float:left; padding: 3px; text-align:center' onmouseover='javascript:document.getElementById(\"NewFacesName".$divId."\").innerHTML=\"<nobr>".$new['FirstName']." ".$new['LastName']."</nobr>\"' onmouseout='javascript:document.getElementById(\"NewFacesName".$divId."\").innerHTML=\"&nbsp;\"'>";
			if (! isset($new['photo'])) { $new['photo'] = "/ui/gfx/photo.jpg"; }
			else { $new['photo'] = str_replace("/upload/","/upload/48x48_",$new['photo']); }
			echo "<a href='".$XCOW_B['url']."/view?user=".$new['Id']."'><img src='".$XCOW_B['url'].$new['photo']."' width='48' height='48' alt='' /></a>";
			echo "</div>";
			$divId++;

		}
		echo "</td></tr>";
		echo "<tr><td></td><td>"; 

		$divId = 1;
		foreach ($session['response']['param']['newList'] as $new) {
			echo "<div id='NewFacesName".$divId."' style='float:left; padding: 3px; width: 48px; height: 14px; text-align:center;'>";
			echo "&nbsp;";
			echo "</div>";
			$divId++;
		}
		echo "</td></tr>";
	}
?>
</table>
</div>

