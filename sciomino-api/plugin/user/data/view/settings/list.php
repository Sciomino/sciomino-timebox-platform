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
  <Settings>

<?php
foreach (array_keys($session['response']['param']['settingsList']) as $key) {

	$userSettingsId = $session['response']['param']['settingsList'][$key]['id'];
	$userSettingsName = $session['response']['param']['settingsList'][$key]['name'];
	$userSettingsValue = $session['response']['param']['settingsList'][$key]['value'];

   	echo "    <Setting>\n";
   	echo "     <Id>".$userSettingsId."</Id>\n";
   	echo "     <Name>".xmlTokens($userSettingsName)."</Name>\n";
   	echo "     <Value>".xmlTokens($userSettingsValue)."</Value>\n";
   	echo "    </Setting>\n";
}
?>

  </Settings>

  <Summary>
   <CompleteListSize><?php echo $session['response']['param']['listSize'] ?></CompleteListSize>
   <Cursor><?php echo $session['response']['param']['listCursor'] ?></Cursor>
  </Summary> 

 </Content>

</Response>
