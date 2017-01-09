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
  <Users>

<?php
foreach (array_keys($session['response']['param']['userList']) as $key) {

        $userId = $session['response']['param']['userList'][$key]['userId'];
        $userFirstName = $session['response']['param']['userList'][$key]['userFirstName'];
        $userLastName = $session['response']['param']['userList'][$key]['userLastName'];
        $userLoginName = $session['response']['param']['userList'][$key]['userLoginName'];
        $userPageName = $session['response']['param']['userList'][$key]['userPageName'];
        $userTimestamp = $session['response']['param']['userList'][$key]['userTimestamp'];
        $userViews = $session['response']['param']['userList'][$key]['userViews'];
        $reference = $session['response']['param']['userList'][$key]['reference'];

  	echo "   <User>\n";

   	echo "    <Id>".$userLoginName."</Id>\n";
   	echo "    <FirstName>".xmlTokens($userFirstName)."</FirstName>\n";
   	echo "    <LastName>".xmlTokens($userLastName)."</LastName>\n";
   	/*
   	echo "    <LoginName>".xmlTokens($userLoginName)."</LoginName>\n";
   	echo "    <PageName>".xmlTokens($userPageName)."</PageName>\n";
   	echo "    <Timestamp>".$userTimestamp."</Timestamp>\n";
   	echo "    <Views>".$userViews."</Views>\n";
   	echo "    <Reference>".xmlTokens($reference)."</Reference>\n";
   	*/

	// annotation
	/*
	foreach ($session['response']['param']['userList'][$key]['annotation'] as $annotation) {
		echo "    <".$annotation['name'].">".xmlTokens($annotation['value'])."</".$annotation['name'].">\n";
	}
	*/

	// sort profile list by group, needed for xml2php2... :-(
	$sortArray = array();
	foreach($session['response']['param']['userList'][$key]['profile'] as $profile) {
	    $sortArray[] = $profile["group"];
	}
	array_multisort($sortArray, $session['response']['param']['userList'][$key]['profile']);

	foreach ($session['response']['param']['userList'][$key]['profile'] as $profile) {
		// only display availability data (not knowledge, hobby & tag)
		if ($profile['group'] == "availability") {
			echo "    <".$profile['group'].">\n";
			echo "     <Id>".$profile['id']."</Id>\n";
			echo "     <Name>".xmlTokens($profile['name'])."</Name>\n";

			foreach ($profile['annotation'] as $profileAnnotation) {
				echo "     <".$profileAnnotation['name'].">".xmlTokens($profileAnnotation['value'])."</".$profileAnnotation['name'].">\n";
			}

			echo "    </".$profile['group'].">\n";
		}
	}

  	echo "   </User>\n";
}
?>

  </Users>

  <Summary>
   <CompleteListSize><?php echo $session['response']['param']['listSize'] ?></CompleteListSize>
   <Cursor><?php echo $session['response']['param']['listCursor'] ?></Cursor>
  </Summary> 

 </Content>

</Response>
