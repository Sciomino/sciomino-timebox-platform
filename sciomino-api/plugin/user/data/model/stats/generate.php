<?

class statsGenerate extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$statsSingleList = array();
	$statsMultipleList = array();
	
	$statsId = 0;
	$statsId = StatsNew();
	if ($statsId == 0 || $statsId == '') {
		$this->status = "Cannot initialize stats";
	}

	$now = time();

	#
	# GO
	#
	if (! $this->status) {

		// get personal stats
		$statsSingleList['UserCount'] = StatsGetUserCount();
		$statsSingleList['MaleCount'] = StatsGetUserAnnotationCount('gender', 'M');
		$statsSingleList['FemaleCount'] = StatsGetUserAnnotationCount('gender', 'V');
		$statsSingleList['UnknownGenderCount'] = $statsSingleList['UserCount'] - $statsSingleList['MaleCount'] - $statsSingleList['FemaleCount'];

		$statsSingleList['Male1Count'] = 0;
		$statsSingleList['Male15Count'] = 0;
		$statsSingleList['Male25Count'] = 0;
		$statsSingleList['Male35Count'] = 0;
		$statsSingleList['Male45Count'] = 0;
		$statsSingleList['Male55Count'] = 0;
		$statsSingleList['Female1Count'] = 0;
		$statsSingleList['Female15Count'] = 0;
		$statsSingleList['Female25Count'] = 0;
		$statsSingleList['Female35Count'] = 0;
		$statsSingleList['Female45Count'] = 0;
		$statsSingleList['Female55Count'] = 0;
		$statsSingleList['UnknownGender1Count'] = 0;
		$statsSingleList['UnknownGender15Count'] = 0;
		$statsSingleList['UnknownGender25Count'] = 0;
		$statsSingleList['UnknownGender35Count'] = 0;
		$statsSingleList['UnknownGender45Count'] = 0;
		$statsSingleList['UnknownGender55Count'] = 0;
		$statsSingleList['UnknownAgeCount'] = 0;
		$statsMultipleList['UserCountXBirthdayDay'] = array();
		$statsMultipleList['UserCountXBirthdayMonth'] = array();

		$dobList = StatsGetDateOfBirthValues();
		foreach ($dobList as $dobKey => $dobVal) {
			if ($dobVal['year'] != '' && $dobVal['month'] != '' && $dobVal['day'] != '') {
				$dob = strtotime($dobVal['year']."-".$dobVal['month']."-".$dobVal['day']);
				$age = date('Y', $now-$dob) - date('Y', 0);
				$month = ltrim($dobVal['month'],"0");
				$day = ltrim($dobVal['day'],"0");

				if (! isset($statsMultipleList['UserCountXBirthdayMonth']["m-".$month]) ) {
					$statsMultipleList['UserCountXBirthdayMonth']["m-".$month] = 1;
				}
				else {
					$statsMultipleList['UserCountXBirthdayMonth']["m-".$month] = $statsMultipleList['UserCountXBirthdayMonth']["m-".$month] + 1;
				}
				if (! isset($statsMultipleList['UserCountXBirthdayDay']["d-".$month."-".$day]) ) {
					$statsMultipleList['UserCountXBirthdayDay']["d-".$month."-".$day] = 1;
				}
				else {
					$statsMultipleList['UserCountXBirthdayDay']["d-".$month."-".$day] = $statsMultipleList['UserCountXBirthdayDay']["d-".$month."-".$day] + 1;
				}

				if ($dobVal['gender'] == "M") {
					if ($age < 15) { $statsSingleList['Male1Count'] = $statsSingleList['Male1Count'] + 1; }
					elseif ($age < 25) { $statsSingleList['Male15Count'] = $statsSingleList['Male15Count'] + 1; }
					elseif ($age < 35) { $statsSingleList['Male25Count'] = $statsSingleList['Male25Count'] + 1; }
					elseif ($age < 45) { $statsSingleList['Male35Count'] = $statsSingleList['Male35Count'] + 1; }
					elseif ($age < 55) { $statsSingleList['Male45Count'] = $statsSingleList['Male45Count'] + 1; }
					else { $statsSingleList['Male55Count'] = $statsSingleList['Male55Count'] + 1; }
				}
				elseif ($dobVal['gender'] == "V") {
					if ($age < 15) { $statsSingleList['Female1Count'] = $statsSingleList['Female1Count'] + 1; }
					elseif ($age < 25) { $statsSingleList['Female15Count'] = $statsSingleList['Female15Count'] + 1; }
					elseif ($age < 35) { $statsSingleList['Female25Count'] = $statsSingleList['Female25Count'] + 1; }
					elseif ($age < 45) { $statsSingleList['Female35Count'] = $statsSingleList['Female35Count'] + 1; }
					elseif ($age < 55) { $statsSingleList['Female45Count'] = $statsSingleList['Female45Count'] + 1; }
					else { $statsSingleList['Male55Count'] = $statsSingleList['Male55Count'] + 1; }
				}
				else {
					if ($age < 15) { $statsSingleList['UnknownGender1Count'] = $statsSingleList['UnknownGender1Count'] + 1; }
					elseif ($age < 25) { $statsSingleList['UnknownGender15Count'] = $statsSingleList['UnknownGender15Count'] + 1; }
					elseif ($age < 35) { $statsSingleList['UnknownGender25Count'] = $statsSingleList['UnknownGender25Count'] + 1; }
					elseif ($age < 45) { $statsSingleList['UnknownGender35Count'] = $statsSingleList['UnknownGender35Count'] + 1; }
					elseif ($age < 55) { $statsSingleList['UnknownGender45Count'] = $statsSingleList['UnknownGender45Count'] + 1; }
					else { $statsSingleList['UnknownGender55Count'] = $statsSingleList['UnknownGender55Count'] + 1; }
				}
			}
			else {
				$statsSingleList['UnknownAgeCount'] = $statsSingleList['UnknownAgeCount'] + 1;
			}
		}

		// get address stats
		$statsSingleList['WorkplaceCount'] = StatsGetAddressCount('city', 'Work');
		$statsSingleList['HometownCount'] = StatsGetAddressCount('city', 'Home');
		$statsSingleList['WorkplaceCountryCount'] = StatsGetAddressCount('country', 'Work');
		$statsSingleList['HometownCountryCount'] = StatsGetAddressCount('country', 'Home');
		$statsMultipleList['UserCountXWorkplace'] = StatsGetAddressValues('city', 'Work');
		$statsMultipleList['UserCountXHometown'] = StatsGetAddressValues('city', 'Home');

		$statsMultipleList['WorkplaceCountXCountry'] = array();
		$statsMultipleList['HometownCountXCountry'] = array();

		$cXcList = StatsGetCityXCountryValues('Work');
		$seen = array();
		foreach ($cXcList as $cXcKey => $cXcVal) {
			$city = $cXcVal['city'];		
			$country = $cXcVal['country'];
			if (! isset($statsMultipleList['WorkplaceCountXCountry'][$country]) ) {
				$statsMultipleList['WorkplaceCountXCountry'][$country] = 1;
				$seen[$country] = array();
				$seen[$country][] = $city;
			}
			else {
				if (! in_array($city, $seen[$country])) {
					$statsMultipleList['WorkplaceCountXCountry'][$country] = $statsMultipleList['WorkplaceCountXCountry'][$country] + 1;
					$seen[$country][] = $city;
				}
			}
		}

		$cXcList = StatsGetCityXCountryValues('Home');
		$seen = array();
		foreach ($cXcList as $cXcKey => $cXcVal) {
			$city = $cXcVal['city'];		
			$country = $cXcVal['country'];
			if (! isset($statsMultipleList['HometownCountXCountry'][$country]) ) {
				$statsMultipleList['HometownCountXCountry'][$country] = 1;
				$seen[$country] = array();
				$seen[$country][] = $city;
			}
			else {
				if (! in_array($city, $seen[$country])) {
					$statsMultipleList['HometownCountXCountry'][$country] = $statsMultipleList['HometownCountXCountry'][$country] + 1;
					$seen[$country][] = $city;
				}
			}
		}

		// get publication stats
		$statsSingleList['UserPublicationCount'] = StatsGetUserPublicationCount();
		$statsSingleList['UserTwitterCount'] = StatsGetUserPublicationValueCount('title', 'twitter', 'SocialNetwork');
		$statsSingleList['UserLinkedinCount'] = StatsGetUserPublicationValueCount('title', 'linkedin', 'SocialNetwork');
		$statsSingleList['UserBlogCount'] = StatsGetUserPublicationSectionCount('Blog');
		$statsSingleList['UserPresentationCount'] = StatsGetUserPublicationSectionCount('Share');
		$statsSingleList['UserWebsiteCount'] = StatsGetUserPublicationSectionCount('Website');
		$statsSingleList['UserOtherPubCount'] = StatsGetUserPublicationSectionCount('Other');
		$statsSingleList['SocialNetworkCount'] = StatsGetPublicationCount('SocialNetwork');
		$statsSingleList['BlogCount'] = StatsGetPublicationCount('Blog');
		$statsSingleList['PresentationCount'] = StatsGetPublicationCount('Share');
		$statsSingleList['WebsiteCount'] = StatsGetPublicationCount('Website');
		$statsSingleList['OtherPubCount'] = StatsGetPublicationCount('Other');

		// get knowledge/hobby/tag stats
		$statsSingleList['UserKnowledgeCount'] = StatsGetUserProfileCount('knowledgefield');
		$statsSingleList['UserHobbyCount'] = StatsGetUserProfileCount('hobbyfield');
		$statsSingleList['UserTagCount'] = StatsGetUserProfileCount('tag');
		$statsSingleList['KnowledgeCount'] = StatsGetUserProfileValueCount('field','knowledgefield');
		$statsSingleList['HobbyCount'] = StatsGetUserProfileValueCount('field', 'hobbyfield');
		$statsSingleList['TagCount'] = StatsGetUserProfileValueCount('name', 'tag');

		// get experience stats
		$statsSingleList['UserExperienceCount'] = StatsGetUserExperienceCount();
		$statsSingleList['ProductCount'] = StatsGetExperienceCount('Product');
		$statsSingleList['CompanyCount'] = StatsGetExperienceCount('Company');
		$statsSingleList['EventCount'] = StatsGetExperienceCount('Event');
		$statsSingleList['EducationCount'] = StatsGetExperienceCount('Education');

		// get best/worst scores
		$productList = StatsGetExperienceValues('Product');
		$companyList = StatsGetExperienceValues('Company');
		$eventList = StatsGetExperienceValues('Event');
		$educationList = StatsGetExperienceValues('Education');
		$statsMultipleList['Score_ProductXSubject'] = array();
		$statsMultipleList['Score_CompanyXSubject'] = array();
		$statsMultipleList['Score_EventXSubject'] = array();
		$statsMultipleList['Score_EducationXSubject'] = array();
		foreach ($productList as $productKey => $productVal) {
			$subject = $productVal['subject'];		
			$title = $productVal['title'];
			$ref = $productVal['subject']."||".$productVal['title'];
			$like = $productVal['like'];
			$score = 0;
			if ($like == 1) {$score = 3;}
			elseif ($like == 2) {$score = 1;}
			elseif ($like == 3) {$score = -1;}
			elseif ($like == 4) {$score = -3;}
			if (! isset($statsMultipleList['Score_ProductXSubject'][$ref]) ) {
				$statsMultipleList['Score_ProductXSubject'][$ref] = $score;
			}
			else {
				$statsMultipleList['Score_ProductXSubject'][$ref] = $statsMultipleList['Score_ProductXSubject'][$ref] + $score;
			}
		}
		foreach ($companyList as $companyKey => $companyVal) {
			$subject = $companyVal['subject'];		
			$title = $companyVal['title'];
			$ref = $companyVal['subject']."||".$companyVal['title'];
			$like = $companyVal['like'];
			$score = 0;
			if ($like == 1) {$score = 3;}
			elseif ($like == 2) {$score = 1;}
			elseif ($like == 3) {$score = -1;}
			elseif ($like == 4) {$score = -3;}
			if (! isset($statsMultipleList['Score_CompanyXSubject'][$ref]) ) {
				$statsMultipleList['Score_CompanyXSubject'][$ref] = $score;
			}
			else {
				$statsMultipleList['Score_CompanyXSubject'][$ref] = $statsMultipleList['Score_CompanyXSubject'][$ref] + $score;
			}
		}
		foreach ($eventList as $eventKey => $eventVal) {
			$subject = $eventVal['subject'];		
			$title = $eventVal['title'];
			$ref = $eventVal['subject']."||".$eventVal['title'];
			$like = $eventVal['like'];
			$score = 0;
			if ($like == 1) {$score = 3;}
			elseif ($like == 2) {$score = 1;}
			elseif ($like == 3) {$score = -1;}
			elseif ($like == 4) {$score = -3;}
			if (! isset($statsMultipleList['Score_EventXSubject'][$ref]) ) {
				$statsMultipleList['Score_EventXSubject'][$ref] = $score;
			}
			else {
				$statsMultipleList['Score_EventXSubject'][$ref] = $statsMultipleList['Score_EventXSubject'][$ref] + $score;
			}
		}
		foreach ($educationList as $educationKey => $educationVal) {
			$subject = $educationVal['subject'];		
			$title = $educationVal['title'];
			$ref = $educationVal['subject']."||".$educationVal['title'];
			$like = $educationVal['like'];
			$score = 0;
			if ($like == 1) {$score = 3;}
			elseif ($like == 2) {$score = 1;}
			elseif ($like == 3) {$score = -1;}
			elseif ($like == 4) {$score = -3;}
			if (! isset($statsMultipleList['Score_EducationXSubject'][$ref]) ) {
				$statsMultipleList['Score_EducationXSubject'][$ref] = $score;
			}
			else {
				$statsMultipleList['Score_EducationXSubject'][$ref] = $statsMultipleList['Score_EducationXSubject'][$ref] + $score;
			}
		}

		// get network counters
		$table = "UserGroup";
		$where = "WHERE UserGroupType = 'public'";
		$order = "";
		$limit = "";
		$expand = 0;
		$networkGroup = UserGroupListWithValues($table, $where, $order, $limit, $expand);
		if (count($networkGroup) > 0) {
			foreach ($networkGroup as $key => $network) {
				$networkName = "network_".$network['id'];
				$statsMultipleList[$networkName] = array();
				$statsMultipleList[$networkName]['UserCount'] = StatsGetUserCountForNetwork($network['id']);

				// get publication stats
				$statsMultipleList[$networkName]['UserPublicationCount'] = StatsGetUserPublicationCountForNetwork($network['id']);
				$statsMultipleList[$networkName]['UserTwitterCount'] = StatsGetUserPublicationValueCountForNetwork($network['id'], 'title', 'twitter', 'SocialNetwork');
				$statsMultipleList[$networkName]['UserLinkedinCount'] = StatsGetUserPublicationValueCountForNetwork($network['id'], 'title', 'linkedin', 'SocialNetwork');
				$statsMultipleList[$networkName]['UserBlogCount'] = StatsGetUserPublicationSectionCountForNetwork($network['id'], 'Blog');
				$statsMultipleList[$networkName]['UserPresentationCount'] = StatsGetUserPublicationSectionCountForNetwork($network['id'], 'Share');
				$statsMultipleList[$networkName]['UserWebsiteCount'] = StatsGetUserPublicationSectionCountForNetwork($network['id'], 'Website');
				$statsMultipleList[$networkName]['UserOtherPubCount'] = StatsGetUserPublicationSectionCountForNetwork($network['id'], 'Other');
				$statsMultipleList[$networkName]['SocialNetworkCount'] = StatsGetPublicationCountForNetwork($network['id'], 'SocialNetwork');
				$statsMultipleList[$networkName]['BlogCount'] = StatsGetPublicationCountForNetwork($network['id'], 'Blog');
				$statsMultipleList[$networkName]['PresentationCount'] = StatsGetPublicationCountForNetwork($network['id'], 'Share');
				$statsMultipleList[$networkName]['WebsiteCount'] = StatsGetPublicationCountForNetwork($network['id'], 'Website');
				$statsMultipleList[$networkName]['OtherPubCount'] = StatsGetPublicationCountForNetwork($network['id'], 'Other');

				// get knowledge/hobby/tag stats
				$statsMultipleList[$networkName]['UserKnowledgeCount'] = StatsGetUserProfileCountForNetwork($network['id'], 'knowledgefield');
				$statsMultipleList[$networkName]['UserHobbyCount'] = StatsGetUserProfileCountForNetwork($network['id'], 'hobbyfield');
				$statsMultipleList[$networkName]['UserTagCount'] = StatsGetUserProfileCountForNetwork($network['id'], 'tag');
				$statsMultipleList[$networkName]['KnowledgeCount'] = StatsGetUserProfileValueCountForNetwork($network['id'], 'field','knowledgefield');
				$statsMultipleList[$networkName]['HobbyCount'] = StatsGetUserProfileValueCountForNetwork($network['id'], 'field', 'hobbyfield');
				$statsMultipleList[$networkName]['TagCount'] = StatsGetUserProfileValueCountForNetwork($network['id'], 'name', 'tag');

				// get experience stats
				$statsMultipleList[$networkName]['UserExperienceCount'] = StatsGetUserExperienceCountForNetwork($network['id']);
				$statsMultipleList[$networkName]['ProductCount'] = StatsGetExperienceCountForNetwork($network['id'], 'Product');
				$statsMultipleList[$networkName]['CompanyCount'] = StatsGetExperienceCountForNetwork($network['id'], 'Company');
				$statsMultipleList[$networkName]['EventCount'] = StatsGetExperienceCountForNetwork($network['id'], 'Event');
				$statsMultipleList[$networkName]['EducationCount'] = StatsGetExperienceCountForNetwork($network['id'], 'Education');
			}
		}

		// update stats
		if (StatsUpdateSingleCounts($statsId, $statsSingleList, "int") == 0) {
			$this->status = "500 Internal Error";
		}

		if (StatsUpdateMultipleCounts($statsId, $statsMultipleList) == 0) {
			$this->status = "500 Internal Error";
		}

        }

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }


}

?>
