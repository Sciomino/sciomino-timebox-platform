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
  <Usages>

<?php
foreach (array_keys($session['response']['param']['usageList']) as $key) {

	$usageGroupId = $session['response']['param']['usageList'][$key]['groupId'];
	$usageYear = $session['response']['param']['usageList'][$key]['year'];
	$usageMonth = $session['response']['param']['usageList'][$key]['month'];
	$usageDay = $session['response']['param']['usageList'][$key]['day'];
	$usageCount = $session['response']['param']['usageList'][$key]['count'];

   	echo "    <Usage>\n";
   	echo "     <GroupId>".$usageGroupId."</GroupId>\n";
   	echo "     <Year>".$usageYear."</Year>\n";
   	echo "     <Month>".$usageMonth."</Month>\n";
   	echo "     <Day>".$usageDay."</Day>\n";
   	echo "     <Count>".$usageCount."</Count>\n";
   	echo "    </Usage>\n";

}
?>

  </Usages>

  <Summary>
   <CompleteListSize><?php echo $session['response']['param']['listSize'] ?></CompleteListSize>
   <Cursor><?php echo $session['response']['param']['listCursor'] ?></Cursor>
  </Summary> 

 </Content>

</Response>
