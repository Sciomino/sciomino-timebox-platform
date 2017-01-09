<?

class knowledge extends control {

    function Run() {

        global $XCOW_B;

	//
	// who?
	//
    $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

	// param
	$this->knowledge = $this->ses['request']['param']['k'];
	$this->level = $this->ses['request']['param']['level'];

    $this->offset = $this->ses['request']['param']['offset'];
    if (! isset($this->offset)) {$this->offset = 0;}
    $this->limit = $this->ses['request']['param']['limit'];
    if (! isset($this->limit)) {$this->limit = 20;}

	// init
	// set $max to show at once
	$max = ($this->limit * 2);
	$searchList = array();
	$userList = array();

	// search
	$query = "&k[".urlencode($this->knowledge)."]";
	if (isset($this->level)) {
		$query .= "=".$this->level;
	}
	$searchList = UserApiListSearchWithQuery("userId=".$this->userId."&detail=none&order=lastname".$query);

	$knowledge = array();
	$knowledge['detail'] = 'knowledge';
	$knowledge['knowledgeDetail'] = $this->knowledge;
	$detailList = UserApiDetailSearchKnowledge($knowledge, "userId=".$this->userId."&k[".urlencode($this->knowledge)."]");
	// don't sort on count.
	ksort($detailList['level']);

	$userCount = 0;
	foreach($detailList['level'] as $level => $count) {
		$userCount = $userCount + $count;
	}
	$metoo = 0;
	if (in_array($this->userId, $searchList['user'])) {
		$metoo = 1;
	}

	// content
	$this->ses['response']['param']['userCount'] = $userCount;
	$this->ses['response']['param']['showMetoo'] = $metoo;
	$this->ses['response']['param']['me'] = $this->userId;

	$this->ses['response']['param']['knowledgeField'] = $this->knowledge;
	$this->ses['response']['param']['knowledgeLevel'] = $this->level;
	$this->ses['response']['param']['knowledgeDetail'] = $detailList;

	$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];

	// results
	$numberOfUsers = count($searchList['user']);
	if ($numberOfUsers > $this->offset) {
		$userString = "";
		if ($numberOfUsers < $max) {
			$this->limit = $numberOfUsers + 1;
		}
		$userLimit = array_slice($searchList['user'], $this->offset, $this->limit);
		foreach ($userLimit as $userId) {
			$userString .= "user[".$userId."]&";
		}
		$userString = rtrim($userString, "&");
		// append same order
		$userString .= "&order=lastname";
		$userList = UserApiListUserWithQuery($userString);
	}

	$this->ses['response']['param']['searchOffset'] = $this->offset + $this->limit;
	$this->ses['response']['param']['searchString'] = implode(',',array_slice($searchList['user'], 0, 100));

	$this->ses['response']['param']['userList'] = $userList;

	// stats
	$statsList = array();
	$statsList['MF1Count'] = 0;
	$statsList['MF15Count'] = 0;
	$statsList['MF25Count'] = 0;
	$statsList['MF35Count'] = 0;
	$statsList['MF45Count'] = 0;
	$statsList['MF55Count'] = 0;
	foreach ($userList as $user) {
		$now = time();
		$dob = strtotime($user['dateofbirthyear']."-".$user['dateofbirthmonth']."-".$user['dateofbirthday']);
		$age = date('Y', $now-$dob) - date('Y', 0);

		if ($age < 15) { $statsList['MF1Count'] = $statsList['MF1Count'] + 1; }
		elseif ($age < 25) { $statsList['MF15Count'] = $statsList['MF15Count'] + 1; }
		elseif ($age < 35) { $statsList['MF25Count'] = $statsList['MF25Count'] + 1; }
		elseif ($age < 45) { $statsList['MF35Count'] = $statsList['MF35Count'] + 1; }
		elseif ($age < 55) { $statsList['MF45Count'] = $statsList['MF45Count'] + 1; }
		else { $statsList['MF55Count'] = $statsList['MF55Count'] + 1; }
	}
	$this->ses['response']['param']['stats'] = $statsList;

	$thereIsMore = 1;
	if (count($userList) < $this->limit) {
		$thereIsMore = 0;
	}
	$this->ses['response']['param']['thereIsMore'] = $thereIsMore;

    }

}

?>
