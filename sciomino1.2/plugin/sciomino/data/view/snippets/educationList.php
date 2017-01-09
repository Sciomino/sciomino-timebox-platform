	<h3><?php echo language('sciomio_header_user_experience_education'); ?></h3>

        <div class="add-header highlight">
            <a class="tinybutton metoo" rel="/snippet/education-list" href="<?php echo $XCOW_B['url'] ?>/snippet/education-new-form"><?php echo language('sciomio_text_ervaring_toevoegen'); ?></a>
	<?php
	$count = count($session['response']['param']['educationList']); 
	if ($count == 0) {
		echo "<p>".language('sciomio_text_ervaring_geen')."</p>";
	}
	elseif ($count == 1) {
		echo "<p>".language('sciomio_text_ervaring_een')."</p>";
	}
	else {
		$languageTemplate = array();
		$languageTemplate['count'] = $count;
		echo "<p>".language_template('sciomio_text_ervaring_meer', $languageTemplate)."</p>";
	}
	?>
        </div>

<?php
$oldSubject = "";
foreach ($session['response']['param']['educationList'] as $education) {
	if ($education['subject'] != $oldSubject) {
		if ($oldSubject != "") {
			echo "</ul></div>";
		}
		$oldSubject = $education['subject'];
		echo "<div class='section'>";
		echo "<h4><a href='".$XCOW_B['url']."/browse/experience?e[Education]=".urlencode($education['subject'])."'>{$education['subject']}</a></h4>";
		echo "<ul class='comps exp-list'>";
	}

	$verdict = "happy";
	if ($education['like'] == 1) {$verdict = "happy-xl";}
	if ($education['like'] == 2) {$verdict = "happy";}
	if ($education['like'] == 3) {$verdict = "unhappy";}
	if ($education['like'] == 4) {$verdict = "unhappy-xl";}

	echo "<li>";

        echo "<div class='tinybuttons'>";
        echo "<a href='".$XCOW_B['url']."/snippet/education-edit-form?educationId=".$education['Id']."' rel='/snippet/education-list' class='tinybutton metoo'>".language('sciomio_word_edit')."</a>";
        echo "<a class='tinybutton delete' href='javascript:ScioMino.EducationDelete.action(".$education['Id'].");'>".language('sciomio_word_delete')."</a>";
        echo "</div>";

	$displayDate = "";
	if ($education['date']) {
		$displayDate = "(".$education['date'].")";
	}
	echo "<a class='verdict ".$verdict." exp-link' href='".$XCOW_B['url']."/browse/experience?e[Education]=".urlencode($education['subject'])."&title=".urlencode($education['title'])."'>".$education['title']."</a> ".$displayDate;
	echo "<p>".$education['description']."</p>";
	if ($education['relation-self'] != "") {
		echo "<p><a href='".$education['relation-self']."'>-&gt; website</a></p>";
	}
	#echo "Aanbieder:"."{$education['publisher']}"."<br/>";

	echo "</li>";
}
echo "</ul>";

# echo "<a href='#' class='more'>toon alle ervaringen&hellip;</a>";

echo "</div>";
?>
