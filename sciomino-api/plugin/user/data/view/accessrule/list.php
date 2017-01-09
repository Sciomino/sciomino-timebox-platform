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
  <AccessRules>

<?php
foreach (array_keys($session['response']['param']['accessRuleList']) as $key) {

	$accessRuleId = $session['response']['param']['accessRuleList'][$key]['id'];
	$accessRuleName = $session['response']['param']['accessRuleList'][$key]['name'];
	$accessRuleValue = $session['response']['param']['accessRuleList'][$key]['value'];

   	echo "    <AccessRule>\n";
   	echo "     <Id>".$accessRuleId."</Id>\n";
   	echo "     <Name>".$accessRuleName."</Name>\n";
   	echo "     <Value>".$accessRuleValue."</Value>\n";
   	echo "    </AccessRule>\n";
}
?>

  </AccessRules>

  <Summary>
   <CompleteListSize><?php echo $session['response']['param']['listSize'] ?></CompleteListSize>
   <Cursor><?php echo $session['response']['param']['listCursor'] ?></Cursor>
  </Summary> 

 </Content>

</Response>
