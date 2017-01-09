<?

class userViewPersonal extends control {

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
	$this->type = $this->ses['request']['param']['type'];
	$this->limit = $this->ses['request']['param']['limit'];
	if (! isset($this->limit)) { $this->limit = 2; }

	// init
	$itsMe = 0;
	$userList = array();
	
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
	}
	else {
		$userList = current(UserApiListUserById($this->user,"SC_UserApiListUserById_".$this->user));
	}

	//
	// split, check & sort/shuffle knowledgefields
	//
	$knowledgeList = array();
	if (isset($userList['knowledgefield'])) {
		$knowledgeList = $userList['knowledgefield'];
	}

	$kList1 = get_list_from_multi_array($knowledgeList,"level", "1");
	$kList2 = get_list_from_multi_array($knowledgeList,"level", "2");
	$kList3 = get_list_from_multi_array($knowledgeList,"level", "3");
	$knowledgeList1 = array_intersect_key($knowledgeList, array_flip($kList1));
	$knowledgeList2 = array_intersect_key($knowledgeList, array_flip($kList2));
	$knowledgeList3 = array_intersect_key($knowledgeList, array_flip($kList3));

	// Only show knowledge fields with > 1 result
	$kQuery = array();
	$kQuery['context'] = "knowledge";
	$kQuery['userId'] = $this->userId;
	$kQuery['mode'] = "user";
	$kQuery['sort'] = "num";
	$kSearchList = UserApiListListAll($kQuery);
	$excludeK = array_keys($kSearchList['knowledge'], "1");

	// sort & shuffle
	function sortK($x, $y) {
		if ( $x['field'] == $y['field'] ) { return 0; }
		else if ( $x['field'] < $y['field'] ) { return -1; }
		else { return 1; }
	}

	$knowledgeList1Checked = array();
	foreach ($knowledgeList1 as $k => $v) {
		if (! in_array($v['field'],$excludeK)) {
			$knowledgeList1Checked[$k] = $v;
		}
	}
	uasort($knowledgeList1Checked, "sortK");
	$knowledgeList1Shuffled = $knowledgeList1Checked;
	shuffle($knowledgeList1Shuffled);
	
	$knowledgeList2Checked = array();
	foreach ($knowledgeList2 as $k => $v) {
		if (! in_array($v['field'],$excludeK)) {
			$knowledgeList2Checked[$k] = $v;
		}
	}
	uasort($knowledgeList2Checked, "sortK");
	$knowledgeList2Shuffled = $knowledgeList2Checked;
	shuffle($knowledgeList2Shuffled);
	
	$knowledgeList3Checked = array();
	foreach ($knowledgeList3 as $k => $v) {
		if (! in_array($v['field'],$excludeK)) {
			$knowledgeList3Checked[$k] = $v;
		}
	}
	uasort($knowledgeList3Checked, "sortK");
	$knowledgeList3Shuffled = $knowledgeList3Checked;
	shuffle($knowledgeList3Shuffled);

	$knowledgeList1Slice = array_slice($knowledgeList1Shuffled, 0, $this->limit);
	$knowledgeList2Slice = array_slice($knowledgeList2Shuffled, 0, $this->limit);
	$knowledgeList3Slice = array_slice($knowledgeList3Shuffled, 0, $this->limit);

	// Hobby
	$hobbyList = array();
	if (isset($userList['hobbyfield'])) {
		$hobbyList = $userList['hobbyfield'];
	}
	
	$hQuery = array();
	$hQuery['context'] = "hobby";
	$hQuery['userId'] = $this->userId;
	$hQuery['mode'] = "user";
	$hQuery['sort'] = "num";
	$hSearchList = UserApiListListAll($hQuery);
	$excludeH = array_keys($hSearchList['hobby'], "1");

	function sortH($x, $y) {
		if ( $x['field'] == $y['field'] ) { return 0; }
		else if ( $x['field'] < $y['field'] ) { return -1; }
		else { return 1; }
	}
	
	$hobbyListChecked = array();
	foreach ($hobbyList as $k => $v) {
		if (! in_array($v['field'],$excludeH)) {
			$hobbyListChecked[$k] = $v;
		}
	}
	uasort($hobbyListChecked, "sortH");
	$hobbyListShuffled = $hobbyListChecked;
	shuffle($hobbyListShuffled);

	$hobbyListSlice = array_slice($hobbyListShuffled, 0, $this->limit);

	// Tag
	$tagList = array();
	if (isset($userList['tag'])) {
		$tagList = $userList['tag'];
	}
	
	$tQuery = array();
	$tQuery['context'] = "tag";
	$tQuery['userId'] = $this->userId;
	$tQuery['mode'] = "user";
	$tQuery['sort'] = "num";
	$tSearchList = UserApiListListAll($tQuery);
	$excludeT = array_keys($tSearchList['tag'], "1");

	function sortT($x, $y) {
		if ( $x['name'] == $y['name'] ) { return 0; }
		else if ( $x['name'] < $y['name'] ) { return -1; }
		else { return 1; }
	}
	
	$tagListChecked = array();
	foreach ($tagList as $k => $v) {
		if (! in_array($v['name'],$excludeT)) {
			$tagListChecked[$k] = $v;
		}
	}
	uasort($tagListChecked, "sortT");
	$tagListShuffled = $tagListChecked;
	shuffle($tagListShuffled);

	$tagListSlice = array_slice($tagListShuffled, 0, $this->limit);
	
	// Network
	$networkList = array();
	if (is_array($userList['GroupMember'])) {
		$nList = get_list_from_multi_array($userList['GroupMember'], 'Type', 'public');
		$networkList = array_intersect_key($userList['GroupMember'], array_flip($nList));
	}
	function sortNetwork($x, $y) {
		if ( $x['Name'] == $y['Name'] ) { return 0; }
		else if ( $x['Name'] < $y['Name'] ) { return -1; }
		else { return 1; }
	}
	uasort($networkList, "sortNetwork");
	$networkListSlice = array_slice($networkList, 0, $this->limit);

	//
	// faces (incorporated)
	//
	$faces_limit = 7;
	$day= array();
	$birthDayList = array();
	$firstBirthDayList = array();
	$nextBirthDayList = array();
	$newList = array();
	list($day['day'], $day['month'], $day['year']) = explode(',', date('j,n,Y', time()));

	// get faces from today
	$birthDayList = UserApiListUserWithQuery("format=short&accessId=4&accessId_match=not&annotation[dateofbirthday]=".$day['day']."&annotation[dateofbirthmonth]=".$day['month']."&annotation_operator=ge&order=birthday&limit=".$faces_limit);

	// only count faces of this month
	foreach ($birthDayList as $user) {
		if ($user['dateofbirthmonth'] == $day['month'] ) {
			$firstBirthDayList[$user['Id']] = $user;
		}
	}
	
	// if not enough faces this month, get more faces from the first of next month
	if (count($firstBirthDayList) < $faces_limit ) {
		$nextMonth = $day['month'] + 1;
		if ($nextMonth == 13) { $nextMonth = 1; } 
		$nextBirthDayList = UserApiListUserWithQuery("format=short&accessId=4&accessId_match=not&annotation[dateofbirthday]=1&annotation[dateofbirthmonth]=".$nextMonth."&annotation_operator=ge&order=birthday&limit=".$faces_limit);
		$birthDayList = array_slice(array_merge($firstBirthDayList, $nextBirthDayList), 0, $faces_limit);
	}
	else {
		$birthDayList = array_slice($firstBirthDayList, 0, $faces_limit);
	}

	$newList = UserApiListUserWithQuery("mode=active&format=short&accessId=4&accessId_match=not&order=date&direction=desc&limit=".$faces_limit);

	//
	// content
	//
	//the user who is viewing (itsMe = 0|1)
	$this->ses['response']['param']['me'] = $itsMe;
	$this->ses['response']['param']['meUser'] = $this->userId;
	$this->ses['response']['param']['meInfo'] = $this->userInfo;

	//the user who is viewed
	$this->ses['response']['param']['view'] = $this->user;
	$this->ses['response']['param']['user'] = $userList;

	// full lists
	$this->ses['response']['param']['listType'] = $this->type;
	$this->ses['response']['param']['knowledgeList1'] = $knowledgeList1Checked;
	$this->ses['response']['param']['knowledgeList2'] = $knowledgeList2Checked;
	$this->ses['response']['param']['knowledgeList3'] = $knowledgeList3Checked;
	$this->ses['response']['param']['hobbyList'] = $hobbyListChecked;
	$this->ses['response']['param']['tagList'] = $tagListChecked;
	$this->ses['response']['param']['networkList'] = $networkList;
	$this->ses['response']['param']['birthDayList'] = $birthDayList;
	$this->ses['response']['param']['newList'] = $newList;
	
	// sliced lists
	$this->ses['response']['param']['knowledgeList1Slice'] = $knowledgeList1Slice;
	$this->ses['response']['param']['knowledgeList2Slice'] = $knowledgeList2Slice;
	$this->ses['response']['param']['knowledgeList3Slice'] = $knowledgeList3Slice;
	$this->ses['response']['param']['hobbyListSlice'] = $hobbyListSlice;
	$this->ses['response']['param']['tagListSlice'] = $tagListSlice;
	$this->ses['response']['param']['networkListSlice'] = $networkListSlice;

	//more
	$this->ses['response']['param']['limit'] = $this->limit;
	$this->ses['response']['param']['newLimit'] = $this->limit + 10;

	$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];

     }

}

?>
