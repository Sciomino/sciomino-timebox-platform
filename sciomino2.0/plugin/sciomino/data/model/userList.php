<?

class userList extends control {

    function Run() {

        global $XCOW_B;
        
	//
	// who?
	//
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

	// param
	$this->name = $this->ses['request']['param']['n'];
	$this->query = $this->ses['request']['param']['q'];
	$this->knowledge = $this->ses['request']['param']['k'];
	if (! isset($this->knowledge)) { $this->knowledge = array(); }
	$this->experience = $this->ses['request']['param']['e'];
	if (! isset($this->experience)) { $this->experience = array(); }
	$this->hobby = $this->ses['request']['param']['h'];
	if (! isset($this->hobby)) { $this->hobby = array(); }
	$this->tag = $this->ses['request']['param']['t'];
	if (! isset($this->tag)) { $this->tag = array(); }
	$this->personal = $this->ses['request']['param']['p'];
	if (! isset($this->personal)) { $this->personal = array(); }
	$this->list = $this->ses['request']['param']['l'];
	if (! isset($this->list)) { $this->list = array(); }
	$this->typeList = $this->ses['request']['param']['tl'];
	if (! isset($this->typeList)) { $this->typeList = array(); }

        $this->offset = $this->ses['request']['param']['offset'];
        if (! isset($this->offset)) {$this->offset = 0;}
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 20;}

	// init
	// set $max to show at once
	$max = ($this->limit * 2);
	$searchList = array();
	$userList = array();
	$twitterAccountList = array();

	//
	// get user id's
	// - select user id's from reverse index
	// - ORDER BY subselect field from field_table
	//
	// get filterlijst
	// - select all filters for user id's
	//
	$focus = "";
	if ($this->name != '') {
		$focus .= "&n=".urlencode($this->name);
	}
	if ($this->query != '') {
		$focus .= "&q=".urlencode($this->query);
	}
	foreach ($this->knowledge as $key => $val) {
		if ($val != '') {
			$focus .= "&k[".urlencode($key)."]=".urlencode($val);
		}
		else {
			$focus .= "&k[".urlencode($key)."]";
		}
	}
	foreach ($this->experience as $key => $val) {
		foreach ($val as $subkey => $subval) {
			if ($subval != '') {
				$focus .= "&e[".urlencode($key)."][".urlencode($subkey)."]=".urlencode($subval);
			}
			else {
				$focus .= "&e[".urlencode($key)."][".urlencode($subkey)."]";
			}
		}
	}
	foreach ($this->hobby as $key => $dummy) {
		$focus .= "&h[".urlencode($key)."]";
	}
	foreach ($this->tag as $key => $dummy) {
		$focus .= "&t[".urlencode($key)."]";
	}
	foreach ($this->personal as $key => $val) {
		$focus .= "&p[".urlencode($key)."]=".urlencode($val);
	}
	foreach ($this->list as $key => $dummy) {
		$focus .= "&l[".urlencode($key)."]";
	}
	foreach ($this->typeList as $key => $val) {
		$focus .= "&tl[".urlencode($key)."]=".urlencode($val);
	}

	ksort($this->knowledge);
	ksort($this->experience);
	ksort($this->hobby);
	ksort($this->tag);
	ksort($this->personal);
	ksort($this->list);
	ksort($this->typeList);
	
	$searchList = UserApiListSearchWithQuery("userId=".$this->userId."&order=lastname&".$focus);

	$this->ses['response']['param']['user'] = $this->userId;

	$this->ses['response']['param']['query'] = array();	
	$this->ses['response']['param']['query']['focus'] = $focus;
	$this->ses['response']['param']['query']['name'] = $this->name;
	$this->ses['response']['param']['query']['words'] = $this->query;
	$this->ses['response']['param']['query']['knowledge'] = $this->knowledge;
	$this->ses['response']['param']['query']['experience'] = $this->experience;
	$this->ses['response']['param']['query']['hobby'] = $this->hobby;
	$this->ses['response']['param']['query']['tag'] = $this->tag;
	$this->ses['response']['param']['query']['personal'] = $this->personal;
	$this->ses['response']['param']['query']['list'] = $this->list;
	$this->ses['response']['param']['query']['typeList'] = $this->typeList;

	array_multisort(array_values($searchList['knowledge']), SORT_DESC, array_keys($searchList['knowledge']), SORT_ASC, $searchList['knowledge']);
	array_multisort(array_values($searchList['company']), SORT_DESC, array_keys($searchList['company']), SORT_ASC, $searchList['company']);
	array_multisort(array_values($searchList['event']), SORT_DESC, array_keys($searchList['event']), SORT_ASC, $searchList['event']);
	array_multisort(array_values($searchList['education']), SORT_DESC, array_keys($searchList['education']), SORT_ASC, $searchList['education']);
	array_multisort(array_values($searchList['product']), SORT_DESC, array_keys($searchList['product']), SORT_ASC, $searchList['product']);
	array_multisort(array_values($searchList['hobby']), SORT_DESC, array_keys($searchList['hobby']), SORT_ASC, $searchList['hobby']);
	array_multisort(array_values($searchList['tag']), SORT_DESC, array_keys($searchList['tag']), SORT_ASC, $searchList['tag']);
	array_multisort(array_values($searchList['industry']), SORT_DESC, array_keys($searchList['industry']), SORT_ASC, $searchList['industry']);
	array_multisort(array_values($searchList['organization']), SORT_DESC, array_keys($searchList['organization']), SORT_ASC, $searchList['organization']);
	array_multisort(array_values($searchList['businessunit']), SORT_DESC, array_keys($searchList['businessunit']), SORT_ASC, $searchList['businessunit']);
	array_multisort(array_values($searchList['section']), SORT_DESC, array_keys($searchList['section']), SORT_ASC, $searchList['section']);
	array_multisort(array_values($searchList['role']), SORT_DESC, array_keys($searchList['role']), SORT_ASC, $searchList['role']);
	array_multisort(array_values($searchList['hometown']), SORT_DESC, array_keys($searchList['hometown']), SORT_ASC, $searchList['hometown']);
	array_multisort(array_values($searchList['workplace']), SORT_DESC, array_keys($searchList['workplace']), SORT_ASC, $searchList['workplace']);
	array_multisort(array_values($searchList['list']), SORT_DESC, array_keys($searchList['list']), SORT_ASC, $searchList['list']);
	array_multisort(array_values($searchList['managerList']), SORT_DESC, array_keys($searchList['managerList']), SORT_ASC, $searchList['managerList']);
	array_multisort(array_values($searchList['publicList']), SORT_DESC, array_keys($searchList['publicList']), SORT_ASC, $searchList['publicList']);

	$this->ses['response']['param']['userCount'] = count($searchList['user']);
	$this->ses['response']['param']['suggestList'] = $searchList['suggest'];
	$this->ses['response']['param']['knowledgeList'] = $searchList['knowledge'];
	$this->ses['response']['param']['companyList'] = $searchList['company'];
	$this->ses['response']['param']['eventList'] = $searchList['event'];
	$this->ses['response']['param']['educationList'] = $searchList['education'];
	$this->ses['response']['param']['productList'] = $searchList['product'];
	$this->ses['response']['param']['hobbyList'] = $searchList['hobby'];
	$this->ses['response']['param']['tagList'] = $searchList['tag'];
	$this->ses['response']['param']['industryList'] = $searchList['industry'];
	$this->ses['response']['param']['organizationList'] = $searchList['organization'];
	$this->ses['response']['param']['businessunitList'] = $searchList['businessunit'];
	$this->ses['response']['param']['sectionList'] = $searchList['section'];
	$this->ses['response']['param']['roleList'] = $searchList['role'];
	$this->ses['response']['param']['hometownList'] = $searchList['hometown'];
	$this->ses['response']['param']['workplaceList'] = $searchList['workplace'];
	$this->ses['response']['param']['listList'] = $searchList['list'];
	$this->ses['response']['param']['managerListList'] = $searchList['managerList'];
	$this->ses['response']['param']['publicListList'] = $searchList['publicList'];

	//
	// get result information from FIRST 10 id's using XmlHttpRequest
	// - get information from next 10 results when 'more' button is pushed
	//
	$numberOfUsers = count($searchList['user']);
	if ($numberOfUsers > $this->offset) {
		$userString = "";
		if ($numberOfUsers < $max) {
			$this->limit = $numberOfUsers + 1;
		}
		$userLimit = array_slice($searchList['user'], $this->offset, $this->limit);
		foreach ($userLimit as $userId) {
			$userString .= "user[".$userId."]&";
			$twitterAccountList[$userId] = "";
		}
		$userString = rtrim($userString, "&");
		// append same order
		$userString .= "&order=lastname";
		$userList = UserApiListUserWithQuery($userString);
	}

	$this->ses['response']['param']['searchOffset'] = $this->offset + $this->limit;
	$this->ses['response']['param']['searchString'] = implode(',',array_slice($searchList['user'], 0, 250));

	$this->ses['response']['param']['userList'] = $userList;

	$thereIsMore = 1;
	if (count($userList) < $this->limit) {
		$thereIsMore = 0;
	}
	$this->ses['response']['param']['thereIsMore'] = $thereIsMore;

	// twitter
	foreach (array_keys($twitterAccountList) as $twitterUser) {
		$user_sesId = $userList[$twitterUser]['Reference'];
		$yourConnectionList = OauthClientGetConnections($user_sesId);
		$twitterAccountList[$twitterUser] = $yourConnectionList[get_id_from_multi_array($yourConnectionList, 'app', 'twitter')]['reference'];
	}

	$this->ses['response']['param']['twitterAccountList'] = $twitterAccountList;
	$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];

     }

}

?>
