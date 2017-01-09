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
  <Connects>

<?php
foreach (array_keys($session['response']['param']['connectList']) as $key) {

        $connectId = $session['response']['param']['connectList'][$key]['connectId'];
        $connectType = $session['response']['param']['connectList'][$key]['connectType'];
        $connectName = $session['response']['param']['connectList'][$key]['connectName'];
        $connectTimestamp = $session['response']['param']['connectList'][$key]['connectTimestamp'];
        $reference = $session['response']['param']['connectList'][$key]['reference'];

  	echo "   <Connect>\n";

   	echo "    <Id>".$connectId."</Id>\n";
   	echo "    <Type>".xmlTokens($connectType)."</Type>\n";
   	echo "    <Name>".xmlTokens($connectName)."</Name>\n";
   	echo "    <Timestamp>".$connectTimestamp."</Timestamp>\n";
   	echo "    <Reference>".$reference."</Reference>\n";

	foreach ($session['response']['param']['connectList'][$key]['annotation'] as $annotation) {
		echo "    <".$annotation['name'].">".xmlTokens($annotation['value'])."</".$annotation['name'].">\n";
	}

	foreach ($session['response']['param']['connectList'][$key]['profile'] as $profile) {
		echo "    <".$profile['group'].">\n";
		echo "     <name>".$profile['name']."</name>\n";

		foreach ($profile['annotation'] as $profileAnnotation) {
			echo "     <".$profileAnnotation['name'].">".xmlTokens($profileAnnotation['value'])."</".$profileAnnotation['name'].">\n";
		}

		echo "    </".$profile['group'].">\n";
	}

  	echo "   </Connect>\n";
}
?>

  </Connects>

  <Summary>
   <CompleteListSize><?php echo $session['response']['param']['listSize'] ?></CompleteListSize>
   <Cursor><?php echo $session['response']['param']['listCursor'] ?></Cursor>
  </Summary> 

 </Content>

</Response>
