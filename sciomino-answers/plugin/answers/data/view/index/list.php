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
foreach ($session['response']['param']['indexList'] as $index) {
  	echo "   <Act>".$index."</Act>\n";
}
?>
  </Acts>
  <Suggest>
<?php
foreach ($session['response']['param']['suggestList'] as $suggestVal) {
   	echo "   <entry>\n";
   	echo "    <word>".xmlTokens($suggestVal['Word'])."</word>\n";
   	echo "    <context>".$suggestVal['Context']."</context>\n";
   	echo "   </entry>\n";

}
?>
  </Suggest>
  <Knowledge>
<?php
foreach ($session['response']['param']['knowledgeList'] as $knowledgeKey => $knowledgeVal) {
   	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($knowledgeKey)."</name>\n";
   	echo "    <count>".$knowledgeVal."</count>\n";
   	echo "   </entry>\n";

}
?>
  </Knowledge>
  <Hobby>
<?php
foreach ($session['response']['param']['hobbyList'] as $hobbyKey => $hobbyVal) {
   	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($hobbyKey)."</name>\n";
   	echo "    <count>".$hobbyVal."</count>\n";
   	echo "   </entry>\n";

}
?>
  </Hobby>
  <Businessunit>
<?php
foreach ($session['response']['param']['businessunitList'] as $businessunitKey => $businessunitVal) {
   	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($businessunitKey)."</name>\n";
   	echo "    <count>".$businessunitVal."</count>\n";
   	echo "   </entry>\n";
}
?>
  </Businessunit>
  <Workplace>
<?php
foreach ($session['response']['param']['workplaceList'] as $workplaceKey => $workplaceVal) {
   	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($workplaceKey)."</name>\n";
   	echo "    <count>".$workplaceVal."</count>\n";
   	echo "   </entry>\n";
}
?>
  </Workplace>
  <Status>
<?php
foreach ($session['response']['param']['statusList'] as $statusKey => $statusVal) {
   	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($statusKey)."</name>\n";
   	echo "    <count>".$statusVal."</count>\n";
   	echo "   </entry>\n";
}
?>
  </Status>
  <My>
<?php
foreach ($session['response']['param']['myList'] as $myKey => $myVal) {
   	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($myKey)."</name>\n";
   	echo "    <count>".$myVal."</count>\n";
   	echo "   </entry>\n";
}
?>
  </My>
  <Network>
<?php
foreach ($session['response']['param']['networkList'] as $netKey => $netVal) {
   	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($netKey)."</name>\n";
   	echo "    <count>".$netVal."</count>\n";
   	echo "   </entry>\n";
}
?>
  </Network>

  <Summary>
   <CompleteListSize><?php echo $session['response']['param']['listSize'] ?></CompleteListSize>
   <Cursor><?php echo $session['response']['param']['listCursor'] ?></Cursor>
  </Summary> 
 </Content>

</Response>
