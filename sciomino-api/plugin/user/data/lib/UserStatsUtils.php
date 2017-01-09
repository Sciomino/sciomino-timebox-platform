<?php

//
// New Stats
//
function StatsNew() {
        global $XCOW_B;

	$statsId = 0;
	$timestamp = time();

	$result = mysql_query("INSERT INTO Stats VALUES(NULL, '$timestamp')", $XCOW_B['mysql_link']);
        if ($result) {
 		$statsId = mysql_insert_id($XCOW_B['mysql_link']);
        }
	else {
		catchMysqlError("StatsNew", $XCOW_B['mysql_link']);
	}

	return $statsId;
}



//
// Update stats
//
function StatsUpdateSingleCounts($statsId, $statsList, $statsType) {

	$annotationId = 0;

	foreach ($statsList as $statsKey => $statsValue) {

		$stat = array();
		$stat['name'] = $statsKey;
		$stat['value'] = $statsValue;
		$stat['type'] = $statsType;

		$annotationId = UserAnnotationInsert($stat, 'stats', $statsId, 0);

		if ($annotationId == 0) {
			break;
		}
	}

	return $annotationId;
	
}

function StatsUpdateMultipleCounts($statsId, $statsList) {

	$profileId = 0;
	$annotationId = 0;

	foreach ($statsList as $statsKey => $statsValue) {
		
		$profile = array();
		$profile['group'] = $statsKey;
		$profile['name'] = $statsKey;

		foreach ($statsValue as $statsSubKey => $statsSubValue) {
			$profileId = UserProfileInsert($profile, "stats", $statsId, 0);
	        	if ($profileId != 0) {
				$stat1 = array();
				$stat1['name'] = 'label';
				$stat1['value'] = $statsSubKey;
				$stat1['type'] = "string";
				$stat2 = array();
				$stat2['name'] = 'count';
				$stat2['value'] = $statsSubValue;
				$stat2['type'] = "int";

				$annotationId = UserAnnotationInsert($stat1, 'statsProfile', $profileId, 0);
				$annotationId = UserAnnotationInsert($stat2, 'statsProfile', $profileId, 0);
			}
			else {
				break;
			}

			if ($annotationId == 0) {
				break;
			}
		}

	}

	return $annotationId;

}



//
// Get Count
//
function StatsGetUserCount() {
        global $XCOW_B;

	# let op: count users
        $result = mysql_query("SELECT count(UserId) FROM User", $XCOW_B['mysql_link']);
        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

function StatsGetUserAnnotationCount($name, $value) {
        global $XCOW_B;

	# let op: count users with attributes/values pairs (ie: gender=M)
        $result = mysql_query("SELECT count(User.UserId) FROM User, UserAnnotation WHERE User.UserId = UserAnnotation.UserId AND UserAnnotation.AnnotationAttribute = '".safeInsert($name)."' AND UserAnnotation.AnnotationValue = '".safeInsert($value)."'", $XCOW_B['mysql_link']);
        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

function StatsGetUserProfileCount($group) {
        global $XCOW_B;

	# let op: count distinct users with profile groups (ie: one or more knowledge/tags/...)
        $result = mysql_query("SELECT count(DISTINCT UserId) FROM UserProfile WHERE ProfileGroup = '".safeInsert($group)."'", $XCOW_B['mysql_link']);

        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

// TODO: this one should not use UserProfileAnnotation table, but it is here for later when it might be used for top10's of knowledgefield...
function StatsGetUserProfileValueCount($name, $group) {
        global $XCOW_B;

	# let op: count distinct values (ie: # knowledge/tags/...)
        $result = mysql_query("SELECT count(DISTINCT UserProfileAnnotation.AnnotationValue) FROM UserProfile, UserProfileAnnotation WHERE UserProfile.ProfileId = UserProfileAnnotation.ProfileId AND UserProfile.ProfileGroup = '".safeInsert($group)."' AND UserProfileAnnotation.AnnotationAttribute = '".safeInsert($name)."'", $XCOW_B['mysql_link']);

        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

function StatsGetAddressCount($name, $section) {
        global $XCOW_B;

	# let op: count distinct values (ie: # cities/countries)
        $result = mysql_query("SELECT count(DISTINCT UserAddressAnnotation.AnnotationValue) FROM UserAddress, UserAddressAnnotation WHERE UserAddress.SectionId = UserAddressAnnotation.SectionId AND UserAddress.SectionName = '".safeInsert($section)."' AND UserAddressAnnotation.AnnotationAttribute = '".safeInsert($name)."'", $XCOW_B['mysql_link']);

        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

function StatsGetUserPublicationCount() {
        global $XCOW_B;

	# let op: count distinct users with one or more publications
        $result = mysql_query("SELECT count(DISTINCT UserId) FROM UserPublication", $XCOW_B['mysql_link']);

        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

function StatsGetUserPublicationSectionCount($section) {
        global $XCOW_B;

	# let op: count distinct users from a publication section (ie: one or more Blogs/Websites/...)
        $result = mysql_query("SELECT count(DISTINCT UserId) FROM UserPublication WHERE SectionName = '".safeInsert($section)."'", $XCOW_B['mysql_link']);

        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

function StatsGetUserPublicationValueCount($name, $value, $section) {
        global $XCOW_B;

	# let op: count distinct users with attributes/values pairs (ie: SocialNetwork=twitter)
        $result = mysql_query("SELECT count(DISTINCT UserPublication.UserId) FROM UserPublication, UserPublicationAnnotation WHERE UserPublication.SectionId = UserPublicationAnnotation.SectionId AND UserPublication.SectionName = '".safeInsert($section)."' AND UserPublicationAnnotation.AnnotationAttribute = '".safeInsert($name)."' AND UserPublicationAnnotation.AnnotationValue = '".safeInsert($value)."'", $XCOW_B['mysql_link']);

        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

function StatsGetPublicationCount($section) {
        global $XCOW_B;

	# let op: count entries in publication sections (ie: # blogs/websites/...)
        $result = mysql_query("SELECT count(SectionId) FROM UserPublication WHERE SectionName = '".safeInsert($section)."'", $XCOW_B['mysql_link']);

        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

function StatsGetUserExperienceCount() {
        global $XCOW_B;

	# let op: count distinct users with one or more experiences
        $result = mysql_query("SELECT count(DISTINCT UserId) FROM UserExperience", $XCOW_B['mysql_link']);

        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

function StatsGetExperienceCount($section) {
        global $XCOW_B;

	# let op: count entries in experience sections (ie: # products/companies/...)
        $result = mysql_query("SELECT count(SectionId) FROM UserExperience WHERE SectionName = '".safeInsert($section)."'", $XCOW_B['mysql_link']);

        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}



//
// Get Value
//
function StatsGetDateOfBirthValues() {
        global $XCOW_B;

	$userList = array();

	# hum, 'dateofbirth*' is a word defined in the frontend...
        $result = mysql_query("SELECT User.UserId, UserAnnotation.AnnotationAttribute, UserAnnotation.AnnotationValue FROM User, UserAnnotation WHERE User.UserId = UserAnnotation.UserId AND (UserAnnotation.AnnotationAttribute = 'dateofbirthday' OR UserAnnotation.AnnotationAttribute = 'dateofbirthmonth' OR UserAnnotation.AnnotationAttribute = 'dateofbirthyear' OR UserAnnotation.AnnotationAttribute = 'gender')", $XCOW_B['mysql_link']);

	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {
			$id = $result_row['UserId'];
			if (! is_array($userList[$id])) {
				$userList[$id] = array();
			}
			if ($result_row['AnnotationAttribute'] == "dateofbirthday") {
				$userList[$id]['day'] = $result_row['AnnotationValue'];
			}
			elseif ($result_row['AnnotationAttribute'] == "dateofbirthmonth") {
				$userList[$id]['month'] = $result_row['AnnotationValue'];
			}
			elseif ($result_row['AnnotationAttribute'] == "dateofbirthyear") {
				$userList[$id]['year'] = $result_row['AnnotationValue'];
			}
			elseif ($result_row['AnnotationAttribute'] == "gender") {
				$userList[$id]['gender'] = $result_row['AnnotationValue'];
			}
		}
	}
	else {
		catchMysqlError("StatsGetDateOfBirthValues", $XCOW_B['mysql_link']);
	}

        return $userList;
}

function StatsGetAddressValues($name, $section) {
        global $XCOW_B;

	$addressList = array();

        $result = mysql_query("SELECT UserAddressAnnotation.AnnotationValue FROM UserAddress, UserAddressAnnotation WHERE UserAddress.SectionId = UserAddressAnnotation.SectionId AND UserAddress.SectionName = '".safeInsert($section)."' AND UserAddressAnnotation.AnnotationAttribute = '".safeInsert($name)."'", $XCOW_B['mysql_link']);

	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {
			if ($result_row['AnnotationValue'] != '') {
				$id = $result_row['AnnotationValue'];
			}
			else {
				$id = "UNKNOWN";
			}

			if (! isset($addressList[$id])) {
				$addressList[$id] = 1;
			}
			else {
				$addressList[$id] = $addressList[$id] + 1;
			}
		}
	}
	else {
		catchMysqlError("StatsGetAddressValues", $XCOW_B['mysql_link']);
	}

        return $addressList;
}

function StatsGetExperienceValues($section) {
        global $XCOW_B;

	$experienceList = array();

	# hum, 'subject/title/like' is a word defined in the frontend...
        $result = mysql_query("SELECT UserExperienceAnnotation.SectionId, UserExperienceAnnotation.AnnotationAttribute, UserExperienceAnnotation.AnnotationValue FROM UserExperience, UserExperienceAnnotation WHERE UserExperience.SectionId = UserExperienceAnnotation.SectionId AND UserExperience.SectionName = '".safeInsert($section)."' AND (UserExperienceAnnotation.AnnotationAttribute = 'subject' OR UserExperienceAnnotation.AnnotationAttribute = 'title' OR UserExperienceAnnotation.AnnotationAttribute = 'like')", $XCOW_B['mysql_link']);

	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {
			$id = $result_row['SectionId'];
			if (! is_array($experienceList[$id])) {
				$experienceList[$id] = array();
			}
			$key = $result_row['AnnotationAttribute'];
			if ($result_row['AnnotationValue'] != '') {
				$experienceList[$id][$key] = $result_row['AnnotationValue'];
			}
			else {
				$experienceList[$id][$key] = "UNKNOWN";
			}
		}
	}
	else {
		catchMysqlError("StatsGetExperienceValues", $XCOW_B['mysql_link']);
	}

        return $experienceList;
}

function StatsGetCityXCountryValues($section) {
        global $XCOW_B;

	$addressList = array();

	# hum, 'city/country' is a word defined in the frontend...
        $result = mysql_query("SELECT UserAddressAnnotation.SectionId, UserAddressAnnotation.AnnotationAttribute, UserAddressAnnotation.AnnotationValue FROM UserAddress, UserAddressAnnotation WHERE UserAddress.SectionId = UserAddressAnnotation.SectionId AND UserAddress.SectionName = '".safeInsert($section)."' AND (UserAddressAnnotation.AnnotationAttribute = 'city' OR UserAddressAnnotation.AnnotationAttribute = 'country')", $XCOW_B['mysql_link']);

	if ($result) {
		while ($result_row = mysql_fetch_assoc($result)) {
			$id = $result_row['SectionId'];
			if (! is_array($addressList[$id])) {
				$addressList[$id] = array();
			}
			$key = $result_row['AnnotationAttribute'];
			if ($result_row['AnnotationValue'] != '') {
				$addressList[$id][$key] = $result_row['AnnotationValue'];
			}
			else {
				$addressList[$id][$key] = "UNKNOWN";
			}
		}
	}
	else {
		catchMysqlError("StatsGetCityXCountryValues", $XCOW_B['mysql_link']);
	}

        return $addressList;
}

//
// For Networks
// - add the following filter as WHERE/AND
// - UserId in (SELECT UserInGroup.UserId FROM UserGroup, UserInGroup WHERE UserGroup.UserGroupId = UserInGroup.UserGroupId AND UserGroup.UserGroupId=".$network.")

function StatsGetUserCountForNetwork($network) {
        global $XCOW_B;

		# let op: count users
        $result = mysql_query("SELECT count(UserId) FROM User WHERE UserId in (SELECT UserInGroup.UserId FROM UserGroup, UserInGroup WHERE UserGroup.UserGroupId = UserInGroup.UserGroupId AND UserGroup.UserGroupId=".$network.")", $XCOW_B['mysql_link']);
        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

function StatsGetUserProfileCountForNetwork($network, $group) {
        global $XCOW_B;

		# let op: count distinct users with profile groups (ie: one or more knowledge/tags/...)
        $result = mysql_query("SELECT count(DISTINCT UserId) FROM UserProfile WHERE ProfileGroup = '".safeInsert($group)."' AND UserId in (SELECT UserInGroup.UserId FROM UserGroup, UserInGroup WHERE UserGroup.UserGroupId = UserInGroup.UserGroupId AND UserGroup.UserGroupId=".$network.")", $XCOW_B['mysql_link']);

        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

function StatsGetUserProfileValueCountForNetwork($network, $name, $group) {
        global $XCOW_B;

		# let op: count distinct values (ie: # knowledge/tags/...)
        $result = mysql_query("SELECT count(DISTINCT UserProfileAnnotation.AnnotationValue) FROM UserProfile, UserProfileAnnotation WHERE UserProfile.ProfileId = UserProfileAnnotation.ProfileId AND UserProfile.ProfileGroup = '".safeInsert($group)."' AND UserProfileAnnotation.AnnotationAttribute = '".safeInsert($name)."' AND UserProfile.UserId in (SELECT UserInGroup.UserId FROM UserGroup, UserInGroup WHERE UserGroup.UserGroupId = UserInGroup.UserGroupId AND UserGroup.UserGroupId=".$network.")", $XCOW_B['mysql_link']);

        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

function StatsGetUserPublicationCountForNetwork($network) {
        global $XCOW_B;

		# let op: count distinct users with one or more publications
        $result = mysql_query("SELECT count(DISTINCT UserId) FROM UserPublication Where UserId in (SELECT UserInGroup.UserId FROM UserGroup, UserInGroup WHERE UserGroup.UserGroupId = UserInGroup.UserGroupId AND UserGroup.UserGroupId=".$network.")", $XCOW_B['mysql_link']);

        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

function StatsGetUserPublicationSectionCountForNetwork($network, $section) {
        global $XCOW_B;

		# let op: count distinct users from a publication section (ie: one or more Blogs/Websites/...)
        $result = mysql_query("SELECT count(DISTINCT UserId) FROM UserPublication WHERE SectionName = '".safeInsert($section)."' AND UserId in (SELECT UserInGroup.UserId FROM UserGroup, UserInGroup WHERE UserGroup.UserGroupId = UserInGroup.UserGroupId AND UserGroup.UserGroupId=".$network.")", $XCOW_B['mysql_link']);

        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

function StatsGetUserPublicationValueCountForNetwork($network, $name, $value, $section) {
        global $XCOW_B;

		# let op: count distinct users with attributes/values pairs (ie: SocialNetwork=twitter)
        $result = mysql_query("SELECT count(DISTINCT UserPublication.UserId) FROM UserPublication, UserPublicationAnnotation WHERE UserPublication.SectionId = UserPublicationAnnotation.SectionId AND UserPublication.SectionName = '".safeInsert($section)."' AND UserPublicationAnnotation.AnnotationAttribute = '".safeInsert($name)."' AND UserPublicationAnnotation.AnnotationValue = '".safeInsert($value)."' AND UserPublication.UserId in (SELECT UserInGroup.UserId FROM UserGroup, UserInGroup WHERE UserGroup.UserGroupId = UserInGroup.UserGroupId AND UserGroup.UserGroupId=".$network.")", $XCOW_B['mysql_link']);

        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

function StatsGetPublicationCountForNetwork($network, $section) {
        global $XCOW_B;

		# let op: count entries in publication sections (ie: # blogs/websites/...)
        $result = mysql_query("SELECT count(SectionId) FROM UserPublication WHERE SectionName = '".safeInsert($section)."' AND UserId in (SELECT UserInGroup.UserId FROM UserGroup, UserInGroup WHERE UserGroup.UserGroupId = UserInGroup.UserGroupId AND UserGroup.UserGroupId=".$network.")", $XCOW_B['mysql_link']);

        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

function StatsGetUserExperienceCountForNetwork($network) {
        global $XCOW_B;

		# let op: count distinct users with one or more experiences
        $result = mysql_query("SELECT count(DISTINCT UserId) FROM UserExperience WHERE UserId in (SELECT UserInGroup.UserId FROM UserGroup, UserInGroup WHERE UserGroup.UserGroupId = UserInGroup.UserGroupId AND UserGroup.UserGroupId=".$network.")", $XCOW_B['mysql_link']);

        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

function StatsGetExperienceCountForNetwork($network, $section) {
        global $XCOW_B;

		# let op: count entries in experience sections (ie: # products/companies/...)
        $result = mysql_query("SELECT count(SectionId) FROM UserExperience WHERE SectionName = '".safeInsert($section)."' AND UserId in (SELECT UserInGroup.UserId FROM UserGroup, UserInGroup WHERE UserGroup.UserGroupId = UserInGroup.UserGroupId AND UserGroup.UserGroupId=".$network.")", $XCOW_B['mysql_link']);

        $result_row = mysql_fetch_row($result);

        return ($result_row[0]);
}

?>
