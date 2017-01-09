<?php
 echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
?>
<Response>

 <Header>
  <Status><?php echo $session['response']['stats']['status'] ?></Status>
  <Request><?php echo $session['response']['stats']['request'] ?></Request>
  <ResponseDate><?php echo $session['response']['stats']['date'] ?></ResponseDate>
  <ResponseTime><?php echo $session['response']['stats']['time'] ?></ResponseTime>
 </Header>

 <Content> 

  <Stats>

<?php
$statsList = $session['response']['param']['statsList'];

echo "    <Id>".$statsList['statsId']."</Id>\n";
echo "    <Timestamp>".$statsList['statsTimestamp']."</Timestamp>\n";

// annotation
if ($session['response']['param']['mode'] == "insights") {
	foreach ($statsList['annotation'] as $annotation) {
		echo "    <".$annotation['name'].">".xmlTokens($annotation['value'])."</".$annotation['name'].">\n";
	}
}

// sort profile list by group, needed for xml2php2... :-(
$sortArray = array();
foreach($statsList['profile'] as $profile) {
    $sortArray[] = $profile["group"];
}
array_multisort($sortArray, $statsList['profile']);

foreach ($statsList['profile'] as $profile) {
	if ($session['response']['param']['mode'] == "score") {
		# only score, if asked for
		if (strpos($profile['group'], "Score_") !== false) {
			echo "    <".$profile['group'].">\n";
			echo "     <Id>".$profile['id']."</Id>\n";
			echo "     <Name>".xmlTokens($profile['name'])."</Name>\n";

			foreach ($profile['annotation'] as $profileAnnotation) {
				echo "     <".$profileAnnotation['name'].">".xmlTokens($profileAnnotation['value'])."</".$profileAnnotation['name'].">\n";
			}

			echo "    </".$profile['group'].">\n";
		}
	}
	if ($session['response']['param']['mode'] == "insights") {
		if (strpos($profile['group'], "Score_") !== false) {
			# exclude score from insights data
		}
		else {
			echo "    <".$profile['group'].">\n";
			echo "     <Id>".$profile['id']."</Id>\n";
			echo "     <Name>".xmlTokens($profile['name'])."</Name>\n";

			foreach ($profile['annotation'] as $profileAnnotation) {
				echo "     <".$profileAnnotation['name'].">".xmlTokens($profileAnnotation['value'])."</".$profileAnnotation['name'].">\n";
			}

			echo "    </".$profile['group'].">\n";
		}
	}
}

?>

  </Stats>

 </Content>

</Response>
