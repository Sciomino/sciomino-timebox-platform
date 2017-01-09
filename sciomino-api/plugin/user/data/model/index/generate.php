<?

class indexGenerate extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$userList = array();

	$index = array();
	$index['user'] = array();
	$index['user'][] = 'userFirstName';
	$index['user'][] = 'userLastName';
	$index['user'][] = 'userLoginName';
	$index['userContextIn'] = 'userFirstName';
	$index['userContextOut'] = 'name';
	$index['userContextIn2'] = 'userLastName';
	$index['userContextOut2'] = 'name';
	$index['userAnnotation'] = array();
	$index['userAnnotation'][] = 'description';
	$index['userProfile'] = array();
	$index['userProfile'][] = 'knowledgefield';
	$index['userProfileAnnotation'] = array();
	$index['userProfileAnnotation'][] = 'field';
	$index['userProfileContextIn'] = 'field';
	$index['userProfileContextOut'] = 'knowledge';
	$index['userProfile2'] = array();
	$index['userProfile2'][] = 'tag';
	$index['userProfile2Annotation'] = array();
	$index['userProfile2Annotation'][] = 'name';
	$index['userProfile2ContextIn'] = 'name';
	$index['userProfile2ContextOut'] = 'tag';
	$index['userProfile3'] = array();
	$index['userProfile3'][] = 'hobbyfield';
	$index['userProfile3Annotation'] = array();
	$index['userProfile3Annotation'][] = 'field';
	$index['userProfile3ContextIn'] = 'field';
	$index['userProfile3ContextOut'] = 'hobby';
	$index['contactAnnotation'] = array();
	$index['contactAnnotation'][] = 'email';
	$index['contactAnnotation'][] = 'telExtern';
	$index['contactAnnotation'][] = 'telMobile';
	$index['contactAnnotation'][] = 'telHome';
	$index['contactAnnotation'][] = 'pac';
	$index['contactAnnotation'][] = 'myId';
	$index['address'] = array();
	$index['address'][] = 'Home';
	$index['addressAnnotation'] = array();
	$index['addressAnnotation'][] = 'city';
	$index['addressAnnotation'][] = 'country';
	$index['addressContextIn'] = 'city';
	$index['addressContextIn2'] = 'country';
	$index['addressContextOut'] = 'hometown';
	$index['address2'] = array();
	$index['address2'][] = 'Work';
	$index['address2Annotation'] = array();
	$index['address2Annotation'][] = 'city';
	$index['address2Annotation'][] = 'country';
	$index['address2ContextIn'] = 'city';
	$index['address2ContextIn2'] = 'country';
	$index['address2ContextOut'] = 'workplace';
	$index['organization'] = array();
	$index['organization'][] = 'Current';
	$index['organizationAnnotation'] = array();
	$index['organizationAnnotation'][] = 'role';
	$index['organizationContextIn'] = 'role';
	$index['organizationContextOut'] = 'role';
	$index['organization2'] = array();
	$index['organization2'][] = 'Current';
	$index['organization2Annotation'] = array();
	$index['organization2Annotation'][] = 'division';
	$index['organization2ContextIn'] = 'division';
	$index['organization2ContextOut'] = 'businessunit';
	$index['organization3'] = array();
	$index['organization3'][] = 'Current';
	$index['organization3Annotation'] = array();
	$index['organization3Annotation'][] = 'section';
	$index['organization3ContextIn'] = 'section';
	$index['organization3ContextOut'] = 'section';
	$index['organization4'] = array();
	$index['organization4'][] = 'Current';
	$index['organization4Annotation'] = array();
	$index['organization4Annotation'][] = 'company';
	$index['organization4ContextIn'] = 'company';
	$index['organization4ContextOut'] = 'organization';
	$index['organization5'] = array();
	$index['organization5'][] = 'Current';
	$index['organization5Annotation'] = array();
	$index['organization5Annotation'][] = 'industry';
	$index['organization5ContextIn'] = 'industry';
	$index['organization5ContextOut'] = 'industry';
	$index['publicationAnnotation'] = array();
	#$index['publicationAnnotation'][] = 'title';
	$index['publicationAnnotation'][] = 'description';
	$index['publicationAnnotation'][] = 'relation-self';
	$index['publicationAnnotation'][] = 'relation-other';
	#be warned, if self & other both exist, the blog will be counted twice!
	$index['publicationContextIn'] = 'relation-self';
	$index['publicationContext2In'] = 'relation-other';
	$index['publicationContextOut'] = '';
	$index['publicationContext2Out'] = '';
	$index['experienceAnnotation'] = array();
	$index['experienceAnnotation'][] = 'title';
	$index['experienceAnnotation'][] = 'alternative';
	$index['experienceAnnotation'][] = 'subject';
	$index['experienceAnnotation'][] = 'description';
	$index['experienceAnnotation'][] = 'publisher';
	$index['experienceAnnotation'][] = 'positive1';
	$index['experienceAnnotation'][] = 'positive2';
	$index['experienceAnnotation'][] = 'positive3';
	$index['experienceAnnotation'][] = 'negative1';
	$index['experienceAnnotation'][] = 'negative2';
	$index['experienceAnnotation'][] = 'negative3';
	$index['experienceContextIn1'] = 'subject';
	$index['experienceContextIn2'] = 'title';
	$index['experienceContextIn3'] = 'publisher';
	$index['experienceContextOut'] = '';
	$index['experienceContextOut2'] = '';
	$index['experienceContextOut3'] = '';

	$currentId = 0;
	$currentWords = array();
	$contextWords = array();
	
	#
	# get specific user
	#
	# action = one, update one user
	# action = all, update all users
	# action = del, delete one user
	# action = queue, update one user from queue
	#
	$this->action = $this->ses['request']['param']['action'];
	if (! isset($this->action)) {$this->action = 'none';}	

	$this->userId = $this->ses['request']['param']['userId'];

	# note: DEL must be called if user exist. DEL does not work if user is already deleted!
	if ($this->action == "one" || $this->action == "del") {
		if (isset($this->userId) && $this->userId != '') {
			$userList = UserListWithValues('User', 'WHERE UserId = '.$this->userId, '', '', 1);
		}
		else {
			$this->status = "400 Bad Request (missing userId)";
		}
	}
	elseif ($this->action == "queue") {
		$userIdFromQueueList = array();
		# read 10 entries at a time (if available)
		for ($i=0; $i<10; $i++) {
			$userIdFromQueue = getQueueEntry();
			if ($userIdFromQueue != 0) {
				$userIdFromQueueList[] = $userIdFromQueue;
			}
			else {
				break;
			}
		}
		if (count($userIdFromQueueList) != 0) {
			$userIdString = implode(",", $userIdFromQueueList);
			$userList = UserListWithValues('User', 'WHERE UserId IN ('.$userIdString.')', '', '', 1);
		}
		else {
			$this->status = "400 Bad Request (no entry in queue)";
		}
	}
	# note: ALL is an update, users are NOT deleted this way.
	elseif ($this->action == "all") {
		$userList = userList();
	}
	else {
		$this->status = "400 Bad Request (wrong action)";
	}

	#
	# TRAVERS USERLIST
	# - create a list of words
	#
	if (! $this->status) {

	        # print_r ($userList);
		foreach ($userList as $user) {
			//print_r($user);
			
			$currentId = $user['userId'];
			$currentAccess = $user['access'];
			$currentWords = array();
			$contextWords = array();

			if ($this->action != "del") {
				// use 'data' for invisible users
				// - data=0 if only login & wizard data is present (email, firstname, lastname, birthday, gender)
				// ---firstname and lastname are set in user properties, 
				// ---birthday & gender are not used in the index (these are user annotations)
				// ---email is set in contact
				// - so everything else results in data=1
				$data = 0;
				
				// user properties
				$first = "";
				$second = "";
				$done = 0;
				foreach ($index['user'] as $userIndex) {
					$words = explode (" ", $user[$userIndex]);
					$currentWords = array_merge($currentWords, $words);
					if ($userIndex == $index['userContextIn']) {
						#$contextWord = array($user[$userIndex], $index['userContextOut']);
						#$contextWords[] = $contextWord;
						$first = $user[$userIndex];
					}
					if ($userIndex == $index['userContextIn2']) {
						#$contextWord = array($user[$userIndex], $index['userContextOut2']);
						#$contextWords[] = $contextWord;
						$second = $user[$userIndex];
					}
					if (! $done && $first != '' && $second != '') {
						$contextWord = array($first." ".$second, $index['userContextOut']);
						$contextWords[] = $contextWord;
						$done = 1;
					}
				}

				// user annotations
				foreach ($user['annotation'] as $annotation) {
					if (in_array($annotation['name'], $index['userAnnotation']) && trim($annotation['value']) != "") {
						$words = explode (" ", $annotation['value']);
						$currentWords = array_merge($currentWords, $words);			
						$data = 1;
						if ($annotation['name'] == $index['userAnnotationContextIn']) {
							$contextWord = array($annotation['value'], $index['userAnnotationContextOut']);
							$contextWords[] = $contextWord;
						}
					}
				}

				// user profile annnotations
				foreach ($user['profile'] as $profile) {
					if (in_array($profile['group'], $index['userProfile'])) {
						foreach ($profile['annotation'] as $annotation) {
							if (in_array($annotation['name'], $index['userProfileAnnotation']) && trim($annotation['value']) != "") {
								$words = explode (" ", $annotation['value']);
								$currentWords = array_merge($currentWords, $words);
								$data = 1;
								if ($annotation['name'] == $index['userProfileContextIn']) {
									$contextWord = array($annotation['value'], $index['userProfileContextOut']);
									$contextWords[] = $contextWord;
								}
							}
						}
					}
					if (in_array($profile['group'], $index['userProfile2'])) {
						foreach ($profile['annotation'] as $annotation) {
							if (in_array($annotation['name'], $index['userProfile2Annotation']) && trim($annotation['value']) != "") {
								$words = explode (" ", $annotation['value']);
								$currentWords = array_merge($currentWords, $words);
								$data = 1;

								// also store #tag without "#", to find #boeken with string "boeken"
								$newWord = $annotation['value'];
								if ( (stripos($newWord, "#") !== false) && (stripos($newWord, "#") == 0) ) {
									$newWord = ltrim($newWord, "#");
								}
								$currentWords[] = $newWord;

								if ($annotation['name'] == $index['userProfile2ContextIn']) {
									$contextWord = array($annotation['value'], $index['userProfile2ContextOut']);
									$contextWords[] = $contextWord;
								}
							}
						}
					}
					if (in_array($profile['group'], $index['userProfile3'])) {
						foreach ($profile['annotation'] as $annotation) {
							if (in_array($annotation['name'], $index['userProfile3Annotation']) && trim($annotation['value']) != "") {
								$words = explode (" ", $annotation['value']);
								$currentWords = array_merge($currentWords, $words);
								$data = 1;
								if ($annotation['name'] == $index['userProfile3ContextIn']) {
									$contextWord = array($annotation['value'], $index['userProfile3ContextOut']);
									$contextWords[] = $contextWord;
								}
							}
						}
					}
				}

				// contact
				$contactList = UserSectionList("contact", $currentId);
				foreach ($contactList as $contact) {
					foreach ($contact['annotation'] as $annotation) {
						if (in_array($annotation['name'], $index['contactAnnotation']) && trim($annotation['value']) != "") {
							$words = explode (" ", $annotation['value']);
							$currentWords = array_merge($currentWords, $words);
							if ($annotation['name'] != "email") {
								$data = 1;
							}
						}
					}
				}

				// address
				$addressList = UserSectionList("address", $currentId);
				foreach ($addressList as $address) {
					$first = "";
					$second = "";
					$done = 0;					
					if (in_array($address['name'], $index['address'])) {
						foreach ($address['annotation'] as $annotation) {
							if (in_array($annotation['name'], $index['addressAnnotation']) && trim($annotation['value']) != "") {
								$words = explode (" ", $annotation['value']);
								$currentWords = array_merge($currentWords, $words);
								$data = 1;
								if ($annotation['name'] == $index['addressContextIn']) {
									$first = $annotation['value'];
								}
								if ($annotation['name'] == $index['addressContextIn2']) {
									$second = $annotation['value'];
								}
								if (! $done && $first != '' && $second != '') {
									$contextWord = array($first.", ".$second, $index['addressContextOut']);
									$contextWords[] = $contextWord;
									$done = 1;
								}
							}
						}
					}
					if (in_array($address['name'], $index['address2'])) {
						foreach ($address['annotation'] as $annotation) {
							if (in_array($annotation['name'], $index['address2Annotation']) && trim($annotation['value']) != "") {
								$words = explode (" ", $annotation['value']);
								$currentWords = array_merge($currentWords, $words);
								$data = 1;
								if ($annotation['name'] == $index['address2ContextIn']) {
									$first = $annotation['value'];
								}
								if ($annotation['name'] == $index['address2ContextIn2']) {
									$second = $annotation['value'];
								}
								if (! $done && $first != '' && $second != '') {
									$contextWord = array($first.", ".$second, $index['address2ContextOut']);
									$contextWords[] = $contextWord;
									$done = 1;
								}
							}
						}
					}
				}

				// organization
				$organizationList = UserSectionList("organization", $currentId);
				foreach ($organizationList as $organization) {
					if (in_array($organization['name'], $index['organization'])) {
						foreach ($organization['annotation'] as $annotation) {
							if (in_array($annotation['name'], $index['organizationAnnotation']) && trim($annotation['value']) != "") {
								$words = explode (" ", $annotation['value']);
								$currentWords = array_merge($currentWords, $words);
								$data = 1;
								if ($annotation['name'] == $index['organizationContextIn']) {
									$contextWord = array($annotation['value'], $index['organizationContextOut']);
									$contextWords[] = $contextWord;
								}
							}
						}
					}
					if (in_array($organization['name'], $index['organization2'])) {
						foreach ($organization['annotation'] as $annotation) {
							if (in_array($annotation['name'], $index['organization2Annotation']) && trim($annotation['value']) != "") {
								$words = explode (" ", $annotation['value']);
								$currentWords = array_merge($currentWords, $words);
								$data = 1;
								if ($annotation['name'] == $index['organization2ContextIn']) {
									$contextWord = array($annotation['value'], $index['organization2ContextOut']);
									$contextWords[] = $contextWord;
								}
							}
						}
					}
					if (in_array($organization['name'], $index['organization3'])) {
						foreach ($organization['annotation'] as $annotation) {
							if (in_array($annotation['name'], $index['organization3Annotation']) && trim($annotation['value']) != "") {
								$words = explode (" ", $annotation['value']);
								$currentWords = array_merge($currentWords, $words);
								$data = 1;
								if ($annotation['name'] == $index['organization3ContextIn']) {
									$contextWord = array($annotation['value'], $index['organization3ContextOut']);
									$contextWords[] = $contextWord;
								}
							}
						}
					}
					if (in_array($organization['name'], $index['organization4'])) {
						foreach ($organization['annotation'] as $annotation) {
							if (in_array($annotation['name'], $index['organization4Annotation']) && trim($annotation['value']) != "") {
								$words = explode (" ", $annotation['value']);
								$currentWords = array_merge($currentWords, $words);
								$data = 1;
								if ($annotation['name'] == $index['organization4ContextIn']) {
									$contextWord = array($annotation['value'], $index['organization4ContextOut']);
									$contextWords[] = $contextWord;
								}
							}
						}
					}
					if (in_array($organization['name'], $index['organization5'])) {
						foreach ($organization['annotation'] as $annotation) {
							if (in_array($annotation['name'], $index['organization5Annotation']) && trim($annotation['value']) != "") {
								$words = explode (" ", $annotation['value']);
								$currentWords = array_merge($currentWords, $words);
								$data = 1;
								if ($annotation['name'] == $index['organization5ContextIn']) {
									$contextWord = array($annotation['value'], $index['organization5ContextOut']);
									$contextWords[] = $contextWord;
								}
							}
						}
					}
				}

				// publications
				$publicationList = UserSectionList("publication", $currentId);
				foreach ($publicationList as $publication) {
					$context = $publication['name'];
					foreach ($publication['annotation'] as $annotation) {
						# digg deeper for SocialNetworks! This is tricky...
						if ($context == "SocialNetwork") {
							if ($annotation['name'] == "title") {
								$context = $annotation['value'];
							}
						}
						if (in_array($annotation['name'], $index['publicationAnnotation']) && trim($annotation['value']) != "") {
							$words = explode (" ", $annotation['value']);
							$currentWords = array_merge($currentWords, $words);			
							$data = 1;
							if ($annotation['name'] == $index['publicationContextIn']) {
								$contextWord = array($annotation['value'], $context);
								$contextWords[] = $contextWord;
							}
							if ($annotation['name'] == $index['publicationContext2In']) {
								$contextWord = array($annotation['value'], $context);
								$contextWords[] = $contextWord;
							}
						}
					}
				}

				// experiences
				$experienceList = UserSectionList("experience", $currentId);
				foreach ($experienceList as $experience) {
					$first = "";
					$second = "";
					$done = 0;
					$context = $experience['name'];
					foreach ($experience['annotation'] as $annotation) {
						if (in_array($annotation['name'], $index['experienceAnnotation']) && trim($annotation['value']) != "") {
							$words = explode (" ", $annotation['value']);
							$currentWords = array_merge($currentWords, $words);			
							$data = 1;
							if ($annotation['name'] == $index['experienceContextIn1']) {
								$contextWord = array($annotation['value'], $context);
								$contextWords[] = $contextWord;
								$first = $annotation['value'];
							}
							if ($annotation['name'] == $index['experienceContextIn2']) {
								$second = $annotation['value'];
							}
							if (! $done && $first != '' && $second != '') {
								$contextWord = array($second, $context."-".$first);
								$contextWords[] = $contextWord;
								$done = 1;
							}
							if ($annotation['name'] == $index['experienceContextIn3']) {
								$contextWord = array($annotation['value'], $context."-".$annotation['name']);
								$contextWords[] = $contextWord;
							}
						}
					}
				}

				// final functions
				// - trim words
				// - set to lowercase
				// - remove duplicates
				$currentWords = array_map('my_trim', $currentWords);
				$currentWords = array_map('strtolower', $currentWords);
				$currentWords = array_unique($currentWords);
				//print_r ($currentWords);

				// context words worden niet getrimmed en niet in lower case gezet. Hierdoor is een exacte match mogelijk!
				$contextWords = unique_multi_array_all_fields($contextWords);

				// data=0, makes users invisible
				// - and update user with new access according to data
				//echo "DATA:".$data.", ACCESS:".$currentAccess;
				if ($data == 0) {
					$currentWords = array();
					$contextWords = array();
					
					if ($currentAccess != 4) {
						$userUpdate = array();
						$userUpdate['AccessRuleId'] = 4;
						UserUpdate(array($currentId), $userUpdate);
					}
				}
				if ($data == 1) {
					if ($currentAccess != 1) {
						$userUpdate = array();
						$userUpdate['AccessRuleId'] = 1;
						UserUpdate(array($currentId), $userUpdate);
					}
				}

			}

			// update index
			if (SearchIndexUpdate($currentId, $currentWords, $contextWords) == 0) {
				$this->status = "500 Internal Error";
				break;
			}

		}

        }

    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }


}

?>
