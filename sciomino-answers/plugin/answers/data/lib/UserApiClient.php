<?php

//////////
// CONNECT WITH API HOST: Save, Update, List & Delete functions
// + Detail function
//////////

function UserApiSave ($object, $values) {

	global $XCOW_B;
	$headers = array();
	$params = array();

	// request
	$url = $XCOW_B['user_api']['host'].$object."/save";

	// auth
	$authParams = postUserApiAuth();
	$params = array_merge($values, $authParams);

	// post
	$body = postResponse ($url, $headers, $params);
	$bodyObj = new Xml2Php2();
	$bodyObj->startProcessing($body[1], 1);
	$bodyArray = $bodyObj->getPhpArray();

        return $bodyArray;

}

function UserApiUpdate ($object, $id, $values) {

	global $XCOW_B;
	$headers = array();
	$params = array();

	// request
	if ($id == 0) {
		$url = $XCOW_B['user_api']['host'].$object."/update";
	}
	else {
		$url = $XCOW_B['user_api']['host'].$object."/".$id."/update";
	}

	// auth
	$authParams = postUserApiAuth();
	$params = array_merge($values, $authParams);

	// post
	$body = postResponse ($url, $headers, $params);
	$bodyObj = new Xml2Php2();
	$bodyObj->startProcessing($body[1], 1);
	$bodyArray = $bodyObj->getPhpArray();

        return $bodyArray;

}

function UserApiList ($object, $id, $query) {

	global $XCOW_B;

	// request
	if ($id == 0) {
		$url = $XCOW_B['user_api']['host'].$object."/list";
	}
	else {
		$url = $XCOW_B['user_api']['host'].$object."/".$id."/list";
	}

	// auth
	if ($query != '') {
		$query .= "&".GetUserApiAuth();
	}
	else {
		$query .= GetUserApiAuth();
	}
	
	if ($query != '') {
		$url = $url."?".$query;
	}

	// get
	$body = getResponse ($url);
	$bodyObj = new Xml2Php2();
	$bodyObj->startProcessing($body, 1);
	$bodyArray = $bodyObj->getPhpArray();

        return $bodyArray;

}

function UserApiDelete ($object, $id) {

	global $XCOW_B;
	$headers = array();
	$params = array();

	// request
	$url = $XCOW_B['user_api']['host'].$object."/".$id."/delete";

	// auth
	$authParams = postUserApiAuth();
	$params = $authParams;
	
	// post
	$body = postResponse ($url, $headers, $params);
	$bodyObj = new Xml2Php2();
	$bodyObj->startProcessing($body[1], 1);
	$bodyArray = $bodyObj->getPhpArray();

        return $bodyArray;

}

function UserApiDetail ($object, $values) {

	global $XCOW_B;

	// request
	$url = $XCOW_B['user_api']['host'].$object;

	// auth
	$query = "";
	$query .= GetUserApiAuth();
	foreach ($values as $key => $val) {
		if ($query == "") {		
			$query .= urlencode($key)."=".urlencode($val);
		}
		else {
			$query .= "&".urlencode($key)."=".urlencode($val);
		}
	}
	$url = $url."&".$query;

	// get
	$body = getResponse ($url);
	$bodyObj = new Xml2Php2();
	$bodyObj->startProcessing($body, 1);
	$bodyArray = $bodyObj->getPhpArray();

        return $bodyArray;

}

function UserApiDetailPlain ($object, $values) {

	global $XCOW_B;

	// request
	$url = $XCOW_B['user_api']['host'].$object;

	// auth
	$query = "";
	$query .= GetUserApiAuth();
	foreach ($values as $key => $val) {
		if ($query == "") {		
			$query .= urlencode($key)."=".urlencode($val);
		}
		else {
			$query .= "&".urlencode($key)."=".urlencode($val);
		}
	}
	$url = $url."&".$query;

	// get
	$body = getResponse ($url);

        return $body;

}

function GetUserApiAuth () {

	global $XCOW_B;

	$query = "";

	// auth
	if ($XCOW_B['user_api']['auth']) {
		$query .= "user_api_id=".$XCOW_B['user_api']['id'];
		$query .= "&user_api_nonce=".$XCOW_B['user_api']['nonce'];
		$query .= "&user_api_key=".$XCOW_B['user_api']['key'];
	}

	return $query;
}

function PostUserApiAuth () {

	global $XCOW_B;

	$params = array();

	// auth
	if ($XCOW_B['user_api']['auth']) {
		$params['user_api_id'] = $XCOW_B['user_api']['id'];
		$params['user_api_nonce'] = $XCOW_B['user_api']['nonce'];
		$params['user_api_key'] = $XCOW_B['user_api']['key'];
	}

	return $params;
}


//////////
// SEARCH: List & Detail function
//////////
function UserApiListListAll($query) {

	$bodyArray = UserApiDetail("index/listAll?", $query);

	return UserApiListSearchResponse($bodyArray);

}

function UserApiListSearchWithQuery($query) {

	$bodyArray = UserApiList("index", '0', $query);

	return UserApiListSearchResponse($bodyArray);

}

function UserApiListSearchResponse($bodyArray) {

	$searchList = array();

	$searchList['user'] = array();
	$searchList['suggest'] = array();
	$searchList['knowledge'] = array();
	$searchList['company'] = array();
	$searchList['event'] = array();
	$searchList['education'] = array();
	$searchList['product'] = array();
	$searchList['hobby'] = array();
	$searchList['tag'] = array();
	$searchList['organization'] = array();
	$searchList['businessunit'] = array();
	$searchList['section'] = array();
	$searchList['role'] = array();
	$searchList['hometown'] = array();
	$searchList['workplace'] = array();
	$searchList['list'] = array();

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {

		if ($bodyArray['Response'][0]['Content'][0]['Summary'][0]['CompleteListSize']['0'] > 0) {
			$searchList['user'] = $bodyArray['Response'][0]['Content'][0]['Users'][0]['User'];

			if (isset($bodyArray['Response'][0]['Content'][0]['Suggest'][0]['entry'])) {
				$count = 0;
				foreach ($bodyArray['Response'][0]['Content'][0]['Suggest'][0]['entry'] as $entry) {
					$searchList['suggest'][$count] = array();
					$searchList['suggest'][$count]['word'] = $entry['word'][0];
					$searchList['suggest'][$count]['context'] = $entry['context'][0];
					$count++;
				}
			}

		
			//foreach ($bodyArray['Response'][0]['Content'][0]['Knowledge'][0] as $itemKey => $itemVal) {
			//	$searchList['knowledge'][$itemKey] = $itemVal[0];
			//}
			if (isset($bodyArray['Response'][0]['Content'][0]['Knowledge'][0]['entry'])) {
				foreach ($bodyArray['Response'][0]['Content'][0]['Knowledge'][0]['entry'] as $entry) {
					$name = $entry['name'][0];
					$searchList['knowledge'][$name] = $entry['count'][0];
				}
			}

			if (isset($bodyArray['Response'][0]['Content'][0]['Company'][0]['entry'])) {
				foreach ($bodyArray['Response'][0]['Content'][0]['Company'][0]['entry'] as $entry) {
					$name = $entry['name'][0];
					$searchList['company'][$name] = $entry['count'][0];
				}
			}

			if (isset($bodyArray['Response'][0]['Content'][0]['Event'][0]['entry'])) {
				foreach ($bodyArray['Response'][0]['Content'][0]['Event'][0]['entry'] as $entry) {
					$name = $entry['name'][0];
					$searchList['event'][$name] = $entry['count'][0];
				}
			}

			if (isset($bodyArray['Response'][0]['Content'][0]['Education'][0]['entry'])) {
				foreach ($bodyArray['Response'][0]['Content'][0]['Education'][0]['entry'] as $entry) {
					$name = $entry['name'][0];
					$searchList['education'][$name] = $entry['count'][0];
				}
			}

			if (isset($bodyArray['Response'][0]['Content'][0]['Product'][0]['entry'])) {
				foreach ($bodyArray['Response'][0]['Content'][0]['Product'][0]['entry'] as $entry) {
					$name = $entry['name'][0];
					$searchList['product'][$name] = $entry['count'][0];
				}
			}

			if (isset($bodyArray['Response'][0]['Content'][0]['Hobby'][0]['entry'])) {
				foreach ($bodyArray['Response'][0]['Content'][0]['Hobby'][0]['entry'] as $entry) {
					$name = $entry['name'][0];
					$searchList['hobby'][$name] = $entry['count'][0];
				}
			}

			if (isset($bodyArray['Response'][0]['Content'][0]['Tag'][0]['entry'])) {
				foreach ($bodyArray['Response'][0]['Content'][0]['Tag'][0]['entry'] as $entry) {
					$name = $entry['name'][0];
					$searchList['tag'][$name] = $entry['count'][0];
				}
			}

			if (isset($bodyArray['Response'][0]['Content'][0]['Organization'][0]['entry'])) {
				foreach ($bodyArray['Response'][0]['Content'][0]['Organization'][0]['entry'] as $entry) {
					$name = $entry['name'][0];
					$searchList['organization'][$name] = $entry['count'][0];
				}
			}

			if (isset($bodyArray['Response'][0]['Content'][0]['Businessunit'][0]['entry'])) {
				foreach ($bodyArray['Response'][0]['Content'][0]['Businessunit'][0]['entry'] as $entry) {
					$name = $entry['name'][0];
					$searchList['businessunit'][$name] = $entry['count'][0];
				}
			}

			if (isset($bodyArray['Response'][0]['Content'][0]['Section'][0]['entry'])) {
				foreach ($bodyArray['Response'][0]['Content'][0]['Section'][0]['entry'] as $entry) {
					$name = $entry['name'][0];
					$searchList['section'][$name] = $entry['count'][0];
				}
			}

			if (isset($bodyArray['Response'][0]['Content'][0]['Role'][0]['entry'])) {
				foreach ($bodyArray['Response'][0]['Content'][0]['Role'][0]['entry'] as $entry) {
					$name = $entry['name'][0];
					$searchList['role'][$name] = $entry['count'][0];
				}
			}

			if (isset($bodyArray['Response'][0]['Content'][0]['Hometown'][0]['entry'])) {
				foreach ($bodyArray['Response'][0]['Content'][0]['Hometown'][0]['entry'] as $entry) {
					$name = $entry['name'][0];
					$searchList['hometown'][$name] = $entry['count'][0];
				}
			}

			if (isset($bodyArray['Response'][0]['Content'][0]['Workplace'][0]['entry'])) {
				foreach ($bodyArray['Response'][0]['Content'][0]['Workplace'][0]['entry'] as $entry) {
					$name = $entry['name'][0];
					$searchList['workplace'][$name] = $entry['count'][0];
				}
			}

			if (isset($bodyArray['Response'][0]['Content'][0]['List'][0]['entry'])) {
				foreach ($bodyArray['Response'][0]['Content'][0]['List'][0]['entry'] as $entry) {
					$name = $entry['name'][0];
					$searchList['list'][$name] = $entry['count'][0];
				}
			}

		}

	}
	else {
		# error
	}

	return $searchList;

}

function UserApiDetailSearchKnowledge($knowledge, $focus) {

	$bodyArray = UserApiDetail("index/listKnowledgeDetail?".$focus, $knowledge);

	return UserApiDetailSearchKnowledgeResponse($bodyArray);

}

function UserApiDetailSearchKnowledgeResponse($bodyArray) {

	$searchList = array();

	$searchList['level'] = array();

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {

		if (isset($bodyArray['Response'][0]['Content'][0]['Level'][0]['entry'])) {
			foreach ($bodyArray['Response'][0]['Content'][0]['Level'][0]['entry'] as $entry) {
				$name = $entry['name'][0];
				$searchList['level'][$name] = $entry['count'][0];
			}
		}

	}
	else {
		# error
	}

	return $searchList;

}

function UserApiDetailSearchExperience($experience, $focus) {

	$bodyArray = UserApiDetail("index/listExperienceDetail?".$focus, $experience);

	return UserApiDetailSearchExperienceResponse($bodyArray);

}

function UserApiDetailSearchExperienceResponse($bodyArray) {

	$searchList = array();

	$searchList['title'] = array();
	$searchList['alternative'] = array();
	$searchList['like'] = array();
	$searchList['has'] = array();

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {

		if (isset($bodyArray['Response'][0]['Content'][0]['Title'][0]['entry'])) {
			foreach ($bodyArray['Response'][0]['Content'][0]['Title'][0]['entry'] as $entry) {
				$name = $entry['name'][0];
				$searchList['title'][$name] = $entry['count'][0];
			}
		}
		if (isset($bodyArray['Response'][0]['Content'][0]['Alternative'][0]['entry'])) {
			foreach ($bodyArray['Response'][0]['Content'][0]['Alternative'][0]['entry'] as $entry) {
				$name = $entry['name'][0];
				$searchList['alternative'][$name] = $entry['count'][0];
			}
		}
		if (isset($bodyArray['Response'][0]['Content'][0]['Like'][0]['entry'])) {
			foreach ($bodyArray['Response'][0]['Content'][0]['Like'][0]['entry'] as $entry) {
				$name = $entry['name'][0];
				$searchList['like'][$name] = $entry['count'][0];
			}
		}
		if (isset($bodyArray['Response'][0]['Content'][0]['Has'][0]['entry'])) {
			foreach ($bodyArray['Response'][0]['Content'][0]['Has'][0]['entry'] as $entry) {
				$name = $entry['name'][0];
				$searchList['has'][$name] = $entry['count'][0];
			}
		}

	}
	else {
		# error
	}

	return $searchList;

}

function UserApiDetailSearchMap($focus) {

	$body = UserApiDetailPlain("index/listLocationDetail?".$focus, array());

	return $body;

}

function UserApiDetailSearchMapSimple($focus, $mode) {

	if ($mode == "workplace") {
		$body = UserApiDetailPlain("index/listWorkplaceOnlyDetail?".$focus, array());
	}
	elseif ($mode == "hometown") {
		$body = UserApiDetailPlain("index/listHometownOnlyDetail?".$focus, array());
	}

	return $body;

}



//////////
// GROUP: List
//////////
function UserApiGroupListById($id) {

	$bodyArray = UserApiList("group", $id, "");

	return UserApiGroupListResponse($bodyArray);
}

function UserApiGroupListWithQuery($query) {

	$bodyArray = UserApiList("group", '0', $query);

	return UserApiGroupListResponse($bodyArray);

}

function UserApiGroupListResponse($bodyArray) {
	$groupList = array();

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {

		if ($bodyArray['Response'][0]['Content'][0]['Summary'][0]['CompleteListSize']['0'] > 0) {
			foreach ($bodyArray['Response'][0]['Content'][0]['Groups'][0]['Group'] as $group) {
				// an array for each group, sorted by ID
				$id = $group['Id'][0];
				$groupList[$id] = array();

				// get plain section elements
				foreach ($group as $itemKey => $itemVal) {
					if (! is_array($itemVal[0]) ){
						$groupList[$id][$itemKey] = $itemVal[0];
					}
					else {
						// get list elements
						$groupList[$id][$itemKey] = array();
						foreach ($itemVal as $subItemKey => $subItemVal) {
							$subId = $subItemVal['Id'][0];
							$groupList[$id][$itemKey][$subId] = array();
							
							foreach ($subItemVal as $subSubKey => $subSubVal) {
								$groupList[$id][$itemKey][$subId][$subSubKey] = $subSubVal[0];							
							}
						}
					}
				}
			}
		}

	}
	else {
		# error
	}

	return $groupList;
}

function UserApiGroupSaveUser($group, $user) {

	$insertId = 0;

	$param = array();

	// request
	$bodyArray = UserApiDetail("group/saveUser?group[".$group."]&user[".$user."]",$param);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$insertId = $bodyArray['Response'][0]['Content'][0]['Group'][0]['Id'][0];
	}
	else {
		# error
	}

        return $insertId;
}

function UserApiGroupDeleteUser($group, $user) {

	$insertId = 0;

	$param = array();

	// request
	$bodyArray = UserApiDetail("group/deleteUser?group[".$group."]&user[".$user."]",$param);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$insertId = $bodyArray['Response'][0]['Content'][0]['Group'][0]['Id'][0];
	}
	else {
		# error
	}

        return $insertId;
}

function UserApiGroupSave($group, $user, $access) {
        $groupId = 0;

	$group['userId'] = $user;
	$group['access'] = $access;

	// request
	$bodyArray = UserApiSave("group", $group);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$groupId = $bodyArray['Response'][0]['Content'][0]['Group'][0]['Id'][0];
	}
	else {
		# error
	}

        return $groupId;
}

function UserApiGroupUpdate ($id, $group) {
        $groupId = 0;

	// request
	$bodyArray = UserApiUpdate("group", $id, $group);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$groupId = $bodyArray['Response'][0]['Content'][0]['Group'][0]['Id'][0];
	}
	else {
		// error
	}

        return $groupId;
}

function UserApiGroupDelete ($id) {
        $groupId = 0;

	// request
	$bodyArray = UserApiDelete("group", $id);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$groupId = $bodyArray['Response'][0]['Content'][0]['Group'][0]['Id'][0];
	}
	else {
		// error
	}

        return $groupId;
}



//////////
// SECTION: Create, Save, List & Delete functions (total: 8 public functions)
//////////
// not implemented:
// - Update functions (not used for now, but should build the functions)
// more functions: 
// - Update AnnotationList from section

function UserApiGetSection($name, $object, $object_id) {
	$sectionId = 0;

	// request
	$bodyArray = UserApiList("user/".$object_id."/".$object, '0', 'name='.$name."&name_match=exact");

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {

		$listSize = $bodyArray['Response'][0]['Content'][0]['Summary'][0]['CompleteListSize'][0];
		if ($listSize == 1) {
			$sectionId = $bodyArray['Response'][0]['Content'][0]['Sections'][0]['Section'][0]['Id'][0];
		}
		else {
			# error
		}
	
	}
	else {
		# error
	}

	return $sectionId;
}

function UserApiCreateSection($type, $name, $object, $object_id) {
        $sectionId = 0;

	$section = array();
	$section['type'] = $type;
	$section['name'] = $name;
	$sectionId = UserApiSaveSection($section, 1, $object, $object_id);

	return $sectionId;
}

function UserApiSaveSection($section, $access, $object, $object_id) {
        $sectionId = 0;

	$section['access'] = $access;

	// request
	$bodyArray = UserApiSave("user/".$object_id."/".$object, $section);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$sectionId = $bodyArray['Response'][0]['Content'][0]['Section'][0]['Id'][0];
	}
	else {
		# error
	}

        return $sectionId;
}

function UserApiListSection($object, $object_id) {
	$bodyArray = UserApiList("user/".$object_id."/".$object, '0', '');

	return UserApiListSectionResponse($bodyArray, "Id");
}

function UserApiListSectionSorted($object, $object_id, $sort) {
	$bodyArray = UserApiList("user/".$object_id."/".$object, '0', '');

	return UserApiListSectionResponse($bodyArray, $sort);
}

function UserApiListSectionById($object, $object_id, $id) {
	$bodyArray = UserApiList("user/".$object_id."/".$object, $id, '');

	return UserApiListSectionResponse($bodyArray, "Id");
}

function UserApiListSectionWithQuery($object, $object_id, $query) {
	$bodyArray = UserApiList("user/".$object_id."/".$object, '0', $query);

	return UserApiListSectionResponse($bodyArray, "Id");
}

function UserApiListSectionResponse($bodyArray, $sort) {
	$sectionList = array();

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {

		if ($bodyArray['Response'][0]['Content'][0]['Summary'][0]['CompleteListSize']['0'] > 0) {
			foreach ($bodyArray['Response'][0]['Content'][0]['Sections'][0]['Section'] as $section) {
				// an array for each section, sorted by PARAM
				$id = $section[$sort][0];
				$sectionList[$id] = array();

				// get plain section elements
				foreach ($section as $itemKey => $itemVal) {
					if (! is_array($itemVal[0]) ){
						$sectionList[$id][$itemKey] = $itemVal[0];
					}
				}
			}
		}

	}
	else {
		# error
	}

	return $sectionList;
}

function UserApiDeleteSection ($object, $object_id, $id) {
        $sectionId = 0;

	// request
	$bodyArray = UserApiDelete("user/".$object_id."/".$object, $id);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$sectionId = $bodyArray['Response'][0]['Content'][0]['Section'][0]['Id'][0];
	}
	else {
		// error
	}

        return $sectionId;
}

function UserApiUpdateSectionAnnotationList($sectionAnnotationList, $object, $object_id) {

	foreach ($sectionAnnotationList as $sectionKey => $sectionVal) {

		$sectionId = UserApiGetSection($sectionKey, $object, $object_id);
		$annotationId = UserApiUpdateAnnotationList($sectionVal, $object, $sectionId);

	}

	// remember: sectionId is returned!
	return $sectionId;

}



//////////
// Profile Create, Save, List & Delete functions (total 9 public functions)
//////////
// not implemented:
// - Update functions (not used for now, but should build the functions)
// more functions: 
// - Save AnnotationList to profile
// - Update Annotation from profile

function UserApiGetProfile($name, $object, $object_id) {
	$profileId = 0;

	// request
	$bodyArray = UserApiList($object."/".$object_id."/profile", '0', 'name='.$name."&name_match=exact");

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {

		$listSize = $bodyArray['Response'][0]['Content'][0]['Summary'][0]['CompleteListSize'][0];
		if ($listSize == 1) {
			$profileId = $bodyArray['Response'][0]['Content'][0]['Profiles'][0]['Profile'][0]['Id'][0];
		}
		else {
			# error
		}
	
	}
	else {
		# error
	}

	return $profileId;
}

function UserApiCreateProfile($group, $name, $object, $object_id) {
        $profileId = 0;

	$profile = array();
	$profile['group'] = $group;
	$profile['name'] = $name;
	$profileId = UserApiSaveProfile($profile, 1, $object, $object_id);

	return $profileId;
}

function UserApiSaveProfile($profile, $access, $object, $object_id) {
        $profileId = 0;

	$profile['access'] = $access;

	// request
	$bodyArray = UserApiSave($object."/".$object_id."/profile", $profile);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$profileId = $bodyArray['Response'][0]['Content'][0]['Profile'][0]['Id'][0];
	}
	else {
		# error
	}

        return $profileId;
}

function UserApiListProfile($object, $object_id) {
	$bodyArray = UserApiList($object."/".$object_id."/profile", '0', '');

	return UserApiListProfileResponse($bodyArray);
}

function UserApiListProfileById($object, $object_id, $id) {
	$bodyArray = UserApiList($object."/".$object_id."/profile", $id, '');

	return UserApiListProfileResponse($bodyArray);
}

function UserApiListProfileWithQuery($object, $object_id, $query) {
	$bodyArray = UserApiList($object."/".$object_id."/profile", '0', $query);

	return UserApiListProfileResponse($bodyArray);
}

function UserApiListProfileResponse($bodyArray) {
	$profileList = array();

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {

		if ($bodyArray['Response'][0]['Content'][0]['Summary'][0]['CompleteListSize']['0'] > 0) {
			foreach ($bodyArray['Response'][0]['Content'][0]['Profiles'][0]['Profile'] as $profile) {
				// an array for each profile, sorted by id
				$id = $profile['Id'][0];
				$profileList[$id] = array();

				// get plain profile elements
				foreach ($profile as $itemKey => $itemVal) {
					if (! is_array($itemVal[0]) ){
						$profileList[$id][$itemKey] = $itemVal[0];
					}
				}
			}
		}

	}
	else {
		# error
	}

	return $profileList;
}

function UserApiDeleteProfile ($id, $object) {
        $profileId = 0;

	// request
	$bodyArray = UserApiDelete($object."/profile", $id);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$profileId = $bodyArray['Response'][0]['Content'][0]['Profile'][0]['Id'][0];
	}
	else {
		// error
	}

        return $profileId;
}

function UserApiSaveProfileAnnotationList($profile, $AnnotationList, $access, $object, $object_id) {
	$annotationId = 0;

	// save profile
	$profileId = UserApiSaveProfile($profile, $access, $object, $object_id);

	// save profile annotations
	$annotationId = UserApiSaveAnnotationList($AnnotationList, $access, $object."/profile", $profileId);

	// remember: profileId is returned!
	return $profileId;

}

function UserApiUpdateProfileAnnotation($profileAnnotation, $object, $object_id) {
	$annotationId = 0;

	$annotationId = UserApiUpdateAnnotationList ($profileAnnotation, $object."/profile", $object_id);

	// remember: profileId is returned!
	return $object_id;
}



//////////
// Annotation Create, Save, Update & Delete functions (total 7 public functions)
//////////
// not implemented:
// - List functions (annotation are listed in profiles or sections)
// more functions: 
// - Save AnnotationList
// - Update AnnotationList

function UserApiGetAnnotation($name, $object, $object_id) {
	$annotationId = 0;

	// request
	$bodyArray = UserApiList($object."/".$object_id."/annotation", '0', "name=".$name."&name_match=exact");

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {

		$listSize = $bodyArray['Response'][0]['Content'][0]['Summary'][0]['CompleteListSize'][0];
		if ($listSize == 0) {
			$annotationId = UserApiCreateAnnotation($name, $object, $object_id);
		}
		elseif ($listSize == 1) {
			$annotationId = $bodyArray['Response'][0]['Content'][0]['Annotations'][0]['Annotation'][0]['Id'][0];
		}
		else {
			# error
		}
	
	}
	else {
		# error
	}

	return $annotationId;
}

function UserApiCreateAnnotation($name, $object, $object_id) {
	$annotation = array();
	$annotation['name'] = $name;

	$annotationId = UserApiSaveAnnotation($annotation, 1, $object, $object_id);


	return $annotationId;
}

function UserApiSaveAnnotation ($annotation, $access, $object, $object_id) {
        $annotationId = 0;

	$annotation['access'] = $access;

	// request
	$bodyArray = UserApiSave($object."/".$object_id."/annotation", $annotation);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$annotationId = $bodyArray['Response'][0]['Content'][0]['Annotation'][0]['Id'][0];
	}
	else {
		# error
	}

        return $annotationId;
}

function UserApiSaveAnnotationList ($annotationList, $access, $object, $object_id) {
        $annotationId = 0;

	// save multiple annotations one-by-one... Is this OK?
	foreach ($annotationList as $key => $val) {
		$oneAnnotation = array();
		$oneAnnotation['name'] = $key;
		$oneAnnotation['value'] = $val;
		if (is_numeric($val)) {
			$oneAnnotation['type'] = "int";
		}
		else {
			$oneAnnotation['type'] = "string";
		}

		$annotationId = UserApiSaveAnnotation($oneAnnotation, $access, $object, $object_id);

	}

	// remember, the object_id is returned!
        return $object_id;
}

function UserApiUpdateAnnotation ($id, $annotation, $object) {
        $annotationId = 0;

	// request
	$bodyArray = UserApiUpdate($object."/annotation", $id, $annotation);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$annotationId = $bodyArray['Response'][0]['Content'][0]['Annotation'][0]['Id'][0];
	}
	else {
		// error
	}

        return $annotationId;
}

function UserApiUpdateAnnotationList ($annotationList, $object, $object_id) {
	$annotationId = 0;

	foreach ($annotationList as $key => $val) {
		$oneAnnotation = array();
		$oneAnnotation['value'] = $val;
		if (is_numeric($val)) {
			$oneAnnotation['type'] = "int";
		}
		else {
			$oneAnnotation['type'] = "string";
		}
		// TODO: how to update access?

		$annotationId = UserApiGetAnnotation($key, $object, $object_id);

		$annotationId = UserApiUpdateAnnotation($annotationId, $oneAnnotation, $object);

	}

	// remember, the object_id is returned!
        return $object_id;
}

function UserApiDeleteAnnotation ($id, $object) {
        $annotationId = 0;

	// request
	$bodyArray = UserApiDelete($object."/annotation", $id);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$annotationId = $bodyArray['Response'][0]['Content'][0]['Annotation'][0]['Id'][0];
	}
	else {
		// error
	}

        return $annotationId;
}



//////////
// USER
//////////

// create a new user
function UserApiCreateUser($user, $reference) {
	$userId = UserApiSaveUser($user, $reference, 1);

	// create contact types
	UserApiCreateContact($userId, '1', 'Home');
	UserApiCreateContact($userId, '2', 'Work');

	// create address types
	UserApiCreateAddress($userId, '1', 'Home');
	UserApiCreateAddress($userId, '2', 'Work');

	// create organization types
	UserApiCreateOrganization($userId, '1', 'Current');
	UserApiCreateOrganization($userId, '2', 'Past');

	return ($userId);
}

// get a user from the reference
function UserApiGetUserFromReference($reference) {

	$userId = 0;

	// request
	$bodyArray = UserApiList("user", '0', 'reference='.$reference);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {

		$listSize = $bodyArray['Response'][0]['Content'][0]['Summary'][0]['CompleteListSize'][0];
		if ($listSize == 1) {
			$userId = $bodyArray['Response'][0]['Content'][0]['Users'][0]['User'][0]['Id'][0];
		}
		else {
			# error
		}
	
	}
	else {
		# error
	}

	return $userId;

}

function UserApiSaveUser ($user, $reference, $access) {

        $userId = 0;

	$user['access'] = $access;
	$user['reference'] = $reference;

	// request
	$bodyArray = UserApiSave("user", $user);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$userId = $bodyArray['Response'][0]['Content'][0]['User'][0]['Id'][0];
	}
	else {
		# error
	}

        return $userId;

}

function UserApiUpdateUserByReference ($user, $reference) {

	$userId = UserApiGetUserFromReference($reference);

	return UserApiUpdateUser($userId, $user);
}

function UserApiUpdateUser ($id, $user) {

        $userId = 0;

	// request
	$bodyArray = UserApiUpdate("user", $id, $user);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$userId = $bodyArray['Response'][0]['Content'][0]['User'][0]['Id'][0];
	}
	else {
		// error (userId = 0)
	}

        return $userId;

}

function UserApiListUser() {

	$bodyArray = UserApiList("user", '0', '');

	return UserApiListUserResponse($bodyArray);

}

function UserApiListUserWithQuery($query) {

	$bodyArray = UserApiList("user", '0', $query);

	return UserApiListUserResponse($bodyArray);

}

function UserApiListUserById($id) {

	$bodyArray = UserApiList("user", $id, '');

	return UserApiListUserResponse($bodyArray);

}

function UserApiListUserByReference($reference) {

	$userId = UserApiGetUserFromReference($reference);

	$bodyArray = UserApiList("user", $userId, '');

	return UserApiListUserResponse($bodyArray);

}

function UserApiListUserResponse($bodyArray) {

	$userList = array();

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {

		if ($bodyArray['Response'][0]['Content'][0]['Summary'][0]['CompleteListSize']['0'] > 0) {
			foreach ($bodyArray['Response'][0]['Content'][0]['Users'][0]['User'] as $user) {
				// an array for each user
				$id = $user['Id'][0];
				$userList[$id] = array();

				// get plain section elements
				foreach ($user as $itemKey => $itemVal) {
					if (! is_array($itemVal[0]) ){
						$userList[$id][$itemKey] = $itemVal[0];
					}
					else {
						// get list elements
						$userList[$id][$itemKey] = array();
						foreach ($itemVal as $subItemKey => $subItemVal) {
							$subId = $subItemVal['Id'][0];
							$userList[$id][$itemKey][$subId] = array();
							
							foreach ($subItemVal as $subSubKey => $subSubVal) {
								$userList[$id][$itemKey][$subId][$subSubKey] = $subSubVal[0];							
							}
						}
					}
				}
			}
		}

	}
	else {
		# error
	}

	return $userList;

}

// User Profile

function UserApiSaveUserProfileAnnotationList ($profile, $AnnotationList, $userId, $access) {

	return UserApiSaveProfileAnnotationList($profile, $AnnotationList, $access, "user", $userId);

}

function UserApiGetUserProfile ($userId, $profileId) {

	return UserApiListProfileById("user", $userId, $profileId);

}

function UserApiUpdateUserProfileAnnotation($profileAnnotation, $userId, $profileId) {

	# ownership of userId should be checked before this call!
	return UserApiUpdateProfileAnnotation($profileAnnotation, "user", $profileId);

}

function UserApiListUserProfileByUser ($group, $userId) {

	return UserApiListProfileWithQuery("user", $userId, "group=$group&group_match=exact");

}

function UserApiDeleteUserProfile ($userId, $profileId) {

	# ownership of userId should be checked before this call!
	return UserApiDeleteProfile($profileId, "user");

}

// User Annotation

function UserApiGetUserAnnotationFromUser($userId, $name) {

	return UserApiGetAnnotation($name, "user", $userId);

}

function UserApiCreateUserAnnotation($userId, $name) {

        return UserApiCreateAnnotation($name, "user", $userId);

}

function UserApiSaveUserAnnotation ($annotation, $userId, $access) {

        return UserApiSaveAnnotation($annotation, $access, "user", $userId);

}

function UserApiSaveUserAnnotationList ($annotationList, $userId, $access) {

        return UserApiSaveAnnotationList($annotationList, $access, "user", $userId);

}

function UserApiUpdateUserAnnotationListByUser ($annotationList, $userId) {

        return UserApiUpdateAnnotationList($annotationList, "user", $userId);

}

function UserApiUpdateUserAnnotation ($id, $annotation) {

        return UserApiUpdateAnnotation($id, $annotation, "user");

}

function UserApiDeleteUserAnnotation ($id) {

        return UserApiDeleteAnnotation($id, "user");

}



//////////
// CONTACT
//////////

// Contact section

function UserApiGetContact($userId, $name) {

	return UserApiGetSection($name, "contact", $userId);

}

function UserApiCreateContact($userId, $type, $name) {

	return UserApiCreateSection($type, $name, "contact", $userId);

}

function UserApiSaveContact ($contact, $userId, $access) {

	return UserApiSaveSection($contact, $access, "contact", $userId);

}

function UserApiListContactById ($userId, $contactId) {

	return UserApiListSectionById("contact", $userId, $contactId);

}

function UserApiListContactByName ($userId, $name) {

	return UserApiListSectionWithQuery("contact", $userId, "name=$name&name_match=exact");

}

function UserApiListContactByUser($userId) {

	return UserApiListSection("contact", $userId);

}

function UserApiListContactByUserSorted($userId, $sort) {

	return UserApiListSectionSorted("contact", $userId, $sort);

}

function UserApiUpdateContactAnnotationListByUser($contactAnnotationList, $userId) {

	return UserApiUpdateSectionAnnotationList($contactAnnotationList, "contact", $userId);

}

function UserApiDeleteContact ($userId, $contactId) {

	return UserApiDeleteSection("contact", $userId, $contactId);

}

// Contact Annotation

function UserApiGetContactAnnotationFromContact($contactId, $name) {

	return UserApiGetAnnotationFromObject($name, "contact", $contactId);

}

function UserApiCreateContactAnnotation($contactId, $name) {

        return UserApiCreateAnnotation($name, "contact", $contactId);

}

function UserApiSaveContactAnnotation ($annotation, $contactId, $access) {

	return UserApiSaveAnnotation($annotation, $access, "contact", $contactId);

}

function UserApiSaveContactAnnotationList ($annotationList, $contactId, $access) {

        return UserApiSaveAnnotationList($annotationList, $access, "contact", $contactId);

}

function UserApiUpdateContactAnnotationListByContact ($annotationList, $contactId) {

        return UserApiUpdateAnnotationList($annotationList, "contact", $contactId);

}

function UserApiUpdateContactAnnotation ($id, $annotation) {

        return UserApiUpdateAnnotation($id, $annotation, "contact");

}

function UserApiDeleteContactAnnotation ($id) {

        return UserApiDeleteAnnotation($id, "contact");

}



//////////
// ADDRESS
//////////

// Address section

function UserApiGetAddress($userId, $name) {

	return UserApiGetSection($name, "address", $userId);

}

function UserApiCreateAddress($userId, $type, $name) {

	return UserApiCreateSection($type, $name, "address", $userId);

}

function UserApiSaveAddress ($address, $userId, $access) {

	return UserApiSaveSection($address, $access, "address", $userId);

}

function UserApiListAddressById ($userId, $addressId) {

	return UserApiListSectionById("address", $userId, $addressId);

}

function UserApiListAddressByName ($userId, $name) {

	return UserApiListSectionWithQuery("address", $userId, "name=$name&name_match=exact");

}

function UserApiListAddressByUser($userId) {

	return UserApiListSection("address", $userId);

}

function UserApiListAddressByUserSorted($userId, $sort) {

	return UserApiListSectionSorted("address", $userId, $sort);

}

function UserApiUpdateAddressAnnotationListByUser($addressAnnotationList, $userId) {

	return UserApiUpdateSectionAnnotationList($addressAnnotationList, "address", $userId);

}

function UserApiDeleteAddress ($userId, $addressId) {

	return UserApiDeleteSection("address", $userId, $addressId);

}

// Address Annotation

function UserApiGetAddressAnnotationFromAddress($addressId, $name) {

	return UserApiGetAnnotationFromObject($name, "address", $addressId);

}

function UserApiCreateAddressAnnotation($addressId, $name) {

        return UserApiCreateAnnotation($name, "address", $addressId);

}

function UserApiSaveAddressAnnotation ($annotation, $addressId, $access) {

	return UserApiSaveAnnotation($annotation, $access, "address", $addressId);

}

function UserApiSaveAddressAnnotationList ($annotationList, $addressId, $access) {

        return UserApiSaveAnnotationList($annotationList, $access, "address", $addressId);

}

function UserApiUpdateAddressAnnotationListByAddress ($annotationList, $addressId) {

        return UserApiUpdateAnnotationList($annotationList, "address", $addressId);

}

function UserApiUpdateAddressAnnotation ($id, $annotation) {

        return UserApiUpdateAnnotation($id, $annotation, "address");

}

function UserApiDeleteAddressAnnotation ($id) {

        return UserApiDeleteAnnotation($id, "address");

}



///////////////
// ORGANIZATION
///////////////

// Organization section

function UserApiGetOrganization($userId, $name) {

	return UserApiGetSection($name, "organization", $userId);

}

function UserApiCreateOrganization($userId, $type, $name) {

	return UserApiCreateSection($type, $name, "organization", $userId);

}

function UserApiSaveOrganization ($organization, $userId, $access) {

	return UserApiSaveSection($organization, $access, "organization", $userId);

}

function UserApiListOrganizationById ($userId, $organizationId) {

	return UserApiListSectionById("organization", $userId, $organizationId);

}

function UserApiListOrganizationByName ($userId, $name) {

	return UserApiListSectionWithQuery("organization", $userId, "name=$name&name_match=exact");

}

function UserApiListOrganizationByUser($userId) {

	return UserApiListSection("organization", $userId);

}

function UserApiListOrganizationByUserSorted($userId, $sort) {

	return UserApiListSectionSorted("organization", $userId, $sort);

}

function UserApiUpdateOrganizationAnnotationListByUser($organizationAnnotationList, $userId) {

	return UserApiUpdateSectionAnnotationList($organizationAnnotationList, "organization", $userId);

}

function UserApiDeleteOrganization ($userId, $organizationId) {

	return UserApiDeleteSection("organization", $userId, $organizationId);

}

// Organization Annotation

function UserApiGetOrganizationAnnotationFromOrganization($organizationId, $name) {

	return UserApiGetAnnotationFromObject($name, "organization", $organizationId);

}

function UserApiCreateOrganizationAnnotation($organizationId, $name) {

        return UserApiCreateAnnotation($name, "organization", $organizationId);

}

function UserApiSaveOrganizationAnnotation ($annotation, $organizationId, $access) {

	return UserApiSaveAnnotation($annotation, $access, "organization", $organizationId);

}

function UserApiSaveOrganizationAnnotationList ($annotationList, $organizationId, $access) {

        return UserApiSaveAnnotationList($annotationList, $access, "organization", $organizationId);

}

function UserApiUpdateOrganizationAnnotationListByOrganization ($annotationList, $organizationId) {

        return UserApiUpdateAnnotationList($annotationList, "organization", $organizationId);

}

function UserApiUpdateOrganizationAnnotation ($id, $annotation) {

        return UserApiUpdateAnnotation($id, $annotation, "organization");

}

function UserApiDeleteOrganizationAnnotation ($id) {

        return UserApiDeleteAnnotation($id, "organization");

}



//////////
// Publication
//////////

// Publication section

function UserApiGetPublication($userId, $name) {

	return UserApiGetSection($name, "publication", $userId);

}

function UserApiCreatePublication($userId, $type, $name) {

	return UserApiCreateSection($type, $name, "publication", $userId);

}

function UserApiSavePublication ($publication, $userId, $access) {

	return UserApiSaveSection($publication, $access, "publication", $userId);

}

function UserApiListPublicationByUser($userId) {

	return UserApiListSection("publication", $userId);

}

function UserApiListPublicationById ($userId, $publicationId) {

	return UserApiListSectionById("publication", $userId, $publicationId);

}

function UserApiListPublicationByName ($userId, $name) {

	return UserApiListSectionWithQuery("publication", $userId, "name=$name&name_match=exact");

}

function UserApiUpdatePublicationAnnotationListByUser($publicationAnnotationList, $userId) {

	return UserApiUpdateSectionAnnotationList($publicationAnnotationList, "publication", $userId);

}

function UserApiDeletePublication ($userId, $publicationId) {

	return UserApiDeleteSection("publication", $userId, $publicationId);

}

// Publication Profile

function UserApiSavePublicationProfileAnnotationList ($profile, $AnnotationList, $publicationId, $access) {

	return UserApiSaveProfileAnnotationList($profile, $AnnotationList, $access, "publication", $publicationId);

}

function UserApiUpdatePublicationProfileAnnotation($profileAnnotation, $publicationId, $profileId) {

	# ownership of publicationId should be checked before this call!
	return UserApiUpdateProfileAnnotation($profileAnnotation, "publication", $profileId);

}

function UserApiGetPublicationProfile ($publicationId, $profileId) {

	return UserApiListProfileById("publication", $publicationId, $profileId);

}

function UserApiListPublicationProfileByUser ($group, $publicationId) {

	return UserApiListProfileWithQuery("publication", $publicationId, "group=$group&group_match=exact");

}

function UserApiDeletePublicationProfile ($publicationId, $profileId) {

	# ownership of publicationId should be checked before this call!
	return UserApiDeleteProfile($profileId, "publication");

}

// Publication Annotation

function UserApiGetPublicationAnnotationFromPublication($publicationId, $name) {

	return UserApiGetAnnotationFromObject($name, "publication", $publicationId);

}

function UserApiCreatePublicationAnnotation($publicationId, $name) {

        return UserApiCreateAnnotation($name, "publication", $publicationId);

}

function UserApiSavePublicationAnnotation ($annotation, $publicationId, $access) {

	return UserApiSaveAnnotation($annotation, $access, "publication", $publicationId);

}

function UserApiSavePublicationAnnotationList ($annotationList, $publicationId, $access) {

        return UserApiSaveAnnotationList($annotationList, $access, "publication", $publicationId);

}

function UserApiUpdatePublicationAnnotationListByPublication ($annotationList, $publicationId) {

        return UserApiUpdateAnnotationList($annotationList, "publication", $publicationId);

}

function UserApiUpdatePublicationAnnotation ($id, $annotation) {

        return UserApiUpdateAnnotation($id, $annotation, "publication");

}

function UserApiDeletePublicationAnnotation ($id) {

        return UserApiDeleteAnnotation($id, "publication");

}



//////////
// Experience
//////////

// Experience section

function UserApiGetExperience($userId, $name) {

	return UserApiGetSection($name, "experience", $userId);

}

function UserApiCreateExperience($userId, $type, $name) {

	return UserApiCreateSection($type, $name, "experience", $userId);

}

function UserApiSaveExperience ($experience, $userId, $access) {

	return UserApiSaveSection($experience, $access, "experience", $userId);

}

function UserApiListExperienceById ($userId, $experienceId) {

	return UserApiListSectionById("experience", $userId, $experienceId);

}

function UserApiListExperienceByName ($userId, $name) {

	return UserApiListSectionWithQuery("experience", $userId, "name=$name&name_match=exact");

}

function UserApiListExperienceByUser($userId) {

	return UserApiListSection("experience", $userId);

}

function UserApiUpdateExperienceAnnotationListByUser($experienceAnnotationList, $userId) {

	return UserApiUpdateSectionAnnotationList($experienceAnnotationList, "experience", $userId);

}

function UserApiDeleteExperience ($userId, $experienceId) {

	return UserApiDeleteSection("experience", $userId, $experienceId);

}

// Experience Profile

function UserApiSaveExperienceProfileAnnotationList ($profile, $AnnotationList, $experienceId, $access) {

	return UserApiSaveProfileAnnotationList($profile, $AnnotationList, $access, "experience", $experienceId);

}

function UserApiUpdateExperienceProfileAnnotation($profileAnnotation, $experienceId, $profileId) {

	# ownership of experienceId should be checked before this call!
	return UserApiUpdateProfileAnnotation($profileAnnotation, "experience", $profileId);

}

function UserApiGetExperienceProfile ($experienceId, $profileId) {

	return UserApiListProfileById("experience", $experienceId, $profileId);

}

function UserApiListExperienceProfileByUser ($group, $experienceId) {

	return UserApiListProfileWithQuery("experience", $experienceId, "group=$group&group_match=exact");

}

function UserApiDeleteExperienceProfile ($experienceId, $profileId) {

	# ownership of experienceId should be checked before this call!
	return UserApiDeleteProfile($profileId, "experience");

}

// Experience Annotation

function UserApiGetExperienceAnnotationFromExperience($experienceId, $name) {

	return UserApiGetAnnotationFromObject($name, "experience", $experienceId);

}

function UserApiCreateExperienceAnnotation($experienceId, $name) {

        return UserApiCreateAnnotation($name, "experience", $experienceId);

}

function UserApiSaveExperienceAnnotation ($annotation, $experienceId, $access) {

	return UserApiSaveAnnotation($annotation, $access, "experience", $experienceId);

}

function UserApiSaveExperienceAnnotationList ($annotationList, $experienceId, $access) {

        return UserApiSaveAnnotationList($annotationList, $access, "experience", $experienceId);

}

function UserApiUpdateExperienceAnnotationListByExperience ($annotationList, $experienceId) {

        return UserApiUpdateAnnotationList($annotationList, "experience", $experienceId);

}

function UserApiUpdateExperienceAnnotation ($id, $annotation) {

        return UserApiUpdateAnnotation($id, $annotation, "experience");

}

function UserApiDeleteExperienceAnnotation ($id) {

        return UserApiDeleteAnnotation($id, "experience");

}

// 
// DATA
//
function UserApiSaveData ($data) {

        $dataId = 0;

	// request
	$bodyArray = UserApiSave("data", $data);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$dataId = $bodyArray['Response'][0]['Content'][0]['Data'][0]['Id'][0];
	}
	else {
		# error
	}

        return $dataId;

}

function UserApiListDataWithQuery($query) {

	$bodyArray = UserApiList("data", '0', $query);

	return UserApiListDataResponse($bodyArray);

}

function UserApiListDataResponse($bodyArray) {

	$dataList = array();

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {

		if ($bodyArray['Response'][0]['Content'][0]['Summary'][0]['CompleteListSize']['0'] > 0) {
			foreach ($bodyArray['Response'][0]['Content'][0]['Datas'][0]['Data'] as $data) {
				// an array for each data
				$id = $data['Id'][0];
				$dataList[$id] = array();

				// get plain data elements
				foreach ($data as $itemKey => $itemVal) {
					if (! is_array($itemVal[0]) ){
						$dataList[$id][$itemKey] = $itemVal[0];
					}
				}
			}
		}

	}
	else {
		# error
	}

	return $dataList;

}

function UserApiDeleteData ($id) {
        $dataId = 0;

	// request
	$bodyArray = UserApiDelete("data", $id);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$dataId = $bodyArray['Response'][0]['Content'][0]['Data'][0]['Id'][0];
	}
	else {
		// error
	}

        return $dataId;
}

// 
// ACTIVITY
//
function UserApiSaveActivity ($activity) {

        $activityId = 0;

	// request
	$bodyArray = UserApiSave("activity", $activity);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$activityId = $bodyArray['Response'][0]['Content'][0]['Activity'][0]['Id'][0];
	}
	else {
		# error
	}

        return $activityId;

}

function UserApiListActivity() {

	$bodyArray = UserApiList("activity", '0', '');

	return UserApiListActivityResponse($bodyArray);

}

function UserApiListActivityWithQuery($query) {

	$bodyArray = UserApiList("activity", '0', $query);

	return UserApiListActivityResponse($bodyArray);

}

function UserApiListActivityResponse($bodyArray) {

	$activityList = array();

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {

		if ($bodyArray['Response'][0]['Content'][0]['Summary'][0]['CompleteListSize']['0'] > 0) {
			foreach ($bodyArray['Response'][0]['Content'][0]['Activities'][0]['Activity'] as $activity) {
				// an array for each data
				$id = $activity['Id'][0];
				$activityList[$id] = array();

				// get plain elements
				foreach ($activity as $itemKey => $itemVal) {
					if (! is_array($itemVal[0]) ){
						$activityList[$id][$itemKey] = $itemVal[0];
					}
					else {
						// get list elements
						$activityList[$id][$itemKey] = array();
						foreach ($itemVal as $subItemKey => $subItemVal) {
							foreach ($subItemVal as $subSubKey => $subSubVal) {
								$activityList[$id][$itemKey][$subSubKey] = $subSubVal[0];							
							}
						}
						/*
						// should be this, right!?
						$activityList[$id][$itemKey] = array();
						foreach ($itemVal as $subItemKey => $subItemVal) {
							$subId = $subItemVal['Id'][0];
							$activityList[$id][$itemKey][$subId] = array();
							
							foreach ($subItemVal as $subSubKey => $subSubVal) {
								$activityList[$id][$itemKey][$subId][$subSubKey] = $subSubVal[0];							
							}
						}
						*/
					}
				}
			}
		}

	}
	else {
		# error
	}

	return $activityList;

}

function UserApiDeleteActivity ($id) {
        $activityId = 0;

	// request
	$bodyArray = UserApiDelete("activity", $id);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$activityId = $bodyArray['Response'][0]['Content'][0]['Activity'][0]['Id'][0];
	}
	else {
		// error
	}

        return $activityId;
}

// 
// STATS
//

function UserApiListStats() {

	$bodyArray = UserApiList("stats", '0', '');

	return UserApiListStatsResponse($bodyArray);

}

function UserApiListStatsWithQuery($query) {

	$bodyArray = UserApiList("stats", '0', $query);

	return UserApiListStatsResponse($bodyArray);

}

function UserApiListStatsResponse($bodyArray) {
	$statsList = array();

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];

	if ($responseStatus == "200 OK") {
		$stat = $bodyArray['Response'][0]['Content'][0]['Stats']['0'];

		// an array for the stats data
		$id = $stat['Id'][0];
		$statsList[$id] = array();

		// get plain elements
		foreach ($stat as $itemKey => $itemVal) {
			if (! is_array($itemVal[0]) ){
				$statsList[$id][$itemKey] = $itemVal[0];
			}
			else {
				// get list elements
				$statsList[$id][$itemKey] = array();
				foreach ($itemVal as $subItemKey => $subItemVal) {
					$subId = $subItemVal['Id'][0];
					$statsList[$id][$itemKey][$subId] = array();
					
					foreach ($subItemVal as $subSubKey => $subSubVal) {
						$statsList[$id][$itemKey][$subId][$subSubKey] = $subSubVal[0];							
					}
				}

			}
		}
	}
	else {
		# error
	}

	return $statsList;

}

?>
