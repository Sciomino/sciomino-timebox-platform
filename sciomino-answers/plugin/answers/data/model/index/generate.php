<?

class indexGenerate extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$actList = array();

	$index = array();
	$index['act'] = array();
	$index['act'][] = 'description';
	$index['actContextIn'] = 'description';
	$index['actContextOut'] = 'tag';
	$index['actAnnotation'] = array();
	$index['actAnnotation'][] = 'network';
	$index['actAnnotationContextIn'] = 'network';
	$index['actAnnotationContextOut'] = 'network';
	$index['actProfile'] = array();
	$index['actProfile'][] = 'knowledgefield';
	$index['actProfileAnnotation'] = array();
	$index['actProfileAnnotation'][] = 'field';
	$index['actProfileContextIn'] = 'field';
	$index['actProfileContextOut'] = 'knowledge';
	$index['actProfile2'] = array();
	$index['actProfile2'][] = 'hobbyfield';
	$index['actProfile2Annotation'] = array();
	$index['actProfile2Annotation'][] = 'field';
	$index['actProfile2ContextIn'] = 'field';
	$index['actProfile2ContextOut'] = 'hobby';
	$index['user'] = array();
	$index['user'][] = 'FirstName';
	$index['user'][] = 'LastName';
	$index['userContextIn'] = 'FirstName';
	$index['userContextOut'] = 'name';
	$index['userContextIn2'] = 'LastName';
	$index['userContextOut2'] = 'name';
	$index['address'] = array();
	$index['address'][] = 'Work';
	$index['addressContextIn'] = 'city';
	$index['addressContextOut'] = 'workplace';
	$index['organization'] = array();
	$index['organization'][] = 'Current';
	$index['organizationAnnotation'] = array();
	$index['organizationAnnotation'][] = 'division';
	$index['organizationContextIn'] = 'division';
	$index['organizationContextOut'] = 'businessunit';

	$currentId = 0;
	$currentWords = array();
	$contextWords = array();
	
	#
	# get specific act
	#
	# action = one, update one act
	# action = all, update all acts
	# action = del, delete one act
	# action = queue, update one act from queue
	#
	$this->action = $this->ses['request']['param']['action'];
	if (! isset($this->action)) {$this->action = 'none';}	

	$this->actId = $this->ses['request']['param']['actId'];

	if ($this->action == "one" || $this->action == "del") {
		if (isset($this->actId) && $this->actId != '') {
			$actList = ActListWithValues('Act', 'WHERE ActParent = 0 AND ActId = '.$this->actId, '', '', 1);

			// if actId is missing, it is deleted, do so...
			if (count($actList) == 0) {
				SearchIndexUpdate($this->actId, array(), array());
			}
		}
		else {
			$this->status = "400 Bad Request (missing actId)";
		}
	}
	elseif ($this->action == "queue") {
		$actIdFromQueueList = array();
		# read 10 entries at a time (if available)
		for ($i=0; $i<10; $i++) {
			$actIdFromQueue = getQueueEntry();
			if ($actIdFromQueue != 0) {
				$actIdFromQueueList[] = $actIdFromQueue;
			}
			else {
				break;
			}
		}
		if (count($actIdFromQueueList) != 0) {
			$actIdString = implode(",", $actIdFromQueueList);
			$actList = ActListWithValues('Act', 'WHERE ActParent = 0 AND ActId IN ('.$actIdString.')', '', '', 1);

			// if actId is missing, it is deleted, do so...
			if (count($actList) < $actIdFromQueueList) {
				foreach ($actIdFromQueueList as $delAct) {
					if (! array_key_exists($delAct, $actList)) {
						SearchIndexUpdate($delAct, array(), array());
					}
				}
			}

		}
		else {
			$this->status = "400 Bad Request (no entry in queue)";
		}
	}
	elseif ($this->action == "all") {
		//$actList = actList();
		$actList = ActListWithValues('Act', 'WHERE ActParent = 0', '', '', 1);
	}
	else {
		$this->status = "400 Bad Request (wrong action)";
	}

	#
	# TRAVERS ACTLIST
	# - create a list of words
	#
	if (! $this->status) {

	        # print_r ($actList);
		foreach ($actList as $act) {
			$currentId = $act['id'];
			$currentRef = $act['reference'];
			$currentWords = array();
			$contextWords = array();
			$currentUserInfo = array();

			if ($this->action != "del") {
				// act properties
				foreach ($index['act'] as $actIndex) {
					$words = explode (" ", $act[$actIndex]);
					$currentWords = array_merge($currentWords, $words);
					if ($actIndex == $index['actContextIn']) {
						// mark words beginning with # as tag
						foreach ($words as $word) {
							if (substr($word,0,1) == "#") {
								$contextWord = array($word, $index['actContextOut']);
								$contextWords[] = $contextWord;
							}
						}
					}
				}

				// act annotations
				foreach ($act['annotation'] as $annotation) {
					if (in_array($annotation['name'], $index['actAnnotation'])) {
						$words = explode (" ", $annotation['value']);
						$currentWords = array_merge($currentWords, $words);			
						if ($annotation['name'] == $index['actAnnotationContextIn']) {
							$contextWord = array($annotation['value'], $index['actAnnotationContextOut']);
							$contextWords[] = $contextWord;
						}
					}
				}

				// act profile annnotations
				foreach ($act['profile'] as $profile) {
					if (in_array($profile['group'], $index['actProfile'])) {
						foreach ($profile['annotation'] as $annotation) {
							if (in_array($annotation['name'], $index['actProfileAnnotation'])) {
								$words = explode (" ", $annotation['value']);
								$currentWords = array_merge($currentWords, $words);
								if ($annotation['name'] == $index['actProfileContextIn']) {
									$contextWord = array($annotation['value'], $index['actProfileContextOut']);
									$contextWords[] = $contextWord;
								}
							}
						}
					}
					if (in_array($profile['group'], $index['actProfile2'])) {
						foreach ($profile['annotation'] as $annotation) {
							if (in_array($annotation['name'], $index['actProfile2Annotation'])) {
								$words = explode (" ", $annotation['value']);
								$currentWords = array_merge($currentWords, $words);
								if ($annotation['name'] == $index['actProfile2ContextIn']) {
									$contextWord = array($annotation['value'], $index['actProfile2ContextOut']);
									$contextWords[] = $contextWord;
								}
							}
						}
					}
				}

				//
				// read userInfo from user API
				//
				$currentUserInfo = UserApiListUserWithQuery("reference=".$currentRef);
				if(count($currentUserInfo) == 1) {
					$currentUserInfo = current($currentUserInfo);

					//
					// name
					//
					foreach ($index['user'] as $userIndex) {
						$words = explode (" ", $currentUserInfo[$userIndex]);
						$currentWords = array_merge($currentWords, $words);
						if ($userIndex == $index['userContextIn']) {
							$contextWord = array($currentUserInfo[$userIndex], $index['userContextOut']);
							$contextWords[] = $contextWord;
						}
						if ($userIndex == $index['userContextIn2']) {
							$contextWord = array($currentUserInfo[$userIndex], $index['userContextOut2']);
							$contextWords[] = $contextWord;
						}
					}

					// address
					/* disabled since version 1.2n
					if (is_array($currentUserInfo['Address'])) {
						$addressList = $currentUserInfo['Address'];
						foreach ($addressList as $address) {
							foreach ($index['address'] as $addressIndex) {
								if ($address['Name'] == $addressIndex) {
									// the contect is the key to fetch
									$addressKey = $index['addressContextIn'];
									$words = explode (" ", $address[$addressKey]);
									$currentWords = array_merge($currentWords, $words);
									// directly save the context as well
									$contextWord = array($address[$addressKey], $index['addressContextOut']);
									$contextWords[] = $contextWord;
								}
							}
						}
					}
					*/

					// organization
					/* disabled since version 1.2n
					if (is_array($currentUserInfo['Organization'])) {
						$organizationList = $currentUserInfo['Organization'];
						foreach ($organizationList as $organization) {
							foreach ($index['organization'] as $organizationIndex) {
								if ($organization['Name'] == $organizationIndex) {
									// the contect is the key to fetch
									$organizationKey = $index['organizationContextIn'];
									$words = explode (" ", $organization[$organizationKey]);
									$currentWords = array_merge($currentWords, $words);
									// directly save the context as well
									$contextWord = array($organization[$organizationKey], $index['organizationContextOut']);
									$contextWords[] = $contextWord;
								}
							}
						}
					}
					*/
				}

				// final functions
				// - trim words
				// - set to lowercase
				// - remove duplicates
				$currentWords = array_map('my_trim', $currentWords);
				$currentWords = array_map('strtolower', $currentWords);
				$currentWords = array_unique($currentWords);
				# print_r ($currentWords);

				// context words worden niet getrimmed en niet in lower case gezet. Hierdoor is een exacte match mogelijk!
				$contextWords = unique_multi_array_all_fields($contextWords);
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
