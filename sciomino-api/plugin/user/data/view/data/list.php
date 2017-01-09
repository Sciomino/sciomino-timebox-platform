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
  <Datas>

<?php
foreach (array_keys($session['response']['param']['dataList']) as $key) {

	$userDataId = $session['response']['param']['dataList'][$key]['id'];
	$userDataName = $session['response']['param']['dataList'][$key]['name'];
	$userDataValue = $session['response']['param']['dataList'][$key]['value'];

   	echo "    <Data>\n";
   	echo "     <Id>".$userDataId."</Id>\n";
   	echo "     <Name>".xmlTokens($userDataName)."</Name>\n";
   	echo "     <Value>".xmlTokens($userDataValue)."</Value>\n";
   	echo "    </Data>\n";
}
?>

  </Datas>

  <Summary>
   <CompleteListSize><?php echo $session['response']['param']['listSize'] ?></CompleteListSize>
   <Cursor><?php echo $session['response']['param']['listCursor'] ?></Cursor>
  </Summary> 

 </Content>

</Response>
