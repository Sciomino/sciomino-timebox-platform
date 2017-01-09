<?php

// USER

function UserInsert ($user, $reference, $access) {
        global $XCOW_B;

        $userId = 0;
	$timestamp = time();

	$user = safeListInsert($user);

        $result = mysql_query("INSERT INTO User VALUES(NULL, '{$user['firstName']}', '{$user['lastName']}', '{$user['loginName']}', '{$user['pageName']}', '$timestamp', 0, '$reference', $access)", $XCOW_B['mysql_link']);

        if ($result) {
                $userId = mysql_insert_id($XCOW_B['mysql_link']);
        }
	else {
		catchMysqlError("UserInsert", $XCOW_B['mysql_link']);
	}

        return $userId;
}

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

function UserUpdate ($ids, $user) {
        global $XCOW_B;

        $status = NULL;
	$updateString = "";
	$userString = "";

	$user = safeListInsert($user);

	# create update string
	foreach ($user as $attribute => $value) {
		if ($updateString != "") { $updateString .= ","; }

		switch ($attribute) {
			case "firstName":
				$updateString .= "UserFirstName='".$value."'";
				break;
			case "lastName":
				$updateString .= "UserLastName"."='".$value."'";
				break;
			case "loginName":
				$updateString .= "UserLoginName"."='".$value."'";
				break;
			case "pageName":
				$updateString .= "UserPageName"."='".$value."'";
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

 		$userString = implode(",",$ids);
                $where = "UserId in ($userString)";
		$result = mysql_query("UPDATE User SET $updateString WHERE $where", $XCOW_B['mysql_link']);

		if (! $result) {
       			catchMysqlError("UserUpdate", $XCOW_B['mysql_link']);
			$status = "500 Internal Error";
		}
                elseif (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
			# mysql geeft 0 affected rows als een update wordt gedaan met allemaal dezelfde waarden
                        # $status = "404 Not Found";
                }

	}

	return $status;
}

function UserDelete ($ids) {
        global $XCOW_B;

        $status = NULL;
	$userString = "";

	$userString = implode(",",$ids);
        $where = "UserId in ($userString)";
	$result = mysql_query("DELETE FROM User WHERE $where", $XCOW_B['mysql_link']);
     	if (! $result) {
             	catchMysqlError("UserDelete", $XCOW_B['mysql_link']);
		$status = "500 Internal Error";
        }
        if (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
               	$status = "404 Not Found";
        }

	return $status;
}

// ACTIVITY

function UserActivityInsert ($activity, $userId, $access) {
        global $XCOW_B;

        $activityId = 0;
	$timestamp = time();

	$activity = safeListInsert($activity);

        $result = mysql_query("INSERT INTO UserActivity VALUES(NULL, '$timestamp', '{$activity['title']}', '{$activity['description']}', {$activity['priority']}, '{$activity['url']}', $userId, $access)", $XCOW_B['mysql_link']);

        if ($result) {
                $activityId = mysql_insert_id($XCOW_B['mysql_link']);
        }
	else {
		catchMysqlError("UserActivityInsert", $XCOW_B['mysql_link']);
	}

        return $activityId;
}

function UserActivityList($userId) {
        global $XCOW_B;

	$table = "UserActivity";
	$where = "WHERE UserId = $userId";
	$order = "";
	$limit = "";
	$expand = 1;

	return UserActivityListWithValues($table, $where, $order, $limit, $expand);
}

function UserActivityListWithValues($table, $where, $order, $limit, $expand) {
        global $XCOW_B;

	$activityList = array();
	$userIdList = array();

	#
        # Construct QUERY
        #
        $query = "SELECT UserActivity.UserActivityId, UserActivity.UserActivityTimestamp, UserActivity.UserActivityTitle, UserActivity.UserActivityDescription, UserActivity.UserActivityPriority, UserActivity.UserActivityUrl, UserActivity.UserId, UserActivity.AccessRuleId from $table $where $order $limit";
 	#log2file("ActivityList: Query: ".$query);

	#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {
			// TODO: check AccessRule                	

            $activityId = $result_row['UserActivityId'];
			$userId = $result_row['UserId'];
			$userIdList[] = $userId;

            $activityList[$activityId] = array();
			$activityList[$activityId]['id'] = $result_row['UserActivityId'];
			$activityList[$activityId]['timestamp'] = $result_row['UserActivityTimestamp'];
			$activityList[$activityId]['title'] = $result_row['UserActivityTitle'];
			$activityList[$activityId]['description'] = $result_row['UserActivityDescription'];
			$activityList[$activityId]['priority'] = $result_row['UserActivityPriority'];
			$activityList[$activityId]['url'] = $result_row['UserActivityUrl'];
			$activityList[$activityId]['userId'] = $result_row['UserId'];
			# too slow
			#$activityList[$activityId]['user'] = array();
			#$activityList[$activityId]['user'] = UserListWithValues("User", "WHERE User.UserId = $userId", '', '', 0);

		}
		if ($expand) {
			$userIdList = array_unique($userIdList);
			if (count($userIdList) > 0) {
				$userIdString = "(".implode(",", $userIdList).")";
				$userList = UserListWithValues("User", "WHERE User.UserId in $userIdString", '', '', 0);
				foreach ($activityList as $key => $val) {
					$thisUserId = $activityList[$key]['userId'];
					$activityList[$key]['user'] = array($thisUserId => $userList[$thisUserId]);
				}
			}
		}
	}
	else {
		catchMysqlError("UserActivityListWithValues", $XCOW_B['mysql_link']);
	}

	return $activityList;

}

function UserActivityUpdate ($ids, $activity) {
        global $XCOW_B;

        $status = NULL;
	$updateString = "";
	$activityString = "";

	$activity = safeListInsert($activity);

	# create update string
	foreach ($activity as $attribute => $value) {
		if ($updateString != "") { $updateString .= ","; }

		switch ($attribute) {
			case "title":
				$updateString .= "UserActivityTitle='".$value."'";
				break;
			case "description":
				$updateString .= "UserActivityDescription='".$value."'";
				break;
			case "priority":
				$updateString .= "UserActivityPriority='".$value."'";
				break;
			case "url":
				$updateString .= "UserActivityUrl='".$value."'";
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

 		$activityString = implode(",",$ids);
                $where = "UserActivityId in ($activityString)";
		$result = mysql_query("UPDATE UserActivity SET $updateString WHERE $where", $XCOW_B['mysql_link']);

		if (! $result) {
       			catchMysqlError("UserActivityUpdate", $XCOW_B['mysql_link']);
			$status = "500 Internal Error";
		}
                elseif (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
			# mysql geeft 0 affected rows als een update wordt gedaan met allemaal dezelfde waarden
                        # $status = "404 Not Found";
                }

	}

	return $status;
}

function UserActivityDelete ($ids) {
        global $XCOW_B;

        $status = NULL;
	$activityString = "";

	$activityString = implode(",",$ids);
        $where = "UserActivityId in ($activityString)";
	$result = mysql_query("DELETE FROM UserActivity WHERE $where", $XCOW_B['mysql_link']);
     	if (! $result) {
             	catchMysqlError("UserActivityDelete", $XCOW_B['mysql_link']);
		$status = "500 Internal Error";
        }
        if (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
               	$status = "404 Not Found";
        }

	return $status;
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

// SETTINGS

function UserSettingsInsert ($settings, $userId) {
        global $XCOW_B;

        $settingsId = 0;

	$settings = safeListInsert($settings);

        $result = mysql_query("INSERT INTO UserSettings VALUES(NULL, '{$settings['name']}', '{$settings['value']}', $userId)", $XCOW_B['mysql_link']);

        if ($result) {
                $settingsId = mysql_insert_id($XCOW_B['mysql_link']);
        }
	else {
		catchMysqlError("UserSettingsInsert", $XCOW_B['mysql_link']);
	}

        return $settingsId;
}

function UserSettingsList($userId) {
        global $XCOW_B;

	$table = "UserSettings";
	$where = "WHERE UserId = $userId";
	$order = "";
	$limit = "";
	$expand = 1;

	return UserSettingsListWithValues($table, $where, $order, $limit, $expand);
}

function UserSettingsListWithValues($table, $where, $order, $limit, $expand) {
        global $XCOW_B;

	$settingsList = array();

	#
        # Construct QUERY
        #
	$query = "SELECT UserSettings.UserSettingsId, UserSettings.UserSettingsAttribute, UserSettings.UserSettingsValue from $table $where $order $limit";
 	#log2file("SettingsList: Query: ".$query);

	#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {
                 	$settingsId = $result_row['UserSettingsId'];

                	$settingsList[$settingsId] = array();
			$settingsList[$settingsId]['id'] = $result_row['UserSettingsId'];
			$settingsList[$settingsId]['name'] = $result_row['UserSettingsAttribute'];
			$settingsList[$settingsId]['value'] = $result_row['UserSettingsValue'];
       		}
        }
	else {
		catchMysqlError("UserSettingsListWithValues", $XCOW_B['mysql_link']);
	}
	
	return $settingsList;

}

function UserSettingsUpdate ($ids, $settings) {
        global $XCOW_B;

        $status = NULL;
	$updateString = "";
	$settingsString = "";

	$settings = safeListInsert($settings);

	# create update string
	foreach ($settings as $attribute => $value) {
		if ($updateString != "") { $updateString .= ","; }

		switch ($attribute) {
			case "name":
				$updateString .= "UserSettingsAttribute='".$value."'";
				break;
			case "value":
				$updateString .= "UserSettingsValue='".$value."'";
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

 		$settingsString = implode(",",$ids);
                $where = "UserSettingsId in ($settingsString)";
		$result = mysql_query("UPDATE UserSettings SET $updateString WHERE $where", $XCOW_B['mysql_link']);

		if (! $result) {
       			catchMysqlError("UserSettingsUpdate", $XCOW_B['mysql_link']);
			$status = "500 Internal Error";
		}
                elseif (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
			# mysql geeft 0 affected rows als een update wordt gedaan met allemaal dezelfde waarden
                        # $status = "404 Not Found";
                }

	}

	return $status;
}

function UserSettingsDelete ($ids) {
        global $XCOW_B;

        $status = NULL;
	$settingsString = "";

	$settingsString = implode(",",$ids);
        $where = "UserSettingsId in ($settingsString)";
	$result = mysql_query("DELETE FROM UserSettings WHERE $where", $XCOW_B['mysql_link']);
     	if (! $result) {
             	catchMysqlError("UserSettingsDelete", $XCOW_B['mysql_link']);
		$status = "500 Internal Error";
        }
        if (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
               	$status = "404 Not Found";
        }

	return $status;
}

//obsolete
function getUserSettings($userId) {
        global $XCOW_B;

	$settings = array();

	$result = mysql_query("SELECT UserSettingsAttribute, UserSettingsValue FROM UserSettings WHERE UserId = $userId", $XCOW_B['mysql_link']);

        while ($result_row = mysql_fetch_assoc($result)) {
		$att = $result_row['UserSettingsAttribute'];
		$val = $result_row['UserSettingsValue'];
		$settings[$att] = $val;
        }

	return ($settings);
}

// DATA

function UserDataInsert ($data, $userId) {
        global $XCOW_B;

        $dataId = 0;

	$data = safeListInsert($data);

        $result = mysql_query("INSERT INTO UserData VALUES(NULL, '{$data['name']}', '{$data['value']}', $userId)", $XCOW_B['mysql_link']);

        if ($result) {
                $dataId = mysql_insert_id($XCOW_B['mysql_link']);
        }
	else {
		catchMysqlError("UserDataInsert", $XCOW_B['mysql_link']);
	}

        return $dataId;
}

function UserDataList($userId) {
        global $XCOW_B;

	$table = "UserData";
	$where = "WHERE UserId = $userId";
	$order = "";
	$limit = "";
	$expand = 1;

	return UserDataListWithValues($table, $where, $order, $limit, $expand);
}

function UserDataListWithValues($table, $where, $order, $limit, $expand) {
        global $XCOW_B;

	$dataList = array();

	#
        # Construct QUERY
        #
	$query = "SELECT UserData.UserDataId, UserData.UserDataAttribute, UserData.UserDataValue from $table $where $order $limit";
 	#log2file("DataList: Query: ".$query);

	#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {
                 	$dataId = $result_row['UserDataId'];

                	$dataList[$dataId] = array();
			$dataList[$dataId]['id'] = $result_row['UserDataId'];
			$dataList[$dataId]['name'] = $result_row['UserDataAttribute'];
			$dataList[$dataId]['value'] = $result_row['UserDataValue'];
       		}
        }
	else {
		catchMysqlError("UserDataListWithValues", $XCOW_B['mysql_link']);
	}
	
	return $dataList;

}

function UserDataUpdate ($ids, $data) {
        global $XCOW_B;

        $status = NULL;
	$updateString = "";
	$dataString = "";

	$data = safeListInsert($data);

	# create update string
	foreach ($data as $attribute => $value) {
		if ($updateString != "") { $updateString .= ","; }

		switch ($attribute) {
			case "name":
				$updateString .= "UserDataAttribute='".$value."'";
				break;
			case "value":
				$updateString .= "UserDataValue='".$value."'";
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

 		$dataString = implode(",",$ids);
                $where = "UserDataId in ($dataString)";
		$result = mysql_query("UPDATE UserData SET $updateString WHERE $where", $XCOW_B['mysql_link']);

		if (! $result) {
       			catchMysqlError("UserDataUpdate", $XCOW_B['mysql_link']);
			$status = "500 Internal Error";
		}
                elseif (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
			# mysql geeft 0 affected rows als een update wordt gedaan met allemaal dezelfde waarden
                        # $status = "404 Not Found";
                }

	}

	return $status;
}

function UserDataDelete ($ids) {
        global $XCOW_B;

        $status = NULL;
	$dataString = "";

	$dataString = implode(",",$ids);
        $where = "UserDataId in ($dataString)";
	$result = mysql_query("DELETE FROM UserData WHERE $where", $XCOW_B['mysql_link']);
     	if (! $result) {
             	catchMysqlError("UserDataDelete", $XCOW_B['mysql_link']);
		$status = "500 Internal Error";
        }
        if (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
               	$status = "404 Not Found";
        }

	return $status;
}

// ACCESS RULE

function AccessRuleInsert ($accessRule) {
        global $XCOW_B;

        $accessRuleId = 0;

	$accessRule = safeListInsert($accessRule);

        $result = mysql_query("INSERT INTO AccessRule VALUES(NULL, '{$accessRule['name']}', '{$accessRule['value']}')", $XCOW_B['mysql_link']);

        if ($result) {
                $accessRuleId = mysql_insert_id($XCOW_B['mysql_link']);
        }
	else {
		catchMysqlError("AccessRuleInsert", $XCOW_B['mysql_link']);
	}

        return $accessRuleId;
}

function AccessRuleList() {
        global $XCOW_B;

	$table = "AccessRule";
	$where = "";
	$order = "";
	$limit = "";
	$expand = 1;

	return AccessRuleListWithValues($table, $where, $order, $limit, $expand);
}

function AccessRuleListWithValues($table, $where, $order, $limit, $expand) {
        global $XCOW_B;

	$accessRuleList = array();

	#
        # Construct QUERY
        #
	$query = "SELECT AccessRule.AccessRuleId, AccessRule.AccessRuleAttribute, AccessRule.AccessRuleValue from $table $where $order $limit";
 	#log2file("AccessRuleList: Query: ".$query);

	#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);
	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {
                 	$accessRuleId = $result_row['AccessRuleId'];

                	$accessRuleList[$accessRuleId] = array();
			$accessRuleList[$accessRuleId]['id'] = $result_row['AccessRuleId'];
			$accessRuleList[$accessRuleId]['name'] = $result_row['AccessRuleAttribute'];
			$accessRuleList[$accessRuleId]['value'] = $result_row['AccessRuleValue'];
       		}
        }
	else {
		catchMysqlError("AccessRuleListWithValues", $XCOW_B['mysql_link']);
	}

	return $accessRuleList;

}

function AccessRuleUpdate ($ids, $accessRule) {
        global $XCOW_B;

        $status = NULL;
	$updateString = "";
	$accessRuleString = "";

	$accessRule = safeListInsert($accessRule);

	# create update string
	foreach ($accessRule as $attribute => $value) {
		if ($updateString != "") { $updateString .= ","; }

		switch ($attribute) {
			case "name":
				$updateString .= "AccessRuleAttribute='".$value."'";
				break;
			case "value":
				$updateString .= "AccessRuleValue='".$value."'";
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

 		$accessRuleString = implode(",",$ids);
                $where = "AccessRuleId in ($accessRuleString)";
		$result = mysql_query("UPDATE AccessRule SET $updateString WHERE $where", $XCOW_B['mysql_link']);

		if (! $result) {
       			catchMysqlError("AccessRuleUpdate", $XCOW_B['mysql_link']);
			$status = "500 Internal Error";
		}
                elseif (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
			# mysql geeft 0 affected rows als een update wordt gedaan met allemaal dezelfde waarden
                        # $status = "404 Not Found";
                }

	}

	return $status;
}

function AccessRuleDelete ($ids) {
        global $XCOW_B;

        $status = NULL;
	$accessRuleString = "";

	$accessRuleString = implode(",",$ids);
        $where = "AccessRuleId in ($accessRuleString)";
	$result = mysql_query("DELETE FROM AccessRule WHERE $where", $XCOW_B['mysql_link']);
     	if (! $result) {
             	catchMysqlError("AccessRuleDelete", $XCOW_B['mysql_link']);
		$status = "500 Internal Error";
        }
        if (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
               	$status = "404 Not Found";
        }

	return $status;
}

// ACCESS GROUP

function AccessGroupInsert ($accessGroup) {
        global $XCOW_B;

        $accessGroupId = 0;

	$accessGroup = safeListInsert($accessGroup);

        $result = mysql_query("INSERT INTO AccessGroup VALUES(NULL, '{$accessGroup['name']}', '{$accessGroup['level']}')", $XCOW_B['mysql_link']);

        if ($result) {
                $accessGroupId = mysql_insert_id($XCOW_B['mysql_link']);
        }
	else {
		catchMysqlError("AccessGroupInsert", $XCOW_B['mysql_link']);
	}

        return $accessGroupId;
}

function AccessGroupList() {
        global $XCOW_B;

	$table = "AccessGroup";
	$where = "";
	$order = "";
	$limit = "";
	$expand = 1;

	return AccessGroupListWithValues($table, $where, $order, $limit, $expand);
}

function AccessGroupListWithValues($table, $where, $order, $limit, $expand) {
        global $XCOW_B;

	$accessGroupList = array();

	#
        # Construct QUERY
        #
	$query = "SELECT AccessGroup.AccessGroupId, AccessGroup.AccessGroupName, AccessGroup.AccessGroupLevel from $table $where $order $limit";
 	#log2file("AccessGroupList: Query: ".$query);

	#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);
	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {
                 	$accessGroupId = $result_row['AccessGroupId'];

                	$accessGroupList[$accessGroupId] = array();
			$accessGroupList[$accessGroupId]['id'] = $result_row['AccessGroupId'];
			$accessGroupList[$accessGroupId]['name'] = $result_row['AccessGroupName'];
			$accessGroupList[$accessGroupId]['level'] = $result_row['AccessGroupLevel'];
       		}
        }
	else {
		catchMysqlError("AccessGroupListWithValues", $XCOW_B['mysql_link']);
	}

	return $accessGroupList;

}

function AccessGroupUpdate ($ids, $accessGroup) {
        global $XCOW_B;

        $status = NULL;
	$updateString = "";
	$accessGroupString = "";

	$accessGroup = safeListInsert($accessGroup);

	# create update string
	foreach ($accessGroup as $attribute => $value) {
		if ($updateString != "") { $updateString .= ","; }

		switch ($attribute) {
			case "name":
				$updateString .= "AccessGroupName='".$value."'";
				break;
			case "level":
				$updateString .= "AccessGroupLevel='".$value."'";
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

 		$accessGroupString = implode(",",$ids);
                $where = "AccessGroupId in ($accessGroupString)";
		$result = mysql_query("UPDATE AccessGroup SET $updateString WHERE $where", $XCOW_B['mysql_link']);

		if (! $result) {
       			catchMysqlError("AccessGroupUpdate", $XCOW_B['mysql_link']);
			$status = "500 Internal Error";
		}
                elseif (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
			# mysql geeft 0 affected rows als een update wordt gedaan met allemaal dezelfde waarden
                        # $status = "404 Not Found";
                }

	}

	return $status;
}

function AccessGroupDelete ($ids) {
        global $XCOW_B;

        $status = NULL;
	$accessGroupString = "";

	$accessGroupString = implode(",",$ids);
        $where = "AccessGroupId in ($accessGroupString)";
	$result = mysql_query("DELETE FROM AccessGroup WHERE $where", $XCOW_B['mysql_link']);
     	if (! $result) {
             	catchMysqlError("AccessGroupDelete", $XCOW_B['mysql_link']);
		$status = "500 Internal Error";
        }
        if (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
               	$status = "404 Not Found";
        }

	return $status;
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
	else {
		catchMysqlError("AccessAppInsert", $XCOW_B['mysql_link']);
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
 	#log2file("AccessAppList: Query: ".$query);

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

			$accessAppList[$accessAppId]['group'] = array();
			if ($expand) {
				$accessAppList[$accessAppId]['group'] = UserGroupListWithValues("UserGroup, UserGroupInApp", "WHERE UserGroupInApp.AccessAppId = {$result_row['AccessAppId']} AND UserGroupInApp.UserGroupId = UserGroup.UserGroupId", '', '', 0);

			}
       		}
        }
	else {
		catchMysqlError("AccessAppListWithValues", $XCOW_B['mysql_link']);
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

	#
	# go
	#
	if ($updateString != "") {

 		$accessAppString = implode(",",$ids);
                $where = "AccessAppId in ($accessAppString)";
		$result = mysql_query("UPDATE AccessApp SET $updateString WHERE $where", $XCOW_B['mysql_link']);

		if (! $result) {
       			catchMysqlError("AccessAppUpdate", $XCOW_B['mysql_link']);
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
             	catchMysqlError("AccessAppDelete", $XCOW_B['mysql_link']);
		$status = "500 Internal Error";
        }
        if (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
               	$status = "404 Not Found";
        }

	return $status;
}

function AccessAppInsertGroup ($apps, $groups) {
        global $XCOW_B;

        $status = NULL;

	foreach ($apps as $app) {
		foreach ($groups as $group) {
	        	$result = mysql_query("INSERT INTO UserGroupInApp VALUES($group, $app)", $XCOW_B['mysql_link']);
			// a double entry is not allowed, but we do not error on it.			
			// if (! $result) { $status = "500 Internal Error"; break; }
		}
	}

        return $status;
}

function AccessAppDeleteGroup ($apps, $groups) {
        global $XCOW_B;

        $status = NULL;
	$appString = "";
	$groupString = "";

	$appString = implode(",",$apps);
	$groupString = implode(",",$groups);
        $where = "UserGroupId in ($groupString) AND AccessAppId in ($appString)";
	$result = mysql_query("DELETE FROM UserGroupInApp WHERE $where", $XCOW_B['mysql_link']);
     	if (! $result) {
             	catchMysqlError("AccessAppDeleteGroup", $XCOW_B['mysql_link']);
		$status = "500 Internal Error";
        }
        if (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
               	$status = "404 Not Found";
        }

	return $status;
}

// GROUP

function UserGroupInsert ($group, $userId, $access) {
        global $XCOW_B;

        $groupId = 0;
	$timestamp = time();

	$group = safeListInsert($group);

        $result = mysql_query("INSERT INTO UserGroup VALUES(NULL, '{$group['name']}', '{$group['description']}', '{$group['type']}', '$timestamp', $userId, $access)", $XCOW_B['mysql_link']);

        if ($result) {
                $groupId = mysql_insert_id($XCOW_B['mysql_link']);
        }
	else {
		catchMysqlError("UserGroupInsert", $XCOW_B['mysql_link']);
	}

        return $groupId;
}

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

function UserGroupUpdate ($ids, $group) {
        global $XCOW_B;

        $status = NULL;
	$updateString = "";
	$groupString = "";

	$group = safeListInsert($group);

	# create update string
	foreach ($group as $attribute => $value) {
		if ($updateString != "") { $updateString .= ","; }

		switch ($attribute) {
			case "name":
				$updateString .= "UserGroupName='".$value."'";
				break;
			case "description":
				$updateString .= "UserGroupDescription='".$value."'";
				break;
			case "type":
				$updateString .= "UserGroupType='".$value."'";
				break;
			case "access":
				$updateString .= "AccessGroupId='".$value."'";
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

 		$groupString = implode(",",$ids);
                $where = "UserGroupId in ($groupString)";
		$result = mysql_query("UPDATE UserGroup SET $updateString WHERE $where", $XCOW_B['mysql_link']);

		if (! $result) {
       			catchMysqlError("UserGroupUpdate", $XCOW_B['mysql_link']);
			$status = "500 Internal Error";
		}
                elseif (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
			# mysql geeft 0 affected rows als een update wordt gedaan met allemaal dezelfde waarden
                        # $status = "404 Not Found";
                }

	}

	return $status;
}

function UserGroupDelete ($ids) {
        global $XCOW_B;

        $status = NULL;
	$groupString = "";

	$groupString = implode(",",$ids);
        $where = "UserGroupId in ($groupString)";
	$result = mysql_query("DELETE FROM UserGroup WHERE $where", $XCOW_B['mysql_link']);
     	if (! $result) {
             	catchMysqlError("UserGroupDelete", $XCOW_B['mysql_link']);
		$status = "500 Internal Error";
        }
        if (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
               	$status = "404 Not Found";
        }

	return $status;
}

function UserGroupInsertUser ($groups, $users) {
        global $XCOW_B;

        $status = NULL;

	foreach ($groups as $group) {
		foreach ($users as $user) {
	        	$result = mysql_query("INSERT INTO UserInGroup VALUES($user, $group)", $XCOW_B['mysql_link']);
			// a double entry is not allowed, but we do not error on it.			
			// if (! $result) { $status = "500 Internal Error"; break; }
		}
	}

        return $status;
}

function UserGroupDeleteUser ($groups, $users) {
        global $XCOW_B;

        $status = NULL;
	$groupString = "";
	$userString = "";

	$groupString = implode(",",$groups);
	$userString = implode(",",$users);
        $where = "UserGroupId in ($groupString) AND UserId in ($userString)";
	$result = mysql_query("DELETE FROM UserInGroup WHERE $where", $XCOW_B['mysql_link']);
     	if (! $result) {
             	catchMysqlError("UserGroupDeleteUser", $XCOW_B['mysql_link']);
		$status = "500 Internal Error";
        }
        if (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
               	$status = "404 Not Found";
        }

	return $status;
}

// RELATION

function UserRelationInsert ($relation, $userId, $access) {
        global $XCOW_B;

        $relationId = 0;

	$relation = safeListInsert($relation);

        $result = mysql_query("INSERT INTO UserRelation VALUES(NULL, '{$relation['userId']}', $userId, $access)", $XCOW_B['mysql_link']);

        if ($result) {
                $relationId = mysql_insert_id($XCOW_B['mysql_link']);
        }
	else {
		catchMysqlError("UserRelationInsert", $XCOW_B['mysql_link']);
	}

        return $relationId;
}

function UserRelationList($userId) {
        global $XCOW_B;

	$table = "UserRelation";
	$where = "WHERE UserId = $userId";
	$order = "";
	$limit = "";
	$expand = 1;

	return UserRelationListWithValues($table, $where, $order, $limit, $expand);
}

function UserRelationListWithValues($table, $where, $order, $limit, $expand) {
        global $XCOW_B;

	$relationList = array();

	#
        # Construct QUERY
        #
        $query = "SELECT UserRelation.UserRelationId, UserRelation.UserRelationUserId, UserRelation.UserId, UserRelation.AccessGroupId from $table $where $order $limit";
 	#log2file("RelationList: Query: ".$query);

	#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {
			// TODO: check AccessRule                	

                 	$relationId = $result_row['UserRelationId'];

                	$relationList[$relationId] = array();
			$relationList[$relationId]['id'] = $result_row['UserRelationId'];
			$relationList[$relationId]['userId'] = $result_row['UserRelationUserId'];

			$relationList[$relationId]['accessGroup'] = array();
			if ($expand) {
				$relationList[$relationId]['accessGroup'] = AccessGroupListWithValues('AccessGroup', "WHERE AccessGroupId = {$result_row['AccessGroupId']}", '', '', 0);
			}
       		}
        }
	else {
		catchMysqlError("UserRelationListWithValues", $XCOW_B['mysql_link']);
	}

	return $relationList;

}

function UserRelationUpdate ($ids, $relation) {
        global $XCOW_B;

        $status = NULL;
	$updateString = "";
	$relationString = "";

	$relation = safeListInsert($relation);

	# create update string
	foreach ($relation as $attribute => $value) {
		if ($updateString != "") { $updateString .= ","; }

		switch ($attribute) {
			case "access":
				$updateString .= "AccessGroupId='".$value."'";
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

 		$relationString = implode(",",$ids);
                $where = "UserRelationId in ($relationString)";
		$result = mysql_query("UPDATE UserRelation SET $updateString WHERE $where", $XCOW_B['mysql_link']);

		if (! $result) {
       			catchMysqlError("UserRelationUpdate", $XCOW_B['mysql_link']);
			$status = "500 Internal Error";
		}
                elseif (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
			# mysql geeft 0 affected rows als een update wordt gedaan met allemaal dezelfde waarden
                        # $status = "404 Not Found";
                }

	}

	return $status;
}

function UserRelationDelete ($ids) {
        global $XCOW_B;

        $status = NULL;
	$relationString = "";

	$relationString = implode(",",$ids);
        $where = "UserRelationId in ($relationString)";
	$result = mysql_query("DELETE FROM UserRelation WHERE $where", $XCOW_B['mysql_link']);
     	if (! $result) {
             	catchMysqlError("UserRelationDelete", $XCOW_B['mysql_link']);
		$status = "500 Internal Error";
        }
        if (mysql_affected_rows($XCOW_B['mysql_link']) == 0 ) {
               	$status = "404 Not Found";
        }

	return $status;
}

// STATS

function StatsList() {
        global $XCOW_B;

	$table = "Stats";
	$where = "";
	$order = "ORDER BY StatsTimestamp DESC";
	$limit = "LIMIT 1";
	$expand = 1;

	return StatsListWithValues($table, $where, $order, $limit, $expand);
}

function StatsListBetweenDates($from, $to) {
        global $XCOW_B;

	$table = "Stats";
	$where = "";
	if ($from > 0) {
		if ($where == "") {$where = "WHERE ";} else {$where .= " AND ";} 
		$where .= "StatsTimestamp > $from";
	}
	if ($to > 0) {
		if ($where == "") {$where = "WHERE ";} else {$where .= " AND ";} 
		$where .= "StatsTimestamp < $to";
	}
	$order = "ORDER BY StatsTimestamp DESC";
	$limit = "LIMIT 1";
	$expand = 1;

	return StatsListWithValues($table, $where, $order, $limit, $expand);
}


function StatsListWithValues($table, $where, $order, $limit, $expand) {
        global $XCOW_B;

	$statsList = array();

	#
        # Construct QUERY
        #
        $query = "SELECT Stats.StatsId, Stats.StatsTimestamp from $table $where $order $limit";
	
	#log2file("StatsList: Query: ".$query);

	#
        # SELECT
        #
        $result = mysql_query("$query", $XCOW_B['mysql_link']);

	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {
			// TODO: check AccessRule                	

			$statsId = $result_row['StatsId'];
	
                	$statsList[$statsId] = array();
                	$statsList[$statsId]['statsId'] = $statsId;
                	$statsList[$statsId]['statsTimestamp'] = $result_row['StatsTimestamp'];
 
			$statsList[$statsId]['annotation'] = array();
			$statsList[$statsId]['profile'] = array();
			$statsList[$statsId]['annotation'] = UserAnnotationList('stats', $statsId);
			if ($expand) {
				$statsList[$statsId]['profile'] = UserProfileList('stats', $statsId);
			}
        	}
        }
	else {
		catchMysqlError("StatsListWithValues", $XCOW_B['mysql_link']);
	}

	return $statsList;
}

?>
