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
foreach ($session['response']['param']['mailblocks'] as $mailblockId) {
	echo "  <Mailblock>\n";
	echo "   <Id>".$mailblockId."</Id>\n";
	echo "  </Mailblock>\n";
}
?>
 </Content>

</Response>
