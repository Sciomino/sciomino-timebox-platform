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
   <CheckupTotal><?php echo $session['response']['param']['checkupTotal'] ?></CheckupTotal>
   <CheckupFail><?php echo $session['response']['param']['checkupCount'] ?></CheckupFail>
   <CheckupFailUsers><?php echo $session['response']['param']['checkupCountUsers'] ?></CheckupFailUsers>
   <CheckupUsers><?php echo $session['response']['param']['checkupUsers'] ?></CheckupUsers>
   <?php
   foreach ($session['response']['param']['checkupDetail'] as $detailKey => $detailVal) {
	   echo "<CheckupDetail>".$detailKey.":".$detailVal."</CheckupDetail>\n";
   }
   ?>
</Content>

</Response>

