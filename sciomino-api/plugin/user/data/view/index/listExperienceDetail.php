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
  <Title>
<?php
foreach ($session['response']['param']['titleList'] as $titleKey => $titleVal) {
    	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($titleKey)."</name>\n";
   	echo "    <count>".$titleVal."</count>\n";
   	echo "   </entry>\n";

}
?>
  </Title>
  <Alternative>
<?php
foreach ($session['response']['param']['alternativeList'] as $alternativeKey => $alternativeVal) {
    	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($alternativeKey)."</name>\n";
   	echo "    <count>".$alternativeVal."</count>\n";
   	echo "   </entry>\n";

}
?>
  </Alternative>
  <Like>
<?php
foreach ($session['response']['param']['likeList'] as $likeKey => $likeVal) {
    	echo "   <entry>\n";
   	echo "    <name>".$likeKey."</name>\n";
   	echo "    <count>".$likeVal."</count>\n";
   	echo "   </entry>\n";

}
?>
  </Like>
  <Has>
<?php
foreach ($session['response']['param']['hasList'] as $hasKey => $hasVal) {
    	echo "   <entry>\n";
   	echo "    <name>".$hasKey."</name>\n";
   	echo "    <count>".$hasVal."</count>\n";
   	echo "   </entry>\n";

}
?>
  </Has>
  <Summary>
   <CompleteListSize><?php echo $session['response']['param']['listSize'] ?></CompleteListSize>
   <Cursor><?php echo $session['response']['param']['listCursor'] ?></Cursor>
  </Summary> 
 </Content>

</Response>
