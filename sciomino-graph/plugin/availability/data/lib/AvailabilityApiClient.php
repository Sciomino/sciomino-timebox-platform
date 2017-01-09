<?php

//////////
// CONNECT WITH API HOST: Save, Update, List & Delete functions
// + Detail function
//////////

function AvailabilityApiSave ($object, $values) {

	global $XCOW_B;
	$headers = array();
	$params = array();

	// request
	$url = $XCOW_B['availability_api']['host'].$object."/save";

	// auth
	$authParams = postAvailabilityApiAuth();
	$params = array_merge($values, $authParams);

	// post
	$body = postResponse ($url, $headers, $params);
	$bodyObj = new Xml2Php2();
	$bodyObj->startProcessing($body[1], 1);
	$bodyArray = $bodyObj->getPhpArray();

    return $bodyArray;

}

function AvailabilityApiUpdate ($object, $id, $values) {

	global $XCOW_B;
	$headers = array();
	$params = array();

	// request
	if ($id == 0) {
		$url = $XCOW_B['availability_api']['host'].$object."/update";
	}
	else {
		$url = $XCOW_B['availability_api']['host'].$object."/".$id."/update";
	}

	// auth
	$authParams = postAvailabilityApiAuth();
	$params = array_merge($values, $authParams);

	// post
	$body = postResponse ($url, $headers, $params);
	$bodyObj = new Xml2Php2();
	$bodyObj->startProcessing($body[1], 1);
	$bodyArray = $bodyObj->getPhpArray();

    return $bodyArray;

}

function AvailabilityApiList ($object, $id, $query) {

	global $XCOW_B;

	// request
	if ($id == 0) {
		$url = $XCOW_B['availability_api']['host'].$object."/list";
	}
	else {
		$url = $XCOW_B['availability_api']['host'].$object."/".$id."/list";
	}

	// auth
	if ($query != '') {
		$query .= "&".GetAvailabilityApiAuth();
	}
	else {
		$query .= GetAvailabilityApiAuth();
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

function AvailabilityApiDelete ($object, $id) {

	global $XCOW_B;
	$headers = array();
	$params = array();

	// request
	$url = $XCOW_B['availability_api']['host'].$object."/".$id."/delete";

	// auth
	$authParams = postAvailabilityApiAuth();
	$params = $authParams;
	
	// post
	$body = postResponse ($url, $headers, $params);
	$bodyObj = new Xml2Php2();
	$bodyObj->startProcessing($body[1], 1);
	$bodyArray = $bodyObj->getPhpArray();

    return $bodyArray;

}

function AvailabilityApiDetail ($object, $values) {

	global $XCOW_B;

	// request
	$url = $XCOW_B['availability_api']['host'].$object;

	// auth
	$query = "";
	$query .= GetAvailabilityApiAuth();
	foreach ($values as $key => $val) {
		if ($query == "") {		
			$query .= urlencode($key)."=".urlencode($val);
		}
		else {
			$query .= "&".urlencode($key)."=".urlencode($val);
		}
	}
	$url = $url."?".$query;

	// get
	$body = getResponse ($url);
	$bodyObj = new Xml2Php2();
	$bodyObj->startProcessing($body, 1);
	$bodyArray = $bodyObj->getPhpArray();

    return $bodyArray;

}

function AvailabilityApiDetailPlain ($object, $values) {

	global $XCOW_B;

	// request
	$url = $XCOW_B['availability_api']['host'].$object;

	// auth
	$query = "";
	$query .= GetAvailabilityApiAuth();
	foreach ($values as $key => $val) {
		if ($query == "") {		
			$query .= urlencode($key)."=".urlencode($val);
		}
		else {
			$query .= "&".urlencode($key)."=".urlencode($val);
		}
	}
	$url = $url."?".$query;

	// get
	$body = getResponse ($url);

    return $body;

}

function GetAvailabilityApiAuth () {

	global $XCOW_B;

	$query = "";

	// auth
	if ($XCOW_B['availability_api']['auth']) {
		$query .= "availability_api_id=".$XCOW_B['availability_api']['id'];
		$query .= "&availability_api_nonce=".$XCOW_B['availability_api']['nonce'];
		$query .= "&availability_api_key=".$XCOW_B['availability_api']['key'];
	}

	return $query;
}

function PostAvailabilityApiAuth () {

	global $XCOW_B;

	$params = array();

	// auth
	if ($XCOW_B['availability_api']['auth']) {
		$params['availability_api_id'] = $XCOW_B['availability_api']['id'];
		$params['availability_api_nonce'] = $XCOW_B['availability_api']['nonce'];
		$params['availability_api_key'] = $XCOW_B['availability_api']['key'];
	}

	return $params;
}

//////////
// USER: List: Get Availability Data
//////////

// plain user list
function AvailabilityApiUserListPlainWithQuery($query) {

	$body = AvailabilityApiDetailPlain("user/list", $query);

	return $body;

}

// plain user info
function AvailabilityApiUserInfoPlainWithQuery($user, $query) {

	$body = AvailabilityApiDetailPlain("user/".$user."/list", $query);

	return $body;

}

/*
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
*/

?>
