<?

class userView extends control {

    function Run() {

        global $XCOW_B;
        
	//
	// who?
	//
    $this->id = $this->ses['id'];
    # do not cache, because this is the viewer himself
	#$this->userInfo = current(UserApiListUserWithQuery("reference=".$this->id."&format=long", "SC_UserApiListUserWithQuery_".$this->id."_long"));
	$this->userInfo = current(UserApiListUserWithQuery("reference=".$this->id."&format=long"));
	$this->userId = $this->userInfo['Id'];

	// param
	$this->user = $this->ses['request']['param']['user'];
	if (! isset($this->user)) { $this->user = $this->userId; }
	$this->name = $this->ses['request']['param']['name'];
	$this->limit = $this->ses['request']['param']['limit'];
	if (! isset($this->limit)) { $this->limit = 5; }

	// init
	$itsMe = 0;
	$userList = array();
	$sameTempList = array();
	$sameUserList = array();

	// find people by their loginname
	if ( isset($this->name) ) {
		$sesId = getUserIdFromUserName($this->name);
		if ( isset($sesId) ) {
			$this->user = UserApiGetUserFromReference($sesId, "SC_UserApiGetUserFromReference_".$sesId);
		}
	}
	
	// is it me?
	// echo "VIEWER:".$this->userId;
	// echo "USER:".$this->user;
	if ($this->userId == $this->user) {
		$itsMe = 1;
	}
	
	//
	// go
	//
	if ($itsMe) {
		$userList = $this->userInfo;
		
		function sort_on_subject($a,$b) {
			return strcasecmp($a["subject"], $b["subject"]);
		}
		$experienceList = $userList['Experience'];
		uasort($experienceList, "sort_on_subject");

		$productList = array();
		$productIdList = array_slice(get_list_from_multi_array($userList['Experience'], "Name", "Product"), 0, $this->limit);
		foreach ($productIdList as $key => $val) {
			$productList[$val] = $experienceList[$val];
		}
		uasort($productList, "sort_on_subject");
		
		$companyList = array();
		$companyIdList = array_slice(get_list_from_multi_array($userList['Experience'], "Name", "Company"), 0, $this->limit);
		foreach ($companyIdList as $key => $val) {
			$companyList[$val] = $experienceList[$val];
		}
		uasort($companyList, "sort_on_subject");

		$eventList = array();
		$eventIdList = array_slice(get_list_from_multi_array($userList['Experience'], "Name", "Event"), 0, $this->limit);
		foreach ($eventIdList as $key => $val) {
			$eventList[$val] = $experienceList[$val];
		}
		uasort($eventList, "sort_on_subject");

		$educationList = array();
		$educationIdList = array_slice(get_list_from_multi_array($userList['Experience'], "Name", "Education"), 0, $this->limit);
		foreach ($educationIdList as $key => $val) {
			$educationList[$val] = $experienceList[$val];
		}
		uasort($educationList, "sort_on_subject");
	}
	else {
		// TODO: could get all info from this user with 'format=long' parameter
		// problem: can only fetch ALL experiences this way, not with limit...
		// solution: put the experiences in a snippet

		$userList = current(UserApiListUserById($this->user,"SC_UserApiListUserById_".$this->user));
		
		#$userList2 = UserApiListUserWithQuery("user[$this->user]&format=long");
		#$experienceList = UserApiListSection("experience", $this->user);
		$productList = UserApiListSectionWithQuery("experience", $this->user, "name=Product&name_match=exact&order=annotation/subject&limit=".$this->limit);
		$companyList = UserApiListSectionWithQuery("experience", $this->user, "name=Company&name_match=exact&order=annotation/subject&limit=".$this->limit);
		$eventList = UserApiListSectionWithQuery("experience", $this->user, "name=Event&name_match=exact&order=annotation/subject&limit=".$this->limit);
		$educationList = UserApiListSectionWithQuery("experience", $this->user, "name=Education&name_match=exact&order=annotation/subject&limit=".$this->limit);

	}

	// don't allow empty page
	if ($userList == "") {
		$this->ses['response']['redirect'] = "/error404";
	}

	// twitter
	$user_sesId = $userList['Reference'];
	$yourConnectionList = OauthClientGetConnections($user_sesId);
	$yourTwitter = $yourConnectionList[get_id_from_multi_array($yourConnectionList, 'app', 'twitter')]['reference'];

	// content

	//the user who is viewing (itsMe = 0|1)
	$this->ses['response']['param']['me'] = $itsMe;
	$this->ses['response']['param']['meUser'] = $this->userId;
	$this->ses['response']['param']['meInfo'] = $this->userInfo;

	//the user who is viewed
	$this->ses['response']['param']['view'] = $this->user;
	$this->ses['response']['param']['user'] = $userList;
	
	//get assistent info
	$assistentArray = array();
	$assistentName = $userList['Contact'][get_id_from_multi_array($userList['Contact'], 'Name', 'Work')]['assistentId'];
	if (isset($assistentName)) {
		$assistentId = getUserIdFromUserName($assistentName);
		if (isset($assistentId)) {
			$assistentArray = current(UserApiListUserWithQuery("reference=".$assistentId."&format=short", "SC_UserApiListUserWithQuery_".$assistentId."_short"));
		}
	}
	$this->ses['response']['param']['assistent'] = $assistentArray;

	//get manager info
	$managerArray = array();
	$managerId = 0;
	$managerName = $userList['Contact'][get_id_from_multi_array($userList['Contact'], 'Name', 'Work')]['managerId'];
	if (isset($managerName)) {
		$managerId = getUserIdFromUserName($managerName);
		if (isset($managerId)) {
			$managerArray = current(UserApiListUserWithQuery("reference=".$managerId."&format=short", "SC_UserApiListUserWithQuery_".$managerId."_short"));
		}
	}
	$this->ses['response']['param']['manager'] = $managerArray;
	// manager list
	$query = "type=manager&userId=".$managerArray['Id'];
	$managerList = UserApiGroupListWithQuery($query);
	if (count($managerList) > 0) {
		$managerList = current($managerList);
		$this->ses['response']['param']['managerList'] = $managerList;
	}
	else {
		$this->ses['response']['param']['managerList'] = "";
	}
	// team list
	$query = "type=manager&userId=".$this->user;
	$teamList = UserApiGroupListWithQuery($query);
	if (count($teamList) > 0) {
		$teamList = current($teamList);
		$this->ses['response']['param']['teamList'] = $teamList;
	}
	else {
		$this->ses['response']['param']['teamList'] = "";
	}

	//more
	$this->ses['response']['param']['limit'] = $this->limit;
	$this->ses['response']['param']['newLimit'] = $this->limit + 10;
	#$this->ses['response']['param']['experience'] = $experienceList;
	$this->ses['response']['param']['product'] = $productList;
	$this->ses['response']['param']['company'] = $companyList;
	$this->ses['response']['param']['event'] = $eventList;
	$this->ses['response']['param']['education'] = $educationList;

	$this->ses['response']['param']['sameUser'] = $sameUserList;
	$this->ses['response']['param']['twitterAccount'] = $yourTwitter;

	$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];

     }

}

?>
