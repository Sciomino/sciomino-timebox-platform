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
  <Level>
<?php
foreach ($session['response']['param']['levelList'] as $knowledgeKey => $knowledgeVal) {
   	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($knowledgeKey)."</name>\n";
   	echo "    <count>".$knowledgeVal."</count>\n";
   	echo "   </entry>\n";

}
?>
  </Level>
  <Summary>
   <CompleteListSize><?php echo $session['response']['param']['listSize'] ?></CompleteListSize>
   <Cursor><?php echo $session['response']['param']['listCursor'] ?></Cursor>
  </Summary> 
 </Content>

</Response>
