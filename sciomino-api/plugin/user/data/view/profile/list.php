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
  <Profiles>

<?php
foreach (array_keys($session['response']['param']['profileList']) as $key) {

        $profileId = $session['response']['param']['profileList'][$key]['id'];
        $profileName = $session['response']['param']['profileList'][$key]['name'];
        $profileGroup = $session['response']['param']['profileList'][$key]['group'];
        #$extId = $session['response']['param']['profileList'][$key]['extId'];
        #$extReference = $session['response']['param']['profileList'][$key]['extReference'];

  	echo "   <Profile>\n";
   	echo "    <Id>".$profileId."</Id>\n";
   	echo "    <Name>".xmlTokens($profileName)."</Name>\n";
   	echo "    <Group>".xmlTokens($profileGroup)."</Group>\n";
   	#echo "    <$extReference>".$extId."</$extReference>\n";

	foreach ($session['response']['param']['profileList'][$key]['annotation'] as $annotation) {
		echo "    <".$annotation['name'].">".xmlTokens($annotation['value'])."</".$annotation['name'].">\n";
	}

  	echo "   </Profile>\n";
}
?>

  </Profiles>

  <Summary>
   <CompleteListSize><?php echo $session['response']['param']['listSize'] ?></CompleteListSize>
   <Cursor><?php echo $session['response']['param']['listCursor'] ?></Cursor>
  </Summary> 

 </Content>

</Response>
