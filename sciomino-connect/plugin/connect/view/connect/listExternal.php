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
  <Connects>

<?php
foreach (array_keys($session['response']['param']['connectList']) as $key) {

        $connectName = $session['response']['param']['connectList'][$key]['connectName'];

        $url = $session['response']['param']['connectList'][$key]['url'];
        $description = $session['response']['param']['connectList'][$key]['description'];

  	echo "   <Connect>\n";

   	echo "    <Name>".xmlTokens($connectName)."</Name>\n";
   	echo "    <Url>".xmlTokens($url)."</Url>\n";
   	echo "    <Description>".xmlTokens($description)."</Description>\n";

  	echo "   </Connect>\n";
}
?>

  </Connects>

  <Summary>
   <CompleteListSize><?php echo $session['response']['param']['listSize'] ?></CompleteListSize>
   <Cursor><?php echo $session['response']['param']['listCursor'] ?></Cursor>
  </Summary> 

 </Content>

</Response>
