<?php

// PROPERTIES...
// ...to be editted before use.

function GetAnnotationProperties($object) {

	$annotationProperties = array();

	switch ($object) {
		case "act":
			$annotationProperties['table'] = "ActAnnotation";
			$annotationProperties['reference'] = "ActId";
			$annotationProperties['access'] = "0";
			break;
		case "actProfile":
			$annotationProperties['table'] = "ActProfileAnnotation";
			$annotationProperties['reference'] = "ProfileId";
			$annotationProperties['access'] = "0";
			break;
		default:
			break;
	}

	return $annotationProperties;
}

function GetProfileProperties($object) {

	$profileProperties = array();

	switch ($object) {
		case "act":
			$profileProperties['table'] = "ActProfile";
			$profileProperties['reference'] = "ActId";
			$profileProperties['annotation'] = "actProfile";
			$profileProperties['access'] = "0";
			break;
		default:
			break;
	}

	return $profileProperties;
}

// dont edit below this line



// ANNOTATION

function AnnotationInsert ($annotation, $object, $object_id) {
        global $XCOW_B;

        $annotationId = 0;

	$annotationProperties = array();
	$annotationProperties = GetAnnotationProperties($object);

	$annotation = safeListInsert($annotation);

	// TODO: check if object_id exists!
	// $exists = existsAct($this->object_id);

	$result = NULL;
	$result = mysql_query("INSERT INTO {$annotationProperties['table']} VALUES(NULL, '{$annotation['name']}', '{$annotation['value']}', '{$annotation['type']}', $object_id)", $XCOW_B['mysql_link']);

        if ($result) {
                $annotationId = mysql_insert_id($XCOW_B['mysql_link']);
        }
	else {
		catchMysqlError("AnnotationInsert", $XCOW_B['mysql_link']);
	}

        return $annotationId;
}

function AnnotationList($object, $object_id) {
        global $XCOW_B;

	$annotationProperties = array();
	$annotationProperties = GetAnnotationProperties($object);

	$table = "{$annotationProperties['table']}";
	$where = "WHERE {$annotationProperties['reference']} = $object_id";
	$order = "";
	$limit = "";
	$expand = 1;

	return AnnotationListWithValues($table, $where, $order, $limit, $annotationProperties, $expand);
}

function AnnotationListWithValues($table, $where, $order, $limit, $annotationProperties, $expand) {
        global $XCOW_B;

	$annotationList = array();

	$query = "SELECT AnnotationId, AnnotationAttribute, AnnotationValue, AnnotationType from $table $where $order $limit";
	#log2file("AnnotationList: Query: ".$query);

	#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {
			// TODO: check AccessRule                	

			$annotationId = $result_row['AnnotationId'];
	
		       	$annotationList[$annotationId] = array();
		       	$annotationList[$annotationId]['id'] = $annotationId;
		       	$annotationList[$annotationId]['name'] = $result_row['AnnotationAttribute'];
		       	$annotationList[$annotationId]['value'] = $result_row['AnnotationValue'];
		       	$annotationList[$annotationId]['type'] = $result_row['AnnotationType'];
			#$annotationList[$annotationId]['extReference'] = $annotationProperties['reference'];
                        #$annotationList[$annotationId]['extId'] = $this->object_id;
        	}
        }
	else {
		catchMysqlError("AnnotationListWithValues", $XCOW_B['mysql_link']);
	}
	
	return $annotationList;
}

function AnnotationUpdate ($ids, $annotation, $object) {
        global $XCOW_B;

	$annotationProperties = array();
	$annotationProperties = GetAnnotationProperties($object);

        $status = NULL;
	$updateString = "";
	$annotationString = "";

	$annotation = safeListInsert($annotation);

	# create update string
	foreach ($annotation as $attribute => $value) {
		if ($updateString != "") { $updateString .= ","; }

		switch ($attribute) {
			case "name":
				$updateString .= "AnnotationAttribute='".$value."'";
				break;
			case "value":
				$updateString .= "AnnotationValue='".$value."'";
				break;
			case "type":
				$updateString .= "AnnotationType='".$value."'";
				break;
			default:
				$updateString .= $attribute."='".$value."'";
				break;
		}
	}

	#
	# go
	#
	if ($updateString != "") {

 		$annotationString = implode(",",$ids);
                $where = "AnnotationId in ($annotationString)";
		$result = mysql_query("UPDATE {$annotationProperties['table']} SET $updateString WHERE $where", $XCOW_B['mysql_link']);

		if (! $result) {
       			catchMysqlError("AnnotationUpdate", $XCOW_B['mysql_link']);
			$status = "500 Internal Error";
		}
                elseif (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
			# mysql geeft 0 affected rows als een update wordt gedaan met allemaal dezelfde waarden
                        # $status = "404 Not Found";
                }

	}

	return $status;
}

function AnnotationDelete ($ids, $object) {
        global $XCOW_B;

	$annotationProperties = array();
	$annotationProperties = GetAnnotationProperties($object);

        $status = NULL;
	$annotationString = "";

	$annotationString = implode(",",$ids);
        $where = "AnnotationId in ($annotationString)";
	$result = mysql_query("DELETE FROM {$annotationProperties['table']} WHERE $where", $XCOW_B['mysql_link']);
     	if (! $result) {
		catchMysqlError("AnnotationDelete", $XCOW_B['mysql_link']);
		$status = "500 Internal Error";
        }
        if (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
               	$status = "404 Not Found";
        }

	return $status;
}

function GetAnnotationTypeFromAnnotationAttribute($object, $attribute) {
        global $XCOW_B;

	$annotationProperties = array();
	$annotationProperties = GetAnnotationProperties($object);

	$attribute = safeInsert($attribute);
        $result = mysql_query("SELECT AnnotationType FROM {$annotationProperties['table']} WHERE AnnotationAttribute = '$attribute' LIMIT 1", $XCOW_B['mysql_link']);
        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}



// PROFILE

function ProfileInsert ($profile, $object, $object_id) {
        global $XCOW_B;

        $profileId = 0;

	$profileProperties = array();
	$profileProperties = GetProfileProperties($object);

	$profile = safeListInsert($profile);

	// TODO: check if object_id exists!
	// $exists = existsConnect($this->object_id);

	$result = NULL;
	$result = mysql_query("INSERT INTO {$profileProperties['table']} VALUES(NULL, '{$profile['group']}', '{$profile['name']}', $object_id)", $XCOW_B['mysql_link']);

        if ($result) {
                $profileId = mysql_insert_id($XCOW_B['mysql_link']);
        }
	else {
		catchMysqlError("ProfileInsert", $XCOW_B['mysql_link']);
	}

        return $profileId;
}

function ProfileList($object, $object_id) {
        global $XCOW_B;

	$profileProperties = array();
	$profileProperties = GetProfileProperties($object);

	$table = "{$profileProperties['table']}";
	$where = "WHERE {$profileProperties['reference']} = $object_id";
	$order = "";
	$limit = "";
	$expand = 1;

	return ProfileListWithValues($table, $where, $order, $limit, $profileProperties, $expand);
}

function ProfileListWithValues($table, $where, $order, $limit, $profileProperties, $expand) {
        global $XCOW_B;

	$profileList = array();

	$query = "SELECT ProfileId, ProfileGroup, ProfileName from $table $where $order $limit";
	#log2file("ProfileList: Query: ".$query);

	#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {

			$profileId = $result_row['ProfileId'];
	
		       	$profileList[$profileId] = array();
		       	$profileList[$profileId]['id'] = $profileId;
		       	$profileList[$profileId]['name'] = $result_row['ProfileName'];
		       	$profileList[$profileId]['group'] = $result_row['ProfileGroup'];
			#$profileList[$profileId]['extReference'] = $profileProperties['reference'];
                        #$profileList[$profileId]['extId'] = $this->object_id;

			$profileList[$profileId]['annotation'] = array();
			if ($expand) {
				$profileList[$profileId]['annotation'] = AnnotationList($profileProperties['annotation'], $profileId);
			}
        	}
        }
	else {
		catchMysqlError("ProfileListWithValues", $XCOW_B['mysql_link']);
	}
	
	return $profileList;
}

function ProfileUpdate ($ids, $profile, $object) {
        global $XCOW_B;

	$profileProperties = array();
	$profileProperties = GetProfileProperties($object);

        $status = NULL;
	$updateString = "";
	$profileString = "";

	$profile = safeListInsert($profile);

	# create update string
	foreach ($profile as $attribute => $value) {
		if ($updateString != "") { $updateString .= ","; }

		switch ($attribute) {
			case "name":
				$updateString .= "ProfileName='".$value."'";
				break;
			case "group":
				$updateString .= "ProfileGroup='".$value."'";
				break;
			default:
				$updateString .= $attribute."='".$value."'";
				break;
		}
	}

	#
	# go
	#
	if ($updateString != "") {

 		$profileString = implode(",",$ids);
                $where = "ProfileId in ($profileString)";
		$result = mysql_query("UPDATE {$profileProperties['table']} SET $updateString WHERE $where", $XCOW_B['mysql_link']);

		if (! $result) {
			catchMysqlError("ProfileUpdate", $XCOW_B['mysql_link']);
			$status = "500 Internal Error";
		}
                elseif (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
			# mysql geeft 0 affected rows als een update wordt gedaan met allemaal dezelfde waarden
                        # $status = "404 Not Found";
                }

	}

	return $status;
}

function ProfileDelete ($ids, $object) {
        global $XCOW_B;

	$profileProperties = array();
	$profileProperties = GetProfileProperties($object);

        $status = NULL;
	$profileString = "";

	$profileString = implode(",",$ids);
        $where = "ProfileId in ($profileString)";
	$result = mysql_query("DELETE FROM {$profileProperties['table']} WHERE $where", $XCOW_B['mysql_link']);
     	if (! $result) {
		catchMysqlError("ProfileDelete", $XCOW_B['mysql_link']);
		$status = "500 Internal Error";
        }
        if (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
               	$status = "404 Not Found";
        }

	return $status;
}

?>
