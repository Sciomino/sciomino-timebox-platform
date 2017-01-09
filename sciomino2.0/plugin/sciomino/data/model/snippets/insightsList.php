<?

class insightsList extends control {

    function Run() {

        global $XCOW_B;

	$searchList = array();
	$userList = array();
     
    $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

	// read input
        $this->list = $this->ses['request']['param']['list'];
        if (! isset($this->list)) {$this->list = 'blog';}
 	$this->start = $this->ses['request']['param']['start'];
 	$this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 10;}

	// init
	$query = array();
	$query['mode'] = "index";
	switch($this->list) {
		case "twitter":
			$query['context'] = 'twitter';
			break;
		case "linkedin":
			$query['context'] = 'linkedin';
			break;
		case "blog":
			$query['context'] = 'Blog';
			break;
		case "presentation":
			$query['context'] = 'Share';
			break;
		case "website":
			$query['context'] = 'Website';
			break;
		case "publication":
			$query['context'] = 'Other';
			break;
	}
	$query['userId'] = $this->userId;
	$query['limit'] = $this->limit;
        if (isset($this->start)) {
		$query['start'] = $this->start;
	}

	// get plain searchList
	$searchList = UserApiListListAll($query);

	// get user info
	if (count($searchList['user']) > 0) {
		$userString = "";
		foreach ($searchList['user'] as $userId) {
			$userString .= "user[".$userId."]&";
		}
		$userString = rtrim($userString, "&");
		// append same order + format=long (= everything about this user :-))
		$userString .= "&order=lastname&format=long";
		$userList = UserApiListUserWithQuery($userString);
	}
	$userList = array_slice($userList, 0, $this->limit);

	// more?
	$thereIsMore = 1;
	if (count($searchList['user']) < $this->limit) {
		$thereIsMore = 0;
	}
	$this->ses['response']['param']['thereIsMore'] = $thereIsMore;

	$this->ses['response']['param']['start'] = $this->start;
	$this->ses['response']['param']['newLimit'] = $this->limit + 10;

	// content
	$this->ses['response']['param']['list'] = $this->list;
	$this->ses['response']['param']['userList'] = $userList;

	$this->ses['response']['param']['skin'] = $XCOW_B['sciomino']['skin'];

	
     }

}

?>
