<?php
if (count($session['response']['param']['linkedinSkillList']) > 0) {
	echo "<div class='fieldset-info highlight'>";
    	echo "<p>".language('sciomio_word_linkedin_skills');
	foreach ($session['response']['param']['linkedinSkillList'] as $skill) {
		echo "<a class='tag add' href='".$XCOW_B['url']."/snippet/knowledge-new-form?fill=".urlencode($skill)."'>$skill</a>";
	}
	echo "</p>";
	echo "</div>";
}
?>

