<?php

//////////
// CONNECT WITH API HOST: Save, Update, List & Delete functions
// + Detail function
//////////

function ConnectApiSave ($object, $values) {

	global $XCOW_B;

	// request
	$url = $XCOW_B['connect_api']['host'].$object."/save";
	$query = "";
	foreach ($values as $key => $val) {
		if ($query == "") {		
			$query .= urlencode($key)."=".urlencode($val);
		}
		else {
			$query .= "&".urlencode($key)."=".urlencode($val);
		}
	}
	$url = $url."?".$query;

	// TODO: dit moet POST worden
	$body = getResponse ($url);
	$bodyObj = new Xml2Php2();
	$bodyObj->startProcessing($body, 1);
	$bodyArray = $bodyObj->getPhpArray();

        return $bodyArray;

}

function ConnectApiUpdate ($object, $id, $values) {

	global $XCOW_B;

	// request
	if ($id == 0) {
		$url = $XCOW_B['connect_api']['host'].$object."/update";
	}
	else {
		$url = $XCOW_B['connect_api']['host'].$object."/".$id."/update";
	}
	$query = "";
	foreach ($values as $key => $val) {
		if ($query == "") {		
			$query .= urlencode($key)."=".urlencode($val);
		}
		else {
			$query .= "&".urlencode($key)."=".urlencode($val);
		}
	}
	$url = $url."?".$query;

	// TODO: dit moet POST worden
	$body = getResponse ($url);
	$bodyObj = new Xml2Php2();
	$bodyObj->startProcessing($body, 1);
	$bodyArray = $bodyObj->getPhpArray();

        return $bodyArray;

}

function ConnectApiList ($object, $id, $query) {

	global $XCOW_B;

	// request
	if ($id == 0) {
		$url = $XCOW_B['connect_api']['host'].$object."/list";
	}
	else {
		$url = $XCOW_B['connect_api']['host'].$object."/".$id."/list";
	}
	if ($query != '') {
		$url = $url."?".$query;
	}
	
	$body = getResponse ($url);
	$bodyObj = new Xml2Php2();
	$bodyObj->startProcessing($body, 1);
	$bodyArray = $bodyObj->getPhpArray();

        return $bodyArray;

}

function ConnectApiDelete ($object, $id) {

	global $XCOW_B;

	// request
	$url = $XCOW_B['connect_api']['host'].$object."/".$id."/delete";
	
	// TODO: dit moet POST worden
	$body = getResponse ($url);
	$bodyObj = new Xml2Php2();
	$bodyObj->startProcessing($body, 1);
	$bodyArray = $bodyObj->getPhpArray();

        return $bodyArray;

}

function ConnectApiDetail ($object, $values) {

	global $XCOW_B;

	// request
	$url = $XCOW_B['connect_api']['host'].$object;

	$query = "";
	foreach ($values as $key => $val) {
		if ($query == "") {		
			$query .= urlencode($key)."=".urlencode($val);
		}
		else {
			$query .= "&".urlencode($key)."=".urlencode($val);
		}
	}
	$url = $url."&".$query;

	// TODO: dit moet POST worden
	$body = getResponse ($url);
	$bodyObj = new Xml2Php2();
	$bodyObj->startProcessing($body, 1);
	$bodyArray = $bodyObj->getPhpArray();

        return $bodyArray;

}



//////////
// Profile Create, Save, List & Delete functions (total 9 public functions)
//////////
// not implemented:
// - Update functions (not used for now, but should build the functions)
// more functions: 
// - Save AnnotationList to profile
// - Update Annotation from profile

function ConnectApiGetProfile($name, $object, $object_id) {
	$profileId = 0;

	// request
	$bodyArray = ConnectApiList($object."/".$object_id."/profile", '0', 'name='.$name."&name_match=exact");

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

function ConnectApiCreateProfile($group, $name, $object, $object_id) {
        $profileId = 0;

	$profile = array();
	$profile['group'] = $group;
	$profile['name'] = $name;
	$profileId = ConnectApiSaveProfile($profile, 1, $object, $object_id);

	return $profileId;
}

function ConnectApiSaveProfile($profile, $access, $object, $object_id) {
        $profileId = 0;

	$profile['access'] = $access;

	// request
	$bodyArray = ConnectApiSave($object."/".$object_id."/profile", $profile);

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

function ConnectApiListProfile($object, $object_id) {
	$bodyArray = ConnectApiList($object."/".$object_id."/profile", '0', '');

	return ConnectApiListProfileResponse($bodyArray);
}

function ConnectApiListProfileById($object, $object_id, $id) {
	$bodyArray = ConnectApiList($object."/".$object_id."/profile", $id, '');

	return ConnectApiListProfileResponse($bodyArray);
}

function ConnectApiListProfileWithQuery($object, $object_id, $query) {
	$bodyArray = ConnectApiList($object."/".$object_id."/profile", '0', $query);

	return ConnectApiListProfileResponse($bodyArray);
}

function ConnectApiListProfileResponse($bodyArray) {
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

function ConnectApiDeleteProfile ($id, $object) {
        $profileId = 0;

	// request
	$bodyArray = ConnectApiDelete($object."/profile", $id);

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

function ConnectApiSaveProfileAnnotationList($profile, $AnnotationList, $access, $object, $object_id) {
	$annotationId = 0;


	// save profile
	$profileId = ConnectApiSaveProfile($profile, $access, $object, $object_id);

	// save profile annotations
	$annotationId = ConnectApiSaveAnnotationList($AnnotationList, $access, $object."/profile", $profileId);

	// only the last id is returned?
	return $annotationId;

}

function ConnectApiUpdateProfileAnnotation($profileAnnotation, $object, $object_id) {
	$annotationId = 0;

	$annotationId = ConnectApiUpdateAnnotationList ($profileAnnotation, $object."/profile", $object_id);

	return $annotationId;
}



//////////
// Annotation Create, Save, Update & Delete functions (total 7 public functions)
//////////
// not implemented:
// - List functions (annotation are listed in profiles or sections)
// more functions: 
// - Save AnnotationList
// - Update AnnotationList

function ConnectApiGetAnnotation($name, $object, $object_id) {
	$annotationId = 0;

	// request
	$bodyArray = ConnectApiList($object."/".$object_id."/annotation", '0', "name=".$name."&name_match=exact");

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {

		$listSize = $bodyArray['Response'][0]['Content'][0]['Summary'][0]['CompleteListSize'][0];
		if ($listSize == 0) {
			$annotationId = ConnectApiCreateAnnotation($name, $object, $object_id);
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

function ConnectApiCreateAnnotation($name, $object, $object_id) {
	$annotation = array();
	$annotation['name'] = $name;

	$annotationId = ConnectApiSaveAnnotation($annotation, 1, $object, $object_id);

	return $annotationId;
}

function ConnectApiSaveAnnotation ($annotation, $access, $object, $object_id) {
        $annotationId = 0;

	$annotation['access'] = $access;

	// request
	$bodyArray = ConnectApiSave($object."/".$object_id."/annotation", $annotation);

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

function ConnectApiSaveAnnotationList ($annotationList, $access, $object, $object_id) {
        $annotationId = 0;

	// save multiple annotations one-by-one... Is this OK?
	foreach ($annotationList as $key => $val) {
		$oneAnnotation = array();
		$oneAnnotation['name'] = $key;
		$oneAnnotation['value'] = $val;
		// TODO: set type based on value
		$oneAnnotation['type'] = "string";

		$annotationId = ConnectApiSaveAnnotation($oneAnnotation, $access, $object, $object_id);

	}

	// only the last id is returned?
        return $annotationId;
}

function ConnectApiUpdateAnnotation ($id, $annotation, $object) {
        $annotationId = 0;

	// request
	$bodyArray = ConnectApiUpdate($object."/annotation", $id, $annotation);

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

function ConnectApiUpdateAnnotationList ($annotationList, $object, $object_id) {
	$annotationId = 0;

	foreach ($annotationList as $key => $val) {
		$oneAnnotation = array();
		$oneAnnotation['value'] = $val;
		// TODO: set type based on value
		$oneAnnotation['type'] = "string";
		// TODO: how to update access?

		$annotationId = ConnectApiGetAnnotation($key, $object, $object_id);

		$annotationId = ConnectApiUpdateAnnotation($annotationId, $oneAnnotation, $object);

	}

	return $annotationId;
}

function ConnectApiDeleteAnnotation ($id, $object) {
        $annotationId = 0;

	// request
	$bodyArray = ConnectApiDelete($object."/annotation", $id);

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
// CONNECT
//////////


function ConnectApiSaveConnect ($connect) {

        $connectId = 0;

	// request
	$bodyArray = ConnectApiSave("connect", $connect);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$connectId = $bodyArray['Response'][0]['Content'][0]['Connect'][0]['Id'][0];
	}
	else {
		# error
	}

        return $connectId;

}

function ConnectApiUpdateConnect ($id, $connect) {

        $connectId = 0;

	// request
	$bodyArray = ConnectApiUpdate("connect", $id, $connect);

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		$connectId = $bodyArray['Response'][0]['Content'][0]['Connect'][0]['Id'][0];
	}
	else {
		// error (connectId = 0)
	}

        return $connectId;

}

function ConnectApiListConnect() {

	$bodyArray = ConnectApiList("connect", '0', '');

	return ConnectApiListConnectResponse($bodyArray);

}

function ConnectApiListConnectWithQuery($query) {

	$bodyArray = ConnectApiList("connect", '0', $query);

	return ConnectApiListConnectResponse($bodyArray);

}

function ConnectApiListConnectById($id) {

	$bodyArray = ConnectApiList("connect", $id, '');

	return ConnectApiListConnectResponse($bodyArray);

}

function ConnectApiListConnectResponse($bodyArray) {

	$connectList = array();

	// response
	$responseStatus = $bodyArray['Response'][0]['Header'][0]['Status'][0];
	if ($responseStatus == "200 OK") {
		if ($bodyArray['Response'][0]['Content'][0]['Summary'][0]['CompleteListSize']['0'] == 0) {
			$count = 1;
			foreach ($bodyArray['Response'][0]['Content'][0]['Connects'][0]['Connect'] as $connect) {
				// an array for each connect
				$id = $count;
				$connectList[$id] = array();
				$connectList[$id]['name'] = $connect['Name'][0];

				$count++;
			}
		}

	}
	else {
		# error
	}

	return $connectList;

}

// Connect Profile

function ConnectApiSaveConnectProfileAnnotationList ($profile, $AnnotationList, $connectId, $access) {

	return ConnectApiSaveProfileAnnotationList($profile, $AnnotationList, $access, "connect", $connectId);

}

function ConnectApiGetConnectProfile ($connectId, $profileId) {

	return ConnectApiListProfileById("connect", $connectId, $profileId);

}

function ConnectApiUpdateConnectProfileAnnotation($profileAnnotation, $connectId, $profileId) {

	# connectId ???
	return ConnectApiUpdateProfileAnnotation($profileAnnotation, "connect", $profileId);

}

function ConnectApiListConnectProfileByConnect ($group, $connectId) {

	return ConnectApiListProfileWithQuery("connect", $connectId, "group=$group&group_match=exact");

}

function ConnectApiDeleteConnectProfile ($connectId, $profileId) {

	# TODO: check ownership of profileId by connectId

	return ConnectApiDeleteProfile($profileId, "connect");

}

// Connect Annotation

function ConnectApiGetConnectAnnotationFromConnect($connectId, $name) {

	return ConnectApiGetAnnotation($name, "connect", $connectId);

}

function ConnectApiCreateConnectAnnotation($connectId, $name) {

        return ConnectApiCreateAnnotation($name, "connect", $connectId);

}

function ConnectApiSaveConnectAnnotation ($annotation, $connectId, $access) {

        return ConnectApiSaveAnnotation($annotation, $access, "connect", $connectId);

}

function ConnectApiSaveConnectAnnotationList ($annotationList, $connectId, $access) {

        return ConnectApiSaveAnnotationList($annotationList, $access, "connect", $connectId);

}

function ConnectApiUpdateConnectAnnotationListByConnect ($annotationList, $connectId) {

        return ConnectApiUpdateAnnotationList($annotationList, "connect", $connectId);

}

function ConnectApiUpdateConnectAnnotation ($id, $annotation) {

        return ConnectApiUpdateAnnotation($id, $annotation, "connect");

}

function ConnectApiDeleteConnectAnnotation ($id) {

        return ConnectApiDeleteAnnotation($id, "connect");

}

?>
