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
  <AccessGroups>

<?php
foreach (array_keys($session['response']['param']['accessGroupList']) as $key) {

	$accessGroupId = $session['response']['param']['accessGroupList'][$key]['id'];
	$accessGroupName = $session['response']['param']['accessGroupList'][$key]['name'];
	$accessGroupLevel = $session['response']['param']['accessGroupList'][$key]['level'];

   	echo "    <AccessGroup>\n";
   	echo "     <Id>".$accessGroupId."</Id>\n";
   	echo "     <Name>".$accessGroupName."</Name>\n";
   	echo "     <Level>".$accessGroupLevel."</Level>\n";
   	echo "    </AccessGroup>\n";
}
?>

  </AccessGroups>

  <Summary>
   <CompleteListSize><?php echo $session['response']['param']['listSize'] ?></CompleteListSize>
   <Cursor><?php echo $session['response']['param']['listCursor'] ?></Cursor>
  </Summary> 

 </Content>

</Response>
