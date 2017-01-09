<?php

// USER

function UserList() {
        global $XCOW_B;

	$table = "User";
	$where = "";
	$order = "";
	$limit = "";
	$expand = 1;

	return UserListWithValues($table, $where, $order, $limit, $expand);
}

function UserListWithValues($table, $where, $order, $limit, $expand) {
        global $XCOW_B;

		$userList = array();
		$userIdList = array();

		#
        # Construct QUERY
        #
        $query = "SELECT User.UserId, User.UserFirstName, User.UserLastName, User.UserLoginName, User.UserPageName, User.UserTimestamp, User.UserViews, User.Reference, User.AccessRuleId from $table $where $order $limit";
		#log2file("UserList: Query: ".$query);

		#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {
			// TODO: check AccessRule                	

			$userId = $result_row['UserId'];
			$userIdList[] = $userId;
	
			$userList[$userId] = array();
			$userList[$userId]['userId'] = $userId;
			$userList[$userId]['userFirstName'] = $result_row['UserFirstName'];
			$userList[$userId]['userLastName'] = $result_row['UserLastName'];
			$userList[$userId]['userLoginName'] = $result_row['UserLoginName'];
			$userList[$userId]['userPageName'] = $result_row['UserPageName'];
			$userList[$userId]['userTimestamp'] = $result_row['UserTimestamp'];
			$userList[$userId]['userViews'] = $result_row['UserViews'];
			$userList[$userId]['reference'] = $result_row['Reference'];
			$userList[$userId]['access'] = $result_row['AccessRuleId'];

			$userList[$userId]['message'] = array();
			$userList[$userId]['annotation'] = array();
			$userList[$userId]['profile'] = array();
			$userList[$userId]['group'] = array();
			$userList[$userId]['groupMember'] = array();
			$userList[$userId]['contact'] = array();
			$userList[$userId]['address'] = array();
			$userList[$userId]['organization'] = array();
			$userList[$userId]['publication'] = array();
			$userList[$userId]['experience'] = array();

			// disabled for Availability
			/*
			# too slow
			$userList[$userId]['message'] = UserActivityListWithValues("UserActivity", "WHERE UserActivity.UserId = $userId AND UserActivityTitle like 'motd'", "ORDER BY UserActivityTimestamp desc", "LIMIT 1", 0);
			# $userList[$userId]['annotation'] = UserAnnotationList('user', $userId);

			if ($expand > 0) {
				# too slow
				# $userList[$userId]['profile'] = UserProfileList('user', $userId);
				$userList[$userId]['group'] = UserGroupListWithValues("UserGroup", "WHERE UserGroup.UserId = $userId", '', '', 0);
				$userList[$userId]['groupMember'] = UserGroupListWithValues("UserGroup, UserInGroup", "WHERE UserInGroup.UserId = $userId AND UserInGroup.UserGroupId = UserGroup.UserGroupId", '', '', 0);
				$userList[$userId]['contact'] = UserSectionList('contact', $userId);
				$userList[$userId]['address'] = UserSectionList('address', $userId);
				$userList[$userId]['organization'] = UserSectionList('organization', $userId);
			}
			if ($expand > 1) {
				$userList[$userId]['publication'] = UserSectionList('publication', $userId);
				$userList[$userId]['experience'] = UserSectionList('experience', $userId);
			}
			*/
        	}
        	if (count($userIdList) > 0 ) {
				$annotationList = UserAnnotationListAll('user', $userIdList);
				foreach ($userList as $key => $val) {
					$userList[$key]['annotation'] = $annotationList[$key];
				}
			}
         	if ($expand > 0 && count($userIdList) > 0 ) {
				$profileList = UserProfileListAll('user', $userIdList);
				foreach ($userList as $key => $val) {
					$userList[$key]['profile'] = $profileList[$key];
				}
			}
       	
        }
	else {
		catchMysqlError("UserListWithValues", $XCOW_B['mysql_link']);
	}

	return $userList;
}

// SECTION

function GetSectionProperties($object) {

	$sectionProperties = array();

	switch ($object) {
		case "address":
			$sectionProperties['table'] = "UserAddress";
			$sectionProperties['reference'] = "UserId";
			$sectionProperties['profile'] = "address";
			$sectionProperties['annotation'] = "address";
			$sectionProperties['access'] = "1";
			break;
		case "contact":
			$sectionProperties['table'] = "UserContact";
			$sectionProperties['reference'] = "UserId";
			$sectionProperties['profile'] = "contact";
			$sectionProperties['annotation'] = "contact";
			$sectionProperties['access'] = "1";
			break;
		case "organization":
			$sectionProperties['table'] = "UserOrganization";
			$sectionProperties['reference'] = "UserId";
			$sectionProperties['profile'] = "organization";
			$sectionProperties['annotation'] = "organization";
			$sectionProperties['access'] = "1";
			break;
		case "publication":
			$sectionProperties['table'] = "UserPublication";
			$sectionProperties['reference'] = "UserId";
			$sectionProperties['profile'] = "publication";
			$sectionProperties['annotation'] = "publication";
			$sectionProperties['access'] = "1";
			break;
		case "experience":
			$sectionProperties['table'] = "UserExperience";
			$sectionProperties['reference'] = "UserId";
			$sectionProperties['profile'] = "experience";
			$sectionProperties['annotation'] = "experience";
			$sectionProperties['access'] = "1";
			break;
		default:
			break;
	}

	return $sectionProperties;
}

function UserSectionInsert ($section, $object, $object_id, $access) {
        global $XCOW_B;

        $sectionId = 0;

	$sectionProperties = array();
	$sectionProperties = GetSectionProperties($object);

	$section = safeListInsert($section);

	// TODO: check if object_id exists!
	// $exists = existsUser($this->object_id);

	$result = NULL;
	if ($sectionProperties['access'] == 0) {
		$result = mysql_query("INSERT INTO {$sectionProperties['table']} VALUES(NULL, '{$section['type']}', '{$section['name']}', $object_id)", $XCOW_B['mysql_link']);
	}
	else {
		$result = mysql_query("INSERT INTO {$sectionProperties['table']} VALUES(NULL, '{$section['type']}', '{$section['name']}', $object_id, '$access')", $XCOW_B['mysql_link']);
	}

        if ($result) {
                $sectionId = mysql_insert_id($XCOW_B['mysql_link']);
        }
	else {
		catchMysqlError("UserSectionInsert", $XCOW_B['mysql_link']);
	}

        return $sectionId;
}

function UserSectionList($object, $object_id) {
        global $XCOW_B;

	$sectionProperties = array();
	$sectionProperties = GetSectionProperties($object);

	$table = "{$sectionProperties['table']}";
	$where = "WHERE {$sectionProperties['reference']} = $object_id";
	$order = "";
	$limit = "";
	$expand = 1;

	return UserSectionListWithValues($table, $where, $order, $limit, $sectionProperties, $expand);
}

function UserSectionListWithValues($table, $where, $order, $limit, $sectionProperties, $expand) {
        global $XCOW_B;

	$sectionList = array();
	$sectionIdList = array();

	if ($sectionProperties['access'] == 0) {
	        $query = "SELECT SectionId, SectionType, SectionName, UserId from $table $where $order $limit";
	}
	else {
	        $query = "SELECT SectionId, SectionType, SectionName, UserId, AccessRuleId from $table $where $order $limit";
	}

	#log2file("SectionList: Query: ".$query);

	#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {
			// TODO: check AccessRule                	

			$sectionId = $result_row['SectionId'];
			$sectionIdList[] = $sectionId;
	
			$sectionList[$sectionId] = array();
			$sectionList[$sectionId]['id'] = $sectionId;
			$sectionList[$sectionId]['type'] = $result_row['SectionType'];
			$sectionList[$sectionId]['name'] = $result_row['SectionName'];
			$sectionList[$sectionId]['userId'] = $result_row['UserId'];

			# too slow
			$sectionList[$sectionId]['annotation'] = array();
			$sectionList[$sectionId]['profile'] = array();
			# if ($expand) {
			#	 $sectionList[$sectionId]['annotation'] = UserAnnotationList($sectionProperties['annotation'], $sectionId);
			#	 $sectionList[$sectionId]['profile'] = UserProfileList($sectionProperties['profile'], $sectionId);
			# }
        	}
        	if ($expand && count($sectionIdList) > 0 ) {
				$annotationList = UserAnnotationListAll($sectionProperties['annotation'], $sectionIdList);
				foreach ($sectionList as $key => $val) {
					$sectionList[$key]['annotation'] = $annotationList[$key];
				}
				$profileList = UserProfileListAll($sectionProperties['profile'], $sectionIdList);
				foreach ($sectionList as $key => $val) {
					$sectionList[$key]['profile'] = $profileList[$key];
				}
			}

        }
	else {
		catchMysqlError("UserSectionListWithValues", $XCOW_B['mysql_link']);
	}
	
	return $sectionList;
}

function UserSectionUpdate ($ids, $section, $object) {
        global $XCOW_B;

	$sectionProperties = array();
	$sectionProperties = GetSectionProperties($object);

        $status = NULL;
	$updateString = "";
	$sectionString = "";

	$section = safeListInsert($section);

	# create update string
	foreach ($section as $attribute => $value) {
		if ($updateString != "") { $updateString .= ","; }

		switch ($attribute) {
			case "name":
				$updateString .= "SectionName='".$value."'";
				break;
			case "type":
				$updateString .= "SectionType='".$value."'";
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

 		$sectionString = implode(",",$ids);
                $where = "SectionId in ($sectionString)";
		$result = mysql_query("UPDATE {$sectionProperties['table']} SET $updateString WHERE $where", $XCOW_B['mysql_link']);

		if (! $result) {
       			catchMysqlError("UserSectionUpdate", $XCOW_B['mysql_link']);
			$status = "500 Internal Error";
		}
                elseif (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
			# mysql geeft 0 affected rows als een update wordt gedaan met allemaal dezelfde waarden
                        # $status = "404 Not Found";
                }

	}

	return $status;
}

function UserSectionDelete ($ids, $object) {
        global $XCOW_B;

	$sectionProperties = array();
	$sectionProperties = GetSectionProperties($object);

        $status = NULL;
	$sectionString = "";

	$sectionString = implode(",",$ids);
        $where = "SectionId in ($sectionString)";
	$result = mysql_query("DELETE FROM {$sectionProperties['table']} WHERE $where", $XCOW_B['mysql_link']);
     	if (! $result) {
             	catchMysqlError("UserSectionDelete", $XCOW_B['mysql_link']);
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
		case "user":
			$profileProperties['table'] = "UserProfile";
			$profileProperties['reference'] = "UserId";
			$profileProperties['section'] = "";
			$profileProperties['annotation'] = "userProfile";
			$profileProperties['access'] = "1";
			break;
		case "address":
			$profileProperties['table'] = "UserAddressProfile";
			$profileProperties['reference'] = "SectionId";
			$profileProperties['section'] = "address";
			$profileProperties['annotation'] = "addressProfile";
			$profileProperties['access'] = "1";
			break;
		case "contact":
			$profileProperties['table'] = "UserContactProfile";
			$profileProperties['reference'] = "SectionId";
			$profileProperties['section'] = "contact";
			$profileProperties['annotation'] = "contactProfile";
			$profileProperties['access'] = "1";
			break;
		case "organization":
			$profileProperties['table'] = "UserOrganizationProfile";
			$profileProperties['reference'] = "SectionId";
			$profileProperties['section'] = "organization";
			$profileProperties['annotation'] = "organizationProfile";
			$profileProperties['access'] = "1";
			break;
		case "publication":
			$profileProperties['table'] = "UserPublicationProfile";
			$profileProperties['reference'] = "SectionId";
			$profileProperties['section'] = "publication";
			$profileProperties['annotation'] = "publicationProfile";
			$profileProperties['access'] = "1";
			break;
		case "experience":
			$profileProperties['table'] = "UserExperienceProfile";
			$profileProperties['reference'] = "SectionId";
			$profileProperties['section'] = "experience";
			$profileProperties['annotation'] = "experienceProfile";
			$profileProperties['access'] = "1";
			break;
		case "stats":
			$profileProperties['table'] = "StatsProfile";
			$profileProperties['reference'] = "StatsId";
			$profileProperties['section'] = "";
			$profileProperties['annotation'] = "statsProfile";
			$profileProperties['access'] = "0";
			break;
		default:
			break;
	}

	return $profileProperties;
}

function UserProfileGetUserId ($object, $profile_id) {
        global $XCOW_B;

        $userId = 0;

	$profileProperties = array();
	$sectionProperties = array();

	$profileProperties = GetProfileProperties($object);

	// TODO: check if object_id exists!
	// $exists = existsUser($this->object_id);

	$result = NULL;
	if ($profileProperties['reference'] == "UserId") {
		$result = mysql_query("SELECT UserId from {$profileProperties['table']} WHERE ProfileId = {$profile_id}", $XCOW_B['mysql_link']);
	}
	elseif ($profileProperties['reference'] == "SectionId") {
		$sectionProperties = GetSectionProperties($profileProperties['section']);

		if ($sectionProperties['reference'] == "UserId") {
			$result = mysql_query("SELECT UserId from {$sectionProperties['table']}, {$profileProperties['table']} WHERE {$sectionProperties['table']}.SectionId = {$profileProperties['table']}.SectionId AND {$profileProperties['table']}.ProfileId = {$profile_id}", $XCOW_B['mysql_link']);
		}
	}

        if ($result) {
		$result_row = mysql_fetch_row($result);
		$userId = $result_row[0];
        }
	else {
		catchMysqlError("UserProfileGetUserId", $XCOW_B['mysql_link']);
	}

        return $userId;
}

function UserProfileInsert ($profile, $object, $object_id, $access) {
        global $XCOW_B;

        $profileId = 0;

	$profileProperties = array();
	$profileProperties = GetProfileProperties($object);

	$profile = safeListInsert($profile);

	// TODO: check if object_id exists!
	// $exists = existsUser($this->object_id);

	$result = NULL;
	if ($profileProperties['access'] == 0) {
		$result = mysql_query("INSERT INTO {$profileProperties['table']} VALUES(NULL, '{$profile['group']}', '{$profile['name']}', $object_id)", $XCOW_B['mysql_link']);
	}
	else {
		$result = mysql_query("INSERT INTO {$profileProperties['table']} VALUES(NULL, '{$profile['group']}', '{$profile['name']}', $object_id, '$access')", $XCOW_B['mysql_link']);
	}

        if ($result) {
                $profileId = mysql_insert_id($XCOW_B['mysql_link']);
		#TODO
		#$activity = array();
		#$activity['title'] = "New profile";
		#$activity['description'] = "New {$profile['group']} is {$profile['name']}.";
		#$activity['priority'] = 1;
		#$activity['url'] = "":
		#UserActivityInsert($activity, USER_ID, $access);
        }
	else {
		catchMysqlError("UserProfileInsert", $XCOW_B['mysql_link']);
	}

        return $profileId;
}

function UserProfileList($object, $object_id) {
        global $XCOW_B;

	$profileProperties = array();
	$profileProperties = GetProfileProperties($object);

	$table = "{$profileProperties['table']}";
	$where = "WHERE {$profileProperties['reference']} = $object_id";
	$order = "";
	$limit = "";
	$expand = 1;

	return UserProfileListWithValues($table, $where, $order, $limit, $profileProperties, $expand);
}

function UserProfileListWithValues($table, $where, $order, $limit, $profileProperties, $expand) {
        global $XCOW_B;

	$profileList = array();
	$profileIdList  = array();

	if ($profileProperties['access'] == 0) {
	        $query = "SELECT ProfileId, ProfileGroup, ProfileName from $table $where $order $limit";
	}
	else {
	        $query = "SELECT ProfileId, ProfileGroup, ProfileName, AccessRuleId from $table $where $order $limit";
	}

	#log2file("ProfileList: Query: ".$query);

	#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {
			// TODO: check AccessRule                	

			$profileId = $result_row['ProfileId'];
				$profileIdList[] = $profileId;
	
		       	$profileList[$profileId] = array();
		       	$profileList[$profileId]['id'] = $profileId;
		       	$profileList[$profileId]['name'] = $result_row['ProfileName'];
		       	$profileList[$profileId]['group'] = $result_row['ProfileGroup'];
				#$profileList[$profileId]['extReference'] = $profileProperties['reference'];
                #$profileList[$profileId]['extId'] = $this->object_id;

			# too slow
			$profileList[$profileId]['annotation'] = array();
			#if ($expand) {
			#	$profileList[$profileId]['annotation'] = UserAnnotationList($profileProperties['annotation'], $profileId);
			#}
        	}
        	if ($expand && count($profileIdList) > 0 ) {
				$annotationList = UserAnnotationListAll($profileProperties['annotation'], $profileIdList);
				foreach ($profileList as $key => $val) {
					$profileList[$key]['annotation'] = $annotationList[$key];
				}
			}
        }
	else {
		catchMysqlError("UserProfileListWithValues", $XCOW_B['mysql_link']);
	}
	
	return $profileList;
}

function UserProfileListAll($object, $object_id_list) {
        global $XCOW_B;

	$object_id_string = "(".implode(",", $object_id_list).")";
	$profileProperties = array();
	$profileProperties = GetProfileProperties($object);

	$table = "{$profileProperties['table']}";
	$where = "WHERE {$profileProperties['reference']} in $object_id_string";
	$order = "";
	$limit = "";
	$expand = 1;

	return UserProfileListWithValuesAll($table, $where, $order, $limit, $profileProperties, $expand, $object_id_list);
}

function UserProfileListWithValuesAll($table, $where, $order, $limit, $profileProperties, $expand, $object_id_list) {
        global $XCOW_B;

	$profileList = array();
	foreach ($object_id_list as $obj) {
		$profileList[$obj] = array();
	}

	$profileIdList  = array();
	$ref = $profileProperties['reference'];

	if ($profileProperties['access'] == 0) {
	        $query = "SELECT ProfileId, ProfileGroup, ProfileName, $ref from $table $where $order $limit";
	}
	else {
	        $query = "SELECT ProfileId, ProfileGroup, ProfileName, AccessRuleId, $ref from $table $where $order $limit";
	}

	#log2file("ProfileList: Query: ".$query);

	#
	# SELECT
	#
	$result = mysql_query("$query", $XCOW_B['mysql_link']);

	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {
			// TODO: check AccessRule                	

			$profileId = $result_row['ProfileId'];
			$profileIdList[] = $profileId;

			$refId = $result_row[$ref];

			$profileList[$refId][$profileId] = array();
			$profileList[$refId][$profileId]['id'] = $profileId;
			$profileList[$refId][$profileId]['name'] = $result_row['ProfileName'];
			$profileList[$refId][$profileId]['group'] = $result_row['ProfileGroup'];
			#$profileList[$profileId]['extReference'] = $profileProperties['reference'];
			#$profileList[$profileId]['extId'] = $this->object_id;

			# too slow
			$profileList[$refId][$profileId]['annotation'] = array();
			#if ($expand) {
			#	$profileList[$profileId]['annotation'] = UserAnnotationList($profileProperties['annotation'], $profileId);
			#}
        	}
        	if ($expand && count($profileIdList) > 0 ) {
				$annotationList = UserAnnotationListAll($profileProperties['annotation'], $profileIdList);
				foreach ($profileList as $key => $val) {
					foreach ($profileList[$key] as $key2 => $val2) {
						$profileList[$key][$key2]['annotation'] = $annotationList[$key2];
					}
				}
			}
        }
	else {
		catchMysqlError("UserProfileListWithValuesAll", $XCOW_B['mysql_link']);
	}
	
	return $profileList;
}

function UserProfileUpdate ($ids, $profile, $object) {
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
       			catchMysqlError("UserProfileUpdate", $XCOW_B['mysql_link']);
			$status = "500 Internal Error";
		}
                elseif (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
			# mysql geeft 0 affected rows als een update wordt gedaan met allemaal dezelfde waarden
                        # $status = "404 Not Found";
                }

	}

	return $status;
}

function UserProfileDelete ($ids, $object) {
        global $XCOW_B;

	$profileProperties = array();
	$profileProperties = GetProfileProperties($object);

        $status = NULL;
	$profileString = "";

	$profileString = implode(",",$ids);
        $where = "ProfileId in ($profileString)";
	$result = mysql_query("DELETE FROM {$profileProperties['table']} WHERE $where", $XCOW_B['mysql_link']);
     	if (! $result) {
             	catchMysqlError("UserProfileDelete", $XCOW_B['mysql_link']);
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
		case "user":
			$annotationProperties['table'] = "UserAnnotation";
			$annotationProperties['reference'] = "UserId";
			$annotationProperties['section'] = "";
			$annotationProperties['profile'] = "";
			$annotationProperties['access'] = "1";
			break;
		case "userProfile":
			$annotationProperties['table'] = "UserProfileAnnotation";
			$annotationProperties['reference'] = "ProfileId";
			$annotationProperties['section'] = "";
			$annotationProperties['profile'] = "user";
			$annotationProperties['access'] = "0";
			break;
		case "address":
			$annotationProperties['table'] = "UserAddressAnnotation";
			$annotationProperties['reference'] = "SectionId";
			$annotationProperties['section'] = "address";
			$annotationProperties['profile'] = "";
			$annotationProperties['access'] = "1";
			break;
		case "addressProfile":
			$annotationProperties['table'] = "UserAddressProfileAnnotation";
			$annotationProperties['reference'] = "ProfileId";
			$annotationProperties['section'] = "address";
			$annotationProperties['profile'] = "address";
			$annotationProperties['access'] = "0";
			break;
		case "contact":
			$annotationProperties['table'] = "UserContactAnnotation";
			$annotationProperties['reference'] = "SectionId";
			$annotationProperties['section'] = "contact";
			$annotationProperties['profile'] = "";
			$annotationProperties['access'] = "1";
			break;
		case "contactProfile":
			$annotationProperties['table'] = "UserContactProfileAnnotation";
			$annotationProperties['reference'] = "ProfileId";
			$annotationProperties['section'] = "contact";
			$annotationProperties['profile'] = "contact";
			$annotationProperties['access'] = "0";
			break;
		case "organization":
			$annotationProperties['table'] = "UserOrganizationAnnotation";
			$annotationProperties['reference'] = "SectionId";
			$annotationProperties['section'] = "organization";
			$annotationProperties['profile'] = "";
			$annotationProperties['access'] = "1";
			break;
		case "organizationProfile":
			$annotationProperties['table'] = "UserOrganizationProfileAnnotation";
			$annotationProperties['reference'] = "ProfileId";
			$annotationProperties['section'] = "organization";
			$annotationProperties['profile'] = "organization";
			$annotationProperties['access'] = "0";
			break;
		case "publication":
			$annotationProperties['table'] = "UserPublicationAnnotation";
			$annotationProperties['reference'] = "SectionId";
			$annotationProperties['section'] = "publication";
			$annotationProperties['profile'] = "";
			$annotationProperties['access'] = "1";
			break;
		case "publicationProfile":
			$annotationProperties['table'] = "UserPublicationProfileAnnotation";
			$annotationProperties['reference'] = "ProfileId";
			$annotationProperties['section'] = "publication";
			$annotationProperties['profile'] = "publication";
			$annotationProperties['access'] = "0";
			break;
		case "experience":
			$annotationProperties['table'] = "UserExperienceAnnotation";
			$annotationProperties['reference'] = "SectionId";
			$annotationProperties['section'] = "experience";
			$annotationProperties['profile'] = "";
			$annotationProperties['access'] = "1";
			break;
		case "experienceProfile":
			$annotationProperties['table'] = "UserExperienceProfileAnnotation";
			$annotationProperties['reference'] = "ProfileId";
			$annotationProperties['section'] = "experience";
			$annotationProperties['profile'] = "experience";
			$annotationProperties['access'] = "0";
			break;
		case "stats":
			$annotationProperties['table'] = "StatsAnnotation";
			$annotationProperties['reference'] = "StatsId";
			$annotationProperties['section'] = "";
			$annotationProperties['profile'] = "";
			$annotationProperties['access'] = "0";
			break;
		case "statsProfile":
			$annotationProperties['table'] = "StatsProfileAnnotation";
			$annotationProperties['reference'] = "ProfileId";
			$annotationProperties['section'] = "";
			$annotationProperties['profile'] = "stats";
			$annotationProperties['access'] = "0";
			break;
		default:
			break;
	}

	return $annotationProperties;
}

function UserAnnotationGetUserId ($object, $annotation_id) {
        global $XCOW_B;

        $userId = 0;

	$annotationProperties = array();
	$profileProperties = array();
	$sectionProperties = array();

	$annotationProperties = GetAnnotationProperties($object);

	// TODO: check if object_id exists!
	// $exists = existsUser($this->object_id);

	$result = NULL;
	if ($annotationProperties['reference'] == "UserId") {
		$result = mysql_query("SELECT UserId from {$annotationProperties['table']} WHERE AnnotationId = {$annotation_id}", $XCOW_B['mysql_link']);
	}
	elseif ($annotationProperties['reference'] == "SectionId") {
		$sectionProperties = GetSectionProperties($annotationProperties['section']);

		if ($sectionProperties['reference'] == "UserId") {
			$result = mysql_query("SELECT UserId from {$sectionProperties['table']}, {$annotationProperties['table']} WHERE {$sectionProperties['table']}.SectionId = {$annotationProperties['table']}.SectionId AND {$annotationProperties['table']}.AnnotationId = {$annotation_id}", $XCOW_B['mysql_link']);
		}
	}
	elseif ($annotationProperties['reference'] == "ProfileId") {
		$profileProperties = GetProfileProperties($annotationProperties['profile']);

		if ($profileProperties['reference'] == "UserId") {
			$result = mysql_query("SELECT UserId from {$profileProperties['table']}, {$annotationProperties['table']} WHERE {$profileProperties['table']}.ProfileId = {$annotationProperties['table']}.ProfileId AND {$annotationProperties['table']}.AnnotationId = {$annotation_id}", $XCOW_B['mysql_link']);
		}
		elseif ($profileProperties['reference'] == "SectionId") {
			$sectionProperties = GetSectionProperties($annotationProperties['section']);
			if ($sectionProperties['reference'] == "UserId") {
				$result = mysql_query("SELECT UserId from {$sectionProperties['table']}, {$profileProperties['table']}, {$annotationProperties['table']} WHERE {$sectionProperties['table']}.SectionId = {$profileProperties['table']}.SectionId AND {$profileProperties['table']}.ProfileId = {$annotationProperties['table']}.ProfileId AND {$annotationProperties['table']}.AnnotationId = {$annotation_id}", $XCOW_B['mysql_link']);
			}
		}
	}

        if ($result) {
		$result_row = mysql_fetch_row($result);
		$userId = $result_row[0];
        }
	else {
		catchMysqlError("UserAnnotationGetUserId", $XCOW_B['mysql_link']);
	}

        return $userId;
}

function UserAnnotationInsert ($annotation, $object, $object_id, $access) {
        global $XCOW_B;

        $annotationId = 0;

	$annotationProperties = array();
	$annotationProperties = GetAnnotationProperties($object);

	$annotation = safeListInsert($annotation);

	// TODO: check if object_id exists!
	// $exists = existsUser($this->object_id);

	$result = NULL;
	if ($annotationProperties['access'] == 0) {
		$result = mysql_query("INSERT INTO {$annotationProperties['table']} VALUES(NULL, '{$annotation['name']}', '{$annotation['value']}', '{$annotation['type']}', $object_id)", $XCOW_B['mysql_link']);
	}
	else {
		$result = mysql_query("INSERT INTO {$annotationProperties['table']} VALUES(NULL, '{$annotation['name']}', '{$annotation['value']}', '{$annotation['type']}', $object_id, '$access')", $XCOW_B['mysql_link']);
	}

        if ($result) {
                $annotationId = mysql_insert_id($XCOW_B['mysql_link']);
        }
	else {
		catchMysqlError("UserAnnotationInsert", $XCOW_B['mysql_link']);
	}

        return $annotationId;
}

function UserAnnotationList($object, $object_id) {
        global $XCOW_B;

	$annotationProperties = array();
	$annotationProperties = GetAnnotationProperties($object);

	$table = "{$annotationProperties['table']}";
	$where = "WHERE {$annotationProperties['reference']} = $object_id";
	$order = "";
	$limit = "";
	$expand = 1;

	return UserAnnotationListWithValues($table, $where, $order, $limit, $annotationProperties, $expand);
}

function UserAnnotationListWithValues($table, $where, $order, $limit, $annotationProperties, $expand) {
        global $XCOW_B;

	$annotationList = array();

	if ($annotationProperties['access'] == 0) {
	        $query = "SELECT AnnotationId, AnnotationAttribute, AnnotationValue, AnnotationType from $table $where $order $limit";
	}
	else {
	        $query = "SELECT AnnotationId, AnnotationAttribute, AnnotationValue, AnnotationType, AccessRuleId from $table $where $order $limit";
	}

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
		catchMysqlError("UserAnnotationListWithValues", $XCOW_B['mysql_link']);
	}
	
	return $annotationList;
}

function UserAnnotationListAll($object, $object_id_list) {
        global $XCOW_B;

	$object_id_string = "(".implode(",", $object_id_list).")";
	$annotationProperties = array();
	$annotationProperties = GetAnnotationProperties($object);

	$table = "{$annotationProperties['table']}";
	$where = "WHERE {$annotationProperties['reference']} in $object_id_string";
	$order = "";
	$limit = "";
	$expand = 1;

	return UserAnnotationListWithValuesAll($table, $where, $order, $limit, $annotationProperties, $expand, $object_id_list);
}

function UserAnnotationListWithValuesAll($table, $where, $order, $limit, $annotationProperties, $expand, $object_id_list) {
        global $XCOW_B;

	# always return an empty array
	$annotationList = array();
	foreach ($object_id_list as $obj) {
		$annotationList[$obj] = array();
	}

	$ref = $annotationProperties['reference'];

	if ($annotationProperties['access'] == 0) {
	        $query = "SELECT AnnotationId, AnnotationAttribute, AnnotationValue, AnnotationType, $ref from $table $where $order $limit";
	}
	else {
	        $query = "SELECT AnnotationId, AnnotationAttribute, AnnotationValue, AnnotationType, AccessRuleId, $ref from $table $where $order $limit";
	}

	#log2file("AnnotationList: Query: ".$query);

	#
	# SELECT
	#
	$result = mysql_query("$query", $XCOW_B['mysql_link']);

	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {
			// TODO: check AccessRule                	

			$refId = $result_row[$ref];
			
			$annotationId = $result_row['AnnotationId'];
					
			$annotationList[$refId][$annotationId] = array();
			$annotationList[$refId][$annotationId]['id'] = $annotationId;
			$annotationList[$refId][$annotationId]['name'] = $result_row['AnnotationAttribute'];
			$annotationList[$refId][$annotationId]['value'] = $result_row['AnnotationValue'];
			$annotationList[$refId][$annotationId]['type'] = $result_row['AnnotationType'];
			#$annotationList[$annotationId]['reference'] = $annotationProperties['reference'];
			#$annotationList[$annotationId]['extId'] = $this->object_id;
		}
	}
	else {
		catchMysqlError("UserAnnotationListWithValuesAll", $XCOW_B['mysql_link']);
	}
	
	return $annotationList;
}

function UserAnnotationUpdate ($ids, $annotation, $object) {
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
       			catchMysqlError("UserAnnotationUpdate", $XCOW_B['mysql_link']);
			$status = "500 Internal Error";
		}
                elseif (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
			# mysql geeft 0 affected rows als een update wordt gedaan met allemaal dezelfde waarden
                        # $status = "404 Not Found";
                }

	}

	return $status;
}

function UserAnnotationDelete ($ids, $object) {
        global $XCOW_B;

	$annotationProperties = array();
	$annotationProperties = GetAnnotationProperties($object);

        $status = NULL;
	$annotationString = "";

	$annotationString = implode(",",$ids);
        $where = "AnnotationId in ($annotationString)";
	$result = mysql_query("DELETE FROM {$annotationProperties['table']} WHERE $where", $XCOW_B['mysql_link']);
     	if (! $result) {
             	catchMysqlError("UserAnnotationDelete", $XCOW_B['mysql_link']);
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

// GROUP

function UserGroupList($userId) {
        global $XCOW_B;

	$table = "UserGroup";
	$where = "WHERE UserId = $userId";
	$order = "";
	$limit = "";
	$expand = 1;

	return UserGroupListWithValues($table, $where, $order, $limit, $expand);
}

function UserGroupListWithValues($table, $where, $order, $limit, $expand) {
        global $XCOW_B;

	$groupList = array();

	#
        # Construct QUERY
        #
        $query = "SELECT UserGroup.UserGroupId, UserGroup.UserGroupName, UserGroup.UserGroupDescription, UserGroup.UserGroupType, UserGroup.UserGroupTimestamp, UserGroup.UserId, UserGroup.AccessGroupId from $table $where $order $limit";
 	#log2file("GroupList: Query: ".$query);

	#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {
			// TODO: check AccessRule                	

                 	$groupId = $result_row['UserGroupId'];

                	$groupList[$groupId] = array();
			$groupList[$groupId]['id'] = $result_row['UserGroupId'];
			$groupList[$groupId]['name'] = $result_row['UserGroupName'];
			$groupList[$groupId]['description'] = $result_row['UserGroupDescription'];
			$groupList[$groupId]['type'] = $result_row['UserGroupType'];
			$groupList[$groupId]['timestamp'] = $result_row['UserGroupTimestamp'];

			$groupList[$groupId]['accessGroup'] = array();
			$groupList[$groupId]['user'] = array();
			if ($expand) {
				$groupList[$groupId]['accessGroup'] = AccessGroupListWithValues('AccessGroup', "WHERE AccessGroupId = {$result_row['AccessGroupId']}", '', '', 0);
				$groupList[$groupId]['user'] = UserListWithValues("User, UserInGroup", "WHERE UserInGroup.UserGroupId = {$result_row['UserGroupId']} AND UserInGroup.UserId = User.UserId", '', '', 0);

			}
       		}
        }
	else {
		catchMysqlError("UserGroupListWithValues", $XCOW_B['mysql_link']);
	}

	return $groupList;

}

function UserInGroupCount($groupId, $where) {
        global $XCOW_B;

	$count = 0;

	#
        # Construct QUERY
        #
	if ($where != '') {
	        $query = "SELECT count(UserInGroup.UserId) FROM UserInGroup WHERE $where";
	}
	else {
	        $query = "SELECT count(UserInGroup.UserId) FROM UserInGroup WHERE UserInGroup.UserGroupId = $groupId";
	}

 	#log2file("GroupList: Query: ".$query);

	#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

	if ($result) {
                if (mysql_num_rows($result) > 0 ) {
                        $result_row = mysql_fetch_row($result);
        		$count = $result_row[0];
                }
        }
	else {
		catchMysqlError("UserInGroupCount", $XCOW_B['mysql_link']);
	}

	return $count;

}

?>
