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
  <Reviews>

<?php
foreach (array_keys($session['response']['param']['reviewList']) as $key) {
 
        $reviewId = $session['response']['param']['reviewList'][$key]['id'];
        $reviewScore = $session['response']['param']['reviewList'][$key]['score'];
        $reference = $session['response']['param']['reviewList'][$key]['reference'];
        $act = $session['response']['param']['reviewList'][$key]['act'];

  	echo "   <Review>\n";

   	echo "    <Id>".$reviewId."</Id>\n";
   	echo "    <Score>".$reviewScore."</Score>\n";
    	echo "    <Reference>".$reference."</Reference>\n";
   	echo "    <Act>".$act."</Act>\n";

  	echo "   </Review>\n";
}
?>

  </Reviews>

  <Summary>
   <CompleteListSize><?php echo $session['response']['param']['listSize'] ?></CompleteListSize>
   <Cursor><?php echo $session['response']['param']['listCursor'] ?></Cursor>
  </Summary> 

 </Content>

</Response>
