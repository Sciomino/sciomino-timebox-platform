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
  <Groups>

<?php
foreach (array_keys($session['response']['param']['groupList']) as $key) {

	$userGroupId = $session['response']['param']['groupList'][$key]['id'];
	$userGroupName = $session['response']['param']['groupList'][$key]['name'];
	$userGroupDescription = $session['response']['param']['groupList'][$key]['description'];
	$userGroupType = $session['response']['param']['groupList'][$key]['type'];
	$userGroupTimestamp = $session['response']['param']['groupList'][$key]['timestamp'];

   	echo "    <Group>\n";
   	echo "     <Id>".$userGroupId."</Id>\n";
   	echo "     <Name>".xmlTokens($userGroupName)."</Name>\n";
   	echo "     <Description>".xmlTokens($userGroupDescription)."</Description>\n";
   	echo "     <Type>".xmlTokens($userGroupType)."</Type>\n";
   	echo "     <Timestamp>".$userGroupTimestamp."</Timestamp>\n";

	foreach ($session['response']['param']['groupList'][$key]['accessGroup'] as $accessGroup) {
	   	echo "     <AccessGroup>\n";
	   	echo "      <Name>".$accessGroup['name']."</Name>\n";
	   	echo "      <Level>".$accessGroup['level']."</Level>\n";
	   	echo "     </AccessGroup>\n";
	}

	foreach ($session['response']['param']['groupList'][$key]['user'] as $user) {
	   	echo "     <User>\n";
	   	echo "      <Id>".$user['userId']."</Id>\n";
	   	echo "      <FirstName>".xmlTokens($user['userFirstName'])."</FirstName>\n";
	   	echo "      <LastName>".xmlTokens($user['userLastName'])."</LastName>\n";
	   	echo "      <LoginName>".xmlTokens($user['userLoginName'])."</LoginName>\n";
	   	echo "      <PageName>".xmlTokens($user['userPageName'])."</PageName>\n";
	   	echo "      <Timestamp>".$user['userTimestamp']."</Timestamp>\n";
	   	echo "      <Views>".$user['userViews']."</Views>\n";
	   	echo "      <Reference>".xmlTokens($user['reference'])."</Reference>\n";
	   	echo "     </User>\n";
	}

   	echo "    </Group>\n";

}
?>

  </Groups>

  <Summary>
   <CompleteListSize><?php echo $session['response']['param']['listSize'] ?></CompleteListSize>
   <Cursor><?php echo $session['response']['param']['listCursor'] ?></Cursor>
  </Summary> 

 </Content>

</Response>
