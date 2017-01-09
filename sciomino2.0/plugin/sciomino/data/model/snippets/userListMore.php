<?

class userListMore extends control {

    function Run() {

        global $XCOW_B;
        
	//
	// who?
	//
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id, "SC_UserApiGetUserFromReference_".$this->id);

	// param
        $this->searchString = $this->ses['request']['param']['searchString'];

        $this->offset = $this->ses['request']['param']['offset'];
        if (! isset($this->offset)) {$this->offset = 0;}
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 20;}

	// init
	$searchList = array();
	$userList = array();

	//
	// get user id's
	// - select user id's from reverse index
	// - ORDER BY subselect field from field_table
	//
	// get filterlijst
	// - select all filters for user id's
	//
	
	//
	// get result information from NEXT 10 id's using XmlHttpRequest
	// - get information from next 10 results when 'more' button is pushed
	//

	$searchList = explode(',', $this->searchString);

	if (count($searchList) > $this->offset) {
		$userString = "";
		$userLimit = array_slice($searchList, $this->offset, $this->limit);
		foreach ($userLimit as $userId) {
			$userString .= "user[".$userId."]&";
		}
		$userString = rtrim($userString, "&");
		// append same order
		$userString .= "&order=lastname";
		$userList = UserApiListUserWithQuery($userString);
	}

	$this->ses['response']['param']['searchOffset'] = $this->offset + $this->limit;
	$this->ses['response']['param']['searchString'] = $this->searchString;

	$this->ses['response']['param']['userList'] = $userList;

	$thereIsMore = 1;
	if (count($userList) < $this->limit) {
		$thereIsMore = 0;
	}
	$this->ses['response']['param']['thereIsMore'] = $thereIsMore;

	$this->ses['response']['param']['user'] = $this->userId;

     }

}

?>
