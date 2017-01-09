<?php

// CONNECT

function ConnectInsert ($connect, $reference) {
        global $XCOW_B;

        $connectId = 0;
	$timestamp = time();

	$connect = safeListInsert($connect);

        $result = mysql_query("INSERT INTO Connection VALUES(NULL, '{$connect['type']}', '{$connect['name']}', '$timestamp', '$reference')", $XCOW_B['mysql_link']);

        if ($result) {
                $connectId = mysql_insert_id($XCOW_B['mysql_link']);
        }

        return $connectId;
}

function ConnectList() {
        global $XCOW_B;

	$table = "Connection";
	$where = "";
	$order = "";
	$limit = "";
	$expand = 1;

	return ConnectListWithValues($table, $where, $order, $limit, $expand);
}

function ConnectListWithValues($table, $where, $order, $limit, $expand) {
        global $XCOW_B;

	$connectList = array();

	#
        # Construct QUERY
        #
        $query = "SELECT Connection.ConnectionId, Connection.ConnectionType, Connection.ConnectionName, Connection.ConnectionTimestamp, Connection.Reference from $table $where $order $limit";
	
	log2file("ConnectList: Query: ".$query);

	#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {

			$connectId = $result_row['ConnectionId'];
	
                	$connectList[$connectId] = array();
                	$connectList[$connectId]['connectId'] = $connectId;
                	$connectList[$connectId]['connectType'] = $result_row['ConnectionType'];
                	$connectList[$connectId]['connectName'] = $result_row['ConnectionName'];
                	$connectList[$connectId]['connectTimestamp'] = $result_row['ConnectionTimestamp'];
                	$connectList[$connectId]['reference'] = $result_row['Reference'];

			$connectList[$connectId]['annotation'] = array();
			$connectList[$connectId]['profile'] = array();
			if ($expand) {
				$connectList[$connectId]['annotation'] = ConnectAnnotationList('connect', $connectId);
				$connectList[$connectId]['profile'] = ConnectProfileList('connect', $connectId);
			}
        	}
        }

	return $connectList;
}

function ConnectUpdate ($ids, $connect) {
        global $XCOW_B;

        $status = NULL;
	$updateString = "";
	$connectString = "";

	$connect = safeListInsert($connect);

	# create update string
	foreach ($connect as $attribute => $value) {
		if ($value != "") {
			if ($updateString != "") { $updateString .= ","; }

			switch ($attribute) {
				case "type":
					$updateString .= "ConnectionType='".$value."'";
					break;
				case "name":
					$updateString .= "ConnectionName"."='".$value."'";
					break;
				default:
					$updateString .= $attribute."='".$value."'";
					break;
			}
		}
	}

	#
	# go
	#
	if ($updateString != "") {

 		$connectString = implode(",",$ids);
                $where = "ConnectionId in ($connectString)";
		$result = mysql_query("UPDATE Connection SET $updateString WHERE $where", $XCOW_B['mysql_link']);

		if (! $result) {
       			echoMysqlError(mysql_errno(), mysql_error());
			$status = "500 Internal Error";
		}
                elseif (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
			# mysql geeft 0 affected rows als een update wordt gedaan met allemaal dezelfde waarden
                        # $status = "404 Not Found";
                }

	}

	return $status;
}

function ConnectDelete ($ids) {
        global $XCOW_B;

        $status = NULL;
	$connectString = "";

	$connectString = implode(",",$ids);
        $where = "ConnectionId in ($connectString)";
	$result = mysql_query("DELETE FROM Connection WHERE $where", $XCOW_B['mysql_link']);
     	if (! $result) {
             	echoMysqlError(mysql_errno(), mysql_error());
		$status = "500 Internal Error";
        }
        if (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
               	$status = "404 Not Found";
        }

	return $status;
}



// PROFILE

function GetProfileProperties($object) {

	$profileProperties = array();

	switch ($object) {
		case "connect":
			$profileProperties['table'] = "ConnectionProfile";
			$profileProperties['reference'] = "ConnectionId";
			$profileProperties['annotation'] = "connectProfile";
			$profileProperties['access'] = "0";
			break;
		default:
			break;
	}

	return $profileProperties;
}

function ConnectProfileInsert ($profile, $object, $object_id) {
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

        return $profileId;
}

function ConnectProfileList($object, $object_id) {
        global $XCOW_B;

	$profileProperties = array();
	$profileProperties = GetProfileProperties($object);

	$table = "{$profileProperties['table']}";
	$where = "WHERE {$profileProperties['reference']} = $object_id";
	$order = "";
	$limit = "";
	$expand = 1;

	return ConnectProfileListWithValues($table, $where, $order, $limit, $profileProperties, $expand);
}

function ConnectProfileListWithValues($table, $where, $order, $limit, $profileProperties, $expand) {
        global $XCOW_B;

	$profileList = array();

	$query = "SELECT ProfileId, ProfileGroup, ProfileName from $table $where $order $limit";
	log2file("ProfileList: Query: ".$query);

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
				$profileList[$profileId]['annotation'] = ConnectAnnotationList($profileProperties['annotation'], $profileId);
			}
        	}
        }
	
	return $profileList;
}

function ConnectProfileUpdate ($ids, $profile, $object) {
        global $XCOW_B;

	$profileProperties = array();
	$profileProperties = GetProfileProperties($object);

        $status = NULL;
	$updateString = "";
	$profileString = "";

	$profile = safeListInsert($profile);

	# create update string
	foreach ($profile as $attribute => $value) {
		if ($value != "") {
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
	}

	#
	# go
	#
	if ($updateString != "") {

 		$profileString = implode(",",$ids);
                $where = "ProfileId in ($profileString)";
		$result = mysql_query("UPDATE {$profileProperties['table']} SET $updateString WHERE $where", $XCOW_B['mysql_link']);

		if (! $result) {
       			echoMysqlError(mysql_errno(), mysql_error());
			$status = "500 Internal Error";
		}
                elseif (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
			# mysql geeft 0 affected rows als een update wordt gedaan met allemaal dezelfde waarden
                        # $status = "404 Not Found";
                }

	}

	return $status;
}

function ConnectProfileDelete ($ids, $object) {
        global $XCOW_B;

	$profileProperties = array();
	$profileProperties = GetProfileProperties($object);

        $status = NULL;
	$profileString = "";

	$profileString = implode(",",$ids);
        $where = "ProfileId in ($profileString)";
	$result = mysql_query("DELETE FROM {$profileProperties['table']} WHERE $where", $XCOW_B['mysql_link']);
     	if (! $result) {
             	echoMysqlError(mysql_errno(), mysql_error());
		$status = "500 Internal Error";
        }
        if (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
               	$status = "404 Not Found";
        }

	return $status;
}



// ANNOTATION

function GetAnnotationProperties($object) {

	$annotationProperties = array();

	switch ($object) {
		case "connect":
			$annotationProperties['table'] = "ConnectionAnnotation";
			$annotationProperties['reference'] = "ConnectionId";
			$annotationProperties['access'] = "0";
			break;
		case "connectProfile":
			$annotationProperties['table'] = "ConnectionProfileAnnotation";
			$annotationProperties['reference'] = "ProfileId";
			$annotationProperties['access'] = "0";
			break;
		default:
			break;
	}

	return $annotationProperties;
}

function ConnectAnnotationInsert ($annotation, $object, $object_id) {
        global $XCOW_B;

        $annotationId = 0;

	$annotationProperties = array();
	$annotationProperties = GetAnnotationProperties($object);

	$annotation = safeListInsert($annotation);

	// TODO: check if object_id exists!
	// $exists = existsConnect($this->object_id);

	$result = NULL;
	$result = mysql_query("INSERT INTO {$annotationProperties['table']} VALUES(NULL, '{$annotation['name']}', '{$annotation['value']}', '{$annotation['type']}', $object_id)", $XCOW_B['mysql_link']);

        if ($result) {
                $annotationId = mysql_insert_id($XCOW_B['mysql_link']);
        }

        return $annotationId;
}

function ConnectAnnotationList($object, $object_id) {
        global $XCOW_B;

	$annotationProperties = array();
	$annotationProperties = GetAnnotationProperties($object);

	$table = "{$annotationProperties['table']}";
	$where = "WHERE {$annotationProperties['reference']} = $object_id";
	$order = "";
	$limit = "";
	$expand = 1;

	return ConnectAnnotationListWithValues($table, $where, $order, $limit, $annotationProperties, $expand);
}

function ConnectAnnotationListWithValues($table, $where, $order, $limit, $annotationProperties, $expand) {
        global $XCOW_B;

	$annotationList = array();

	$query = "SELECT AnnotationId, AnnotationAttribute, AnnotationValue, AnnotationType from $table $where $order $limit";

	log2file("AnnotationList: Query: ".$query);

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
	
	return $annotationList;
}

function ConnectAnnotationUpdate ($ids, $annotation, $object) {
        global $XCOW_B;

	$annotationProperties = array();
	$annotationProperties = GetAnnotationProperties($object);

        $status = NULL;
	$updateString = "";
	$annotationString = "";

	$annotation = safeListInsert($annotation);

	# create update string
	foreach ($annotation as $attribute => $value) {
		if ($value != "") {
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
	}

	#
	# go
	#
	if ($updateString != "") {

 		$annotationString = implode(",",$ids);
                $where = "AnnotationId in ($annotationString)";
		$result = mysql_query("UPDATE {$annotationProperties['table']} SET $updateString WHERE $where", $XCOW_B['mysql_link']);

		if (! $result) {
       			echoMysqlError(mysql_errno(), mysql_error());
			$status = "500 Internal Error";
		}
                elseif (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
			# mysql geeft 0 affected rows als een update wordt gedaan met allemaal dezelfde waarden
                        # $status = "404 Not Found";
                }

	}

	return $status;
}

function ConnectAnnotationDelete ($ids, $object) {
        global $XCOW_B;

	$annotationProperties = array();
	$annotationProperties = GetAnnotationProperties($object);

        $status = NULL;
	$annotationString = "";

	$annotationString = implode(",",$ids);
        $where = "AnnotationId in ($annotationString)";
	$result = mysql_query("DELETE FROM {$annotationProperties['table']} WHERE $where", $XCOW_B['mysql_link']);
     	if (! $result) {
             	echoMysqlError(mysql_errno(), mysql_error());
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

        $result = mysql_query("SELECT AnnotationType FROM {$annotationProperties['table']} WHERE AnnotationAttribute = '$attribute' LIMIT 1", $XCOW_B['mysql_link']);
        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}



// ACCESS APP

function AccessAppInsert ($accessApp) {
        global $XCOW_B;

        $accessAppId = 0;

	$accessApp = safeListInsert($accessApp);

        $result = mysql_query("INSERT INTO AccessApp VALUES(NULL, '{$accessApp['name']}', '{$accessApp['key']}')", $XCOW_B['mysql_link']);

        if ($result) {
                $accessAppId = mysql_insert_id($XCOW_B['mysql_link']);
        }

        return $accessAppId;
}

function AccessAppList() {
        global $XCOW_B;

	$table = "AccessApp";
	$where = "";
	$order = "";
	$limit = "";
	$expand = 1;

	return AccessAppListWithValues($table, $where, $order, $limit, $expand);
}

function AccessAppListWithValues($table, $where, $order, $limit, $expand) {
        global $XCOW_B;

	$accessAppList = array();

	#
        # Construct QUERY
        #
	$query = "SELECT AccessApp.AccessAppId, AccessApp.AccessAppName, AccessApp.AccessAppKey from $table $where $order $limit";
 	log2file("AccessAppList: Query: ".$query);

	#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);
	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {
                 	$accessAppId = $result_row['AccessAppId'];

                	$accessAppList[$accessAppId] = array();
			$accessAppList[$accessAppId]['id'] = $result_row['AccessAppId'];
			$accessAppList[$accessAppId]['name'] = $result_row['AccessAppName'];
			$accessAppList[$accessAppId]['key'] = $result_row['AccessAppKey'];
       		}
        }

	return $accessAppList;

}

function AccessAppUpdate ($ids, $accessApp) {
        global $XCOW_B;

        $status = NULL;
	$updateString = "";
	$accessAppString = "";

	$accessApp = safeListInsert($accessApp);

	# create update string
	foreach ($accessApp as $attribute => $value) {
		if ($value != "") {
			if ($updateString != "") { $updateString .= ","; }

			switch ($attribute) {
				case "name":
					$updateString .= "AccessAppName='".$value."'";
					break;
				case "key":
					$updateString .= "AccessAppKey='".$value."'";
					break;
				default:
					$updateString .= $attribute."='".$value."'";
					break;
			}
		}
	}

	#
	# go
	#
	if ($updateString != "") {

 		$accessAppString = implode(",",$ids);
                $where = "AccessAppId in ($accessAppString)";
		$result = mysql_query("UPDATE AccessApp SET $updateString WHERE $where", $XCOW_B['mysql_link']);

		if (! $result) {
       			echoMysqlError(mysql_errno(), mysql_error());
			$status = "500 Internal Error";
		}
                elseif (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
			# mysql geeft 0 affected rows als een update wordt gedaan met allemaal dezelfde waarden
                        # $status = "404 Not Found";
                }

	}

	return $status;
}

function AccessAppDelete ($ids) {
        global $XCOW_B;

        $status = NULL;
	$accessAppString = "";

	$accessAppString = implode(",",$ids);
        $where = "AccessAppId in ($accessAppString)";
	$result = mysql_query("DELETE FROM AccessApp WHERE $where", $XCOW_B['mysql_link']);
     	if (! $result) {
             	echoMysqlError(mysql_errno(), mysql_error());
		$status = "500 Internal Error";
        }
        if (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
               	$status = "404 Not Found";
        }

	return $status;
}

//
// GEOcode
//
function ConnectGeoCodeListWithValues($table, $where, $order, $limit) {
        global $XCOW_B;

		$GeoList = array();

		#
        # Construct QUERY
        #
        $query = "SELECT GEOcities.GEOcitiesId, GEOcities.GEOcitiesCC, GEOcities.GEOcitiesCA, GEOcities.GEOcitiesName, GEOcities.GEOcitiesLat, GEOcities.GEOcitiesLon, GEOcities.GEOcitiesPrimary from $table $where $order $limit";
	
		log2file("GeoCodeList: Query: ".$query);

		#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

		if ($result) {
			while ($result_row = mysql_fetch_assoc($result)) {

				$geoId = $result_row['GEOcitiesId'];
		
				$GeoList[$geoId] = array();
				$GeoList[$geoId]['id'] = $geoId;
				$GeoList[$geoId]['cc'] = $result_row['GEOcitiesCC'];
				$GeoList[$geoId]['ca'] = $result_row['GEOcitiesCA'];
				$GeoList[$geoId]['name'] = $result_row['GEOcitiesName'];
				$GeoList[$geoId]['lat'] = $result_row['GEOcitiesLat'];
				$GeoList[$geoId]['lon'] = $result_row['GEOcitiesLon'];
				$GeoList[$geoId]['primary'] = $result_row['GEOcitiesPrimary'];
				}
			}

		return $GeoList;
}

?>
