	<h3><?php echo language('sciomio_header_user_experience_company'); ?></h3>

        <div class="add-header highlight">
            <a class="tinybutton metoo" rel="/snippet/company-list" href="<?php echo $XCOW_B['url'] ?>/snippet/company-new-form"><?php echo language('sciomio_text_ervaring_toevoegen'); ?></a>
	<?php
	$count = count($session['response']['param']['companyList']);
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
foreach ($session['response']['param']['companyList'] as $company) {
	if ($company['subject'] != $oldSubject) {
		if ($oldSubject != "") {
			echo "</ul></div>";
		}
		$oldSubject = $company['subject'];
		echo "<div class='section'>";
		echo "<h4><a href='".$XCOW_B['url']."/browse/experience?e[Company]=".urlencode($company['subject'])."'>{$company['subject']}</a></h4>";
		echo "<ul class='comps exp-list'>";
	}

	$verdict = "happy";
	if ($company['like'] == 1) {$verdict = "happy-xl";}
	if ($company['like'] == 2) {$verdict = "happy";}
	if ($company['like'] == 3) {$verdict = "unhappy";}
	if ($company['like'] == 4) {$verdict = "unhappy-xl";}

	echo "<li>";

        echo "<div class='tinybuttons'>";
        echo "<a href='".$XCOW_B['url']."/snippet/company-edit-form?companyId=".$company['Id']."' rel='/snippet/company-list' class='tinybutton metoo'>".language('sciomio_word_edit')."</a>";
        echo "<a class='tinybutton delete' href='javascript:ScioMino.CompanyDelete.action(".$company['Id'].");'>".language('sciomio_word_delete')."</a>";
        echo "</div>";

	$displayDate = "";
	if ($company['date']) {
		$displayDate = "(".$company['date'].")";
	}
	echo "<a class='verdict ".$verdict." exp-link' href='".$XCOW_B['url']."/browse/experience?e[Company]=".urlencode($company['subject'])."&title=".urlencode($company['title'])."'>".$company['title']."</a> ".$displayDate;
	echo "<p>".$company['description']."</p>";

	echo "</li>";
}
echo "</ul>";

# echo "<a href='#' class='more'>toon alle ervaringen&hellip;</a>";

echo "</div>";
?>

