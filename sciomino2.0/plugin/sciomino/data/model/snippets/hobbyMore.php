<?

class hobbyMore extends control {

    function Run() {

        global $XCOW_B;

	//
	// who?
	//
        $this->id = $this->ses['id'];
	$this->userId = UserApiGetUserFromReference($this->id);

	// param
	$this->hobby = $this->ses['request']['param']['h'];
        $this->searchString = $this->ses['request']['param']['searchString'];

        $this->offset = $this->ses['request']['param']['offset'];
        if (! isset($this->offset)) {$this->offset = 0;}
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 20;}

	// init
	$searchList = array();
	$userList = array();

	// search
	$searchList = explode(',', $this->searchString);

	// results
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
	$this->ses['response']['param']['searchString'] = implode(',',array_slice($searchList, 0, 100));

	$this->ses['response']['param']['userList'] = $userList;

	$thereIsMore = 1;
	if (count($userList) < $this->limit) {
		$thereIsMore = 0;
	}
	$this->ses['response']['param']['thereIsMore'] = $thereIsMore;

	$this->ses['response']['param']['hobbyField'] = $this->hobby;
	$this->ses['response']['param']['me'] = $this->userId;

     }

}

?>
