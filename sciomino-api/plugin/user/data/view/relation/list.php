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
  <Relations>

<?php
foreach (array_keys($session['response']['param']['relationList']) as $key) {

	$userRelationId = $session['response']['param']['relationList'][$key]['id'];
	$userRelationUserId = $session['response']['param']['relationList'][$key]['userId'];

   	echo "    <Relation>\n";
   	echo "     <Id>".$userRelationId."</Id>\n";
   	echo "     <RelationUserId>".$userRelationUserId."</RelationUserId>\n";

	foreach ($session['response']['param']['relationList'][$key]['accessGroup'] as $accessGroup) {
	   	echo "     <AccessGroup>\n";
	   	echo "      <Id>".$accessGroup['id']."</Id>\n";
	   	echo "      <Name>".$accessGroup['name']."</Name>\n";
	   	echo "      <Level>".$accessGroup['level']."</Level>\n";
	   	echo "     </AccessGroup>\n";
	}

   	echo "    </Relation>\n";

}
?>

  </Relations>

  <Summary>
   <CompleteListSize><?php echo $session['response']['param']['listSize'] ?></CompleteListSize>
   <Cursor><?php echo $session['response']['param']['listCursor'] ?></Cursor>
  </Summary> 

 </Content>

</Response>
