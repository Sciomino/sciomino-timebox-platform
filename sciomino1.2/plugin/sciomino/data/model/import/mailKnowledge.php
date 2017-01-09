<?

class mailKnowledge extends control {

    function Run() {

        global $XCOW_B;
        
		// who?
		$this->id = $this->ses['request']['param']['id'];
		$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

		// mode=send: sendMail
		// default: display
		$this->mode = $this->ses['request']['param']['mode'];
        if (! isset($this->mode)) {$this->mode = 'onlyDisplay';}
		
		// init
		// - mailInterval: frequency of mail in days (one every seven days)
		$mailInterval = 14;
		
		$mailInfo = array();
		$mailPreferenceName = "mailKnowledgePreference";
		$mailPreferenceValue = "";
		$mailPreferenceId = 0;
		$mailLastName = "mailKnowledgeLast";
		$mailLastValue = "";
		$mailLastId = 0;
		$mailLanguageName = "mailLanguage";
		$mailLanguageValue = "";
		
		// check userData for action (but only if userId exists!)
		// 0. do nothing
		// 1. create user data entry if it does not exist.
		// 2. if mailPreference is 1 (the user indicated to receive mail)
		//    AND mailLast < 1 week (the user did not receive mail last week)
		//    then mailLast is now & sendmail;
		$status = "Nothing to be done";
		$action = 0;
		$timeStamp = time() - (24 * 60 * 60 * $mailInterval);
		if ($this->userId != 0) {

			// make sure the user is active, otherwise this mail is not necessary
			if (isActiveFromUserId($this->id) == 1) {

				$userData = UserApiListDataWithQuery("userId=".$this->userId);
				foreach ($userData as $dataId => $dataList) {
					if ($dataList['Name'] == $mailPreferenceName) {
						$mailPreferenceValue = $dataList['Value'];
						$mailPreferenceId = $dataList['Id'];
					}
					if ($dataList['Name'] == $mailLastName) {
						$mailLastValue = $dataList['Value'];
						$mailLastId = $dataList['Id'];
					}
					if ($dataList['Name'] == $mailLanguageName) {
						$mailLanguageValue = $dataList['Value'];
					}
				}
				
				if ($mailPreferenceValue == "" || $mailLastValue == "") {
					$action = 1;
				}
				elseif ($mailPreferenceValue == 1 && $mailLastValue < $timeStamp) {
					$action = 2;
				}
				if ($mailLanguageValue == "") {
					$mailLanguageValue = $XCOW_B['default_language'];
				}
			
				// override normal behaviour for debug purposes
				if ($this->mode == "onlyDisplay") {
					$action=2;
				}
				
			}
		}
		
		// perform action
		// - create defaults for this user
		if ($action == 1) {
			if ($mailPreferenceValue == "") {
				$dataEntry = array();
				$dataEntry['userId'] = $this->userId;
				$dataEntry['name'] = $mailPreferenceName;
				$dataEntry['value'] = $XCOW_B['sciomino']['skin-notify'];
				UserApiSaveData($dataEntry);
			}
			//- spread the mail activity
			$timeDays = mt_rand(0, $mailInterval - 1);
			$timeStamp = time() - (24 * 60 * 60 * $timeDays);
			if ($mailLastValue == "") {
				$dataEntry = array();
				$dataEntry['userId'] = $this->userId;
				$dataEntry['name'] = $mailLastName;
				$dataEntry['value'] = $timeStamp;
				UserApiSaveData($dataEntry);
			}
			
			log2file("Created 'Knowledge Mail' data entry for id: ".$this->id);
			$status = "Created data entry for user";

		}
		// - sendmail for user
		elseif ($action == 2) {
			if ($this->mode != "onlyDisplay") {
				$dataEntry = array();
				$dataEntry['name'] = $mailLastName;
				$dataEntry['value'] = time();
				UserApiUpdateData($mailLastId, $dataEntry);
			}

			$mailInfo['language'] = $mailLanguageValue;
			
			######
			# USER
			######
			$userInfo = current(UserApiListUserById($this->userId));
			$mailInfo['firstName'] = $userInfo['FirstName'];
			$mailInfo['name'] = $userInfo['FirstName']." ".$userInfo['LastName'];
			$mailInfo['email'] = getUserEmailFromUserId($userInfo['Reference']);
			// get photo stuff
			if (! isset($userInfo['photo'])) { $userInfo['photo'] = "/ui/gfx/photo.jpg"; }
			else { $userInfo['photo'] = str_replace("/upload/","/upload/96x96_",$userInfo['photo']); }
			$mailInfo['photo'] = $XCOW_B['this_host'].$userInfo['photo'];
			$mailInfo['photo_url'] = $XCOW_B['this_host'].$XCOW_B['url']."/view?user=".$userInfo['Id'];

			###########
			# KNOWLEDGE
			###########
			# get knowledge list
			$knowledgeList = array();
			$knowledgeList = ScioMinoApiListKnowledge($this->userId);
			$mailInfo['knowledgeCountUser'] = count($knowledgeList);
			# max 50
			$knowledgeList = array_slice($knowledgeList, 0, 50, true);
			#print_r($knowledgeList);

			# get experts on each knowledge field
			$expertList = array();
			foreach ($knowledgeList as $knowledge) {
				$expertQuery = "userId=".$this->userId."&detail=none&k[".urlencode($knowledge['field'])."]=1";
				$searchList = UserApiListSearchWithQuery($expertQuery);
				$expertList[$knowledge['Id']] = $searchList['user'];
			}
			
			function expertSort($a, $b) {
				return count($b) - count($a);
			}
			uasort($expertList, 'expertSort');
			#print_r($expertList);
			
			# now, 1 give & 2 take
			# 1a. kies kennisveld met minste experts waar jij ook expert in bent (daar ben jij uniekste in!)
			# 1b. er zijn x vragen over dit kennisveld, misschien kun jij die beantwoorden => link naar act
			# 2a. kies kennisveld met meeste experts waar jij 'in wil ontwikkelen' (daar kun je het meeste van leren.)
			# 2b. link naar kennispagina met experts
			$sortedKnowledgeList = array();
			$sortedKnowledgeList = array_replace($expertList, $knowledgeList);
			#print_r($sortedKnowledgeList);

			$toTake = 0;
			$toTake = get_id_from_multi_array($sortedKnowledgeList,"level",3);
			$mailInfo['learnFromId'] = $toTake;
			$mailInfo['learnFromExpert'] = array();
			if ($toTake > 0) {
				$mailInfo['learnFromName'] = $sortedKnowledgeList[$toTake]['field'];
				$mailInfo['learnFromExpertCount'] = count($expertList[$toTake]);
				if (count($expertList[$toTake]) > 2) {
					$shuffleValues = array_values($expertList[$toTake]);
					shuffle($shuffleValues);
					$mailInfo['learnFromExpert'] = array_slice($shuffleValues, 0, 2, true);
				}
				else {
					$mailInfo['learnFromExpert'] = $expertList[$toTake];
				}
				$mailInfo['learnFromUrl'] = $XCOW_B['this_host'].$XCOW_B['url']."/browse/knowledge?k=".urlencode($sortedKnowledgeList[$toTake]['field']);
			}
			$begin = reset($sortedKnowledgeList);
			$mailInfo['knowledgeMostShared'] = $begin['field'];
			$mailInfo['knowledgeMostSharedUrl'] = $XCOW_B['this_host'].$XCOW_B['url']."/browse/knowledge?k=".urlencode($mailInfo['knowledgeMostShared']);
			$beginCount = reset($expertList);
			$mailInfo['knowledgeMostSharedCount'] = count($beginCount) - 1;
			if (count(current($expertList)) > 2) {
				$shuffleValues = array_values(current($expertList));
				$shuffleValues = array_diff($shuffleValues, array($this->userId));
				shuffle($shuffleValues);
				$mailInfo['knowledgeMostSharedUser'] = array_slice($shuffleValues, 0, 2, true);
			}
			else {
				$mailInfo['knowledgeMostSharedUser'] = array();
				if (count(current($expertList)) > 0) {
					$mailInfo['knowledgeMostSharedUser'] = current($expertList);
					$mailInfo['knowledgeMostSharedUser'] = array_diff($mailInfo['knowledgeMostSharedUser'], array($this->userId));
				}
			}
			# get a random expert to exchange ideas with
			$multipleExpertList = array();
			foreach ($expertList as $expertKey => $expertValue) {
				if (count($expertValue) > 1) {
					$multipleExpertList[$expertKey] = $expertValue;
				}
			}
			$mailInfo['knowledgeRandomSharedUser'] = array();
			if (count($multipleExpertList) > 0) {
				$random = array_rand($multipleExpertList);
				$mailInfo['knowledgeRandomShared'] = $sortedKnowledgeList[$random]['field'];
				$mailInfo['knowledgeRandomSharedUrl'] = $XCOW_B['this_host'].$XCOW_B['url']."/browse/knowledge?k=".urlencode($mailInfo['knowledgeRandomShared']);
				$mailInfo['knowledgeRandomSharedCount'] = count($expertList[$random]) - 1;
				if (count($expertList[$random]) > 2) {
					$shuffleValues = array_values($expertList[$random]);
					$shuffleValues = array_diff($shuffleValues, array($this->userId));
					shuffle($shuffleValues);
					$mailInfo['knowledgeRandomSharedUser'] = array_slice($shuffleValues, 0, 2, true);
				}
				else {
					$mailInfo['knowledgeRandomSharedUser'] = $expertList[$random];
					$mailInfo['knowledgeRandomSharedUser'] = array_diff($mailInfo['knowledgeRandomSharedUser'], array($this->userId));
				}
			}

			$sortedKnowledgeList = array_reverse($sortedKnowledgeList, true);
			$toGive = 0;
			$toGive = get_id_from_multi_array($sortedKnowledgeList,"level",1);
			$mailInfo['helpWithId'] = $toGive;
			if ($toGive > 0 ) {
				$mailInfo['helpWithName'] = $sortedKnowledgeList[$toGive]['field'];
				$mailInfo['helpWithExpertCount'] = count($expertList[$toGive]) - 1;
				$mailInfo['helpWithUrl'] = $XCOW_B['this_host'].$XCOW_B['url']."/browse/knowledge?k=".urlencode($sortedKnowledgeList[$toGive]['field']);
				
				$searchList = array();
				$searchList = AnswersApiListSearchWithQuery("reference=".$this->id."&order=time&direction=desc&s[open]&k[".urlencode($sortedKnowledgeList[$toGive]['field'])."]");
				#print_r($searchList);
				$mailInfo['helpWithActCount'] = count($searchList['act']);
				if (count($searchList['act'] > 0)) {
					# relevant acts found!
					$mailInfo['helpWithActUrl'] = $XCOW_B['this_host'].$XCOW_B['url']."/act?s[open]&k[".urlencode($sortedKnowledgeList[$toGive]['field'])."]";
				}
			}

			$mailInfo['generalActUrl'] = $XCOW_B['this_host'].$XCOW_B['url']."/act?s[relevant]";

			######
			# ACTS
			######
			// - get all acts with knowledgefields the user is expert in
			$query = "open=1&profile_param=any&limit=3&order=time&direction=desc&reference_match=not&reference=".$this->id;
			foreach ($knowledgeList as $knowledge) {
				// pick the expert level
				if ($knowledge['level'] == "1") {
					$query .= "&profile[knowledgefield][field][".urlencode($knowledge['field'])."]";
				}
			}
			$mailInfo['acts'] = AnswersApiListActWithQuery($query);
			if (count($mailInfo['acts']) == 0) {
				# get the latest acts
				$query = "open=1&limit=3&order=time&direction=desc&reference_match=not&reference=".$this->id;
				$mailInfo['acts'] = AnswersApiListActWithQuery($query);
			}

			#######
			# HOBBY
			#######
			# get hobby list
			$hobbyList = array();
			$hobbyList = ScioMinoApiListHobby($this->userId);
			$mailInfo['hobbyCountUser'] = count($hobbyList);
			# max 50
			$hobbyList = array_slice($hobbyList, 0, 50, true);
			# print_r ($hobbyList);

			# get interested users on each hobby field
			$interestList = array();
			foreach ($hobbyList as $hobby) {
				$interestQuery = "userId=".$this->userId."&detail=none&h[".urlencode($hobby['field'])."]";
				$searchList = UserApiListSearchWithQuery($interestQuery);
				$interestList[$hobby['Id']] = $searchList['user'];
			}
			
			function interestSort($a, $b) {
				return count($b) - count($a);
			}
			uasort($interestList, 'interestSort');
			# print_r($interestList);
			
			# now what are your most shared interests and unique interests
			$sortedHobbyList = array();
			$sortedHobbyList = array_replace($interestList, $hobbyList);
			# print_r($sortedHobbyList);

			$end = end($sortedHobbyList);
			$begin = reset($sortedHobbyList);
			$mailInfo['hobbyMostUnique'] = $end['field'];
			$mailInfo['hobbyMostShared'] = $begin['field'];
			$mailInfo['hobbyMostUniqueUrl'] = $XCOW_B['this_host'].$XCOW_B['url']."/browse/hobby?h=".urlencode($mailInfo['hobbyMostUnique']);
			$mailInfo['hobbyMostSharedUrl'] = $XCOW_B['this_host'].$XCOW_B['url']."/browse/hobby?h=".urlencode($mailInfo['hobbyMostShared']);
			$endCount = end($interestList);
			$beginCount = reset($interestList);
			$mailInfo['hobbyMostUniqueCount'] = count($endCount) - 1;
			$mailInfo['hobbyMostSharedCount'] = count($beginCount) - 1;
			if (count(current($interestList)) > 2) {
				$shuffleValues = array_values(current($interestList));
				$shuffleValues = array_diff($shuffleValues, array($this->userId));
				shuffle($shuffleValues);
				$mailInfo['hobbyMostSharedUser'] = array_slice($shuffleValues, 0, 2, true);
			}
			else {
				$mailInfo['hobbyMostSharedUser'] = array();
				if (count(current($interestList)) > 0) {
					$mailInfo['hobbyMostSharedUser'] = current($interestList);
					$mailInfo['hobbyMostSharedUser'] = array_diff($mailInfo['hobbyMostSharedUser'], array($this->userId));
				}
			}
			# get a random interest to exchange ideas with
			$multipleInterestList = array();
			foreach ($interestList as $interestKey => $interestValue) {
				if (count($interestValue) > 1) {
					$multipleInterestList[$interestKey] = $interestValue;
				}
			}
			$mailInfo['hobbyRandomSharedUser'] = array();
			if (count($multipleInterestList) > 0) {
				$random = array_rand($multipleInterestList);
				$mailInfo['hobbyRandomShared'] = $sortedHobbyList[$random]['field'];
				$mailInfo['hobbyRandomSharedUrl'] = $XCOW_B['this_host'].$XCOW_B['url']."/browse/hobby?h=".urlencode($mailInfo['hobbyRandomShared']);
				$mailInfo['hobbyRandomSharedCount'] = count($interestList[$random]) - 1;
				if (count($interestList[$random]) > 2) {
					$shuffleValues = array_values($interestList[$random]);
					$shuffleValues = array_diff($shuffleValues, array($this->userId));
					shuffle($shuffleValues);
					$mailInfo['hobbyRandomSharedUser'] = array_slice($shuffleValues, 0, 2, true);
				}
				else {
					$mailInfo['hobbyRandomSharedUser'] = $interestList[$random];
					$mailInfo['hobbyRandomSharedUser'] = array_diff($mailInfo['hobbyRandomSharedUser'], array($this->userId));
				}
			}

			#######
			# STATS
			#######
			$statsList = array();
			$statsList = current(UserApiListStats("SC_UserApiListStats"));
			#print_r($statsList);
			$mailInfo['knowledgeCountAverage'] = round($statsList['KnowledgeCount']/$statsList['UserKnowledgeCount']);
			$mailInfo['hobbyCountAverage'] = round($statsList['HobbyCount']/$statsList['UserHobbyCount']);

			#######
			# USERS
			#######
			$userString = "";
			$otherUserList = "";
			$refSeen = array();
			foreach ($mailInfo['learnFromExpert'] as $uid) {
				if (! in_array($uid, $refSeen)) {
					$refSeen[] = $uid;
					$userString .= "user[".$uid."]&";
				}
			}
			foreach ($mailInfo['knowledgeMostSharedUser'] as $uid) {
				if (! in_array($uid, $refSeen)) {
					$refSeen[] = $uid;
					$userString .= "user[".$uid."]&";
				}
			}
			foreach ($mailInfo['knowledgeRandomSharedUser'] as $uid) {
				if (! in_array($uid, $refSeen)) {
					$refSeen[] = $uid;
					$userString .= "user[".$uid."]&";
				}
			}
			foreach ($mailInfo['acts'] as $actKey => $actValue) {
				$uid = UserApiGetUserFromReference($actValue['Reference']);
				if (! in_array($uid, $refSeen)) {
					$refSeen[] = $uid;
					$userString .= "user[".$uid."]&";
				}
			}
			foreach ($mailInfo['hobbyMostSharedUser'] as $uid) {
				if (! in_array($uid, $refSeen)) {
					$refSeen[] = $uid;
					$userString .= "user[".$uid."]&";
				}
			}
			foreach ($mailInfo['hobbyRandomSharedUser'] as $uid) {
				if (! in_array($uid, $refSeen)) {
					$refSeen[] = $uid;
					$userString .= "user[".$uid."]&";
				}
			}
			$userString = rtrim($userString, "&");
			$userString .= "&format=short";
			$otherUserList = UserApiListUserWithQuery($userString);

			$mailInfo['otherUsers'] = array();
			foreach($otherUserList as $id => $otherUser) {
				$otherUserId = $otherUser['Id'];
				$mailInfo['otherUsers'][$otherUserId] = array();
				$mailInfo['otherUsers'][$otherUserId]['name'] = $otherUser['FirstName']." ".$otherUser['LastName'];
				$mailInfo['otherUsers'][$otherUserId]['url'] = $XCOW_B['this_host'].$XCOW_B['url']."/view?user=".$otherUserId;
				if (! isset($otherUser['photo'])) { $otherUser['photo'] = "/ui/gfx/photo.jpg"; }
				else { $otherUser['photo'] = str_replace("/upload/","/upload/96x96_",$otherUser['photo']); }
				$mailInfo['otherUsers'][$otherUserId]['photo'] = $XCOW_B['this_host'].$XCOW_B['url'].$otherUser['photo'];
			}
			
			######
			# MAIL
			######
			#print_r($mailInfo);

			// addresses
			$receiver_name = $mailInfo['name'];
			$receiver_mail = $mailInfo['email'];

			$sender_name = $XCOW_B['this_name'];
			$sender_mail = $XCOW_B['this_mail'];

			// subject
			$subject = "";
			switch ($mailLanguageValue) {
				case "en":
					$subject = "This week on ".$XCOW_B['this_name'];
					break;
				default:
					$subject = "Deze week op ".$XCOW_B['this_name'];
			}

			// intro
			$mailInfo['intro'] = "";
			switch ($mailLanguageValue) {
				case "en":
					$mailInfo['intro'] .= "Hi ".$mailInfo['firstName'].", this is a quick and easy way to see how you can help others, who to reach out to, or who to meet for coffee (or tea). We hope it's helpful!";
					break;
				default:
					$mailInfo['intro'] .= "Hoi ".$mailInfo['firstName'].", dit is een makkelijke manier om te zien of je anderen kunt helpen, benaderen of uitnodigen voor koffie (of thee). We hopen dat deze mail nuttig is!";
			}

			// inzichten
			$mailInfo['inzichten'] = "";
			switch ($mailLanguageValue) {
				case "en":
					$mailInfo['inzichten'] .= "You have <b>".$mailInfo['knowledgeCountUser']." skills</b>, on average people have <b>".$mailInfo['knowledgeCountAverage']."</b><br/>You have <b>".$mailInfo['hobbyCountUser']." hobbies</b>, on average people have <b>".$mailInfo['hobbyCountAverage']."</b><br/>";
					break;
				default:
					$mailInfo['inzichten'] .= "Je hebt <b>".$mailInfo['knowledgeCountUser']." kennisvelden</b>, gemiddeld heeft men er <b>".$mailInfo['knowledgeCountAverage']."</b><br/>Je hebt <b>".$mailInfo['hobbyCountUser']." hobbies</b>, gemiddeld heeft men er <b>".$mailInfo['hobbyCountAverage']."</b><br/>";
			}
			
			$kms = "";
			$hms = "";
			if (isset ($mailInfo['knowledgeMostShared']) && $mailInfo['knowledgeMostSharedCount'] > 0) {
				switch ($mailLanguageValue) {
					case "en":
						$kms = "<a href='".$mailInfo['knowledgeMostSharedUrl']."'>".$mailInfo['knowledgeMostShared']."</a> (".$mailInfo['knowledgeMostSharedCount']." others)";
						break;
					default:
						$kms = "<a href='".$mailInfo['knowledgeMostSharedUrl']."'>".$mailInfo['knowledgeMostShared']."</a> (".$mailInfo['knowledgeMostSharedCount']." anderen)";
				}
			}
			if (isset ($mailInfo['hobbyMostShared']) && $mailInfo['hobbyMostSharedCount'] > 0) {
				switch ($mailLanguageValue) {
					case "en":
						$hms = "<a href='".$mailInfo['hobbyMostSharedUrl']."'>".$mailInfo['hobbyMostShared']."</a> (".$mailInfo['hobbyMostSharedCount']." others)";
						break;
					default:
						$hms = "<a href='".$mailInfo['hobbyMostSharedUrl']."'>".$mailInfo['hobbyMostShared']."</a> (".$mailInfo['hobbyMostSharedCount']." anderen)";
				}
			}
			if ($kms != "" || $hms != "") {
				$msList = array();
				if ($kms != "") { $msList[] = $kms; }
				if ($hms != "") { $msList[] = $hms; }
				switch ($mailLanguageValue) {
					case "en":
						$mailInfo['inzichten'] .= "Most common: ".implode(' and ', $msList)."<br/>";
						break;
					default:
						$mailInfo['inzichten'] .= "Gemene delers: ".implode(' en ', $msList)."<br/>";
				}
			}
			
			$kmu = "";
			$hmu = "";
			if ($mailInfo['helpWithId'] != 0) {
				switch ($mailLanguageValue) {
					case "en":
						$kmu = "<a href='".$mailInfo['helpWithUrl']."'>".$mailInfo['helpWithName']."</a> (".$mailInfo['helpWithExpertCount']." others)";
						break;
					default:
						$kmu = "<a href='".$mailInfo['helpWithUrl']."'>".$mailInfo['helpWithName']."</a> (".$mailInfo['helpWithExpertCount']." anderen)";
				}
			}
			if (isset ($mailInfo['hobbyMostUnique'])) {
				switch ($mailLanguageValue) {
					case "en":
						$hmu = "<a href='".$mailInfo['hobbyMostUniqueUrl']."'>".$mailInfo['hobbyMostUnique']."</a> (".$mailInfo['hobbyMostUniqueCount']." others)";
						break;
					default:
						$hmu = "<a href='".$mailInfo['hobbyMostUniqueUrl']."'>".$mailInfo['hobbyMostUnique']."</a> (".$mailInfo['hobbyMostUniqueCount']." anderen)";
				}
			}
			if ($kmu != "" || $hmu != "") {
				$muList = array();
				if ($kmu != "") { $muList[] = $kmu; }
				if ($hmu != "") { $muList[] = $hmu; }
				switch ($mailLanguageValue) {
					case "en":
						$mailInfo['inzichten'] .= "Uniqueness: ".implode(' and ', $muList)."<br/>";
						break;
					default:
						$mailInfo['inzichten'] .= "Uniek: ".implode(' en ', $muList)."<br/>";
				}
			}

			// 1. Deel je kennis
			$mailInfo['deeljekennis'] = "";
			$mailInfo['deeljekennis-list'] = "";
			if (count($mailInfo['acts']) > 0) {
				switch ($mailLanguageValue) {
					case "en":
						$mailInfo['deeljekennis'] .= "Can you help the following people?";
						break;
					default:
						$mailInfo['deeljekennis'] .= "Kun jij de volgende mensen een stap verder helpen?";
				}
				$mailInfo['deeljekennis-list'] .= $this->getActList($mailInfo['acts'], $mailInfo['otherUsers']);
			}
		
			$mailInfo['deeljekennis2'] = "";
			if ($kmu != "") {
				if ($mailInfo['helpWithActCount'] > 0) {
					switch ($mailLanguageValue) {
						case "en":
							$mailInfo['deeljekennis2'] .= "<b>".$mailInfo['helpWithName']."</b> seperates you from others. There are currently ".$mailInfo['helpWithActCount']." messages. ";
							$mailInfo['deeljekennis2'] .= "<a href='".$mailInfo['helpWithActUrl']."'>Maybe you can help?</a>";
							break;
						default:
							$mailInfo['deeljekennis2'] .= "<b>".$mailInfo['helpWithName']."</b> onderscheid je van de rest. Daar zijn nu ".$mailInfo['helpWithActCount']." berichten over. ";
							$mailInfo['deeljekennis2'] .= "<a href='".$mailInfo['helpWithActUrl']."'>Misschien kun jij helpen?</a>";
					}
				}
			}
			
			if ($mailInfo['deeljekennis'] == "" && $mailInfo['deeljekennis2'] == "") {
				switch ($mailLanguageValue) {
					case "en":
						$mailInfo['deeljekennis'] .= "Currently there are no matching questions to your expertise. Don't forget to <a href='".$mailInfo['generalActUrl']."'>use each other's talents!</a>";
						break;
					default:
						$mailInfo['deeljekennis'] .= "Momenteel zijn er geen relevante vragen voor jouw kennisgebieden. Vergeet niet <a href='".$mailInfo['generalActUrl']."'>elkaars talenten te benutten!</a>";
				}
			}

			// 2. Ontwikkel jezelf
			$mailInfo['ontwikkeljezelf'] = "";
			if ($mailInfo['learnFromId'] != 0) {
				switch ($mailLanguageValue) {
					case "en":
						$mailInfo['ontwikkeljezelf'] .= "You want to learn more about <b>".$mailInfo['learnFromName']."</b>. <a href='".$mailInfo['learnFromUrl']."'>You might not be alone.</a> ";			
						break;
					default:
						$mailInfo['ontwikkeljezelf'] .= "Je wilt je ontwikkelen in <b>".$mailInfo['learnFromName']."</b>. <a href='".$mailInfo['learnFromUrl']."'>Misschien ben je niet de enige.</a> ";			
				}
			}

			$mailInfo['ontwikkeljezelf2'] = "";
			$mailInfo['ontwikkeljezelf2-list'] = "";
			if ($mailInfo['learnFromExpertCount'] > 0) {
				switch ($mailLanguageValue) {
					case "en":
						$mailInfo['ontwikkeljezelf2'] .= "You can also connect to an expert, for example:";
						break;
					default:
						$mailInfo['ontwikkeljezelf2'] .= "Je kunt ook contact zoeken met een expert, bijvoorbeeld:";
				}
				$mailInfo['ontwikkeljezelf2-list'] .= $this->getUserList($mailInfo['learnFromExpert'], $mailInfo['otherUsers']);
			}

			if ($mailInfo['ontwikkeljezelf'] == "" && $mailInfo['ontwikkeljezelf2'] == "") {
				switch ($mailLanguageValue) {
					case "en":
						$mailInfo['ontwikkeljezelf'] .= "Tell us about the skills you would like to develop, and we connect you to the experts.";
						break;
					default:
						$mailInfo['ontwikkeljezelf'] .= "Vertel ons waarin jij je wilt ontwikkelen, en wij verbinden je met de experts.";
				}
			}
			
			// 3. Wissel ervaringen uit
			$mailInfo['wisselervaringenuit2'] = "";
			$mailInfo['wisselervaringenuit2-list'] = "";
			if (count($mailInfo['knowledgeRandomSharedUser']) != 0) {
				switch ($mailLanguageValue) {
					case "en":
						$mailInfo['wisselervaringenuit2'] .= "<a href='".$mailInfo['knowledgeRandomSharedUrl']."'>".$mailInfo['knowledgeRandomSharedCount']." others</a> with knowledge of <b>".$mailInfo['knowledgeRandomShared']."</b>, for example:";
						break;
					default:
						$mailInfo['wisselervaringenuit2'] .= "<a href='".$mailInfo['knowledgeRandomSharedUrl']."'>".$mailInfo['knowledgeRandomSharedCount']." anderen</a> met kennis van <b>".$mailInfo['knowledgeRandomShared']."</b>, bijvoorbeeld:";
				}
				$mailInfo['wisselervaringenuit2-list'] .= $this->getUserList($mailInfo['knowledgeRandomSharedUser'], $mailInfo['otherUsers']);
			}

			$mailInfo['wisselervaringenuit3'] = "";
			$mailInfo['wisselervaringenuit3-list'] = "";
			if (count($mailInfo['hobbyRandomSharedUser']) != 0) {
				switch ($mailLanguageValue) {
					case "en":
						$mailInfo['wisselervaringenuit3'] .= "<a href='".$mailInfo['hobbyRandomSharedUrl']."'>".$mailInfo['hobbyRandomSharedCount']." others</a> with hobby <b>".$mailInfo['hobbyRandomShared']."</b>, for example:";
						break;
					default:
						$mailInfo['wisselervaringenuit3'] .= "<a href='".$mailInfo['hobbyRandomSharedUrl']."'>".$mailInfo['hobbyRandomSharedCount']." anderen</a> met hobby <b>".$mailInfo['hobbyRandomShared']."</b>, bijvoorbeeld:";
				}
				$mailInfo['wisselervaringenuit3-list'] .= $this->getUserList($mailInfo['hobbyRandomSharedUser'], $mailInfo['otherUsers']);
			}

			$mailInfo['wisselervaringenuit'] = "";
			if ($mailInfo['wisselervaringenuit2'] == "" && $mailInfo['wisselervaringenuit3'] == "") {
				switch ($mailLanguageValue) {
					case "en":
						$mailInfo['wisselervaringenuit'] .= "At the moment your skills are unique. Later on we will show you people with the same skills and hobbies so you can easily connect to peers. ";
						break;
					default:
						$mailInfo['wisselervaringenuit'] .= "Op dit moment is jouw kennis nog uniek. Later zullen we je anderen tonen die op jou lijken zodat je ervaringen kunt uitwisselen.";
				}
			} 
			else {
				switch ($mailLanguageValue) {
					case "en":
						$mailInfo['wisselervaringenuit'] .= "Great minds think alike. Exchange experiences with people with the same knowledge or hobbies.";
						break;
					default:
						$mailInfo['wisselervaringenuit'] .= "Great minds think alike. Wissel ervaringen uit met degenen die dezelfde kennis of hobbies hebben.";
				}
			}
			
			// body
			$bodyFiller['intro'] = $mailInfo['intro'];
			$bodyFiller['avatar'] = $mailInfo['photo'];
			$bodyFiller['avatar_url'] = $mailInfo['photo_url'];
			$bodyFiller['inzichten'] = $mailInfo['inzichten'];
			$bodyFiller['deeljekennis'] = $mailInfo['deeljekennis'];
			$bodyFiller['deeljekennis-list'] = $mailInfo['deeljekennis-list'];
			$bodyFiller['deeljekennis2'] = $mailInfo['deeljekennis2'];
			$bodyFiller['ontwikkeljezelf'] = $mailInfo['ontwikkeljezelf'];
			$bodyFiller['ontwikkeljezelf2'] = $mailInfo['ontwikkeljezelf2'];
			$bodyFiller['ontwikkeljezelf2-list'] = $mailInfo['ontwikkeljezelf2-list'];
			$bodyFiller['wisselervaringenuit'] = $mailInfo['wisselervaringenuit'];
			$bodyFiller['wisselervaringenuit2'] = $mailInfo['wisselervaringenuit2'];
			$bodyFiller['wisselervaringenuit2-list'] = $mailInfo['wisselervaringenuit2-list'];
			$bodyFiller['wisselervaringenuit3'] = $mailInfo['wisselervaringenuit3'];
			$bodyFiller['wisselervaringenuit3-list'] = $mailInfo['wisselervaringenuit3-list'];
			$bodyFiller['url'] = $XCOW_B['this_host'].$XCOW_B['url'];

			$body = mail_template('3.1.knowledge-update', $bodyFiller, $mailLanguageValue);

			// names AND emails are checked in sendMail
			if ($this->mode == "onlyDisplay") {
				$status = $body;
			}
			if ($this->mode == "send") {
				$sendStatus = sendMailWithHTML($sender_name, $sender_mail, $receiver_name, $receiver_mail, $subject, $body);
				log2file("Send Knowledge Mail to: ".$receiver_mail);

				if ($sendStatus == 0) { $status = language('sciomio_mail_knowledge_status_wrong'); }
				if ($sendStatus == 1) { $status = language('sciomio_mail_knowledge_status_ok'); }

			}
		}

		# output
		$this->ses['response']['param']['status'] = $status;

     }
     
     function getUserList($ids, $users) {
		 
        global $XCOW_B;

		$userString = "";
		 
		$userString .= '<table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateHeading" style="-webkit-text-size-adjust: none !important; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse !important; background-color: #FFFFFF;" bgcolor="#FFFFFF">';
		foreach ($ids as $id) {
			$userString .= '<tr>';
			$userString .= '<td valign="top" class="avatar" width="48" style="-webkit-text-size-adjust: none !important; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #FDC02A; font-family: \'Helvetica Neue\',Helvetica,Arial; font-size: 20px; font-weight: normal; line-height: 100%; text-align: left; vertical-align: top; background-color: #FFFFFF; padding: 0 20px 10px;" align="left" bgcolor="#FFFFFF">';
			$userString .= '<a href="'.$users[$id]['url'].'" target="_blank" style="-webkit-text-size-adjust: none !important; -ms-text-size-adjust: 100%; color: #1388BF; font-weight: normal; text-decoration: underline;"><img alt="" src="'.$users[$id]['photo'].'" style="max-width: 48px; width: 48px; height: 48px; -ms-interpolation-mode: bicubic; line-height: 100%; outline: none; text-decoration: none; border: 0;" id="avatarImage" /></a>';
			$userString .= '</td>';
			$userString .= '<td valign="top" class="bodyHeading" style="-webkit-text-size-adjust: none !important; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; font-family: Helvetica; font-size:  16px !important; line-height: 100%; text-align: left; padding: 15px 20px 0 0;" align="left">';
			$userString .= '<b>'.$users[$id]['name'].'</b>';
			$userString .= '</td>';
			$userString .= '</tr>';
		}
		$userString .= '</table>';

		return $userString;
	 }

     function getActList($acts, $users) {
		 
        global $XCOW_B;

		$actString = "";
		 
		$actString .= '<table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateHeading" style="-webkit-text-size-adjust: none !important; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse !important; background-color: #FFFFFF;" bgcolor="#FFFFFF">';
		foreach ($acts as $act) {
			$actUser = UserApiGetUserFromReference($act['Reference']);
			$actString .= '<tr>';
			$actString .= '<td valign="top" class="avatar" width="48" style="-webkit-text-size-adjust: none !important; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #FDC02A; font-family: \'Helvetica Neue\',Helvetica,Arial; font-size: 20px; font-weight: normal; line-height: 100%; text-align: left; vertical-align: top; background-color: #FFFFFF; padding: 0 20px 10px;" align="left" bgcolor="#FFFFFF">';
			$actString .= '<a href="'.$users[$actUser]['url'].'" target="_blank" style="-webkit-text-size-adjust: none !important; -ms-text-size-adjust: 100%; color: #1388BF; font-weight: normal; text-decoration: underline;"><img alt="" src="'.$users[$actUser]['photo'].'" style="max-width: 48px; width: 48px; height: 48px; -ms-interpolation-mode: bicubic; line-height: 100%; outline: none; text-decoration: none; border: 0;" id="avatarImage" /></a>';
			$actString .= '</td>';
			$actString .= '<td valign="top" class="bodyHeading" style="-webkit-text-size-adjust: none !important; -ms-text-size-adjust: 100%; mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; font-family: Helvetica; font-size:  16px !important; line-height: 100%; text-align: left; padding: 0px 20px 0 0;" align="left">';
			$actString .= '<b>'.$users[$actUser]['name'].'</b>';
			$actString .= '<br/>'.$act['Description'];
			$actString .= ' (<a href="'.$XCOW_B['this_host'].$XCOW_B['url'].'/act/view?act='.$act['Id'].'">Toon bericht</a>)<br/><br/>';
			$actString .= '</td>';
			$actString .= '</tr>';
		}
		$actString .= '</table>';

		return $actString;
	 }

}

?>
