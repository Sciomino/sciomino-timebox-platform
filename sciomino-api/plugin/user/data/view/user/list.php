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

   	echo "    <Id>".$userId."</Id>\n";
   	echo "    <FirstName>".xmlTokens($userFirstName)."</FirstName>\n";
   	echo "    <LastName>".xmlTokens($userLastName)."</LastName>\n";
   	echo "    <LoginName>".xmlTokens($userLoginName)."</LoginName>\n";
   	echo "    <PageName>".xmlTokens($userPageName)."</PageName>\n";
   	echo "    <Timestamp>".$userTimestamp."</Timestamp>\n";
   	echo "    <Views>".$userViews."</Views>\n";
   	echo "    <Reference>".xmlTokens($reference)."</Reference>\n";

	foreach ($session['response']['param']['userList'][$key]['message'] as $message) {
		echo "    <Message>".xmlTokens($message['description'])."</Message>\n";
		echo "    <MessageTimestamp>".xmlTokens($message['timestamp'])."</MessageTimestamp>\n";
	}

	// annotation
	foreach ($session['response']['param']['userList'][$key]['annotation'] as $annotation) {
		echo "    <".$annotation['name'].">".xmlTokens($annotation['value'])."</".$annotation['name'].">\n";
	}

	// sort profile list by group, needed for xml2php2... :-(
	$sortArray = array();
	foreach($session['response']['param']['userList'][$key]['profile'] as $profile) {
	    $sortArray[] = $profile["group"];
	}
	array_multisort($sortArray, $session['response']['param']['userList'][$key]['profile']);

	foreach ($session['response']['param']['userList'][$key]['profile'] as $profile) {
		echo "    <".$profile['group'].">\n";
		echo "     <Id>".$profile['id']."</Id>\n";
		echo "     <Name>".xmlTokens($profile['name'])."</Name>\n";

		foreach ($profile['annotation'] as $profileAnnotation) {
			echo "     <".$profileAnnotation['name'].">".xmlTokens($profileAnnotation['value'])."</".$profileAnnotation['name'].">\n";
		}

		echo "    </".$profile['group'].">\n";
	}

	// groups
	foreach ($session['response']['param']['userList'][$key]['group'] as $group) {
	  	echo "     <GroupOwner>\n";
		echo " 	    <Id>".$group['id']."</Id>\n";
	   	echo "      <Name>".xmlTokens($group['name'])."</Name>\n";
	   	echo "      <Description>".xmlTokens($group['description'])."</Description>\n";
	   	echo "      <Type>".xmlTokens($group['type'])."</Type>\n";
	   	echo "      <Timestamp>".$group['timestamp']."</Timestamp>\n";
	  	echo "     </GroupOwner>\n";
	}

	foreach ($session['response']['param']['userList'][$key]['groupMember'] as $groupMember) {
	  	echo "     <GroupMember>\n";
		echo " 	    <Id>".$groupMember['id']."</Id>\n";
	   	echo "      <Name>".xmlTokens($groupMember['name'])."</Name>\n";
	   	echo "      <Description>".xmlTokens($groupMember['description'])."</Description>\n";
	   	echo "      <Type>".xmlTokens($groupMember['type'])."</Type>\n";
	   	echo "      <Timestamp>".$groupMember['timestamp']."</Timestamp>\n";
	  	echo "     </GroupMember>\n";
	}

	// contact
	foreach ($session['response']['param']['userList'][$key]['contact'] as $contact) {
		echo "    <Contact>\n";
		echo "     <Id>".$contact['id']."</Id>\n";
		echo "     <Name>".xmlTokens($contact['name'])."</Name>\n";

		foreach ($contact['annotation'] as $contactAnnotation) {
			echo "     <".$contactAnnotation['name'].">".xmlTokens($contactAnnotation['value'])."</".$contactAnnotation['name'].">\n";
		}

		echo "    </Contact>\n";
	}

	// address
	foreach ($session['response']['param']['userList'][$key]['address'] as $address) {
		echo "    <Address>\n";
		echo "     <Id>".$address['id']."</Id>\n";
		echo "     <Name>".xmlTokens($address['name'])."</Name>\n";

		foreach ($address['annotation'] as $addressAnnotation) {
			echo "     <".$addressAnnotation['name'].">".xmlTokens($addressAnnotation['value'])."</".$addressAnnotation['name'].">\n";
		}

		echo "    </Address>\n";
	}

	// organization
	foreach ($session['response']['param']['userList'][$key]['organization'] as $organization) {
		echo "    <Organization>\n";
		echo "     <Id>".$organization['id']."</Id>\n";
		echo "     <Name>".xmlTokens($organization['name'])."</Name>\n";

		foreach ($organization['annotation'] as $organizationAnnotation) {
			echo "     <".$organizationAnnotation['name'].">".xmlTokens($organizationAnnotation['value'])."</".$organizationAnnotation['name'].">\n";
		}

		echo "    </Organization>\n";
	}

	// publication
	foreach ($session['response']['param']['userList'][$key]['publication'] as $publication) {
		echo "    <Publication>\n";
		echo "     <Id>".$publication['id']."</Id>\n";
		echo "     <Name>".xmlTokens($publication['name'])."</Name>\n";

		foreach ($publication['annotation'] as $publicationAnnotation) {
			echo "     <".$publicationAnnotation['name'].">".xmlTokens($publicationAnnotation['value'])."</".$publicationAnnotation['name'].">\n";
		}

		echo "    </Publication>\n";
	}

	// experience
	foreach ($session['response']['param']['userList'][$key]['experience'] as $experience) {
		echo "    <Experience>\n";
		echo "     <Id>".$experience['id']."</Id>\n";
		echo "     <Name>".xmlTokens($experience['name'])."</Name>\n";

		foreach ($experience['annotation'] as $experienceAnnotation) {
			echo "     <".$experienceAnnotation['name'].">".xmlTokens($experienceAnnotation['value'])."</".$experienceAnnotation['name'].">\n";
		}

		echo "    </Experience>\n";
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
