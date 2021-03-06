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
  <Activities>

<?php
foreach (array_keys($session['response']['param']['activityList']) as $key) {

	$userActivityId = $session['response']['param']['activityList'][$key]['id'];
	$userActivityTimestamp = $session['response']['param']['activityList'][$key]['timestamp'];
	$userActivityTitle = $session['response']['param']['activityList'][$key]['title'];
	$userActivityDescription = $session['response']['param']['activityList'][$key]['description'];
	$userActivityPriority = $session['response']['param']['activityList'][$key]['priority'];
	$userActivityUrl = $session['response']['param']['activityList'][$key]['url'];
	$userActivityUserId = $session['response']['param']['activityList'][$key]['userId'];

   	echo "    <Activity>\n";
   	echo "     <Id>".$userActivityId."</Id>\n";
   	echo "     <Timestamp>".$userActivityTimestamp."</Timestamp>\n";
   	echo "     <Title>".xmlTokens($userActivityTitle)."</Title>\n";
   	echo "     <Description>".xmlTokens($userActivityDescription)."</Description>\n";
   	echo "     <Priority>".xmlTokens($userActivityPriority)."</Priority>\n";
   	echo "     <Url>".xmlTokens($userActivityUrl)."</Url>\n";
   	echo "     <UserId>".$userActivityUserId."</UserId>\n";


	foreach ($session['response']['param']['activityList'][$key]['user'] as $user) {
	  	echo "     <User>\n";
	   	echo "      <firstName>".xmlTokens($user['userFirstName'])."</firstName>\n";
	   	echo "      <lastName>".xmlTokens($user['userLastName'])."</lastName>\n";
		foreach ($user['annotation'] as $annotation) {
			echo "    <".$annotation['name'].">".xmlTokens($annotation['value'])."</".$annotation['name'].">\n";
		}
	  	echo "     </User>\n";
	}

   	echo "    </Activity>\n";

}
?>

  </Activities>

  <Summary>
   <CompleteListSize><?php echo $session['response']['param']['listSize'] ?></CompleteListSize>
   <Cursor><?php echo $session['response']['param']['listCursor'] ?></Cursor>
  </Summary> 

 </Content>

</Response>
