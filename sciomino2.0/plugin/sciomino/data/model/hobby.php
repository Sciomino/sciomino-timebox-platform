<?

class hobby extends control {

    function Run() {

        global $XCOW_B;

	//
	// who?
	//
    $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

	// param
	$this->hobby = $this->ses['request']['param']['h'];

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
	$query = "&h[".urlencode($this->hobby)."]";
	$searchList = UserApiListSearchWithQuery("userId=".$this->userId."&detail=none&order=lastname".$query);

	$userCount = count($searchList['user']);
	$metoo = 0;
	if (in_array($this->userId, $searchList['user'])) {
		$metoo = 1;
	}

	// content
	$this->ses['response']['param']['userCount'] = $userCount;
	$this->ses['response']['param']['showMetoo'] = $metoo;
	$this->ses['response']['param']['me'] = $this->userId;

	$this->ses['response']['param']['hobbyField'] = $this->hobby;

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

	$thereIsMore = 1;
	if (count($userList) < $this->limit) {
		$thereIsMore = 0;
	}
	$this->ses['response']['param']['thereIsMore'] = $thereIsMore;

     }

}

?>
