	<h3><?php echo language('sciomio_header_user_experience_event'); ?></h3>

        <div class="add-header highlight">
            <a class="tinybutton metoo" rel="/snippet/event-list" href="<?php echo $XCOW_B['url'] ?>/snippet/event-new-form"><?php echo language('sciomio_text_ervaring_toevoegen'); ?></a>
	<?php
	$count = count($session['response']['param']['eventList']); 
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
foreach ($session['response']['param']['eventList'] as $event) {
	if ($event['subject'] != $oldSubject) {
		if ($oldSubject != "") {
			echo "</ul></div>";
		}
		$oldSubject = $event['subject'];
		echo "<div class='section'>";
		echo "<h4><a href='".$XCOW_B['url']."/browse/experience?e[Event]=".urlencode($event['subject'])."'>{$event['subject']}</a></h4>";
		echo "<ul class='comps exp-list'>";
	}

	$verdict = "happy";
	if ($event['like'] == 1) {$verdict = "happy-xl";}
	if ($event['like'] == 2) {$verdict = "happy";}
	if ($event['like'] == 3) {$verdict = "unhappy";}
	if ($event['like'] == 4) {$verdict = "unhappy-xl";}

	echo "<li>";

        echo "<div class='tinybuttons'>";
        echo "<a href='".$XCOW_B['url']."/snippet/event-edit-form?eventId=".$event['Id']."' rel='/snippet/event-list' class='tinybutton metoo'>".language('sciomio_word_edit')."</a>";
        echo "<a class='tinybutton delete' href='javascript:ScioMino.EventDelete.action(".$event['Id'].");'>".language('sciomio_word_delete')."</a>";
        echo "</div>";

	$displayDate = "";
	if ($event['date']) {
		$displayDate = "(".$event['date'].")";
	}
	echo "<a class='verdict ".$verdict." exp-link' href='".$XCOW_B['url']."/browse/experience?e[Event]=".urlencode($event['subject'])."&title=".urlencode($event['title'])."'>".$event['title']."</a> ".$displayDate;
	echo "<p>".$event['description']."</p>";
	if ($event['relation-self'] != "") {
		echo "<p><a href='".$event['relation-self']."'>-&gt; website</a></p>";
	}
	#echo "Aanbieder:"."{$event['publisher']}"."<br/>";

	echo "</li>";
}
echo "</ul>";

# echo "<a href='#' class='more'>toon alle ervaringen&hellip;</a>";

echo "</div>";
?>
