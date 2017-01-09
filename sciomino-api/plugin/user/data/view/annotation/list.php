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
  <Annotations>

<?php
foreach (array_keys($session['response']['param']['annotationList']) as $key) {

        $annotationId = $session['response']['param']['annotationList'][$key]['id'];
        $annotationName = $session['response']['param']['annotationList'][$key]['name'];
        $annotationValue = $session['response']['param']['annotationList'][$key]['value'];
        $annotationType = $session['response']['param']['annotationList'][$key]['type'];
        #$extId = $session['response']['param']['annotationList'][$key]['extId'];
        #$extReference = $session['response']['param']['annotationList'][$key]['extReference'];

  	echo "   <Annotation>\n";
   	echo "    <Id>".$annotationId."</Id>\n";
   	echo "    <Name>".xmlTokens($annotationName)."</Name>\n";
   	echo "    <Value>".xmlTokens($annotationValue)."</Value>\n";
   	echo "    <Type>".xmlTokens($annotationType)."</Type>\n";
   	#echo "    <$extReference>".$extId."</$extReference>\n";
  	echo "   </Annotation>\n";
}
?>

  </Annotations>

  <Summary>
   <CompleteListSize><?php echo $session['response']['param']['listSize'] ?></CompleteListSize>
   <Cursor><?php echo $session['response']['param']['listCursor'] ?></Cursor>
  </Summary> 

 </Content>

</Response>
