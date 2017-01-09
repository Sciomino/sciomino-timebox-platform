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
  <AccessApps>

<?php
foreach (array_keys($session['response']['param']['accessAppList']) as $key) {

	$accessAppId = $session['response']['param']['accessAppList'][$key]['id'];
	$accessAppName = $session['response']['param']['accessAppList'][$key]['name'];
	$accessAppKey = $session['response']['param']['accessAppList'][$key]['key'];

   	echo "    <AccessApp>\n";
   	echo "     <Id>".$accessAppId."</Id>\n";
   	echo "     <Name>".$accessAppName."</Name>\n";
   	echo "     <Key>".$accessAppKey."</Key>\n";

	foreach ($session['response']['param']['accessAppList'][$key]['group'] as $group) {
	  	echo "     <GroupOwner>\n";
	   	echo "      <Name>".xmlTokens($group['name'])."</Name>\n";
	   	echo "      <Description>".xmlTokens($group['description'])."</Description>\n";
	   	echo "      <Type>".xmlTokens($group['type'])."</Type>\n";
	   	echo "      <Timestamp>".xmlTokens($group['timestamp'])."</Timestamp>\n";
	  	echo "     </GroupOwner>\n";
	}

   	echo "    </AccessApp>\n";
}
?>

  </AccessApps>

  <Summary>
   <CompleteListSize><?php echo $session['response']['param']['listSize'] ?></CompleteListSize>
   <Cursor><?php echo $session['response']['param']['listCursor'] ?></Cursor>
  </Summary> 

 </Content>

</Response>
