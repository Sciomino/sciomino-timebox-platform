<?

class checkupCreation extends control {

    function Run() {

    global $XCOW_B;

	#
	# init
	#
	$userList = array();
	$currentCheckup = array();
	$checkupUsers = array();
	$checkupCount = 0;

	#
	# get specific user
	#
	# action = one, check one user (returns detailed info)
	# action = all, check all users (returns overview)
	#
	$this->action = $this->ses['request']['param']['action'];
	if (! isset($this->action)) {$this->action = 'none';}	

	$this->userId = $this->ses['request']['param']['userId'];

    $this->offset = $this->ses['request']['param']['offset'];
    if (! isset($this->offset)) {$this->offset = 0;}        
    $this->limit = $this->ses['request']['param']['limit'];
    if (! isset($this->limit)) {$this->limit = 2000;}       

	if ($this->action == "one") {
		if (isset($this->userId) && $this->userId != '') {
			$userList = UserListWithValues('User', 'WHERE UserId = '.$this->userId, '', '', 1);
		}
		else {
			$this->status = "400 Bad Request (missing userId)";
		}
	}
	elseif ($this->action == "all") {
		$userList = UserListWithValues('User', '', '', 'limit '.$this->offset.','.$this->limit, 1);

	}
	else {
		$this->status = "400 Bad Request (wrong action)";
	}

	#
	# TRAVERS USERLIST
	# - output is a checkup of the usersList
	#
	if (! $this->status) {

	    # print_r ($userList);
		foreach ($userList as $user) {
			$startCheckupCount = $checkupCount;
			$currentId = $user['userId'];
			$currentCheckup[$currentId] = array();

			// user properties

			// user annotations
			if (! is_array($user['annotation'])) {
				$currentCheckup[$currentId]['Annotation']= "*** not found ***";
				$checkupCount++;
			}
			else {
				$currentCheckup[$currentId]['Annotation']= count($user['annotation']);
			}

			// user profile annnotations
			if (! is_array($user['profile'])) {
				$currentCheckup[$currentId]['Profile']= "*** not found ***";
				$checkupCount++;
			}
			else {
				$currentCheckup[$currentId]['Profile']= count($user['profile']);
				foreach ($user['profile'] as $profile) {
					// een profile moet altijd annotations hebben.
					if (! is_array($profile['annotation']) || count($profile['annotation']) == 0) {
						$currentCheckup[$currentId]['Profile'.$profile['id']]= "*** annotation not found ***";
						$checkupCount++;
					}
					else {
						$currentCheckup[$currentId]['Profile'.$profile['id']]= count($profile['annotation']);
					}
				}
			}

			// contact
			// er moeten twee basis contact arrays zijn, home & work
			if (! is_array($user['contact']) || count($user['contact']) < 2) {
				$currentCheckup[$currentId]['Contact']= "*** not found ***";
				$checkupCount++;
			}
			else {
				$currentCheckup[$currentId]['Contact']= count($user['contact']);
				foreach ($user['contact'] as $contact) {
					if (! is_array($contact['annotation'])) {
						$currentCheckup[$currentId]['Contact'.$contact['id']]= "*** annotation not found ***";
						$checkupCount++;
					}
					else {
						$currentCheckup[$currentId]['Contact'.$contact['id']]= count($contact['annotation']);
					}
				}
			}

			// address
			// er moeten twee basis address arrays zijn, home & work
			if (! is_array($user['address']) || count($user['address']) < 2) {
				$currentCheckup[$currentId]['Address']= "*** not found ***";
				$checkupCount++;
			}
			else {
				$currentCheckup[$currentId]['Address']= count($user['address']);
				foreach ($user['address'] as $address) {
					if (! is_array($address['annotation'])) {
						$currentCheckup[$currentId]['Address'.$address['Id']]= "*** annotation not found ***";
						$checkupCount++;
					}
					else {
						$currentCheckup[$currentId]['Address'.$address['id']]= count($address['annotation']);
					}
				}
			}

			// organization
			// er moeten twee basis organization arrays zijn, current & past
			if (! is_array($user['organization']) || count($user['organization']) < 2) {
				$currentCheckup[$currentId]['Organization']= "*** not found ***";
				$checkupCount++;
			}
			else {
				$currentCheckup[$currentId]['Organization']= count($user['organization']);
				foreach ($user['organization'] as $organization) {
					if (! is_array($organization['annotation'])) {
						$currentCheckup[$currentId]['Organization'.$organization['id']]= "*** annotation not found ***";
						$checkupCount++;
					}
					else {
						$currentCheckup[$currentId]['Organization'.$organization['id']]= count($organization['annotation']);
					}
				}
			}

			// publications
			$publicationList = UserSectionList("publication", $currentId);
			if (! is_array($publicationList)) {
				$currentCheckup[$currentId]['Publication']= "*** not found ***";
				$checkupCount++;
			}
			else {
				$currentCheckup[$currentId]['Publication']= count($publicationList);
				foreach ($publicationList as $publication) {
					// een publication moet altijd annotations hebben.
					if (! is_array($publication['annotation'])) {
						$currentCheckup[$currentId]['Publication'.$publication['id']]= "*** annotation not found ***";
						$checkupCount++;
					}
					else {
						$currentCheckup[$currentId]['Publication'.$publication['id']]= count($publication['annotation']);
					}
				}
			}

			// experiences
			$experienceList = UserSectionList("experience", $currentId);
			if (! is_array($experienceList)) {
				$currentCheckup[$currentId]['Experience']= "*** not found ***";
				$checkupCount++;
			}
			else {
				$currentCheckup[$currentId]['Experience']= count($experienceList);
				foreach ($experienceList as $experience) {
					// een experience moet altijd annotations hebben.
					if (! is_array($experience['annotation'])) {
						$currentCheckup[$currentId]['Experience'.$experience['id']]= "*** annotation not found ***";
						$checkupCount++;
					}
					else {
						$currentCheckup[$currentId]['Experience'.$experience['id']]= count($experience['annotation']);
					}
				}
			}

			if ($startCheckupCount < $checkupCount) {
				$checkupUsers[] = $currentId;
			}
            else {
				unset($currentCheckup[$currentId]);
            }

        }

		# print_r ($currentCheckup);
		$this->ses['response']['param']['checkup'] = $currentCheckup;
		$this->ses['response']['param']['checkupTotal'] = count($userList);
		$this->ses['response']['param']['checkupCount'] = $checkupCount;
		$this->ses['response']['param']['checkupCountUsers'] = count($checkupUsers);
		$this->ses['response']['param']['checkupUsers'] = implode(',',$checkupUsers);
		$this->ses['response']['param']['checkupDetail'] = array();
		if ($this->action == "one") {
			$this->ses['response']['param']['checkupDetail'] = $currentCheckup[$currentId];
		}

    }
	}

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }


}

?>
