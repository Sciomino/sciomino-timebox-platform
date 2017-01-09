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
  <Sections>

<?php
foreach (array_keys($session['response']['param']['sectionList']) as $key) {

        $sectionId = $session['response']['param']['sectionList'][$key]['id'];
        $sectionName = $session['response']['param']['sectionList'][$key]['name'];
        $sectionType = $session['response']['param']['sectionList'][$key]['type'];
        #$extId = $session['response']['param']['sectionList'][$key]['extId'];
        #$extReference = $session['response']['param']['sectionList'][$key]['extReference'];

  	echo "   <Section>\n";
   	echo "    <Id>".$sectionId."</Id>\n";
   	echo "    <Name>".xmlTokens($sectionName)."</Name>\n";
   	echo "    <Type>".xmlTokens($sectionType)."</Type>\n";
   	#echo "    <$extReference>".$extId."</$extReference>\n";

	foreach ($session['response']['param']['sectionList'][$key]['annotation'] as $annotation) {
		echo "    <".$annotation['name'].">".xmlTokens($annotation['value'])."</".$annotation['name'].">\n";
	}

	foreach ($session['response']['param']['sectionList'][$key]['profile'] as $profile) {
		echo "    <".$profile['group'].">\n";
		echo "     <name>".xmlTokens($profile['name'])."</name>\n";

		foreach ($profile['annotation'] as $profileAnnotation) {
			echo "     <".$profileAnnotation['name'].">".xmlTokens($profileAnnotation['value'])."</".$profileAnnotation['name'].">\n";
		}

		echo "    </".$profile['group'].">\n";
	}

  	echo "   </Section>\n";
}
?>

  </Sections>

  <Summary>
   <CompleteListSize><?php echo $session['response']['param']['listSize'] ?></CompleteListSize>
   <Cursor><?php echo $session['response']['param']['listCursor'] ?></Cursor>
  </Summary> 

 </Content>

</Response>
