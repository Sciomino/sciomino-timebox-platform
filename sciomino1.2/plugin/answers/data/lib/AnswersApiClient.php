<?php

//////////
// CONNECT WITH API HOST: Save, Update, List & Delete functions
// + Detail function
//////////

function AnswersApiSave ($object, $values) {

	global $XCOW_B;
	$headers = array();
	$params = array();

	// request
	$url = $XCOW_B['answers_api']['host'].$object."/save";

	// auth
	$authParams = postAnswersApiAuth();
	$params = array_merge($values, $authParams);

	// post
	$body = postResponse ($url, $headers, $params);
	$bodyObj = new Xml2Php2();
	$bodyObj->startProcessing($body[1], 1);
	$bodyArray = $bodyObj->getPhpArray();

        return $bodyArray;

}

function AnswersApiUpdate ($object, $id, $values) {

	global $XCOW_B;
	$headers = array();
	$params = array();

	// request
	if ($id == 0) {
		$url = $XCOW_B['answers_api']['host'].$object."/update";
	}
	else {
		$url = $XCOW_B['answers_api']['host'].$object."/".$id."/update";
	}

	// auth
	$authParams = postAnswersApiAuth();
	$params = array_merge($values, $authParams);

	// post
	$body = postResponse ($url, $headers, $params);
	$bodyObj = new Xml2Php2();
	$bodyObj->startProcessing($body[1], 1);
	$bodyArray = $bodyObj->getPhpArray();

        return $bodyArray;

}

function AnswersApiList ($object, $id, $query) {

	global $XCOW_B;

	// request
	if ($id == 0) {
		$url = $XCOW_B['answers_api']['host'].$object."/list";
	}
	else {
		$url = $XCOW_B['answers_api']['host'].$object."/".$id."/list";
	}

	// auth
	if ($query != '') {
		$query .= "&".GetAnswersApiAuth();
	}
	else {
		$query .= GetAnswersApiAuth();
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

function AnswersApiDelete ($object, $id) {

	global $XCOW_B;
	$headers = array();
	$params = array();

	// request
	$url = $XCOW_B['answers_api']['host'].$object."/".$id."/delete";

	// auth
	$authParams = postAnswersApiAuth();
	$params = $authParams;
	
	// post
	$body = postResponse ($url, $headers, $params);
	$bodyObj = new Xml2Php2();
	$bodyObj->startProcessing($body[1], 1);
	$bodyArray = $bodyObj->getPhpArray();

        return $bodyArray;

}

function AnswersApiDetail ($object, $values) {

	global $XCOW_B;

	// request
	$url = $XCOW_B['answers_api']['host'].$object;

	// auth
	$query = "";
	$query .= GetAnswersApiAuth();
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

function AnswersApiDetailPlain ($object, $values) {

	global $XCOW_B;

	// request
	$url = $XCOW_B['answers_api']['host'].$object;

	// auth
	$query = "";
	$query .= GetAnswersApiAuth();
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

function GetAnswersApiAuth () {

	global $XCOW_B;

	$query = "";

	// auth
	if ($XCOW_B['answers_api']['auth']) {
		$query .= "answers_api_id=".$XCOW_B['answers_api']['id'];
		$query .= "&answers_api_nonce=".$XCOW_B['answers_api']['nonce'];
		$query .= "&answers_api_key=".$XCOW_B['answers_api']['key'];
	}

	return $query;
}

function PostAnswersApiAuth () {

	global $XCOW_B;

	$params = array();

	// auth
	if ($XCOW_B['answers_api']['auth']) {
		$params['answers_api_id'] = $XCOW_B['answers_api']['id'];
		$params['answers_api_nonce'] = $XCOW_B['answers_api']['nonce'];
		$params['answers_api_key'] = $XCOW_B['answers_api']['key'];
	}

	return $params;
}


//////////
// SEARCH: List & Detail function
//////////
function AnswersApiListListAll($query) {

	$bodyArray = AnswersApiDetail("index/listAll?", $query);

	return AnswersApiListSearchResponse($bodyArray);

}

function AnswersApiListSearchWithQuery($query) {

	$bodyArray = AnswersApiList("index", '0', $query);

	return AnswersApiListSearchResponse($bodyArray);

}

function AnswersApiListSearchResponse($bodyArray) {

	$searchList = array();

	$searchList['act'] = array();
	$searchList['suggest'] = array();
	$searchList['knowledge'] = array();
	$searchList['hobby'] = array();
	$searchList['businessunit'] = array();
	$searchList['workplace'] = array();
	$searchList['status'] = array();
	$searchList['my'] = array();
	$searchList['network'] = array();

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {

		if ($bodyArray['Response'][0]['Content'][0]['Summary'][0]['CompleteListSize']['0'] > 0) {
			$searchList['act'] = $bodyArray['Response'][0]['Content'][0]['Acts'][0]['Act'];

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

			if (isset($bodyArray['Response'][0]['Content'][0]['Hobby'][0]['entry'])) {
				foreach ($bodyArray['Response'][0]['Content'][0]['Hobby'][0]['entry'] as $entry) {
					$name = $entry['name'][0];
					$searchList['hobby'][$name] = $entry['count'][0];
				}
			}

			if (isset($bodyArray['Response'][0]['Content'][0]['Businessunit'][0]['entry'])) {
				foreach ($bodyArray['Response'][0]['Content'][0]['Businessunit'][0]['entry'] as $entry) {
					$name = $entry['name'][0];
					$searchList['businessunit'][$name] = $entry['count'][0];
				}
			}

			if (isset($bodyArray['Response'][0]['Content'][0]['Workplace'][0]['entry'])) {
				foreach ($bodyArray['Response'][0]['Content'][0]['Workplace'][0]['entry'] as $entry) {
					$name = $entry['name'][0];
					$searchList['workplace'][$name] = $entry['count'][0];
				}
			}

			if (isset($bodyArray['Response'][0]['Content'][0]['Status'][0]['entry'])) {
				foreach ($bodyArray['Response'][0]['Content'][0]['Status'][0]['entry'] as $entry) {
					$name = $entry['name'][0];
					$searchList['status'][$name] = $entry['count'][0];
				}
			}

			if (isset($bodyArray['Response'][0]['Content'][0]['My'][0]['entry'])) {
				foreach ($bodyArray['Response'][0]['Content'][0]['My'][0]['entry'] as $entry) {
					$name = $entry['name'][0];
					$searchList['my'][$name] = $entry['count'][0];
				}
			}

			if (isset($bodyArray['Response'][0]['Content'][0]['Network'][0]['entry'])) {
				foreach ($bodyArray['Response'][0]['Content'][0]['Network'][0]['entry'] as $entry) {
					$name = $entry['name'][0];
					$searchList['network'][$name] = $entry['count'][0];
				}
			}

		}

	}
	else {
		# error
	}

	return $searchList;

}



//////////
// Profile Create, Save, List & Delete functions (total 9 public functions)
//////////
// not implemented:
// - Update functions (not used for now, but should build the functions)
// more functions: 
// - Save AnnotationList to profile
// - Update Annotation from profile

function AnswersApiGetProfile($name, $object, $object_id) {
	$profileId = 0;

	// request
	$bodyArray = AnswersApiList($object."/".$object_id."/profile", '0', 'name='.$name."&name_match=exact");

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

function AnswersApiCreateProfile($group, $name, $object, $object_id) {
        $profileId = 0;

	$profile = array();
	$profile['group'] = $group;
	$profile['name'] = $name;
	$profileId = AnswersApiSaveProfile($profile, 1, $object, $object_id);

	return $profileId;
}

function AnswersApiSaveProfile($profile, $access, $object, $object_id) {
        $profileId = 0;

	$profile['access'] = $access;

	// request
	$bodyArray = AnswersApiSave($object."/".$object_id."/profile", $profile);

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

function AnswersApiListProfile($object, $object_id) {
	$bodyArray = AnswersApiList($object."/".$object_id."/profile", '0', '');

	return AnswersApiListProfileResponse($bodyArray);
}

function AnswersApiListProfileById($object, $object_id, $id) {
	$bodyArray = AnswersApiList($object."/".$object_id."/profile", $id, '');

	return AnswersApiListProfileResponse($bodyArray);
}

function AnswersApiListProfileWithQuery($object, $object_id, $query) {
	$bodyArray = AnswersApiList($object."/".$object_id."/profile", '0', $query);

	return AnswersApiListProfileResponse($bodyArray);
}

function AnswersApiListProfileResponse($bodyArray) {
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

function AnswersApiDeleteProfile ($id, $object) {
        $profileId = 0;

	// request
	$bodyArray = AnswersApiDelete($object."/profile", $id);

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

function AnswersApiSaveProfileAnnotationList($profile, $AnnotationList, $access, $object, $object_id) {
	$annotationId = 0;

	// save profile
	$profileId = AnswersApiSaveProfile($profile, $access, $object, $object_id);

	// save profile annotations
	$annotationId = AnswersApiSaveAnnotationList($AnnotationList, $access, $object."/profile", $profileId);

	// remember: profileId is returned!
	return $profileId;

}

function AnswersApiUpdateProfileAnnotation($profileAnnotation, $object, $object_id) {
	$annotationId = 0;

	$annotationId = AnswersApiUpdateAnnotationList ($profileAnnotation, $object."/profile", $object_id);

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

function AnswersApiGetAnnotation($name, $object, $object_id) {
	$annotationId = 0;

	// request
	$bodyArray = AnswersApiList($object."/".$object_id."/annotation", '0', "name=".$name."&name_match=exact");

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {

		$listSize = $bodyArray['Response'][0]['Content'][0]['Summary'][0]['CompleteListSize'][0];
		if ($listSize == 0) {
			$annotationId = AnswersApiCreateAnnotation($name, $object, $object_id);
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

function AnswersApiCreateAnnotation($name, $object, $object_id) {
	$annotation = array();
	$annotation['name'] = $name;

	$annotationId = AnswersApiSaveAnnotation($annotation, 1, $object, $object_id);

	return $annotationId;
}

function AnswersApiSaveAnnotation ($annotation, $access, $object, $object_id) {
        $annotationId = 0;

	$annotation['access'] = $access;

	// request
	$bodyArray = AnswersApiSave($object."/".$object_id."/annotation", $annotation);

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

function AnswersApiSaveAnnotationList ($annotationList, $access, $object, $object_id) {
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

		$annotationId = AnswersApiSaveAnnotation($oneAnnotation, $access, $object, $object_id);

	}

	// remember, the object_id is returned!
        return $object_id;
}

function AnswersApiUpdateAnnotation ($id, $annotation, $object) {
        $annotationId = 0;

	// request
	$bodyArray = AnswersApiUpdate($object."/annotation", $id, $annotation);

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

function AnswersApiUpdateAnnotationList ($annotationList, $object, $object_id) {
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

		$annotationId = AnswersApiGetAnnotation($key, $object, $object_id);

		$annotationId = AnswersApiUpdateAnnotation($annotationId, $oneAnnotation, $object);

	}

	// remember, the object_id is returned!
        return $object_id;
}

function AnswersApiDeleteAnnotation ($id, $object) {
        $annotationId = 0;

	// request
	$bodyArray = AnswersApiDelete($object."/annotation", $id);

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
// ACT
//////////

function AnswersApiSaveAct ($act, $reference, $access) {

        $actId = 0;

	$act['access'] = $access;
	$act['reference'] = $reference;

	// request
	$bodyArray = AnswersApiSave("act", $act);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$actId = $bodyArray['Response'][0]['Content'][0]['Act'][0]['Id'][0];
	}
	else {
		# error
	}

        return $actId;

}

function AnswersApiUpdateAct ($id, $act) {

        $actId = 0;

	// request
	$bodyArray = AnswersApiUpdate("act", $id, $act);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$actId = $bodyArray['Response'][0]['Content'][0]['Act'][0]['Id'][0];
	}
	else {
		// error (actId = 0)
	}

        return $actId;

}

function AnswersApiListAct() {

	$bodyArray = AnswersApiList("act", '0', '');

	return AnswersApiListActResponse($bodyArray);

}

function AnswersApiListActWithQuery($query) {

	$bodyArray = AnswersApiList("act", '0', $query);

	return AnswersApiListActResponse($bodyArray);

}

function AnswersApiListActById($id) {

	$bodyArray = AnswersApiList("act", $id, '');

	return AnswersApiListActResponse($bodyArray);

}

function AnswersApiListActResponse($bodyArray) {

	$actList = array();

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {

		if ($bodyArray['Response'][0]['Content'][0]['Summary'][0]['CompleteListSize']['0'] > 0) {
			foreach ($bodyArray['Response'][0]['Content'][0]['Acts'][0]['Act'] as $act) {
				// an array for each act
				$id = $act['Id'][0];
				$actList[$id] = array();

				// get plain section elements
				foreach ($act as $itemKey => $itemVal) {
					if (! is_array($itemVal[0]) ){
						$actList[$id][$itemKey] = $itemVal[0];
					}
					else {
						// get list elements
						$actList[$id][$itemKey] = array();
						foreach ($itemVal as $subItemKey => $subItemVal) {
							$subId = $subItemVal['Id'][0];
							$actList[$id][$itemKey][$subId] = array();
							
							foreach ($subItemVal as $subSubKey => $subSubVal) {
								$actList[$id][$itemKey][$subId][$subSubKey] = $subSubVal[0];							
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

	return $actList;

}


function AnswersApiDeleteAct ($id) {
        $actId = 0;

	// request
	$bodyArray = AnswersApiDelete("act", $id);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$actId = $bodyArray['Response'][0]['Content'][0]['Act'][0]['Id'][0];
	}
	else {
		// error
	}

        return $actId;
}


// Act Profile

function AnswersApiSaveActProfileAnnotationList ($profile, $AnnotationList, $actId, $access) {

	return AnswersApiSaveProfileAnnotationList($profile, $AnnotationList, $access, "act", $actId);

}

function AnswersApiGetActProfile ($actId, $profileId) {

	return AnswersApiListProfileById("act", $actId, $profileId);

}

function AnswersApiUpdateActProfileAnnotation($profileAnnotation, $actId, $profileId) {

	# ownership of actId should be checked before this call!
	return AnswersApiUpdateProfileAnnotation($profileAnnotation, "act", $profileId);

}

function AnswersApiListActProfileByAct ($group, $actId) {

	return AnswersApiListProfileWithQuery("act", $actId, "group=$group&group_match=exact");

}

function AnswersApiDeleteActProfile ($actId, $profileId) {

	# ownership of actId should be checked before this call!
	return AnswersApiDeleteProfile($profileId, "act");

}

// Act Annotation

function AnswersApiGetActAnnotationFromAct($actId, $name) {

	return AnswersApiGetAnnotation($name, "act", $actId);

}

function AnswersApiCreateActAnnotation($actId, $name) {

        return AnswersApiCreateAnnotation($name, "act", $actId);

}

function AnswersApiSaveActAnnotation ($annotation, $actId, $access) {

        return AnswersApiSaveAnnotation($annotation, $access, "act", $actId);

}

function AnswersApiSaveActAnnotationList ($annotationList, $actId, $access) {

        return AnswersApiSaveAnnotationList($annotationList, $access, "act", $actId);

}

function AnswersApiUpdateActAnnotationListByAct ($annotationList, $actId) {

        return AnswersApiUpdateAnnotationList($annotationList, "act", $actId);

}

function AnswersApiUpdateActAnnotation ($id, $annotation) {

        return AnswersApiUpdateAnnotation($id, $annotation, "act");

}

function AnswersApiDeleteActAnnotation ($id) {

        return AnswersApiDeleteAnnotation($id, "act");

}

//////////
// ACT REVIEW
//////////

function AnswersApiSaveActReview ($act, $reference, $access) {

        $reviewId = 0;
	$actReview = array();

	$actReview['act'] = $act;
	$actReview['reference'] = $reference;
	//$actReview['access'] = $access;

	// request
	$bodyArray = AnswersApiSave("review", $actReview);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$reviewId = $bodyArray['Response'][0]['Content'][0]['Review'][0]['Id'][0];
	}
	else {
		# error
	}

        return $reviewId;

}

function AnswersApiDeleteActReview ($id) {
        $reviewId = 0;

	// request
	$bodyArray = AnswersApiDelete("review", $id);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$reviewId = $bodyArray['Response'][0]['Content'][0]['Review'][0]['Id'][0];
	}
	else {
		// error
	}

        return $reviewId;
}


//////////
// ACT MAILBLOCK
//////////

function AnswersApiSaveActMailblock ($act, $reference, $access) {

    $mailblockId = 0;
	$actMailblock = array();

	$actMailblock['act'] = $act;
	$actMailblock['reference'] = $reference;
	//$actReview['access'] = $access;

	// request
	$bodyArray = AnswersApiSave("mailblock", $actMailblock);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$mailblockId = $bodyArray['Response'][0]['Content'][0]['Mailblock'][0]['Id'][0];
	}
	else {
		# error
	}

    return $mailblockId;
}

function AnswersApiDeleteActMailblock ($id) {
    $mailblockId = 0;

	// request
	$bodyArray = AnswersApiDelete("mailblock", $id);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$mailblockId = $bodyArray['Response'][0]['Content'][0]['Mailblock'][0]['Id'][0];
	}
	else {
		// error
	}

    return $mailblockId;
}

?>
