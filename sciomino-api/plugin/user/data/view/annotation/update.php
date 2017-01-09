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
<?php
foreach ($session['response']['param']['annotations'] as $annotationId) {
	echo "  <Annotation>\n";
	echo "   <Id>".$annotationId."</Id>\n";
	echo "  </Annotation>\n";
}
?>
 </Content>

</Response>