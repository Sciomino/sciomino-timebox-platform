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
  <Acts>

<?php
foreach (array_keys($session['response']['param']['actList']) as $key) {
 
        $actId = $session['response']['param']['actList'][$key]['id'];
        $actDescription = $session['response']['param']['actList'][$key]['description'];
        $actTimestamp = $session['response']['param']['actList'][$key]['timestamp'];
        $actExpiration = $session['response']['param']['actList'][$key]['expiration'];
        $actActive = $session['response']['param']['actList'][$key]['active'];
        $actParent = $session['response']['param']['actList'][$key]['parent'];
        $reference = $session['response']['param']['actList'][$key]['reference'];

  	echo "   <Act>\n";

   	echo "    <Id>".$actId."</Id>\n";
   	echo "    <Description>".xmlTokens($actDescription)."</Description>\n";
    	echo "    <Timestamp>".$actTimestamp."</Timestamp>\n";
    	echo "    <Expiration>".$actExpiration."</Expiration>\n";
    	echo "    <Active>".$actActive."</Active>\n";
    	echo "    <Parent>".$actParent."</Parent>\n";
   	echo "    <Reference>".$reference."</Reference>\n";

	// annotation
	foreach ($session['response']['param']['actList'][$key]['annotation'] as $annotation) {
		echo "    <".$annotation['name'].">".xmlTokens($annotation['value'])."</".$annotation['name'].">\n";
	}

	// sort profile list by group, needed for xml2php2... :-(
	$sortArray = array();
	foreach($session['response']['param']['actList'][$key]['profile'] as $profile) {
	    $sortArray[] = $profile["group"];
	}
	array_multisort($sortArray, $session['response']['param']['actList'][$key]['profile']);

	foreach ($session['response']['param']['actList'][$key]['profile'] as $profile) {
		echo "    <".$profile['group'].">\n";
		echo "     <Id>".$profile['id']."</Id>\n";
		echo "     <Name>".xmlTokens($profile['name'])."</Name>\n";

		foreach ($profile['annotation'] as $profileAnnotation) {
			echo "     <".$profileAnnotation['name'].">".xmlTokens($profileAnnotation['value'])."</".$profileAnnotation['name'].">\n";
		}

		echo "    </".$profile['group'].">\n";
	}

	// review
	foreach ($session['response']['param']['actList'][$key]['review'] as $review) {
		echo "    <Review>\n";
		echo "     <Id>".$review['id']."</Id>\n";
		echo "     <Score>".$review['score']."</Score>\n";
		echo "     <Reference>".$review['reference']."</Reference>\n";
		echo "    </Review>\n";
	}

	// mailblock
	foreach ($session['response']['param']['actList'][$key]['mailblock'] as $mailblock) {
		echo "    <Mailblock>\n";
		echo "     <Id>".$mailblock['id']."</Id>\n";
		echo "     <Reference>".$mailblock['reference']."</Reference>\n";
		echo "    </Mailblock>\n";
	}

  	echo "   </Act>\n";
}
?>

  </Acts>

  <Summary>
   <CompleteListSize><?php echo $session['response']['param']['listSize'] ?></CompleteListSize>
   <Cursor><?php echo $session['response']['param']['listCursor'] ?></Cursor>
  </Summary> 

 </Content>

</Response>
