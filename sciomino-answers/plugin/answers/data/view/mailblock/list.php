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
  <Mailblocks>

<?php
foreach (array_keys($session['response']['param']['mailblockList']) as $key) {
 
	$mailblockId = $session['response']['param']['mailblockList'][$key]['id'];
	$reference = $session['response']['param']['mailblockList'][$key]['reference'];
	$act = $session['response']['param']['mailblockList'][$key]['act'];

  	echo "   <Mailblock>\n";
   	echo "    <Id>".$mailblockId."</Id>\n";
   	echo "    <Reference>".$reference."</Reference>\n";
   	echo "    <Act>".$act."</Act>\n";
  	echo "   </Mailblock>\n";
}
?>

  </Mailblocks>

  <Summary>
   <CompleteListSize><?php echo $session['response']['param']['listSize'] ?></CompleteListSize>
   <Cursor><?php echo $session['response']['param']['listCursor'] ?></Cursor>
  </Summary> 

 </Content>

</Response>
