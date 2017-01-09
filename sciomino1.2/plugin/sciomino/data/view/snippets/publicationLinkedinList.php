<div class="section">
	
	<?php
	if (! empty($session['response']['param']['yourLinkedinInfo']['account'])) {

		if ($session['response']['param']['yourLinkedinInfo']['mode'] == "private") {
			
			if (count($session['response']['param']['linkedinSkillList']) > 0) {
				echo "<div class='fieldset-info highlight'>";
				echo "<p>".language('sciomio_word_linkedin_skills');
				$first = 1;
				foreach ($session['response']['param']['linkedinSkillList'] as $skill) {
					if ($first) { $first = 0; } else { echo ", "; }
					echo "<a class='modalflex' href='".$XCOW_B['url']."/snippet/knowledge-new-form-ikook?mode=modal&fill=".urlencode($skill)."'>".$skill."</a>";

				}
				echo "</p>";
				echo "</div>";
			}

			echo "<table class='user-data listed'>";

			echo "<thead>";
			echo "<tr><td></td>";
			echo "<th><img src='".$XCOW_B['url']."/ui/gfx/icon_linkedin.gif' width='52' height='15' alt='Icon Linkedin' /></th></tr>";
			echo "</thead>";

			echo "<tbody>";

			echo "<tr><th></th>";
			echo "<td>".$session['response']['param']['yourLinkedinInfo']['name']."<span class='you-label'>".$session['response']['param']['yourLinkedinInfo']['distance']."</span></td></tr>";

			echo "<tr><th></th>";
			echo "<td>".$session['response']['param']['yourLinkedinInfo']['headline']."</td></tr>";

			#echo "<tr><th>Summary</th>";
			#echo "<td>".$session['response']['param']['yourLinkedinInfo']['summary']."</td></tr>";

			#echo "<tr><th>Specialties</th>";
			#echo "<td>".$session['response']['param']['yourLinkedinInfo']['specialties']."</td></tr>";

			#echo "<tr><th>Skills</th>";
			#echo "<td>".$session['response']['param']['yourLinkedinInfo']['skills']."</td></tr>";

			echo "<tr><th>Current</th>";
			echo "<td>".$session['response']['param']['yourLinkedinInfo']['current']."</td></tr>";

			echo "<tr><th>Past</th>";
			echo "<td>".$session['response']['param']['yourLinkedinInfo']['past']."</td></tr>";

			echo "<tr><th>Education</th>";
			echo "<td>".$session['response']['param']['yourLinkedinInfo']['education']."</td></tr>";

			echo "</tbody>";

			echo "</table>";

			echo "<div class='showprofile'>";
			echo "<a target='_blank' href='".$session['response']['param']['yourLinkedinInfo']['link']."' class='linkbutton'>".language('sciomio_word_linkedin_view')."</a>";
			echo "</div>";

		}

		if ($session['response']['param']['yourLinkedinInfo']['mode'] == "public") {

			echo "<div>";
			echo "<p><img src='".$XCOW_B['url']."/ui/gfx/icon_linkedin.gif' width='52' height='15' alt='Icon Linkedin' /></p>";
			echo "<script type='IN/MemberProfile' data-id='".$session['response']['param']['yourLinkedinInfo']['url']."' data-format='inline' data-related='false' data-width='300'></script>";
			echo "</div>";

		}

		if ($session['response']['param']['yourLinkedinInfo']['mode'] == "expired") {

			echo "<div>";
			echo "<p><img src='".$XCOW_B['url']."/ui/gfx/icon_linkedin.gif' width='52' height='15' alt='Icon Linkedin' /></p>";
			echo "<p>De verbinding met linkedin is verlopen. <a href='".$XCOW_B['url']."/oauth/connect?app=linkedin&action=request'>"."Verbinding tot 60 dagen verlengen."."</a></p>";
			echo "</div>";

		}

	}
	?>
	
</div>
